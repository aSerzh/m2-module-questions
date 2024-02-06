<?php

declare(strict_types=1);

namespace Neklo\Questions\Model;

use Magento\Framework\Model\AbstractModel;

class Questions extends AbstractModel
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Questions::class);
    }
}
