<?php
declare(strict_types=1);

namespace MB\Catalog\Api;

/**
 * Interface PublisherInterface
 * @package MB\Catalog\Api
 */
interface PublisherInterface
{

    /**
     * Publish message to queue
     *
     * @param string[] $product
     * @param ?int $storeId
     *
     * @return void
     */
    public function publish(array $product, ?int $storeId): void;
}
