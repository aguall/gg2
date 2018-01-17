<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Yandex\Translate\Translator;
use Yandex\Translate\Exception;

use App\Models\Shop;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ProductOptionVariant;
use App\Models\ProductOption;
use App\Models\ProductShopPrice;
use App\Models\ProductImage;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// ID магазинов нового GUNS = URL магазинам старого GUNS
		$shops = [                              
					132 => 'stvol.html',                        
					165 => 'zbroia.html',                       
					171 => 'kozaki.html',                    
					137 => 'voentorg.html',                   
					180 => 'cobra.html',                        
					160 => 'fonarevka.html',                    
					170 => 'ris.html',                          
					167 => 'latek.html',                        
					143 => 'braconier.html',                    
					117 => 'ibis.html',                         
					177 => 'one-click.html',                    
					156 => 'lezo.html',                         
					155 => 'ohota.html',                        
					136 => 'filingun.html',                     
					141 => 'fire_lion.html',                    
					121 => 'airgun.html',                       
					109 => 'jaguar_225.html',                   
					104 => 'internet_magazin_klich_15.html',    
					103 => 'gunshop_1219.html',                 
					144 => 'best-arms.html',                  
					164 => 'safari.html',                       
					119 => 'nitecore-ua.html',                 
					135 => 'shkval.html',                     
					140 => 'me.html',                           
					113 => 'vintorez_10338.html',               
					150 => '4_seasons.html',                    
					169 => 'erdi.html',                         
					188 => 'internet-magazin-sklad-070.html',   
					138 => 'sherif.html',                       
					176 => 'barton.html',                       
					194 => 'berkut.html',                       
					154 => 'blindazh.html',                     
					191 => 'pancer.html',                    
					122 => 'svit_zbroyi.html',                  
					142 => 'amadeus.html',                      
					112 => 'knife_e_5508.html',                 
					126 => 'eguns.html',                        
					153 => 'mir_rybaka.html',                   
					115 => 'mate.html',                         
					196 => 'legioner.html',                     
					157 => 'creative_armor.html',               
					116 => 'schmeisser.html',                   
					168 => 'krechet.html',                      
					149 => 'kyrok.html',                        
					179 => 'shotgun.html',                      
					111 => 'amalteya_1220.html',                
					125 => 'specsnariaga.html',                 
					107 => 'igun_223.html',                     
					139 => 'gilza.html',                        
					134 => 'strelok.html',                      
					147 => 'prival.html',                       
					106 => 'fonaric_222.html',                  
					172 => 'new-shops.html',                    
					181 => 'black-eagle.html',                  
					108 => 'jones_224.html',                    
					124 => 'kypok.html',                        
					123 => 'internet-magazin-prorez.html',      
					158 => 'kajan.html',                    
					174 => 'carabine.html',                     
					146 => 'zaimka.html',                       
					161 => 'sportguns.html',                    
					173 => 'x-zone.html',                       
					105 => 'agron_221.html',                    
					184 => 'ledlenser.html',                    
					151 => 'oz_ua.html',                        
					148 => 'gofree.html',                       
					190 => 'gunhouse.html',                     
					114 => 'celox_14149.html',                  
					159 => '7-62.html',                         
					182 => 'orb.html',                          
					128 => 'kalibr.html',                      
					178 => 'pistolet.html',                     
					118 => 'liga-sporta.html',                  
					189 => 'duck-store.html',                   
					186 => 'pohl-force.html',                   
					127 => 'leupold.html',                      
					133 => 'strela.html',                       
					175 => 'pistonoff.html',                    
					187 => 'martis.html',                       
					166 => 'knivesshop.html',                   
					192 => 'magazin-crosman.html',              
					110 => 'spyscope_226.html',                 
					120 => 'scout.html',                        
					185 => 'hapstone.html',                     
					162 => 'laser-genetics.html',              
					183 => 'sindikat.html',                   
					129 => 'gamo.html',                         
					193 => 'nozhik.html',                       
					152 => 'antique.html',                      
					195 => 'arbalety.html',                     
					163 => 'mysky.html',                       
					130 => 'benchmade.html',                    
					131 => 'stainer.html',                      
					145 => 'internet-magazin-puls.html'
				];
		
		
		$gunsDB = \DB::connection('guns_old');
		
		// GUNS OLD
		// Патроны -> Патроны для пистолета -> Патроны 9 мм (id = 36)
		// Патроны -> Патроны для пистолета -> Пули 4 мм (id = 73161)
		// Патроны -> Патроны для пистолета -> Пули 4,5 мм (id = 73141)
		// Патроны -> Патроны для пневматики -> Пули 4,5 мм (id = 35)
		// Патроны -> Патроны для пневматики -> Пули 5,5 мм (id = 1307)
		// Патроны -> Патроны для пневматики -> Пули 6,35 мм (id = 73160)
		// Патроны -> Патроны для винтовки -> Патроны 22 (5,6 mm)/22 LR/22 WMR/22 Hornet (id = 17265)
		// Патроны -> Патроны для винтовки -> Патроны 222 (id = 17262)
		// Патроны -> Патроны для винтовки -> Патроны 223 (id = 17263)
		// Патроны -> Патроны для винтовки -> Патроны 243 Win (id = 17269)
		// Патроны -> Патроны для винтовки -> Патроны 270 WSM (id = 73158)
		// Патроны -> Патроны для винтовки -> Патроны 30R Blaser/30-06/30-30/300 WSM/300 Rem Ultra Mag/300 WBY Mag/300 Win Mag (id = 17271)
		// Патроны -> Патроны для винтовки -> Патроны 338 WinMag (id = 17264)
		// Патроны -> Патроны для винтовки -> Патроны 375 H&H Mag (id = 18260)
		// Патроны -> Патроны для винтовки -> Патроны 416 Rem Mag (id = 17273)
		// Патроны -> Патроны для винтовки -> Патроны 444 Marlin (id = 17274)
		// Патроны -> Патроны для винтовки -> Патроны 7 mm Remington Magnum (id = 17266)
		// Патроны -> Патроны для винтовки -> Патроны 7,62 /308 Win (id = 14582)
		// Патроны -> Патроны для винтовки -> Патроны 8х57 JRS (id = 18272)
		// Патроны -> Патроны для винтовки -> Патроны для винтовки (id = 73142)
		// Патроны -> Патроны для ружья -> К-р. 12 (id = 4671)
		// Патроны -> Патроны для ружья -> К-р. 16 (id = 4670)
		// Патроны -> Патроны для ружья -> К-р. 20 (id = 4672)
		// Патроны -> Патроны для ружья -> К-р. 410 (id = 73157)
		// Патроны -> Макеты патронов (id = 33859)
		// Патроны -> Снаряжение для патронов (id = 73152)
		// Метательное оружие -> Рогатки (id = 37)
		// Метательное оружие -> Арбалеты (id = 4448)
		// Метательное оружие -> Духовые трубки (id = 33863)
		// Метательное оружие -> Луки (id = 33864)
		// Метательное оружие -> Дартс (id = 33869)
		// Метательное оружие -> Сюрикены (id = 33870)
		// Метательное оружие -> Стрелы и аксесуары (id = 72009)
		// Оптика (id = 23)
		// Аксессуары -> Аксессуары для пневматики (id = 29)
		// Аксессуары -> Средства по уходу за оружием (id = 49)
		// Аксессуары -> Сошки (id = 1308)
		// Аксессуары -> Сейфы, замки на спусковой крючок (id = 5510)
		// Аксессуары -> Направленные микрофоны (id = 5511)
		// Аксессуары -> Продукция с символикой (id = 33841)
		// Аксессуары -> Мишени и оборудование (id = 33843)
		// Аксессуары -> Подставки (id = 33845)
		// Аксессуары -> Холодная пристрелка (id = 33861)
		// Аксессуары -> Точилки (id = 4392)
		// Аксессуары -> Аксессуары ножи (id = 33849)
		// Аксессуары -> Аксессуары оптика (id = 73151)
		// Фонари (id = 26)
		// Запасные части -> Запасные части к винтовкам (id = 73147)
		// Запасные части -> Запасные части к ружьям (id = 73145)
		// Запасные части -> Запчасти для фонарей (id = 73146)
		// Запасные части -> Запасные части к пистолетам (id = 73159)
		// Ножи (id = 73148)
		// Спецсредства -> Самоспасатели (id = 73149)
		// Спецсредства -> Бронежилеты (id = 14028)
		// Спецсредства -> Газовые баллончики (id = 14029)
		// Спецсредства -> Каски (id = 14044)
		// Спецсредства -> Тактические щиты (id = 14047)
		// Спецсредства -> Дубинки (id = 33847)
		// Спецсредства -> Наручники (id = 33848)
		// Спецсредства -> Бронепластины (id = 73162)
		// Аптечка (id = 14573)
		// Гладкоствольное оружие (id = 15049)
		// Нарезное оружие -> Пневматические газо-балонные винтовки (PCP) (id = 27)
		// Нарезное оружие -> Пневматические пружинно-поршневые винтовки (id = 51)
		// Нарезное оружие -> Пневматические мультикомпрессионные винтовки (id = 28)
		// Нарезное оружие -> Самозарядные винтовки и карабины (id = 18280)
		// Нарезное оружие -> Магазинные винтовки и карабины (id = 18281)
		// Нарезное оружие -> Макеты винтовок (ММГ) (id = 72131)
		// Макеты массогабаритные (id = 73156)
		// Снаряжение (id = 33835)
		// Одежда (id = 33836)
		// Airsoft (id = 73155)
		// Пистолеты (id = 73143)
		// Обувь (id = 73154)
		// Тюнинг оружия (id = 73163)
		$productsSQL = $gunsDB->table('modx_product_item')
							  ->join('modx_product_images', 'modx_product_images.parent', '=', 'modx_product_item.id')
							  ->join('modx_product2catalog', 'modx_product2catalog.itemid', '=', 'modx_product_item.id')
							  ->join('modx_product_catalog', 'modx_product_catalog.id', '=', 'modx_product2catalog.catalogid')
							  ->select([
											'modx_product_item.id AS product_id',
											'modx_product_item.alias AS product_url',
											'modx_product_catalog.alias AS product_category_url',
											'modx_product_item.name AS product_name',
											'modx_product_item.description AS product_body',
											'modx_product_images.name AS product_image'
									    ])
							  ->where( 'modx_product2catalog.catalogid', 73163 )	// Категория к которой принадлежат товары
							  ->where( 'modx_product_item.active', 1 )
							  ->get();
		
		// Ключ => Id продукта
		$products = [];
		foreach( $productsSQL as $product )
			$products[$product->product_id] = $product;
		
		// ID's продуктов
		$productsIds = array_keys($products);
		
		// Опции продуктов
		$productsOptions = $gunsDB->table('modx_product_attrs')
								  ->join('modx_product_attrs_info', 'modx_product_attrs_info.id', '=', 'modx_product_attrs.attr_id')
								  ->join('modx_product_attrs_values', 'modx_product_attrs_values.attr_value_id', '=', 'modx_product_attrs.attr_value_id')
								  ->select([
												'modx_product_attrs.item_id AS product_id',
												'modx_product_attrs_info.name AS product_attr_name',
												'modx_product_attrs_info.postfix AS product_attr_name_postfix',
												'modx_product_attrs_values.attr_value AS product_attr_value'
										   ])
								  ->whereIn('modx_product_attrs.item_id', $productsIds)
								  ->get();
		
		// Добавляем опции продуктов в общий массив данных
		foreach( $productsOptions as $item )
			if( $products[$item->product_id] )
				$products[$item->product_id]->product_options[] = $item;
		
		// Получаем все цены в магазинах для товара (если они есть)
		$productPrices = [];
		foreach( $products as $product )
		{
			$document = new \DOMDocument();
		
			if( @$document->loadHTMLFile( 'http://www.guns.ua/' . $product->product_category_url . '/' . $product->product_url . '.html' ) )
			{
				$xPath = new \DOMXPath( $document );
			
				// Классы, где находятся нужные нам данные на загружаемой странице
				$classNames = ['tit', 'cen', 'titaa'];
				$docResults = [];
				foreach( $classNames as $item )
					$docResults[] = $xPath->query("//*[@class='" . $item . "']");
				
				if( $docResults[0]->length > 1 && $docResults[1]->length > 1 && $docResults[2]->length > 1 )
				{
					$productShopsPrices = [];
					foreach( $docResults as $key => $value )
					{
						for( $i = 1; $i <= ($value->length - 1); $i++ )
						{
							// Наименование товара в магазине
							if( $key == 0 )
								$productShopsPrices[$i - 1]['product_shop_name'] = trim($value->item( $i )->nodeValue);
							
							// Цена товара в магазине
							if( $key == 1 )
							{
								$productShopPrice = trim($value->item( $i )->getElementsByTagName('strong')->item(0)->nodeValue);
								
								if( is_numeric($productShopPrice) )
									$productShopsPrices[$i - 1]['product_shop_price'] = $productShopPrice;
								else
									$productShopsPrices[$i - 1]['product_shop_price'] = 0;
							}
							
							// ID магазина + ссылка на него на новом GUNS
							if( $key == 2 )
							{
								$productShopURL = $value->item( $i )->getElementsByTagName('a')->item(0)->getAttribute('href');
								
								if( $shopKey = array_search($productShopURL, $shops) )
								{
									$productShopsPrices[$i - 1]['product_shop_id']  = $shopKey;
									$productShopsPrices[$i - 1]['product_shop_url'] = '/shops/' . Shop::find( $shopKey )->slug;
								}	
							}
						}
					}
					
					// Приводим элементы массива к типу объектов
					foreach( $productShopsPrices as $key => $value )
						$productShopsPrices[$key] = (object)$value;
					
					// Заполняем наш массив нужными данными
					$productPrices[$product->product_id] = $productShopsPrices;
				}
			}
		}
		
		// Добавляем цены продуктов в общий массив данных
		if( $productPrices )
			foreach( $productPrices as $key => $value )
				if( $products[$key] )
					$products[$key]->product_prices = $value;
		
		// Ключ для Яндекс Переводчика
		$key = 'trnsl.1.1.20160523T112613Z.7273ced4b5ed6335.2d434d209fb2abae3e27f58429659be50aa02ac9';
		
		// Собираем все названия опций товаров на новом GUNS
		$productsFeatures = [];
		foreach( ProductFeature::all() as $item )
			$productsFeatures[$item->id] = $item->name_ru;
		
		// Собираем все значения опций товаров на новом GUNS
		$productsOptionsVariants = [];
		foreach( ProductOptionVariant::all() as $item )
			$productsOptionsVariants[$item->feature_id][$item->id] = $item->name_ru;
		
		// Пишем в новую базу товары
		foreach( $products as $product )
		{
			$name_ua = '';
			$name_en = '';
			$meta_title_ua = '';
			$meta_title_en = '';
			
			try
			{
				$translator = new Translator($key);
				
				$name_ua = $translator->translate($product->product_name, 'ru-uk');
				$name_en = $translator->translate($product->product_name, 'ru-en');

				$meta_title_ua = $name_ua . ' - Купити в Україні, ціна. ' . $name_ua . ' - огляди, характеристики, відгуки';
				$meta_title_en = $name_en . ' - Buy in Ukraine, price. ' . $name_en . ' - reviews, specifications';
			}
			catch(Exception $e){}
			
			// GUNS NEW
			// Патроны -> Патроны для пистолета -> Патроны 9 мм (id = 105)
			// Патроны -> Патроны для пистолета -> Пули 4 мм (id = 106)
			// Патроны -> Патроны для пистолета -> Пули 4,5 мм (id = 107)
			// Патроны -> Патроны для пневматики -> Пули 4,5 мм (id = 108)
			// Патроны -> Патроны для пневматики -> Пули 5,5 мм (id = 109)
			// Патроны -> Патроны для пневматики -> Пули 6,35 мм (id = 110)
			// Патроны -> Патроны для винтовки -> Патроны 22 (5,6 mm)/22 LR/22 WMR/22 Hornet (id = 111)
			// Патроны -> Патроны для винтовки -> Патроны 222 (id = 112)
			// Патроны -> Патроны для винтовки -> Патроны 223 (id = 113)
			// Патроны -> Патроны для винтовки -> Патроны 243 Win (id = 114)
			// Патроны -> Патроны для винтовки -> Патроны 270 WSM (id = 115)
			// Патроны -> Патроны для винтовки -> Патроны 30R Blaser/30-06/30-30/300 WSM/300 Rem Ultra Mag/300 WBY Mag/300 Win Mag (id = 116)
			// Патроны -> Патроны для винтовки -> Патроны 338 WinMag (id = 117)
			// Патроны -> Патроны для винтовки -> Патроны 375 H&H Mag (id = 118)
			// Патроны -> Патроны для винтовки -> Патроны 416 Rem Mag (id = 119)
			// Патроны -> Патроны для винтовки -> Патроны 444 Marlin (id = 120)
			// Патроны -> Патроны для винтовки -> Патроны 7 mm Remington Magnum (id = 121)
			// Патроны -> Патроны для винтовки -> Патроны 7,62 /308 Win (id = 122)
			// Патроны -> Патроны для винтовки -> Патроны 8х57 JRS (id = 123)
			// Патроны -> Патроны для винтовки -> Патроны для винтовки (id = 124)
			// Патроны -> Патроны для ружья -> К-р. 12 (id = 125)
			// Патроны -> Патроны для ружья -> К-р. 16 (id = 126)
			// Патроны -> Патроны для ружья -> К-р. 20 (id = 127)
			// Патроны -> Патроны для ружья -> К-р. 410 (id = 128)
			// Патроны -> Макеты патронов (id = 48)
			// Патроны -> Снаряжение для патронов (id = 49)
			// Метательное оружие -> Рогатки (id = 51)
			// Метательное оружие -> Арбалеты (id = 52)
			// Метательное оружие -> Духовые трубки (id = 53)
			// Метательное оружие -> Луки (id = 54)
			// Метательное оружие -> Дартс (id = 55)
			// Метательное оружие -> Сюрикены (id = 56)
			// Метательное оружие -> Стрелы и аксесуары (id = 57)
			// Оптика (id = 58)
			// Аксессуары -> Аксессуары для пневматики (id = 60)
			// Аксессуары -> Средства по уходу за оружием (id = 61)
			// Аксессуары -> Сошки (id = 62)
			// Аксессуары -> Сейфы, замки на спусковой крючок (id = 63)
			// Аксессуары -> Направленные микрофоны (id = 64)
			// Аксессуары -> Продукция с символикой (id = 65)
			// Аксессуары -> Мишени и оборудование (id = 66)
			// Аксессуары -> Подставки (id = 67)
			// Аксессуары -> Холодная пристрелка (id = 68)
			// Аксессуары -> Точилки (id = 69)
			// Аксессуары -> Аксессуары ножи (id = 70)
			// Аксессуары -> Аксессуары оптика (id = 71)
			// Фонари (id = 72)
			// Запасные части -> Запасные части к винтовкам (id = 74)
			// Запасные части -> Запасные части к ружьям (id = 76)
			// Запасные части -> Запчасти для фонарей (id = 77)
			// Запасные части -> Запасные части к пистолетам (id = 75)
			// Ножи (id = 78)
			// Спецсредства -> Самоспасатели (id = 80)
			// Спецсредства -> Бронежилеты (id = 81)
			// Спецсредства -> Газовые баллончики (id = 82)
			// Спецсредства -> Каски (id = 83)
			// Спецсредства -> Тактические щиты (id = 84)
			// Спецсредства -> Дубинки (id = 86)
			// Спецсредства -> Наручники (id = 87)
			// Спецсредства -> Бронепластины (id = 88)
			// Аптечка (id = 89)
			// Гладкоствольное оружие (id = 90)
			// Нарезное оружие -> Пневматические газо-балонные винтовки (PCP) (id = 92)
			// Нарезное оружие -> Пневматические пружинно-поршневые винтовки (id = 93)
			// Нарезное оружие -> Пневматические мультикомпрессионные винтовки (id = 94)
			// Нарезное оружие -> Самозарядные винтовки и карабины (id = 95)
			// Нарезное оружие -> Магазинные винтовки и карабины (id = 96)
			// Нарезное оружие -> Макеты винтовок (ММГ) (id = 97)
			// Макеты массогабаритные (id = 98)
			// Снаряжение (id = 99)
			// Одежда (id = 100)
			// Airsoft (id = 101)
			// Пистолеты (id = 102)
			// Обувь (id = 103)
			// Тюнинг оружия (id = 104)
			$productPost = Product::create([
				'category_id' 	=> 104,		// Категория к которой принадлежат товары
				'slug'		  	=> preg_replace('/[^a-z0-9-_]+/', '', strtolower($product->product_url)),
				'name_ru'	  	=> $product->product_name,
				'name_ua'	  	=> $name_ua,
				'name_en'	  	=> $name_en,
				'body_ru'	  	=> trim($product->product_body),
				'meta_title_ru'	=> $product->product_name . ' - Купить в Украине, цена. ' . $product->product_name . ' - обзоры, характеристики, отзывы',
				'meta_title_ua'	=> $meta_title_ua,
				'meta_title_en'	=> $meta_title_en
			]);
			
			// Опции товара
			if( isset($product->product_options) )
			{
				foreach( $product->product_options as $option )
				{
					$featureName = !empty( $option->product_attr_name_postfix ) ? $option->product_attr_name . ' (' . $option->product_attr_name_postfix . ')' : $option->product_attr_name;
					
					$featureKey  = array_search($featureName, $productsFeatures);
						
					$optionVariantKey = '';
					
					if( isset($productsOptionsVariants[$featureKey]) )
						$optionVariantKey = array_search($option->product_attr_value, $productsOptionsVariants[$featureKey]);
						
					if( $featureKey && $optionVariantKey )
						ProductOption::create([
							'product_id' 		=> $productPost->id, 
							'feature_id' 		=> $featureKey, 
							'option_variant_id' => $optionVariantKey
						]);
				}
			}
			
			// Цены на товар в магазинах
			if( isset($product->product_prices) )
			{
				foreach( $product->product_prices as $price )
				{
					if( isset($price->product_shop_id) )
					{
						ProductShopPrice::create([
							'product_id' 		 => $productPost->id,
							'shop_id' 			 => $price->product_shop_id,
							'shop_product_name'  => $price->product_shop_name,
							'shop_product_link'	 => $price->product_shop_url,
							'shop_product_price' => $price->product_shop_price
						]);
					}
				}
			}
			
			// Изображение товара
			if( $product->product_image && $product->product_image != $product->product_id . '_net-foto.jpeg' )
			{
				try
				{
					// Маленькое изображение
					$imageSmall = \Image::make('http://www.guns.ua/assets/catalog/products/info/' . $product->product_image);
					$imageSmall->save(public_path('uploads/shop/products/thumbs/' . $product->product_image));
					
					// Большое изображение
					$imageSmall = \Image::make('http://www.guns.ua/assets/catalog/products/zoom/' . $product->product_image);
					$imageSmall->save(public_path('uploads/shop/products/' . $product->product_image));
					
					ProductImage::create([
						'product_id' => $productPost->id,
						'image'		 => $product->product_image,
						'active'	 => (int)1
					]);
				}
				catch(Intervention\Image\Exception\NotReadableException $e){}
			}
			
			echo $product->product_id . ' - Success' . "\n\r";
		}
    }
}