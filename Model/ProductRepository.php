<?php
declare(strict_types=1);

namespace MB\Catalog\Model;

use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use MB\Catalog\Api\Data\ProductInterface;
use MB\Catalog\Api\Data\ProductInterfaceFactory;
use MB\Catalog\Api\Data\ProductSearchResultsInterface;
use MB\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use MB\Catalog\Api\ProductRepositoryInterface;
use MB\Catalog\Model\ResourceModel\Product as ResourceProduct;
use MB\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

/**
 * Class ProductRepository
 * @package MB\Catalog\Model
 */
class ProductRepository implements ProductRepositoryInterface
{

    /**
     * @var ResourceProduct
     */
    private $resource;

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var ProductSearchResultsInterfaceFactory
     */
    private $productSearchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param ResourceProduct $resource
     * @param ProductInterfaceFactory $productFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param ProductSearchResultsInterfaceFactory $productSearchResultsFactory
     */
    public function __construct(
        ResourceProduct $resource,
        ProductInterfaceFactory $productFactory,
        ProductCollectionFactory $productCollectionFactory,
        ProductSearchResultsInterfaceFactory $productSearchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->resource = $resource;
        $this->productFactory = $productFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productSearchResultsFactory = $productSearchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

    }

    /**
     * {@inheritdoc}
     */
    public function save(ProductInterface $product): ProductInterface
    {
        try {
            $this->resource->save($product);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the product'), $exception);
        }

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): ProductSearchResultsInterface
    {

        $collection = $this->productCollectionFactory->create();

        if (null === $searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        /** @var ProductSearchResultsInterface $searchResults */
        $searchResults = $this->productSearchResultsFactory->create();
        $searchResults->setItems($collection->getItems());
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByEntityId(int $entityId): bool
    {
        try {
            $model = $this->getByEntityId($entityId);
            $this->resource->delete($model);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete product with Entity ID "%1"', $entityId), $exception);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getByEntityId($entityId, $storeId = null): ProductInterface
    {
        $product = $this->productFactory->create();
        if ($storeId !== null) {
            $product->setStoreId((int)$storeId);
        }
        $this->resource->load($product, $entityId);

        if (!$product->getEntityId()) {
            throw new NoSuchEntityException(__('Product with Entity ID "%1" does not exist.', $entityId));
        }

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function getByProductId($productId, $storeId = null): ProductInterface
    {
        $product = $this->productFactory->create();
        if ($storeId !== null) {
            $product->setData('store_id', $storeId);
        }
        $this->resource->load($product, $productId, ProductInterface::PRODUCT_ID);

        if (!$product->getEntityId()) {
            throw new NoSuchEntityException(__('Product with Product ID "%1" does not exist.', $productId));
        }

        return $product;
    }
}
