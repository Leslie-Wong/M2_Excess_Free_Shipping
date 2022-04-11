<?php

namespace Arlesfishes\ExcessFreeShipping2\Helper;

class ConfigData extends \Magento\Payment\Helper\Data
{
    
  protected $storeManager;

  protected $scopeConfig;


  public function __construct(
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Store\Model\StoreManagerInterface $storeManager
  ) {
      $this->storeManager = $storeManager;
      $this->scopeConfig = $scopeConfig;
  }

  /**
   * Retrieve information from carrier configuration
   *
   * @param   string $field
   * @return  false|string
   */
  public function getConfigData($field)
  {
      $path = 'excessfreeshipping2/settings/' . $field;

      return $this->scopeConfig->getValue(
          $path,
          \Magento\Store\Model\ScopeInterface::SCOPE_STORE
      );
  }

}