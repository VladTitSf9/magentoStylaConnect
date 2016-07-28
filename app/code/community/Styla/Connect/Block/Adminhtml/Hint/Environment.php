<?php

/**
 * Class Styla_Connect_Block_Adminhtml_Hint_Environment
 */
class Styla_Connect_Block_Adminhtml_Hint_Environment
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_template = 'styla/connect/adminhtml/hint/environment.phtml';

    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }

    /**
     *
     * @return bool
     */
    public function userNeedsToRegister()
    {
        return !Mage::helper('styla_connect/config')->isConfiguredForThisMode();
    }

    /**
     * Get styla connect assistant url
     *
     * @return string
     */
    public function getConnectUrl()
    {
        $request = $this->getRequest();

        $params = array();
        if ($website = $request->getParam('website')) {
            $params['website'] = $website;
        }

        if ($store = $request->getParam('store')) {
            $params['store'] = $store;
        }

        $url = Mage::helper('adminhtml')->getUrl('adminhtml/styla_api/index', $params);
        return $url;
    }
}