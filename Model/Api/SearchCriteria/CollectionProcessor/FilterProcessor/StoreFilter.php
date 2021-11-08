<?php
declare(strict_types=1);

namespace MB\Catalog\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use MB\Catalog\Model\ResourceModel\Product\Collection;

/**
 * Class StoreFilter
 * @package MB\Catalog\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor
 */
class StoreFilter implements CustomFilterInterface
{
    /**
     * Apply store filter to product collection
     *
     * @param Filter $filter
     * @param AbstractDb $collection
     * @return bool Whether the filter is applied
     */
    public function apply(Filter $filter, AbstractDb $collection)
    {
        /** @var Collection $collection */
        if ($filter->getField() == 'store_id') {
            $collection->addStoreFilter((int)$filter->getValue());
        }
        return true;
    }
}
