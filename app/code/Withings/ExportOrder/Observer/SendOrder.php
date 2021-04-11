<?php

namespace Withings\ExportOrder\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class SendOrder implements ObserverInterface
{
    const ULR_API_LOGISTICIEN = "/V1/logisticien/data";
    const ULR_HTTP_LOGISTICIEN = "http://logisticien.com";

    /**
     * @var OrderRepositoryInterface
     */

    protected $orderRepository;

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * SendOrder constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param Curl $curl
     */
    public function __construct(OrderRepositoryInterface $orderRepository, Curl $curl)
    {
        $this->orderRepository = $orderRepository;
        $this->curl = $curl;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order) {
            $url = self::ULR_HTTP_LOGISTICIEN . self::ULR_API_LOGISTICIEN;
            $data = $this->getDataApiByOrder($order);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Content-Length", 200);
            $this->curl->post($url, json_encode($data));
            $order->setData('logisiticien_id', $this->curl->getBody());
            $this->orderRepository->save($order);
        }
    }

    /**
     * @param OrderInterface $order
     * @return array[]
     */
    protected function getDataApiByOrder(OrderInterface $order)
    {
        $dataCustomer = [
            'email' => $order->getCustomerEmail(),
            'fistname' => $order->getCustomerFirstname(),
            'lastname' => $order->getCustomerLastname()
        ];

        $dataOrder = [
            'shipping_method' => $order->getShippingMethod(),
            'shipping_address' => $order->getShippingAddress(),
            'total' => $order->getGrandTotal(),
            'currency' => $order->getOrderCurrencyCode(),
            'items' => $this->getOrderItems($order)
        ];

        return [
            'customer' => $dataCustomer,
            'order' => $dataOrder,
        ];
    }

    /**
     * @param OrderInterface $order
     * @return array
     */
    protected function getOrderItems(OrderInterface $order)
    {
        $items = [];
        foreach ($order->getAllVisibleItems() as $item) {
            $items[] = $item->getData();
        }

        return $items;
    }
}
