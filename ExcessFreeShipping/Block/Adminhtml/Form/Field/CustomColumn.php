<?php
 
namespace Arlesfishes\ExcessFreeShipping\Block\Adminhtml\Form\Field;
 
use Magento\Framework\View\Element\Html\Select;
 
use Magento\Customer\Model\ResourceModel\Group\Collection;
use Magento\Backend\Block\Template\Context;
use Magento\Customer\Model\Customer\Attribute\Source\GroupSourceLoggedInOnlyInterface;
use Magento\Directory\Model\Config\Source\Country;
use Magento\Framework\App\ObjectManager;
 
class CustomColumn extends Select
{
    protected $countries;
 
    public function __construct(Context $context, Country $countries = null, array $data = [])
    {
        $this->countries = $countries
            ?: ObjectManager::getInstance()->get(Country::class);
        parent::__construct($context, $data);
    }
 
    public function setInputName($value)
    {
        return $this->setName($value);
    }
 
    public function setInputId($value)
    {
        return $this->setId($value);
    }
 
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }
        return parent::_toHtml();
    }
 
    private function getSourceOptions()
    {
        $options = $this->countries->toOptionArray(true);
        array_unshift($options, ['value' => 'OTHERS', 'label' => __('Other Countries')]);
        array_unshift($options, ['value' => 'ALL', 'label' => __('All Countries')]);
        array_unshift($options, ['value' => '', 'label' => __('--Please Select--')]);
        // $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/Arlesfishes_ExcessFreeShipping.log');
        // $logger = new \Zend\Log\Logger();
        // $logger->addWriter($writer);

        // $logger->info('this->countries->toOptionArray'. var_export($options, true) );
        return $options;
    }
}