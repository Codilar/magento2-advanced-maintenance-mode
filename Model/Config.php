<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/18
 * Time: 3:47 PM
 */

namespace Codilar\AdvancedMaintenanceMode\Model;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface as ConfigWriterInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;
use Magento\Cms\Model\Page as PageModel;
use Magento\Cms\Model\PageFactory as PageModelFactory;
use Magento\Cms\Model\ResourceModel\Page as PageResource;


class Config
{
    /**
     * @var ConfigWriterInterface
     */
    private $configWriter;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;
    /**
     * @var PageModelFactory
     */
    private $pageModelFactory;
    /**
     * @var PageResource
     */
    private $pageResource;

    /**
     * Config constructor.
     * @param ConfigWriterInterface $configWriter
     * @param ScopeConfigInterface $scopeConfig
     * @param PageRepositoryInterface $pageRepository
     * @param PageModelFactory $pageModelFactory
     * @param PageResource $pageResource
     */
    public function __construct(
        ConfigWriterInterface $configWriter,
        ScopeConfigInterface $scopeConfig,
        PageRepositoryInterface $pageRepository,
        PageModelFactory $pageModelFactory,
        PageResource $pageResource
    )
    {
        $this->configWriter = $configWriter;
        $this->scopeConfig = $scopeConfig;
        $this->pageRepository = $pageRepository;
        $this->pageModelFactory = $pageModelFactory;
        $this->pageResource = $pageResource;
    }

    public function isEnabled() {
        return (bool) $this->getValue('enable');
    }

    public function setMaintenanceMode($mode) {
        $this->setValue($mode, "maintenance_mode_enable");
    }

    public function getMaintenanceResponseCode() {
        return $this->getValue('response_code', 'page');
    }

    public function getMaintenanceResponseHeader() {
        return $this->getValue('maintenance_header', 'page');
    }

    /**
     * @return string
     */
    public function getMaintenanceCmsPage() {
        return $this->getValue('cms_page', 'page');
    }

    public function getValue($field, $group = "general", $section = "maintenance_mode_configuration") {
        return $this->scopeConfig->getValue("$section/$group/$field", ScopeInterface::SCOPE_STORE);
    }

    public function setValue($value, $field, $group = "general", $section = "maintenance_mode_configuration") {
        $this->configWriter->save("$section/$group/$field",  $value, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }
}