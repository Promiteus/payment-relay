<?php


namespace App\Providers;


use App\Handlers\Qiwi\PaymentHandler;
use App\Handlers\Qiwi\PaymentHandlerBase;
use App\Services\ProductInvoiceService;
use App\Services\Qiwi\BillService;
use App\Services\Qiwi\Contracts\BillInterface;
use App\Services\Qiwi\RequestPaymentService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(BillInterface::class, BillService::class);

        $this->app->singleton(ProductInvoiceService::class, ProductInvoiceService::class);

        $this->app->singleton(RequestPaymentService::class, function() {
            return new RequestPaymentService(app()->make(BillService::class));
        });

        $this->app->singleton(PaymentHandler::class, function () {
            return new PaymentHandler(
                app()->make(RequestPaymentService::class),
                app()->make(ProductInvoiceService::class));
        });
    }

}
