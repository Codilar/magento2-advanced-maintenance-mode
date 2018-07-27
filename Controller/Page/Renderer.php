<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/18
 * Time: 5:15 PM
 */

namespace Codilar\AdvancedMaintenanceMode\Controller\Page;


use Codilar\AdvancedMaintenanceMode\Plugin\App;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Renderer extends Action
{
    /**
     * @var PageFactory
     */
    private $pageFactory;
    /**
     * @var Registry
     */
    private $registry;
    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * Renderer constructor.
     * @param PageFactory $pageFactory
     * @param Registry $registry
     * @param Context $context
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        PageFactory $pageFactory,
        Registry $registry,
        Context $context,
        FilterProvider $filterProvider
    )
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->registry = $registry;
        $this->filterProvider = $filterProvider;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $page = $this->pageFactory->create();
        if ($cmsPage = $this->registry->registry(App::MAINTENANCE_CMS_PAGE_KEY)) {
            /* @var PageInterface $cmsPage */
            $content = $this->filterProvider->getPageFilter()->filter($cmsPage->getContent());

            echo '
<html>
    <head>
        <title>'.$cmsPage->getTitle().'</title>                    
    </head>
    <body>
        '.$content.'
    </body>
</html>
            ';exit(0);
        }
        return $page;
    }
}