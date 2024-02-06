<?php

declare(strict_types=1);

namespace Neklo\Questions\Setup\Patch\Data;

use Neklo\Questions\Model\ResourceModel\Questions;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class InitialQuestions implements DataPatchInterface
{
    /**
     * InitialQuestions constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ResourceConnection $resource
     */
    public function __construct(
        protected ModuleDataSetupInterface $moduleDataSetup,
        protected ResourceConnection $resource
    ) {
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return self
     */
    public function apply(): self
    {
        $connection = $this->resource->getConnection();
        $data = [
            [
                'name' => 'Alex',
                'email' => 'test1@mail.com',
                'question' => 'Why did my gemstone fall out?',
            ],
            [
                'name' => 'Bob',
                'email' => 'test2@mail.com',
                'question' => 'How can I tell if my gemstone or diamond is loose?',
            ],
            [
                'name' => 'John',
                'email' => 'test3@mail.com',
                'question' => 'What is the process of resizing a ring?',
            ],
        ];
        $connection->insertMultiple(Questions::MAIN_TABLE, $data);

        return $this;
    }
}
