<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Product;

use HTML;
use Cache;

class Banner extends Model
{
	protected $table = 'banners';
		
	protected $guarded = [];

	// Показываем баннер в зависимости от URI 
	public static function showBannersUri( $currentUrl, $zone ){

		$banners = Cache::rememberForever('banners_' . $zone, function() use ($zone){
			return self::whereZone( $zone )->whereVisible( 1 )->orderBy('position')->get();
		});
		
		$showBanners = [];
		if( count($banners) )
		{
			foreach( $banners as $banner )
			{
				if( self::isShowBanner($currentUrl, $banner->include_urls, $banner->exclude_urls) )
					$showBanners[] = self::showBanners($banner->id, $banner->rotation, $banner->code);
				elseif( HTML::getLocalizedSegment(1) == 'product' && !empty(HTML::getLocalizedSegment(2)) )
				{
					// Отображаем баннер, привязанный к категории товаров, в карточках товаров этой категории 
					$productCatURL = Product::select('products_categories.slug AS category_slug')
											->join('products_categories', 'products_categories.id', '=', 'products.category_id')
											->where('products.slug', HTML::getLocalizedSegment(2))
											->first();
					
					if( $productCatURL )
						if( self::isShowBanner($productCatURL->category_slug, $banner->include_urls, $banner->exclude_urls) )
						{
							$showBanners[] = self::showBanners($banner->id, $banner->rotation, $banner->code);
							
							break;
						}
				}
			}
		}
		
		return $showBanners;
	}

	public static function showBanners($bannerId, $bannerRotation, $bannerCode){
		if( $rotation = unserialize($bannerRotation) )
		{
			array_push($rotation, $bannerId); 
			$rotationKeyRandom = array_rand($rotation, 1);
			return self::find( $rotation[$rotationKeyRandom] )->code;
		}
		else
			return $bannerCode;
	}
	
	public static function isShowBanner($currentUrl, $includeUrls, $excludeUrls){
		if( self::findUrls($currentUrl, $includeUrls) )
			if( !self::findUrls($currentUrl, $excludeUrls) ) 
				return TRUE;
		return FALSE; 
    }

    public static function findUrls($currentUrl, $urls){
		$masUrls = explode("\r\n", $urls);
		foreach($masUrls as $value)
			if( trim($value) != "" )
				if( strpos($currentUrl, trim($value)) !== FALSE ) 
					return TRUE;
		return FALSE;
    }
}
