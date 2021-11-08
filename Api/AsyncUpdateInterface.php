<?php
declare(strict_types=1);

namespace MB\Catalog\Api;

/**
 * Interface AsyncUpdateInterface
 * @package MB\Catalog\Api
 */
interface AsyncUpdateInterface
{

    /**
     * Update product
     *
     * @param array $productData
     */
    public function update(array $productData, ?int $storeId = null): void;
}
