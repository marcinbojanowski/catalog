<?php
declare(strict_types=1);

namespace MB\Catalog\MessageQueue;

use Exception;
use Magento\Framework\Serialize\SerializerInterface;
use MB\Catalog\Api\AsyncUpdateInterface;
use MB\Catalog\Api\Data\MessageInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Handler
 * @package MB\Catalog\MessageQueue
 */
class Handler
{
    /**
     * @var AsyncUpdateInterface
     */
    protected $asyncUpdate;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Handler constructor.
     *
     * @param AsyncUpdateInterface $asyncUpdate
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        AsyncUpdateInterface $asyncUpdate,
        SerializerInterface       $serializer,
        LoggerInterface           $logger
    )
    {
        $this->asyncUpdate = $asyncUpdate;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function processMessage(MessageInterface $message): void
    {
        try {
            $productData = $this->serializer->unserialize($message->getProductData());
            $storeId = $message->getStoreId();
            $this->asyncUpdate->update($productData, $storeId);
        } catch (Exception $exception) {
            $this->logger->error($exception);
        }
    }
}
