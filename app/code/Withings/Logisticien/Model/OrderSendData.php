<?php


namespace Withings\Logisticien\Model;


use Withings\Logisticien\Api\OrderSendDataInterface;

class OrderSendData implements OrderSendDataInterface
{

    /**
     * data contient toutes les informations concernant Order
     * @param string|null $data
     * @return int
     */
    public function sendOrderData(string $data = null)
    {
        $order = json_decode($data, true);
        $date = new \DateTime();
        return $date->getTimestamp();
    }
}
