<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DateTime;
use SimpleXMLElement;
use LaravelLocalization;

class Setting extends Model
{
    protected $table = 'settings';
	
	protected $guarded = [];
	
	// Получаем нужную настройку
	public static function getSetting( $setting ){

    	$get = self::whereName( $setting )->first();
    	
		if( $get ) 
			return $get->value;
    }
	
	// Метод отдает мультиязычные XML карты сайта
	public static function getSitemapsXML($locale, $data){
		
		$base = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>';
        
		$xmlbase = new SimpleXMLElement( $base );
	        
		$row = $xmlbase->addChild('url');
		$row->addChild('loc', LaravelLocalization::getLocalizedURL($locale, '/'));
		$row->addChild('lastmod', date('c'));
		$row->addChild('changefreq', 'weekly');
		$row->addChild('priority', '1');
		
		foreach( $data as $item )
		{
			foreach( $item['db_result'] as $val )
			{
				if( $item['url_prefix'] == '/blog/%s/%s' )
					$url = LaravelLocalization::getLocalizedURL($locale, sprintf($item['url_prefix'], $val->category_slug, $val->slug));
				else
					$url = LaravelLocalization::getLocalizedURL($locale, sprintf($item['url_prefix'], $val->slug));
				
				$row = $xmlbase->addChild('url');
				$row->addChild('loc', $url);
				$date = new DateTime($val->updated_at);
				$row->addChild('lastmod', $date->format("Y-m-d\TH:i:sP"));
				$row->addChild('changefreq', 'weekly');
				$row->addChild('priority', '1');
			}
		}
		
		$xmlbase->saveXML(public_path() . '/sitemap_' . $locale . '.xml');
	}
}
