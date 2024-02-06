<?php

declare(strict_types=1);

namespace Neklo\Questions\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Answers extends AbstractDb
{
    /** @var string Main table name */
    public const MAIN_TABLE = 'neklo_question_answers';

    /** @var string Main table primary key field name */
    public const ID_FIELD_NAME = 'id';

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}
