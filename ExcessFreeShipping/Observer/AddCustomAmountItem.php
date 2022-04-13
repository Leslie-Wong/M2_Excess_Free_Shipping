<?php

namespace Arlesfishes\ExcessFreeShipping\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Checkout\Model\Session;

/**
 * Add Weee item to Payment Cart amount.
 */
class AddCustomAmountItem implements ObserverInterface
{
  public $checkout;
  protected $configData;

  public function __construct(
    \Arlesfishes\ExcessFreeShipping\Helper\ConfigData $configData,
    Session $checkout)
  {
    $this->configData = $configData;
    $this->checkout = $checkout;
  }
  /**
   * Add custom amount as custom item to payment cart totals.
   *
   * @param Observer $observer
   * @return void
   */
  public function execute(Observer $observer)
  {
      /** @var \Magento\Payment\Model\Cart $cart */
      $event = $observer->getEvent();
      // Get Order Object
      /* @var $order \Magento\Sales\Model\Order */
      $quote = $this->checkout->getQuote();
      $cart = $event->getCart();

      $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/Arlesfishes_ExcessFreeShipping.log');
      $logger = new \Zend\Log\Logger();
      $logger->addWriter($writer);
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $logger->info('Observer quote =>'.json_encode($quote->getExcessFreeShippingValue()));
      if($quote->getExcessFreeShippingValue() > 0){
        $cart->addCustomItem($this->configData->getConfigData('title'), 1, -1.00 * $quote->getExcessFreeShippingValue(), 'ExcessFreeShipping');
      }
      // $customAmount = 246;
      // $cart->addCustomItem(__('Custom Amount'), 1, -1.00 * $customAmount, 'customfee');
  }
}