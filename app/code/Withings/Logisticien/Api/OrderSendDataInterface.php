<?php


namespace Withings\Logisticien\Api;


interface OrderSendDataInterface
{
    /**
     * @param string|null $data
     * @return int
     */
    public function sendOrderData(string $data = null);
}
