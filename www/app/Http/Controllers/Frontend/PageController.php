<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Frontend\BaseController;

use App\Models\Page;

class PageController extends BaseController
{
	public function index( $page_url ){
		
		$page = Page::whereSlug( $page_url )->firstOrFail();
		
		return view('frontend.showPage', compact(['page']));
    }
}
