<?php


namespace App\dto;


class OrderResponse
{
    /**
     * @var string
     */
    private string $billId;
    /**
     * @var array
     */
    private array $products;
    /**
     * @var float
     */
    private float $totalPrice;
    /**
     * @var string
     */
    private string $payUrl;


    public function __construct(string $billId, array $products, float $totalPrice, string $payUrl) {
        $this->billId = $billId;
        $this->payUrl = $payUrl;
        $this->products = $products;
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return array
     */
    final public function toArray(): array {
        return [
           'billId' => $this->billId,
           'products' => $this->products,
           'totalPrice' => $this->totalPrice,
           'payUrl' => $this->payUrl,
        ];
    }
}
