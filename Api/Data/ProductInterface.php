<?php
declare(strict_types=1);

namespace MB\Catalog\Api\Data;

/**
 * Interface ProductInterface
 * @package MB\Catalog\Api\Data
 */
interface ProductInterface
{
    public const ENTITY_ID = 'entity_id';
    public const PRODUCT_ID = 'product_id';
    public const VPN = 'vpn';
    public const SKU = 'sku';
    public const COPY_WRITE_INFO = 'copy_write_info';

    /**
     * Get Entity ID
     *
     * @return int
     */
    public function getEntityId(): int;

    /**
     * Get Product ID
     *
     * @return string
     */
    public function getProductId(): string;

    /**
     * Get VPN
     * @return string
     */
    public function getVpn(): string;

    /**
     * Get Sku
     * @return string
     */
    public function getSku(): string;

    /**
     * Get Copy Write Info
     * @return string|null
     */
    public function getCopyWriteInfo(): ?string;

    /**
     * Set Entity Id
     * @param int $entityId
     * @return ProductInterface
     */
    public function setEntityId($entityId): ProductInterface;

    /**
     * Set Product ID
     * @param string $productId
     * @return ProductInterface
     */
    public function setProductId(string $productId): ProductInterface;

    /**
     * Set sku
     * @param string $vpn
     * @return ProductInterface
     */
    public function setVpn(string $vpn): ProductInterface;

    /**
     * Set sku
     * @param string $sku
     * @return ProductInterface
     */
    public function setSku(string $sku): ProductInterface;

    /**
     * Set Copy Write Info
     * @param null|string $copyWriteInfo
     * @return ProductInterface
     */
    public function setCopyWriteInfo(?string $copyWriteInfo): ProductInterface;

}

