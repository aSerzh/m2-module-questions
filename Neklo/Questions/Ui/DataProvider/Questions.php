<?php

declare(strict_types=1);

namespace Neklo\Questions\Ui\DataProvider;

use Neklo\Questions\Model\QuestionsFactory;
use Neklo\Questions\Model\ResourceModel\Questions\Collection;
use Neklo\Questions\Model\ResourceModel\Questions\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class Questions extends AbstractDataProvider
{
    /** @var Collection $collection */
    protected $collection;

    /** @var array */
    private array $loadedData;

    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        private CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (!isset($this->loadedData)) {
            $this->loadedData = [];
            $items = $this->collection->getItems();

            /** @var \Neklo\Questions\Model\Questions $item */
            foreach ($items as $item) {
                $questionData = $item->getData();
                $questionId = (int) $item->getId();

                $this->loadedData[$questionId] = $questionData;
            }
        }

        return $this->loadedData;
    }
}
