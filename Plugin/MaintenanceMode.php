<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/18
 * Time: 3:43 PM
 */

namespace Codilar\AdvancedMaintenanceMode\Plugin;
use Codilar\AdvancedMaintenanceMode\Model\Config;
use Magento\Framework\App\MaintenanceMode as Subject;
use Magento\Framework\Registry;

class MaintenanceMode
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Registry
     */
    private $registry;

    /**
     * MaintenanceMode constructor.
     * @param Config $config
     * @param Registry $registry
     */
    public function __construct(
        Config $config,
        Registry $registry
    )
    {
        $this->config = $config;
        $this->registry = $registry;
    }

    /**
     * @param Subject $subject
     * @param bool $isOn
     * @return bool[]
     */
    public function beforeSet(Subject $subject, bool $isOn) {
        if ($this->config->isEnabled()) {
            $mode = $isOn ? 1 : 0;
            $this->config->setMaintenanceMode($mode);
        }
        return [$isOn];
    }
}