<?php
declare(strict_types=1);

namespace MB\Catalog\Api;

/**
 * Interface ProductManagementInterface
 * @package MB\Catalog\Api
 */
interface ProductManagementInterface
{
    /**
     * Get Products by VPN
     *
     * @param string $vpn
     * @return \MB\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getProductsByVpn(string $vpn);

    /**
     * Update product
     *
     * @param \MB\Catalog\Api\Data\ProductInterface $product
     *
     * @return \MB\Catalog\Api\Data\ProductInterface
     */
    public function updateProduct(\MB\Catalog\Api\Data\ProductInterface $product): \MB\Catalog\Api\Data\ProductInterface;
}
