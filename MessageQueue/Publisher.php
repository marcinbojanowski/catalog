<?php
declare(strict_types=1);

namespace MB\Catalog\MessageQueue;

use Magento\Framework\MessageQueue\PublisherInterface as QueuePublisherInterface;
use Magento\Framework\Serialize\SerializerInterface;
use MB\Catalog\Api\Data\MessageInterfaceFactory;
use MB\Catalog\Api\PublisherInterface;

/**
 * Class Publisher
 * @package MB\Catalog\MessageQueue
 */
class Publisher implements PublisherInterface
{

    /**
     * @var QueuePublisherInterface
     */
    protected $publisher;

    /**
     * @var MessageInterfaceFactory
     */
    protected $messageFactory;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Publisher constructor
     *
     * @param QueuePublisherInterface $publisher      Message publisher
     * @param MessageInterfaceFactory $messageFactory Message factory
     */
    public function __construct(
        QueuePublisherInterface $publisher,
        MessageInterfaceFactory $messageFactory,
        SerializerInterface $serializer
    ) {
        $this->publisher = $publisher;
        $this->messageFactory = $messageFactory;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(array $product, ?int $storeId): void
    {
        if ($productData = $this->serializer->serialize($product)) {
            $message = $this->messageFactory->create();
            $message->setProductData($productData);
            $message->setStoreId($storeId);

            $this->publisher->publish('mb_catalog.product.update', $message);
        }
    }
}
