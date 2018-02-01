<?php

namespace App\Http\Controllers\Admin;

use App\Models\StopWord;
use App\Models\ProductStopWord;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductsVideoRequest;
use App\Http\Requests\ProductsPriceRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductFeature;
use App\Models\ProductOption;
use App\Models\ProductImage;
use App\Models\ProductVideo;
use App\Models\ProductShopPrice;
use App\Models\ShopXML;
use App\User;

use Response;
use File;

class AdminProductsController extends AdminBaseController
{
	private $categories = [];
	
	public function __construct(){
		parent::__construct();

		// Категории
		$this->categories = ProductCategory::getNestedList('name_ru', NULL, '&nbsp;&nbsp;&nbsp;');
	}
	
	// Список товаров
    public function index( Request $request ){
        
		$title = 'Товары';
		
		// Товары
		if( $request->get('category') && !empty($request->get('category')) )
			$ids = [ (int)$request->get('category') ];
		else
			$ids = array_keys($this->categories);
		
		$products = Product::select([
										'products.id', 
										'products.visible',
										'products.advertising',
										'products.slug',
										'products.name_ru',
										'products_categories.name_ru AS category_name'
									])
						   ->join('products_categories', 'products_categories.id', '=', 'products.category_id')
						   ->whereIn('products.category_id', $ids);
		
		if( $request->get('search') && !empty($request->get('search')) )
			$products = $products->where('products.name_ru', 'LIKE', '%' . $request->get('search') . '%');
		
		$products = $products->paginate(25);
		
		// Категории
		$categories = ['0' => 'Все категории'] + $this->categories;
		
		return view('admin.showProducts', compact(['title', 'products', 'categories']));
    }
	
	// Удаление товаров
	public function deleteProducts( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
		{
			// Если есть изображения у товаров - удалим их
			if( $images = ProductImage::whereIn('product_id', $ids)->get() ){
				foreach($images as $image){
					if( $image->image ){
						File::delete( public_path() . '/uploads/shop/products/' . $image->image );
						File::delete( public_path() . '/uploads/shop/products/thumbs/' . $image->image );
					}
				}
			}
			
			Product::destroy( $ids );
		}

		return redirect()->back();
	}
	
	// Добавление товара (форма)
	public function getProductAdd(){
		
		$title = 'Добавление товара';
		
		$post  = new Product;
		$post->visible = 1;
		
		// Категории
		$categories = ['0' => 'Выберите категорию...'] + $this->categories;
		
		return view('admin.editProduct', compact(['title', 'post', 'categories']));
	}
	
	// Добавление товара
	public function postProductAdd( ProductRequest $request ){
		
		$post = Product::create( $request->except('_token') );

		return redirect('/master/shop/products/edit/' . $post->id)->with('success', 'Товар добавлен!');
	}
	
	// Редактирование товара (форма)
	public function getProductEdit( $id ){
		
		$title = 'Редактирование товара';
		
		$post  = Product::find( $id );
		
		// Категории
		$categories = ['0' => 'Выберите категорию...'] + $this->categories;

        $product_stop_words=ProductStopWord::where('product_id','=',$id);

		return view('admin.editProduct', compact(['title', 'post', 'categories','product_stop_words']) );
	}
	
	// Редактирование товара
	public function postProductEdit( ProductRequest $request, $id ){
		
		Product::find( $id )->update( $request->except('_token','stop_word') );
        $stop_words = $request->get('stop_word');
        foreach($stop_words as $word) {
            $check_word = StopWord::where('name','like',$word) -> first();
            if(!$check_word) {
                $check_word = StopWord::create(array('name' => $word));
            }

            $check_word_id=ProductStopWord::where('stop_word_id','like',$check_word->id) ->where('product_id','like',$id)->first();
            if(!$check_word_id){
                ProductStopWord::create(array('product_id'=>$id,'stop_word_id'=>$check_word->id));

            }
        }
		return redirect('/master/shop/products/edit/' . $id)->with('success', 'Товар обновлён!','prid');
	}
	
	// Показать/скрыть товар
	public function visibleProduct( Request $request ){
		
		if( $request->ajax() )
		{
			$productVisible = Product::find( (int)$request->id )->visible;
			
			$visible = empty($productVisible) ? 1 : 0;
			
			Product::whereId( (int)$request->id )->update(['visible' => $visible]);
			
			return Response::json(['message' => 'visible completed']);
		}
	}
	
	// Показать как рекламеное предложение
	public function advertisingProduct( Request $request ){
		
		if( $request->ajax() )
		{
			$productAdvertising = Product::find( (int)$request->id )->advertising;
			
			$advertising = empty($productAdvertising) ? 1 : 0;
			
			Product::whereId( (int)$request->id )->update(['advertising' => $advertising]);
			
			return Response::json(['message' => 'advertising completed']);
		}
	}
	
	// Сортировка товаров
	public function sortableProducts( Request $request ){
		
		if( $request->ajax() )
		{
			(int)$i = 1;
			
			foreach($request->get('position') as $item){
				Product::whereId( $item )->update(['position' => $i]);
				$i++;
			}
		}
	}
	
	// Выбор опций для товара (форма)
	public function getProductOptions( $id ){
		
		$title = 'Выбор опций для товара: "' . Product::find( $id )->name . '"';
		
		$features = ProductFeature::with('variants')->get();

		$options  = ProductOption::getOptions( $id );

		return view('admin.showProductSelectionFeatures', compact(['title', 'features', 'options']));
	}
	
	// Выбор опций для товара
	public function postProductOptions( Request $request, $id ){
		
		ProductOption::saveOptions( $id, $request->get('feature') );
		
		return redirect('/master/shop/products/options/' . $id)->with('success', 'Опции успешно обновлены!');
	}
	
	// Получаем все изображения товара
	public function getProductImages( $id ){
		
		$title  = 'Изображения для товара: "' . Product::find( $id )->name . '"';
		 
		$images = ProductImage::whereProductId( $id )->paginate(24);
		
		return view('admin.showProductImages', compact(['title', 'images']));
	}
	
	// Действия с изображениями товара
	public function postProductImages( Request $request, $id ){
		
		switch( $request->get('action') )
		{
			case 'delete':
				ProductImage::deleteProductImages( $request->get('check') );
				return redirect()->back();
			break;
			case 'active':
				ProductImage::activeProductImage( $request->get('id'), $id );
				return Response::json(['message' => 'completed']);
			break;
			default:
				$messages = ProductImage::uploadProductImages( $request->file('images'), $id );
				$messagesType = array_keys($messages);
				$messagesType = $messagesType[0];
				return redirect()->back()->with($messagesType, $messages[$messagesType]);
		}
	}
	
	// Получаем все видеообзоры товара
	public function getProductVideo( $id ){
		
		$title = 'Видеообзоры для товара: "' . Product::find( $id )->name . '"';
		
		$video = ProductVideo::whereProductId( $id )->paginate(24);
		
		return view('admin.showProductVideo', compact(['title', 'video', 'id']));
	}
	
	// Удаляем видеообзоры
	public function postProductVideo( Request $request, $id ){
		
		if( $request->get('action') == 'delete' )
			ProductVideo::destroy( $request->get('check') );

		return redirect()->back();
	}
	
	// Добавление видеообзора (форма)
	public function getProductVideoAdd( $id ){
		
		$title = 'Добавление видеообзора';
		
		// Пользователи
		$usersList = [];
		foreach( User::all() as $user )
			$usersList[$user->id] = $user->name;
		$users = ['0' => 'Выбирите пользователя, от которого Вы получили видеообзор'] + $usersList;
		
		$productName = Product::find( $id )->name;
		
		$post = new ProductVideo;
		
		return view('admin.editProductVideo', compact(['title', 'users', 'post', 'productName', 'id']));
	}
	
	// Добавление видеообзора
	public function postProductVideoAdd( ProductsVideoRequest $request, $id ){
	
		$post = ProductVideo::create( $request->except('_token') );
		
		return redirect('/master/shop/products/video/' . $id . '/edit/' . $post->id)->with('success', 'Видеообзор добавлен!');
	}
	
	// Редактирование видеообзора (форма)
	public function getProductVideoEdit( $product_id, $video_id ){
		
		$title = 'Редактирование видеообзора';
		
		// Пользователи
		$usersList = [];
		foreach( User::all() as $user )
			$usersList[$user->id] = $user->name;
		$users = ['0' => 'Выбирите пользователя, от которого Вы получили видеообзор'] + $usersList;
		
		$productName = Product::find( $product_id )->name;
		
		$post = ProductVideo::find( $video_id );

		$videoCode = ProductVideo::videoCode( $post->video );
		
		$id = $product_id;
		
		return view('admin.editProductVideo', compact(['title', 'users', 'post', 'productName', 'id', 'videoCode']));
	}
	
	// Редактирование видеообзора
	public function postProductVideoEdit( ProductsVideoRequest $request, $product_id, $video_id ){
	
		ProductVideo::find( $video_id )->update( $request->except('_token') );

		return redirect('/master/shop/products/video/' . $product_id . '/edit/' . $video_id)->with('success', 'Информация обновлена!');
	}
	
	// Получаем все цены товара в магазинах
	public function getProductPrices( $id ){
		
		$title  = 'Цены в магазинах для товара: "' . Product::find( $id )->name . '"';
		
		$prices = ProductShopPrice::select('products_shops_prices.*', 'shops.name_ru AS shop_name')
								  ->join('shops', 'shops.id', '=', 'products_shops_prices.shop_id')
								  ->where('products_shops_prices.product_id', $id)
								  ->paginate(24);
		
		return view('admin.showProductPrices', compact(['title', 'prices', 'id']));
	}
	
	// Удаляем цены
	public function postProductPrices( Request $request, $id ){
		
		if( $request->get('action') == 'delete' )
			ProductShopPrice::destroy( $request->get('check') );

		return redirect()->back();
	}
	
	// Добавление цены (форма)
	public function getProductPriceAdd( $id ){
		
		$title = 'Добавление цены';
		
		// Магазины
		$shops = [];
		$shopsXML = ShopXML::select('shops.id', 'shops.name_ru')
						   ->join('shops', 'shops.id', '=', 'shops_xml.shop_id')
						   ->get();
		
		foreach( $shopsXML as $shop )
			$shops[$shop->id] = $shop->name_ru;
		$shops = ['0' => 'Выберите магазин, где находится товар'] + $shops;
		
		$post = new ProductShopPrice;
		
		$productName = Product::find( $id )->name;
		
		return view('admin.editProductPrice', compact(['title', 'shops', 'post', 'productName', 'id']));
	}
	
	// Добавление цены
	public function postProductPriceAdd( ProductsPriceRequest $request, $id ){
	
		$post = ProductShopPrice::create( $request->except('_token') );
		
		return redirect('/master/shop/products/prices/' . $id . '/edit/' . $post->id)->with('success', 'Данные добавлены!');
	}
	
	// Редактирование цены (форма)
	public function getProductPriceEdit( $product_id, $price_id ){
		
		$title = 'Редактирование цены';
		
		// Магазины
		$shops = [];
		$shopsXML = ShopXML::select('shops.id', 'shops.name_ru')
						   ->join('shops', 'shops.id', '=', 'shops_xml.shop_id')
						   ->get();
		
		foreach( $shopsXML as $shop )
			$shops[$shop->id] = $shop->name_ru;
		$shops = ['0' => 'Выберите магазин, где находится товар'] + $shops;
		
		$productName = Product::find( $product_id )->name;
		
		$post = ProductShopPrice::find( $price_id );
		
		$id = $product_id;
		
		return view('admin.editProductPrice', compact(['title', 'shops', 'post', 'productName', 'id']));
	}
	
	// Редактирование цены
	public function postProductPriceEdit( ProductsPriceRequest $request, $product_id, $price_id ){
	
		ProductShopPrice::find( $price_id )->update( $request->except('_token') );

		return redirect('/master/shop/products/prices/' . $product_id . '/edit/' . $price_id)->with('success', 'Данные обновлены!');
	}
	
	// Получаем список товарных позиций которые вносили "вручную"
	public function getProductsNotDelete(){
		
		$title = 'Товары добавленные вручную для XML парсера';
		
		$products = ProductShopPrice::select([
												'products.id AS product_id', 
												'products_shops_prices.id AS product_shops_prices_id',
												'products_shops_prices.shop_product_name AS product_name'
											 ])
									->join('products', 'products.id', '=', 'products_shops_prices.product_id')
									->where('products_shops_prices.not_delete', 1)
									->paginate(25);
		
		return view('admin.showProductsNotDelete', compact(['title', 'products']));
	}
}
