<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/18
 * Time: 3:14 PM
 */

namespace Codilar\AdvancedMaintenanceMode\Plugin;
use Codilar\AdvancedMaintenanceMode\Model\Config;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\AppInterface as Subject;
use Magento\Framework\App\Bootstrap;
use Magento\Framework\Registry;

class App
{
    const MAINTENANCE_CMS_PAGE_KEY = "codilar_advanced_maintenance_mode_cms_page";

    /**
     * @var Config
     */
    private $config;
    /**
     * @var \Magento\Framework\App\Response\Http
     */
    private $response;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * App constructor.
     * @param Config $config
     * @param ResponseInterface $response
     * @param Registry $registry
     */
    public function __construct(
        Config $config,
        ResponseInterface $response,
        Registry $registry
    )
    {
        $this->config = $config;
        $this->response = $response;
        $this->registry = $registry;
    }

    public function aroundCatchException(Subject $subject, callable $proceed, Bootstrap $bootstrap, \Exception $exception) {
        if ($this->config->isEnabled()) {
            if ($bootstrap->getErrorCode() === Bootstrap::ERR_MAINTENANCE) {
                header($this->config->getMaintenanceResponseHeader().': true');
                $page = $this->config->getMaintenanceCmsPage();
                $this->response->setHttpResponseCode($this->config->getMaintenanceResponseCode());
                $this->response->appendBody($page);
                $this->response->sendResponse();
                return true;
            } else {
                header($this->config->getMaintenanceResponseHeader().': false');
                return $proceed($bootstrap, $exception);
            }
        }
        return $proceed($bootstrap, $exception);
    }
}