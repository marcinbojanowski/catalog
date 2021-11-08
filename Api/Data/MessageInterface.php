<?php
declare(strict_types=1);

namespace MB\Catalog\Api\Data;

/**
 * Interface MessageInterface
 * @package MB\Catalog\Api\Data
 */
interface MessageInterface
{
    /**
     * Product
     */
    public const PRODUCT_DATA = 'product_data';
    public const STORE_ID = 'store_id';

    /**
     * Get Product data
     *
     * @return string
     */
    public function getProductData(): string;

    /**
     * Get Store id
     *
     * @return ?int
     */
    public function getStoreId(): ?int;

    /**
     * Set product data
     *
     * @param string $data
     * @return MessageInterface
     */
    public function setProductData(string $data): MessageInterface;

    /**
     * Set Store id
     *
     * @param ?int $storeId
     * @return MessageInterface
     */
    public function setStoreId(?int $storeId): MessageInterface;
}
