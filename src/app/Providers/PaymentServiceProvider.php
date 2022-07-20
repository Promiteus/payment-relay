<?php


namespace App\Providers;


use App\Handlers\Qiwi\PaymentHandler;
use App\Services\ProductInvoiceService;
use App\Services\Qiwi\BillService;
use App\Services\Qiwi\Contracts\BillInterface;
use App\Services\Contracts\ProductInvoiceServiceInterface;
use App\Services\Qiwi\Contracts\RequestPaymentServiceInterface;
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
        $this->app->singleton(BillInterface::class, function () {
            return new BillService();
        });

        $this->app->singleton(ProductInvoiceServiceInterface::class, ProductInvoiceService::class);

        $this->app->singleton(RequestPaymentServiceInterface::class, function() {
            return new RequestPaymentService(app(BillInterface::class));
        });

        $this->app->singleton(PaymentHandler::class, function () {
            return new PaymentHandler(
                app()->make(RequestPaymentServiceInterface::class),
                app()->make(ProductInvoiceServiceInterface::class));
        });
    }

}
