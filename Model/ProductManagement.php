<?php
declare(strict_types=1);

namespace MB\Catalog\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Store\Model\StoreManagerInterface;
use MB\Catalog\Api\Data\ProductInterface;
use MB\Catalog\Api\ProductManagementInterface;
use MB\Catalog\Api\ProductRepositoryInterface;
use MB\Catalog\Api\PublisherInterface;

/**
 * Class ProductManagement
 * @package MB\Catalog\Model
 */
class ProductManagement implements ProductManagementInterface
{

    /**
     * @var SearchCriteriaBuilder
     */
    private $criteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * ProductManagement constructor.
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param ProductRepositoryInterface $productRepository
     * @param PublisherInterface $publisher
     */
    public function __construct(
        SearchCriteriaBuilder $criteriaBuilder,
        FilterBuilder $filterBuilder,
        ProductRepositoryInterface $productRepository,
        PublisherInterface $publisher,
        StoreManagerInterface $storeManager
    )
    {
        $this->criteriaBuilder = $criteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->productRepository = $productRepository;
        $this->publisher = $publisher;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductsByVpn($vpn)
    {
        $this->criteriaBuilder->addFilters(
            [$this->filterBuilder->setField('vpn')->setValue($vpn)->setConditionType('eq')->create()]
        );
        $searchCriteria = $this->criteriaBuilder->create();

        return $this->productRepository->getList($searchCriteria);
    }

    /**
     * {@inheritdoc}
     */
    public function updateProduct(ProductInterface $product): ProductInterface
    {
        $productData = [
            ProductInterface::ENTITY_ID => $product->getEntityId() ?: null,
            ProductInterface::PRODUCT_ID => $product->getProductId() ?: null,
            ProductInterface::SKU => $product->getSku(),
            ProductInterface::VPN => $product->getVpn(),
            ProductInterface::COPY_WRITE_INFO => $product->getCopyWriteInfo() ?: null,
        ];
        $storeId = (int)$this->storeManager->getStore()->getId();

        $this->publisher->publish($productData, $storeId);

        return $product;
    }
}
