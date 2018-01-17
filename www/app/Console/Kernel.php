<?php

namespace App\Console;

use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		// Обнуляем рейтинг у магазинов раз в месяц
		$schedule->call(function(){
			
			$shops = DB::table('shops')->get();
			
			foreach( $shops as $shop )
				DB::table('shops')->where( 'id', $shop->id )->update(['rating' => 0]);
		
		})->cron('1 0 1 * * *');
    }
}
