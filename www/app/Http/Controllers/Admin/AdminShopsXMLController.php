<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ShopXMLRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Shop;
use App\Models\ShopXML;
use App\Models\Product;
use App\Models\ProductShopPrice;

use App;
use URL;
use Slug;
use Excel;
use Session;
use DOMDocument;

class AdminShopsXMLController extends AdminBaseController
{
	// Список магазинов с XML файлами
	public function index(){
		
		$title = 'Управление';
		
		// Магазины
		$shops = ShopXML::select('shops_xml.*', 'shops.name_ru AS shop_name')
						->join('shops', 'shops.id', '=', 'shops_xml.shop_id')
						->paginate(25);
		
		return view('admin.showShopXMLPosts', compact(['title', 'shops']));
	}
	
	// Удаляем XML файлы
	public function deleteShopsXML( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
			ShopXML::destroy( $ids );

		return redirect()->back();
	}
	
	// Добавляем XML файл (форма)
	public function getShopXMLAdd(){
		
		$title = 'Добавление XML файла';
		
		$post  = new ShopXML;
		
		$shops = [];
		foreach( Shop::all() as $shop )
			$shops[$shop->id] = $shop->name;
		
		return view('admin.editShopXML', compact(['title', 'post', 'shops']));
	}
	
	// Добавляем XML файл
	public function postShopXMLAdd( ShopXMLRequest $request ){
		
		$post = ShopXML::create( $request->except('_token') );

		return redirect('/master/shop/parser/control/edit/' . $post->id)->with('success', 'XML файл успешно добавлен!');
	}
	
	// Редактируем данные (форма)
	public function getShopXMLEdit( $id ){
		
		$title = 'Редактирование данных';
		
		$post  = ShopXML::find( $id );
		
		$shops = [];
		foreach( Shop::all() as $shop )
			$shops[$shop->id] = $shop->name;
		
		// Количество товаров в текущем магазине
		$countProductsXML = ProductShopPrice::whereShopId( $post->shop_id )->count();

		return view('admin.editShopXML', compact(['title', 'post', 'shops', 'countProductsXML']));
	}
	
	// Редактируем данные
	public function postShopXMLEdit( ShopXMLRequest $request, $id ){
		
		ShopXML::find( $id )->update($request->except('_token'));

		return redirect()->back()->with('success', 'Данные успешно обновлены!');
	}
	
	// Удаляем товарные позиции текущего магазина
	public function postShopXMLRemovePrice( Request $request ){
		
		ProductShopPrice::whereShopId( (int)$request->get('shop_id') )->delete();
		
		return redirect()->back()->with('success', 'Товарные позиции текущего магазина успешно удалены!');
	}
	
	// Вид для парсинга данных
	public function getShopXMLProcess(){

		$title = 'Парсинг данных';
		
		$shopsMas = [];
		$shopsXML = ShopXML::select('shops_xml.*', 'shops.name_ru AS shop_name')
						   ->join('shops', 'shops.id', '=', 'shops_xml.shop_id')
						   ->get();
		
		foreach( $shopsXML as $shop )
			$shopsMas[$shop->shop_id] = $shop->shop_name;
		$shopsMas = array_add($shopsMas, '0', 'Все магазины...');
		
		return view('admin.showShopXMLProcess', compact(['title', 'shopsMas']));
	}
	
	// Процесс парсинга товаров
	public function postShopXMLProcess( Request $request ){
		
		if( $request->ajax() )
		{
			// Время выполнения скрипта неограничено
			set_time_limit(0);
			
			$item = 1;
			$shopId = (int)$request->get('shop_id');
			$progress = 0;
			$listLinksXML = [];

			$shops = ShopXML::select('shops_xml.*', 'shops.name_ru AS shop_name', 'shops.slug AS shop_slug')
							->join('shops', 'shops.id', '=', 'shops_xml.shop_id');
			
			if( empty($shopId) )
			{
				$shops = $shops->get();
				$progress = $shops->count();
			}
			else
				$shops = $shops->where('shops_xml.shop_id', $shopId)->get();
			
			foreach( $shops as $shop )
			{
				// Товары которые не попали ни в одну карточку
				$excludeProducts = [];
				
				// Удаляем весь прайс с id текущего магазина (потом заменим новым)
				ProductShopPrice::whereShopId( $shop->shop_id )->whereNotDelete(0)->delete();
				
				// Получаем все товары текущего магазина которые добавили "вручную"
				$productsNotDelete = ProductShopPrice::whereShopId( $shop->shop_id )->whereNotDelete(1)->get();

				$DOMDocument = new DOMDocument;
				@$DOMDocument->load( $shop->xml_url );
				
				// Если выбран только один магазин в качестве парсинга, получаем количество товаров для progress bar
				if( !empty($shopId) )
					$progress = $DOMDocument->getElementsByTagName( $shop->xml_tag_wrapper )->length;

				// Парсинг
				$productsXML = $DOMDocument->getElementsByTagName( $shop->xml_tag_wrapper );
				
				foreach( $productsXML AS $productItem )
				{
					$productName  = $productItem->getElementsByTagName( $shop->xml_tag_name )->item(0)->nodeValue;
					$productPrice = $productItem->getElementsByTagName( $shop->xml_tag_price )->item(0)->nodeValue;
					$productURL   = $productItem->getElementsByTagName('url')->item(0)->nodeValue;
					
					if( $productName && $productPrice && $productURL )
					{
						$query = ShopXML::getSearchString( ShopXML::getWords($productName) );
						
						$findProducts = Product::select(['products.id', 'products.name_' . App::getLocale()])
											   ->join('products_search', 'products_search.id', '=', 'products.id')
											   ->whereRaw('MATCH (products_search.name_ru) AGAINST (? IN BOOLEAN MODE) > 0', [$query])
											   ->orderByRaw('MATCH (products_search.name_ru) AGAINST (? IN BOOLEAN MODE) DESC', [$query])
											   ->take(1)
											   ->get();

						if( count($findProducts) )
						{
							foreach( $findProducts as $fp )
							{								
								ProductShopPrice::create([
									'product_id' 		 => $fp->id,
									'shop_id' 			 => $shop->shop_id,
									'shop_product_name'  => trim($productName),
									'shop_product_link'  => $productURL,
									'shop_product_price' => $productPrice
								]);
							}
						}
						else
							$excludeProducts[] = ['name' => trim($productName), 'url' => $productURL];
						
						// Обновляем цену для товаров которые добавили "вручную"
						if( count($productsNotDelete) )
						{
							foreach($productsNotDelete as $productNotDelete)
							{
								if( strcasecmp(trim($productName), trim($productNotDelete->shop_product_name)) == 0 )
								{
									ProductShopPrice::whereId( $productNotDelete->id )->update(['shop_product_price' => $productPrice]);
									
									break;
								}
							}
						}
						
						// Записываем статус прогресса в переменную сессии
						Session::put('info_progress', json_encode(['progress' => floor(($item / $progress) * 100), 'processing' => 'Обрабатывается: ' . $item . ' из ' . $progress]));

						// Закрываем сессию на запись
						Session::save();

						// Инкрементим счетчик (для одного магазина)
						if( !empty($shopId) ) 
							$item++;
					}
				}
				
				unset($DOMDocument);
				
				// Добавляем предварительно удаленнные товары в отчёт товаров которые не попали по карточкам
				$productsPrevDelReport = Product::join('products_shops_prices', 'products_shops_prices.product_id', '=', 'products.id')
												->where('products_shops_prices.shop_id', $shop->shop_id)
												->where('products.products_exceptions', '!=', '')
												->get();
				
				if( count($productsPrevDelReport) )
				{
					$productsPrevDelExceptions = [];
					foreach( $productsPrevDelReport as $product )
						$productsPrevDelExceptions[] = explode("\r\n", $product->products_exceptions);
					
					$productsPrevDelExceptionsNames = array_collapse($productsPrevDelExceptions);
					$productsPrevDelExceptionsNames = array_unique(array_filter($productsPrevDelExceptionsNames));	

					$productsPrevDelReportRes = ProductShopPrice::whereIn('shop_product_name', $productsPrevDelExceptionsNames)
																->whereNotDelete(0)
																->get();
					
					foreach( $productsPrevDelReportRes as $productPrevDelReport )
						$excludeProducts[] = ['name' => $productPrevDelReport->shop_product_name, 'url' => $productPrevDelReport->shop_product_link];
					
					// Удаляем лишние товары прайсов из карточек
					ProductShopPrice::whereIn('shop_product_name', $productsPrevDelExceptionsNames)
									->whereNotDelete(0)
									->delete();
				}
				
				// Удаляем из массива для отчета товары которые добавили "вручную"
				if( count($productsNotDelete) )
				{
					foreach($productsNotDelete as $productNotDelete)
					{
						foreach($excludeProducts as $key => $excludeProduct)
						{
							if( strcasecmp($excludeProduct['name'], trim($productNotDelete->shop_product_name)) == 0 )
							{
								unset($excludeProducts[$key]);
								
								break;
							}
						}
					}
				}

				// Создаем отчет
				if( count($excludeProducts) )
				{
					$fileNameXLS = preg_replace('/[^a-zA-ZА-Яа-я0-9\s]/u', '', $shop->shop_name);
					$fileNameXLS = Slug::make( $fileNameXLS ) . '(' . date('d.m.Y') . ')';
						
					Excel::create($fileNameXLS, function($excel) use ($excludeProducts){
						$excel->sheet('list', function($sheet) use ($excludeProducts){
							$sheet->fromArray($excludeProducts);
						});
					})->store('xls', public_path('uploads/xls'));
						
					unset($excludeProducts);
						
					// Создаем ссылки на XML файлы
					$listLinksXML[] = ['shopName' => $shop->shop_name, 'urlToXML' => url() . '/uploads/xls/' . $fileNameXLS . '.xls'];
				}

				// Инкрементим счетчик (для всех магазинов)
				if( empty($shopId) ) 
					$item++;

				// Обновляем дату парсинга магазина
				ShopXML::whereShopId( $shop->shop_id )->update(['last_updated_products' => date('Y-m-d')]);
			}

			// Чистим сессионную переменную статуса
			Session::forget('info_progress');
			Session::save();
			
			echo json_encode($listLinksXML);

			exit();
		}
	}

	// Статсут выполнения парсинга товаров
	public function postShopXMLProcessStatus( Request $request ){
		
		if( $request->ajax() )
		{
			echo ( Session::has('info_progress') ? Session::get('info_progress') : json_encode(['progress' => 0, 'processing' => '']) );
			exit();
		}
	}
}
