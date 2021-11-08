<?php
declare(strict_types=1);

namespace MB\Catalog\Model;

use Magento\Framework\Model\AbstractModel;
use MB\Catalog\Api\Data\ProductInterface;
use MB\Catalog\Model\ResourceModel\Product as ProductResource;

/**
 * Class Product
 * @package MB\Catalog\Model
 */
class Product extends AbstractModel implements ProductInterface
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ProductResource::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityId(): int
    {
        return (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductId(): string
    {
        return (string)$this->getData(self::PRODUCT_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getSku(): string
    {
        return (string)$this->getData(self::SKU);
    }

    /**
     * {@inheritDoc}
     */
    public function getVpn(): string
    {
        return (string)$this->getData(self::VPN);
    }

    /**
     * {@inheritDoc}
     */
    public function getCopyWriteInfo(): ?string
    {
        return $this->getData(self::COPY_WRITE_INFO);
    }

    /**
     * {@inheritDoc}
     */
    public function setEntityId($entityId): ProductInterface
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * {@inheritDoc}
     */
    public function setProductId(string $productId): ProductInterface
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * {@inheritDoc}
     */
    public function setCopyWriteInfo(?string $copyWriteInfo): ProductInterface
    {
        return $this->setData(self::COPY_WRITE_INFO, $copyWriteInfo);
    }

    /**
     * {@inheritDoc}
     */
    public function setVpn(string $vpn): ProductInterface
    {
        return $this->setData(self::VPN, $vpn);
    }

    /**
     * {@inheritDoc}
     */
    public function setSku(string $sku): ProductInterface
    {
        return $this->setData(self::SKU, $sku);
    }

    public function setStoreId(int $storeId)
    {
        $this->setData('store_id', $storeId);
        $this->getResource()->setStoreId($storeId);
        return $this;
    }
}
