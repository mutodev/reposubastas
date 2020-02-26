<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('APP_ENV') === 'production') {
            \URL::forceScheme('https');
        }

        view()->composer('frontend.partials.bidding', function ($view) {
            $userDeposits = [];

            if (!\Auth::guest()) {
                $userDeposits = \App\Models\UserDeposit::whereRaw('user_deposit.property_id IS NULL AND user_deposit.refunded IS NULL AND user_deposit.user_id = ?', [\Auth::user()->id])->get();
            }

            $depositAmount = 0;
            $offersLeft = 0;

            foreach($userDeposits as $deposit) {
                $depositAmount += $deposit->amount;
                $offersLeft++;
            }

            view()->share('depositAmount', $depositAmount);
            view()->share('offersLeft', $offersLeft);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
