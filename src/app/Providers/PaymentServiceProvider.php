<?php


namespace App\Providers;


use App\Services\Qiwi\BillService;
use App\Services\Qiwi\Contracts\BillInterface;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     * @throws \ErrorException
     */
    public function register()
    {
        $this->app->singleton(BillInterface::class, BillService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}
