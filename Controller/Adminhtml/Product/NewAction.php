<?php
declare(strict_types=1);

namespace MB\Catalog\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * NewAction Controller
 */
class NewAction extends Action implements HttpGetActionInterface
{
    /**
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'MB_Catalog::product_save';

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('MB_Catalog::product');
        $resultPage->getConfig()->getTitle()->prepend(__('New Product'));

        return $resultPage;
    }
}
