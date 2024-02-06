<?php

declare(strict_types=1);

namespace Neklo\Questions\Ui\DataProvider;

use Neklo\Questions\Model\AnswersFactory;
use Neklo\Questions\Model\ResourceModel\Answers\Collection;
use Neklo\Questions\Model\ResourceModel\Answers\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class Answers extends AbstractDataProvider
{
    /** @var Collection $collection */
    protected $collection;

    /** @var array*/
    private array $loadedData;

    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        private CollectionFactory $collectionFactory,
        private RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (!isset($this->loadedData)) {

            $this->loadedData['items'] = [];
            $questionIdFromRequest = (int) $this->request->getParam('question_id');

            if ($questionIdFromRequest) {
                $collection = $this->getCollection();
                $collection->addFieldToFilter('question_id', $questionIdFromRequest);

                $this->loadedData = $collection->toArray();
            }
        }

        return $this->loadedData;
    }
}
