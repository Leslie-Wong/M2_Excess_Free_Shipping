<?php declare(strict_types=1);
/**
 * Save Custom Fee in sales_order before place an order.
 */

namespace Arlesfishes\ExcessFreeShipping2\Observer;

use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SalesModelServiceQuoteSubmitBefore implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return $this;
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        // Get Order Object
        /* @var $order \Magento\Sales\Model\Order */
        $order = $event->getOrder();
        // Get Quote Object
        /** @var $quote \Magento\Quote\Model\Quote $quote */
        $quote = $event->getQuote();

        // $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/Arlesfishes_ExcessFreeShipping.log');
        // $logger = new \Zend\Log\Logger();
        // $logger->addWriter($writer);

        // $logger->info("SalesModelServiceQuoteSubmitBefore getExcessFreeShippingValue".json_encode($quote->getData()));
        if ($quote->getExcessFreeShippingValue() > 0) {
            $order->setBaseShippingAmount(0);
            $order->setShippingAmount(0);
            $order->save();
        }

        return $this;
    }
}