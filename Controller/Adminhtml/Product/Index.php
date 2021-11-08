<?php
declare(strict_types=1);

namespace MB\Catalog\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 * @package MB\Catalog\Controller\Adminhtml\Product
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MB_Catalog::product';

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('MB_Catalog::product')
            ->addBreadcrumb(__('Custom Catalog'), __('Product List'));
        $resultPage->getConfig()->getTitle()->prepend(__('Custom Catalog'));
        return $resultPage;
    }
}
