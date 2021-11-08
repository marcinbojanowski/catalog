<?php
declare(strict_types=1);

namespace MB\Catalog\Model;

use Magento\Framework\Api\SearchResults;
use MB\Catalog\Api\Data\ProductSearchResultsInterface;

/**
 * Class ProductSearchResults
 * @package MB\Catalog\Model
 */
class ProductSearchResults extends SearchResults implements ProductSearchResultsInterface
{
    /**
     * Get Product list.
     * @return \MB\Catalog\Api\Data\ProductInterface[]
     */
    public function getItems()
    {
        return $this->_get(self::KEY_ITEMS) === null ? [] : $this->_get(self::KEY_ITEMS);
    }

    /**
     * Set Product list.
     * @param \MB\Catalog\Api\Data\ProductInterface[] $items
     * @return $this
     */
    public function setItems(array $items)
    {
        return $this->setData(self::KEY_ITEMS, $items);
    }

}
