<?php
declare(strict_types=1);

namespace MB\Catalog\Ui\DataProvider;

use Magento\Backend\Model\Session;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\SearchResultFactory;
use MB\Catalog\Api\Data\ProductInterface;
use MB\Catalog\Api\ProductRepositoryInterface;

/**
 * Class DataProvider
 * @package MB\Catalog\Ui\Component\Form
 */
class ProductDataProvider extends DataProvider
{
    const PRODUCT_FORM_NAME = 'mb_catalog_product_form_data_source';

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;

    /**
     * @var int
     */
    private $productCount;

    /**
     * @var Session
     */
    private $session;

    /**
     * Total source count.
     *
     * @var int
     */
    private $sourceCount;

    /**
     * @var PoolInterface
     */
    private $pool;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param ProductRepositoryInterface $productRepository
     * @param SearchResultFactory $searchResultFactory
     * @param Session $session
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     * @SuppressWarnings(PHPMD.ExcessiveParameterList) All parameters are needed for backward compatibility
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        ProductRepositoryInterface $productRepository,
        SearchResultFactory $searchResultFactory,
        Session $session,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->productRepository = $productRepository;
        $this->searchResultFactory = $searchResultFactory;
        $this->session = $session;
        $this->pool = $pool ?: ObjectManager::getInstance()->get(PoolInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $data = parent::getData();
        if (self::PRODUCT_FORM_NAME === $this->name) {
            if ($data['totalRecords'] > 0) {
                $entityId = $data['items'][0][ProductInterface::ENTITY_ID];
                $dataForSingle[$entityId] = $data['items'][0];
                return $dataForSingle;
            }
            $sessionData = $this->session->getProductFormData(true);
            if (null !== $sessionData) {
                $data = [
                    '' => $sessionData,
                ];
            }
        }
        $data['totalRecords'] = $this->getProductsCount();

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function getSearchResult()
    {
        $searchCriteria = $this->getSearchCriteria();

        $result = $this->productRepository->getList($searchCriteria);

        return $this->searchResultFactory->create(
            $result->getItems(),
            $result->getTotalCount(),
            $searchCriteria,
            ProductInterface::ENTITY_ID
        );
    }

    /**
     * Returns search criteria
     *
     * @return \Magento\Framework\Api\Search\SearchCriteria
     */
    public function getSearchCriteria()
    {
        if (!$this->searchCriteria) {
            $storeId = $this->request->getParam('store_id', 0);
            $filter = $this->filterBuilder->setField('store_id')
                ->setValue($storeId)
                ->create();
            $this->searchCriteria = $this->searchCriteriaBuilder
                ->addFilter($filter)
                ->create();
            $this->searchCriteria->setRequestName($this->name);
        }
        return $this->searchCriteria;
    }

    /**
     * @return int
     */
    private function getProductsCount(): int
    {
        if (!$this->productCount) {
            $this->productCount = $this->productRepository->getList()->getTotalCount();
        }
        return $this->productCount;
    }

}
