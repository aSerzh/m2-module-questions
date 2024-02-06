<?php

declare(strict_types=1);

namespace Neklo\Questions\Model\ResourceModel\Answers;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Neklo\Questions\Model\Answers;
use Neklo\Questions\Model\ResourceModel\Answers as ResourceAnswers;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(
            Answers::class,
            ResourceAnswers::class
        );
    }
}
