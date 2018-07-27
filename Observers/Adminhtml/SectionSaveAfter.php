<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/18
 * Time: 4:10 PM
 */

namespace Codilar\AdvancedMaintenanceMode\Observers\Adminhtml;

use Codilar\AdvancedMaintenanceMode\Model\Config;
use Magento\Framework\App\MaintenanceMode;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SectionSaveAfter implements ObserverInterface
{
    /**
     * @var MaintenanceMode
     */
    private $maintenanceMode;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var Config
     */
    private $config;

    /**
     * SectionSaveAfter constructor.
     * @param MaintenanceMode $maintenanceMode
     * @param RequestInterface $request
     * @param Config $config
     */
    public function __construct(
        MaintenanceMode $maintenanceMode,
        RequestInterface $request,
        Config $config
    )
    {
        $this->maintenanceMode = $maintenanceMode;
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->config->isEnabled()) {
            $groups = $this->request->getParam('groups');
            if (is_array($groups)) { // groups is present
                if (isset($groups['general']) && is_array($groups['general'])) { // general group is present
                    $fields = (isset($groups['general']['fields']) && is_array($groups['general']['fields'])) ? $groups['general']['fields'] : null;
                    if (isset($fields['maintenance_mode_enable']) && is_array($fields['maintenance_mode_enable']) && isset($fields['maintenance_mode_enable']['value'])) { // maintenance_mode_enable value set
                        $value = $fields['maintenance_mode_enable']['value'] == 1 ? true : false;
                        $this->maintenanceMode->set($value);
                    }
                }
            }
        }
    }
}