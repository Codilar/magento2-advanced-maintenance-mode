<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18/4/18
 * Time: 4:46 PM
 */

namespace Codilar\AdvancedMaintenanceMode\Block\Adminhtml\System\Config;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;

class Editor extends Field
{
    /**
     * @var WysiwygConfig
     */
    private $wysiwygConfig;

    /**
     * Editor constructor.
     * @param Context $context
     * @param WysiwygConfig $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        WysiwygConfig $wysiwygConfig,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->wysiwygConfig = $wysiwygConfig;
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->setWysiwyg(true);
        $element->setConfig($this->wysiwygConfig->getConfig($element));
        return parent::_getElementHtml($element);
    }
}