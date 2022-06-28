<?php


namespace App\Providers;


use App\Handlers\Qiwi\PaymentHandler;
use App\Handlers\Qiwi\PaymentHandlerBase;
use App\Repository\InvoiceRepository;
use App\Repository\ProductInvoiceRepository;
use App\Repository\ProductRepository;
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
        $this->app->singleton(BillInterface::class, BillService::class);

        $this->app->singleton(PaymentHandlerBase::class, new PaymentHandler(
            new RequestPaymentService(app()->make(BillInterface::class)),
            app()->make(InvoiceRepository::class),
            app()->make(ProductInvoiceRepository::class),
            app()->make(ProductRepository::class)
        ));
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
