<?php
 
namespace Arlesfishes\ExcessFreeShipping\Block\Adminhtml;
 
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Arlesfishes\ExcessFreeShipping\Block\Adminhtml\Form\Field\CustomColumn;
 
class DynamicField extends AbstractFieldArray
{
    private $dropdownRenderer;

    protected function _prepareToRender()
    {
        $this->addColumn(
            'country_code',
            [
                'label' => __('Country'),
                'renderer' => $this->getDropdownRenderer(),
                'class' => 'required-entry',
            ]
        );
        $this->addColumn(
            'over_amount',
            [
                'label' => __('Over Amount'),
                'class' => 'required-entry',
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];
        $overAmount = $row->getOverAmount();
        if ($overAmount !== null) {
            $options['option_' . $this->getDropdownRenderer()->calcOptionHash($overAmount)] = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }

    private function getDropdownRenderer()
    {
        if (!$this->dropdownRenderer) {
            $this->dropdownRenderer = $this->getLayout()->createBlock(
                CustomColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]);
        }
        return $this->dropdownRenderer;
    }
}