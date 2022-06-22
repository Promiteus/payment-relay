<?php

namespace App\dto;

/**
 * Выходной формат данных при запросе к платежному провайдеру
 * Class PayResponse
 */
class PayResponse
{
    /**
     * @var array
     */
    private array $data = [];
    /**
     * @var string
     */
    private string $error = '';

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }



    /**
     * PayResponse constructor.
     * @param array $data
     * @param string $error
     */
    public function __construct(array $data, string $error) {
        $this->data = $data;
        $this->error = $error;
    }

    /**
     * @return array
     */
    final public function toArray(): array {
        return [
            'data' => $this->data,
            'error' => $this->error,
        ];
    }
}
