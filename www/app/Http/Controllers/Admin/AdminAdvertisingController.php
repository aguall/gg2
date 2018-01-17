<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Banner;
use App\Models\FriendsAndPartners;

class AdminAdvertisingController extends AdminBaseController
{
    // Опции рекламы
    public function index(){
        
        $title = 'Реклама';
        
        $bannersCount = Banner::count();

        $friendsAndPartnersCount = FriendsAndPartners::count();
        
        return view('admin.showAdvertisingOptions', compact(['title', 'bannersCount', 'friendsAndPartnersCount']));
    }
}
