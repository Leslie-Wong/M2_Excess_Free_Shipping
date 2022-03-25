<?php

namespace Arlesfishes\ExcessFreeShipping\Model\Quote;

class FreeShipping extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * Discount calculation object
     *
     * @var \Magento\SalesRule\Model\Validator
     */
    protected $calculator;

    /**
     * Core event manager proxy
     *
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager = null;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    protected $scopeConfig;

    protected $configData;

    /**
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\SalesRule\Model\Validator $validator
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\SalesRule\Model\Validator $validator,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Arlesfishes\ExcessFreeShipping\Helper\ConfigData $configData
    ) {
        $this->setCode('excessfreeshipping');
        $this->eventManager = $eventManager;
        $this->calculator = $validator;
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
        $this->scopeConfig = $scopeConfig;
        $this->configData = $configData;
    }

    /**
     * Retrieve information from carrier configuration
     *
     * @param   string $field
     * @return  false|string
     */
    public function getConfigData($field)
    {
        return $this->configData->getConfigData($field);
    }

    /**
     * Collect address discount amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        // $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/Arlesfishes_ExcessFreeShipping.log');
        // $logger = new \Zend\Log\Logger();
        // $logger->addWriter($writer);

        // $logger->info('Shipping specificcountry: '. json_encode($this->getConfigData('specificcountry')) );
        // $logger->info('Shipping getData: '. json_encode($quote->getData()) );
        // $logger->info('Shipping $total: '. var_export($total, true)  );
        if($this->getConfigData('enable') == 1 && $this->getConfigData('specificcountry') && $quote->getShippingAddress()->getCountryId() && str_contains($this->getConfigData('specificcountry'), $quote->getShippingAddress()->getCountryId())){
            if($this->getConfigData('excess') != "" && $total->getSubtotalWithDiscount() >= floatval($this->getConfigData('excess')) && $total->getShippingAmount() > 0){

                // $logger->info('Shipping Amount: '.$total->getShippingAmount());
                
                $baseDiscount = $total->getShippingAmount();
                $discount =  $this->priceCurrency->convert($baseDiscount);
                // $logger->info('Shipping discount: '.$total->getShippingAmount());
                $total->addTotalAmount('excessfreeshipping', -$discount);
                $total->addBaseTotalAmount('excessfreeshipping', -$baseDiscount);
                // $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
                // $quote->setExcessfreeshipping(-$discount);
                $quote->setExcessFreeShippingValue($discount);
                $total->setBaseShippingDiscountAmount($discount);
                $quote->save();
                // $logger->info('Shipping getData 2: '. json_encode($quote->getData()) );
                #$address             = $shippingAssignment->getShipping()->getAddress();
                
                // $label               = __('Free Shipping Discount');
                // $discountAmount      = $total->getShippingAmount() * -1;   
                // $appliedCartDiscount = 0;
                // if($total->getDiscountDescription()) {
                //     // If a discount exists in cart and another discount is applied, the add both discounts.
                //     $appliedCartDiscount = $total->getDiscountAmount();
                //     $discountAmount      = $total->getDiscountAmount()+$discountAmount;
                //     // $label  	         = $total->getDiscountDescription().', '.$label;
                // }    
                
                // // $total->setDiscountDescription($label);
                // $total->setDiscountAmount($discountAmount);
                // $total->setBaseDiscountAmount($discountAmount);
                // $total->setSubtotalWithDiscount($total->getSubtotal() + $discountAmount);
                // $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $discountAmount);
                
                // if(isset($appliedCartDiscount)) {
                //     $total->addTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
                //     $total->addBaseTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
                // } else {
                //     $total->addTotalAmount($this->getCode(), $discountAmount);
                //     $total->addBaseTotalAmount($this->getCode(), $discountAmount);
                // }
            }else{
                if($total->getSubtotal() > 0){
                    $quote->setExcessFreeShippingValue(NULL);
                    // $logger->info('Shipping getData [else]: '. json_encode($quote->getData()) );
                    $quote->save();
                }
            }
        }else{
            if($quote->getExcessFreeShippingValue() != null){
                $quote->setExcessFreeShippingValue(NULL);
                $quote->save();
                // $logger->info('Shipping getData [else 2]: '. json_encode($quote->getData()) );
            }
        }
        
        
            
        return $this;
    }

 

    /**
     * Add discount total information to address
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array|null
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        // $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/Arlesfishes_ExcessFreeShipping.log');
        // $logger = new \Zend\Log\Logger();
        // $logger->addWriter($writer);

        $result = null;
        $amount = $quote->getExcessFreeShippingValue();

        // $logger->info('Shipping getData fetch: '. json_encode($quote->getData()) );
        // ONLY return 1 discount. Need to append existing
        //see app/code/Magento/Quote/Model/Quote/Address.php
        
        if ($amount != 0) { 
            $description = $total->getDiscountDescription();
            $result = [
                'code' => $this->getCode(),
                'title' => __('Excess Free Shipping'),
                'value' => $total->getShippingAmount(),
            ];
        }
        return $result;
        
        
        /* in magento 1.x
           $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => $title,
                'value' => $amount
            ));
         */
    }
}
