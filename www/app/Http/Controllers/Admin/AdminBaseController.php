<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Setting;
use DB;

class AdminBaseController extends Controller
{
	public function __construct(){
		
		// Говорим, что сайт отключен
        if( Setting::getSetting( 'site_offline' ) == true  ) 
            view()->share('site_offline', 1 );
	}
}
