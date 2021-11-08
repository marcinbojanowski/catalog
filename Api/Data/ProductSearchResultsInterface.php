<?php
declare(strict_types=1);

namespace MB\Catalog\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface ProductSearchResultsInterface
 * @package MB\Catalog\Api\Data
 */
interface ProductSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get Product list.
     * @return \MB\Catalog\Api\Data\ProductInterface[]
     */
    public function getItems();

    /**
     * Set Product list.
     * @param \MB\Catalog\Api\Data\ProductInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

