<?php
declare(strict_types=1);

namespace MB\Catalog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use MB\Catalog\Api\Data\ProductInterface;
use MB\Catalog\Api\Data\ProductSearchResultsInterface;

/**
 * Interface ProductRepositoryInterface
 * @package MB\Catalog\Api
 */
interface ProductRepositoryInterface
{

    /**
     * Save Product
     * @param ProductInterface $product
     * @return ProductInterface
     * @throws CouldNotSaveException
     */
    public function save(ProductInterface $product): ProductInterface;

    /**
     * Retrieve Product by ID
     * @param string $productId
     * @return ProductInterface
     * @throws LocalizedException
     */
    public function getByEntityId(int $entityId, $storeId = null): ProductInterface;

    /**
     * Retrieve Product by Product ID
     * @param string $productId
     * @return ProductInterface
     * @throws LocalizedException
     */
    public function getByProductId(string $productId, $storeId = null): ProductInterface;

    /**
     * Retrieve Product matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ProductSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): ProductSearchResultsInterface;

    /**
     * Delete Product by ID
     * @param int $entityId
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function deleteByEntityId(int $entityId): bool;
}

