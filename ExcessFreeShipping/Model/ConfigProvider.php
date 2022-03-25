<?php

namespace Arlesfishes\ExcessFreeShipping\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
  protected $configData;
  protected $checkoutSession;

  public function __construct(
    \Magento\Checkout\Model\Session $checkoutSession,
    \Arlesfishes\ExcessFreeShipping\Helper\ConfigData $configData
  ) {
    $this->configData = $configData;
    $this->checkoutSession = $checkoutSession;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig()
  {
    // $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/Arlesfishes_ExcessFreeShipping.log');
    // $logger = new \Zend\Log\Logger();
    // $logger->addWriter($writer);

    // $logger->info('getConfig');

    $config = [
      'excessFreeShipping' => [
          'title' => $this->configData->getConfigData('title'),
          'quoteId' => $this->checkoutSession->getQuote()->getId(),
          'quoteData' => $this->checkoutSession->getQuote()->getData(),
          'freevalue' => $this->checkoutSession->getQuote()->getExcessFreeShippingValue(),
      ],
    ];
    return $config;
  }
}