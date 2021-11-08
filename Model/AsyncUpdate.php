<?php
declare(strict_types=1);

namespace MB\Catalog\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use MB\Catalog\Api\AsyncUpdateInterface;
use MB\Catalog\Api\Data\ProductInterface;
use MB\Catalog\Api\Data\ProductInterfaceFactory;
use MB\Catalog\Api\ProductRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AsyncUpdate
 * @package MB\Catalog\Model
 */
class AsyncUpdate implements AsyncUpdateInterface
{

    /**
     * @var ProductInterfaceFactory
     */
    protected $productFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AsyncUpdate constructor.
     * @param ProductInterfaceFactory $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        ProductInterfaceFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        LoggerInterface $logger
    )
    {
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function update(array $productData, ?int $storeId = null): void
    {
        try {
            $product = $this->productFactory->create();
            if (isset($productData[ProductInterface::ENTITY_ID]) && !empty($productData[ProductInterface::ENTITY_ID])) {
                $product->setEntityId($productData[ProductInterface::ENTITY_ID]);
            }
            $product->setProductId($productData[ProductInterface::PRODUCT_ID]);
            $product->setSku($productData[ProductInterface::SKU]);
            $product->setVpn($productData[ProductInterface::VPN]);
            $product->setCopywriteInfo($productData[ProductInterface::COPY_WRITE_INFO]);
            if (!empty($storeId)) {
                $product->setStoreId($storeId);
            }
            $this->productRepository->save($product);
        } catch (CouldNotSaveException $exception) {
            $this->logger->critical($exception->getMessage(), $productData);
        }
    }
}
