<?php
declare(strict_types=1);

namespace MB\Catalog\MessageQueue;

use Magento\Framework\DataObject;
use MB\Catalog\Api\Data\MessageInterface;

/**
 * Class Message
 * @package MB\Catalog\MessageQueue
 */
class Message extends DataObject implements MessageInterface
{

    /**
     * {@inheritdoc}
     */
    public function getProductData(): string
    {
        return $this->getData(self::PRODUCT_DATA);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId(): ?int
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductData(string $data): MessageInterface
    {
        return $this->setData(self::PRODUCT_DATA, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId(?int $storeId): MessageInterface
    {
        return $this->setData(self::STORE_ID, $storeId);
    }
}
