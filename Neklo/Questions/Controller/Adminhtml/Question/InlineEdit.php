<?php

declare(strict_types=1);

namespace Neklo\Questions\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Neklo\Questions\Model\AnswersFactory;
use Neklo\Questions\Model\ResourceModel\Answers as AnswersResource;

class InlineEdit extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Neklo_Questions::questions_save';

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param AnswersFactory $answersFactory
     * @param AnswersResource $answersResource
     */
    public function __construct(
        Context $context,
        private JsonFactory $jsonFactory,
        private AnswersFactory $answersFactory,
        private AnswersResource $answersResource
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute(): Json|ResultInterface|ResponseInterface
    {
        $jsonFactory = $this->jsonFactory->create();

        $messages = [];
        $error = false;

        $isAjax = $this->getRequest()->getParam('isAjax', false);
        $items = $this->getRequest()->getParam('items', []);

        if (!$isAjax || !count($items)) {
            $messages[] = __('Please correct the data sent.');
            $error = true;
        }

        if (!$error) {
            foreach ($items as $item) {
                $id = $item['id'];
                try {
                    $answersFactory = $this->answersFactory->create();
                    $this->answersResource->load($answersFactory, $id);
                    $answersFactory->setData(array_merge($answersFactory->getData(), $item));
                    $this->answersResource->save($answersFactory);
                } catch (\Exception $e) {
                    $messages[] = __("Something went wrong while saving Answer $id");
                    $error = true;
                }
            }
        }

        return $jsonFactory->setData([
            'messages' => $messages,
            'error' => $error,
        ]);
    }
}
