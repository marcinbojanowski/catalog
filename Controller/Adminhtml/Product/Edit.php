<?php
declare(strict_types=1);

namespace MB\Catalog\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use MB\Catalog\Api\Data\ProductInterface;
use MB\Catalog\Api\ProductRepositoryInterface;

/**
 * Class Edit
 * @package MB\Catalog\Controller\Adminhtml\Product
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MB_Catalog::product';

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
        $entityId = $this->getRequest()->getParam(ProductInterface::ENTITY_ID);
        $storeId = $this->getRequest()->getParam('store_id', 0);

        try {
            $product = $this->productRepository->getByEntityId($entityId, $storeId);

            /** @var \Magento\Backend\Model\View\Result\Page $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $result->setActiveMenu('MB_Catalog::product')
                ->addBreadcrumb(__('Edit Product'), __('Edit Product'));
            $result->getConfig()
                ->getTitle()
                ->prepend(__('Edit Product: %name', ['name' => $product->getProductId()]));
        } catch (NoSuchEntityException $e) {
            /** @var \Magento\Framework\Controller\Result\Redirect $result */
            $result = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(
                __('Product with entity id "%value" does not exist.', ['value' => $entityId])
            );
            $result->setPath('*/*');
        }
        return $result;
    }
}
