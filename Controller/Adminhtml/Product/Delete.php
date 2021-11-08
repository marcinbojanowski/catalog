<?php
declare(strict_types=1);

namespace MB\Catalog\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use MB\Catalog\Api\Data\ProductInterface;
use MB\Catalog\Api\ProductRepositoryInterface;

/**
 * Class Delete
 * @package MB\Catalog\Controller\Adminhtml\Product
 */
class Delete extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MB_Catalog::product_delete';

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param Action\Context $context
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Action\Context $context,
        ProductRepositoryInterface $productRepository
    )
    {
        parent::__construct($context);
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $entityId = $this->getRequest()->getParam(ProductInterface::ENTITY_ID);
        if ($entityId === null) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));
            return $resultRedirect->setPath('*/*');
        }

        try {
            $entityId = (int)$entityId;
            $this->productRepository->deleteByEntityId($entityId);
            $this->messageManager->addSuccessMessage(__('The Product has been deleted.'));
            $resultRedirect->setPath('*/*');
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect->setPath('*/*/edit', [
                ProductInterface::ENTITY_ID => $entityId,
                '_current' => true,
            ]);
        }

        return $resultRedirect;
    }
}
