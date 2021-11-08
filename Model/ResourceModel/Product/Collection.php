<?php
declare(strict_types=1);

namespace MB\Catalog\Model\ResourceModel\Product;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use MB\Catalog\Model\Product;
use MB\Catalog\Model\ResourceModel\Product as ProductResource;

/**
 * Class Collection
 * @package MB\Catalog\Model\ResourceModel\Product
 */
class Collection extends AbstractCollection
{

    private const SCOPED_TABLE = 'mb_catalog_product_scoped';
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var int
     */
    private $filterStoreId = 0;


    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Product::class, ProductResource::class);
        $this->addFilterToMap('entity_id', 'main_table.entity_id');
        $this->addFilterToMap('copy_write_info', 'scoped_value.copy_write_info');
    }

    /**
     * Init select
     *
     * @return $this
     */
    protected function _beforeLoad()
    {
        parent::_beforeLoad();

        $storeId = $this->getStoreFilter();

        $this->getSelect()
        ->joinLeft(
            ['scoped_value' => $this->getTable(self::SCOPED_TABLE)],
            implode(
                ' AND ',
                [
                    'main_table.entity_id = scoped_value.entity_id',
                    $this->getConnection()->quoteInto('scoped_value.store_id = ?', (int)$storeId),
                ]
            ),
            []
        )->joinLeft(
            ['default_value' => $this->getTable(self::SCOPED_TABLE)],
            implode(
                ' AND ',
                [
                    'main_table.entity_id = default_value.entity_id',
                    $this->getConnection()->quoteInto('default_value.store_id = ?', Store::DEFAULT_STORE_ID),
                ]
            ),
            []
        )->columns([
            'copy_write_info' => $this->getConnection()->getIfNullSql('`scoped_value`.`copy_write_info`', '`default_value`.`copy_write_info`'),
        ]);

        return $this;
    }

    /**
     * Set store id to filter.
     *
     * @param int $storeId
     * @return void
     */
    public function addStoreFilter(int $storeId)
    {
        $this->filterStoreId = $storeId;
    }

    public function getStoreFilter()
    {
        return $this->filterStoreId;
    }
}
