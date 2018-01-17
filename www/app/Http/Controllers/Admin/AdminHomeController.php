<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;

use DB;

class AdminHomeController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
       
		$title = 'Панель администратора';
		
		// Версия MySQL
		$mysqlVer = DB::select('SELECT VERSION() as mysql_version');
		
        // Свободное место на диске 
		$bytes = disk_free_space('.'); 
		$siPrefix = array( '<', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
		$base = 1024;
		$class = min((int)log($bytes , $base) , count($siPrefix) - 1);
		$totalFreeSpace = sprintf('%1.2f' , $bytes / pow($base,$class)) . $siPrefix[$class];
		
		return view('admin.Main', compact(['title', 'totalFreeSpace', 'mysqlVer']));
    }
}
