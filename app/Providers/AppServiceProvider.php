<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Notification;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{

    View::composer('*', function ($view) {

        if(auth()->check()){


            $notifications = Notification::where(
                'is_read',
                false
            )
            ->latest()
            ->limit(5)
            ->get();



            $notificationCount = Notification::where(
                'is_read',
                false
            )
            ->count();


            $view->with([

                'notifications'=>$notifications,

                'notificationCount'=>$notificationCount

            ]);


        }


    });


}
}
