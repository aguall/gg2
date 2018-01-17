<?php
/*
    HTML macros
*/

// Выборка нужного сегмента вне зависимости от языка
Html::macro('getLocalizedSegment', function( $segment ){
	if( App::getLocale() != 'ru' )
	{
		$segments = Request::segments();
		
		if( in_array($segment, array_keys($segments)) )
			$segment = intval($segment + 1);
		else
			return FALSE;
	}
	return Request::segment( $segment );
});

// Дерево категорий для карты сайта
HTML::macro('categories_tree', function( $data, $currentLocale, $depth = 0 )
{
	if( $data )
	{
		if( $depth != 0) 
			print '<ul>';

		foreach( $data as $val )
		{	
			if( $val->name )
				print '<li><a href="' . \LaravelLocalization::getLocalizedURL($currentLocale, '/category/' . $val->slug) . '">' . $val->name . '</a>';

			if( count($val->children) )
				HTML::categories_tree( $val->children, $currentLocale, $depth + 1 );
		}
		
		if( $depth != 0)
			print '</ul>';
	}
});