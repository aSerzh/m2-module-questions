<?php

declare(strict_types=1);

namespace Neklo\Questions\Cron;

use Neklo\Questions\Model\ResourceModel\Questions\CollectionFactory;

class RemoveRejected
{
    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        private CollectionFactory $collectionFactory
    ) {
    }

    /**
     * Deleting Questions with the status "Rejected" with the Cron Job
     *
     * @return void
     */
    public function execute(): void
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', ['eq' => 'rejected']);

        foreach ($collection as $item) {
            $item->delete();
        }
    }
}
