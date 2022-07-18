<?php


namespace App\Providers;


use App\Handlers\Qiwi\PaymentHandler;
use App\Services\ProductInvoiceService;
use App\Services\Qiwi\BillService;
use App\Services\Qiwi\Contracts\BillInterface;
use App\Services\Qiwi\RequestPaymentService;
use Illuminate\Support\ServiceProvider;
use Qiwi\Api\BillPayments;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->app->singleton(BillPayments::class, function() {
            return new BillPayments(env('QIWI_SECRET_KEY'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        $this->app->singleton(BillInterface::class, function () {
            return new BillService(app(BillPayments::class));
        });

        $this->app->singleton(ProductInvoiceService::class, ProductInvoiceService::class);

        $this->app->singleton(RequestPaymentService::class, function() {
            return new RequestPaymentService(app(BillInterface::class));
        });

        $this->app->singleton(PaymentHandler::class, function () {
            return new PaymentHandler(
                app()->make(RequestPaymentService::class),
                app()->make(ProductInvoiceService::class));
        });
    }

}
