<?php
declare(strict_types=1);

namespace MB\Catalog\Ui\Component\Control\Product;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

/**
 *
 */
class SaveSplitButton implements ButtonProviderInterface
{
    /**
     * @var string
     */
    private $targetName;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param string $targetName
     */
    public function __construct(
        RequestInterface $request,
        string $targetName
    )
    {
        $this->request = $request;
        $this->targetName = $targetName;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save &amp; Continue'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => $this->targetName,
                                'actionName' => 'save',
                                'params' => [
                                    false,
                                    [
                                        'store_id' => $this->getStoreId(),
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'class_name' => Container::SPLIT_BUTTON,
            'options' => $this->getOptions(),
            'sort_order' => 30,
        ];
    }

    /**
     * @return array
     */
    private function getOptions(): array
    {
        $options = [
            [
                'label' => __('Save &amp; Close'),
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => $this->targetName,
                                    'actionName' => 'save',
                                    'params' => [
                                        true,
                                        [
                                            'store_id' => $this->getStoreId(),
                                        ]
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'sort_order' => 10,
            ],
            [
                'label' => __('Save &amp; New'),
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => $this->targetName,
                                    'actionName' => 'save',
                                    'params' => [
                                        true,
                                        [
                                            'redirect_to_new' => 1,
                                            'store_id' => $this->getStoreId(),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'sort_order' => 20,
            ],
        ];
        return $options;
    }


    private function getStoreId()
    {
        return $this->request->getParam('store_id');
    }
}
