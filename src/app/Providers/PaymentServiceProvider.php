<?php


namespace App\Providers;


use App\Services\Qiwi\Contracts\BillInterface;
use Illuminate\Support\ServiceProvider;
use Qiwi\Api\BillPayments;

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
        $this->app->singleton(BillInterface::class, new BillPayments(config('services.qiwi.secret')));
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
