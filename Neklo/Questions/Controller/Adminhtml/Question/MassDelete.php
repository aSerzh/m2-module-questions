<?php

declare(strict_types=1);

namespace Neklo\Questions\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Neklo\Questions\Model\ResourceModel\Answers\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Neklo_Questions::questions_delete';

    /** @var string $questionId*/
    private string $questionId;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        private CollectionFactory $collectionFactory,
        private Filter $filter
    ) {
        parent::__construct($context);
    }

    /**
     * @return Redirect
     * @throws LocalizedException
     */
    public function execute(): Redirect
    {
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $collection = $this->collectionFactory->create();
        $items = $this->filter->getCollection($collection);
        $itemsSize = $items->getSize();

        foreach ($items as $item) {
            $this->questionId = $item->getData('question_id');
            $item->delete();
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 answers(s) have been deleted.', $itemsSize));

        return $redirect->setPath('*/*/edit', ['id' => $this->questionId]);
    }
}
