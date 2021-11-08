<?php
declare(strict_types=1);

namespace MB\Catalog\Controller\Adminhtml\Product;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validation\ValidationException;
use MB\Catalog\Api\Data\ProductInterface;
use MB\Catalog\Api\Data\ProductInterfaceFactory;
use MB\Catalog\Api\ProductRepositoryInterface;

/**
 * Class Save
 * @package MB\Catalog\Controller\Adminhtml\Product
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'MB_Catalog::product';

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param Action\Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param ProductInterfaceFactory $productFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        Action\Context $context,
        ProductRepositoryInterface $productRepository,
        ProductInterfaceFactory $productFactory,
        DataObjectHelper $dataObjectHelper
    )
    {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $request = $this->getRequest();
        $storeId = $this->getRequest()->getParam('store_id', 0);
        $requestData = $request->getPost()->toArray();

        if (!$request->isPost()) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));
            $this->processRedirectAfterFailureSave($resultRedirect);
            return $resultRedirect;
        }
        if (isset($requestData[ProductInterface::ENTITY_ID])) {
            $entityId = $requestData[ProductInterface::ENTITY_ID];
            try {
                $product = $this->productRepository->getByEntityId($entityId, $storeId);
            } catch (NoSuchEntityException $e) {
                $product = $this->productFactory->create();
            }
        } else {
            $product = $this->productFactory->create();
        }
        if (!$product->isObjectNew() && !isset($requestData['id_field_name'])) {
            $this->messageManager->addErrorMessage(__('Could not save Product.'));
            $this->_session->setProductFormData($requestData);
            $this->processRedirectAfterFailureSave($resultRedirect, $product, $requestData);
        } else {
            try {
                $this->processSave($product, $requestData);
                $this->messageManager->addSuccessMessage(__('The Product has been saved.'));
                $this->processRedirectAfterSuccessSave($resultRedirect, $product->getEntityId());
            } catch (ValidationException $e) {
                foreach ($e->getErrors() as $localizedError) {
                    $this->messageManager->addErrorMessage($localizedError->getMessage());
                }
                $this->_session->setProductFormData($requestData);
                $this->processRedirectAfterFailureSave($resultRedirect, $product);
            } catch (CouldNotSaveException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_session->setProductFormData($requestData);
                $this->processRedirectAfterFailureSave($resultRedirect, $product);
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__('Could not save Product.'));
                $this->_session->setProductFormData($requestData);
                $this->processRedirectAfterFailureSave($resultRedirect, $product);
            }
        }
        return $resultRedirect;
    }

    /**
     * Hydrate data from request and save product.
     *
     * @param ProductInterface $product
     * @param array $requestData
     * @return void
     * @throws CouldNotSaveException
     * @throws ValidationException
     */
    private function processSave(ProductInterface $product, array $requestData)
    {
        $this->dataObjectHelper->populateWithArray($product, $requestData, ProductInterface::class);
        $this->productRepository->save($product);
    }

    /**
     * Get redirect url after product save.
     *
     * @param \Magento\Framework\Controller\Result\Redirect $resultRedirect
     * @param int $entityId
     * @return void
     */
    private function processRedirectAfterSuccessSave(Redirect $resultRedirect, int $entityId)
    {
        if ($this->getRequest()->getParam('back')) {
            $resultRedirect->setPath(
                '*/*/edit',
                [
                    ProductInterface::ENTITY_ID => $entityId,
                    '_current' => true,
                ]
            );
        } elseif ($this->getRequest()->getParam('redirect_to_new')) {
            $resultRedirect->setPath(
                '*/*/new',
                [
                    '_current' => true,
                ]
            );
        } else {
            $resultRedirect->setPath('*/*/');
        }
    }

    /**
     * Get redirect url after unsuccessful product save.
     *
     * @param \Magento\Framework\Controller\Result\Redirect $resultRedirect
     * @param null|ProductInterface $product
     * @param array $requestData
     * @return void
     */
    private function processRedirectAfterFailureSave(
        Redirect $resultRedirect,
        ?ProductInterface $product = null,
        array $requestData = []
    ) {
        if (!$product
            || $product->isObjectNew()
            || !$product->isObjectNew() && !isset($requestData['id_field_name'])
        ) {
            $resultRedirect->setPath('*/*/new');
        } else {
            $resultRedirect->setPath(
                '*/*/edit',
                [
                    ProductInterface::ENTITY_ID => $product->getEntityId(),
                    '_current' => true,
                ]
            );
        }
    }
}
