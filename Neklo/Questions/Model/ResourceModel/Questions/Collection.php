<?php

declare(strict_types=1);

namespace Neklo\Questions\Model\ResourceModel\Questions;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Neklo\Questions\Model\Questions;
use Neklo\Questions\Model\ResourceModel\Questions as ResourceQuestions;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(
            Questions::class,
            ResourceQuestions::class
        );
    }
}
