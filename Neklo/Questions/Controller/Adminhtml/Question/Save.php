<?php

declare(strict_types=1);

namespace Neklo\Questions\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Neklo\Questions\Model\EmailSender;
use Neklo\Questions\Model\QuestionsFactory;
use Neklo\Questions\Model\ResourceModel\Questions as QuestionsResource;
use Neklo\Questions\Model\AnswersFactory;
use Neklo\Questions\Model\ResourceModel\Answers as AnswersResource;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Neklo_Questions::questions_save';

    /**
     * @param Context $context
     * @param QuestionsFactory $questionsFactory
     * @param QuestionsResource $questionsResource
     * @param AnswersFactory $answersFactory
     * @param AnswersResource $answersResource
     * @param EmailSender $emailSender
     */
    public function __construct(
        Context $context,
        private QuestionsFactory $questionsFactory,
        private QuestionsResource $questionsResource,
        private AnswersFactory $answersFactory,
        private AnswersResource $answersResource,
        private EmailSender $emailSender
    ) {
        parent::__construct($context);
    }

    /**
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $postData = $this->getRequest()->getPostValue();
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $id = $postData['id'];
        $answerText = $postData['answer'];
        $status = $postData['status'];

        if ($id) {
            $questionsFactory = $this->questionsFactory->create();
            $this->questionsResource->load($questionsFactory, $id);

            if (!$questionsFactory->getId()) {
                $this->messageManager->addErrorMessage(__('The question does not exist.'));
                return $redirect->setPath('*/*/');
            }

            if ($status !== 'default') {
                $questionsFactory->setData('status', $status);
            }

            try {
                $this->questionsResource->save($questionsFactory);

                if (!empty($answerText)) {
                    $answersFactory = $this->answersFactory->create();
                    $answersFactory->setData([
                        'question_id' => $id,
                        'answer' => $answerText,
                    ]);
                    $this->answersResource->save($answersFactory);
                }

                $this->emailSender->sendAnswerToQuestion($this->prepareTemplateVars($postData));
                $this->messageManager->addSuccessMessage(__('The Answer has been saved.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            return $redirect->setPath('*/*/edit', ['id' => $id]);
        }

        $this->messageManager->addErrorMessage(__('Invalid Question id or Answer text.'));
        return $redirect->setPath('*/*/');
    }

    /**
     * @param $postData
     * @return array|null
     */
    private function prepareTemplateVars($postData): array|null
    {
        return [
            'name' => $postData['name'],
            'question' => $postData['question'],
            'answer' => $postData['answer'],
            'email' => $postData['email'],
        ];
    }
}
