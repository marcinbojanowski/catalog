<?php
declare(strict_types=1);

namespace MB\Catalog\Model\ResourceModel;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;
use MB\Catalog\Api\Data\ProductInterface;

/**
 * Class Product
 * @package MB\Catalog\Model\ResourceModel
 */
class Product extends AbstractDb
{
    /**
     * Store id
     *
     * @var int
     */
    private $storeId = 0;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->storeManager = $storeManager;
    }


    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mb_catalog_product', 'entity_id');
        $this->addUniqueField(
            [
                'field' => ProductInterface::PRODUCT_ID,
                'title' => 'Product ID'
            ]
        );
    }

    /**
     * Get load select
     *
     * @param string $field
     * @param mixed $value
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $select->join(
                ['scoped' => $this->getTable('mb_catalog_product_scoped')],
                $this->getMainTable() . '.entity_id = scoped.entity_id'
            )->where(
                'scoped.store_id IN (0, ?)',
                $object->getStoreId()
            )->order(
                'store_id DESC'
            )->limit(
                1
            );
        }
        return $select;
    }

    /**
     * Method to run after save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        parent::_afterSave($object);

        $connection = $this->getConnection();
        $data = new DataObject();
        $data->setEntityId($object->getEntityId())
            ->setStoreId($this->getStoreId())
            ->setCopyWriteInfo($object->getCopyWriteInfo());

        $connection->insertOnDuplicate(
            $this->getTable('mb_catalog_product_scoped'),
            $data->getData(),
            ['copy_write_info']
        );
    }

    /**
     * Set store Id
     *
     * @param integer $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    public function getStoreId()
    {
        return $this->storeId;
    }
}
