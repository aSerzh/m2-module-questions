<?php

declare(strict_types=1);

namespace Neklo\Questions\Controller\Questions;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Neklo\Questions\Model\QuestionsFactory;
use Neklo\Questions\Model\EmailSender;
use Magento\Framework\App\Request\DataPersistorInterface;
//use Magento\Customer\Model\Session;
use Neklo\Questions\Model\HelperData;

class Submit implements HttpPostActionInterface
{

    /**
     * @param QuestionsFactory $questionsFactory
     * @param RequestInterface $request
     * @param RedirectFactory $resultRedirectFactory
     * @param ManagerInterface $messageManager
     * @param DataPersistorInterface $dataPersistor
     * @param EmailSender $emailSender
     * @param HelperData $helperData
     */
    public function __construct(
        private QuestionsFactory $questionsFactory,
        private RequestInterface $request,
        private RedirectFactory $resultRedirectFactory,
        private ManagerInterface $messageManager,
        private DataPersistorInterface $dataPersistor,
        private EmailSender $emailSender,
        private HelperData $helperData
    ) {
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute(): ResultInterface|ResponseInterface|Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $customerId = $this->helperData->getCustomerIdFromSession();
        $data = $this->request->getPostValue();

        if (empty($data)) {
            $this->messageManager->addErrorMessage(__('No data to save.'));
            return $resultRedirect->setPath('store/questions');
        }

        $sanitizedData = [
            'name' => strip_tags($data['name'] ?? ''),
            'email' => filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL),
            'question' => strip_tags($data['question'] ?? ''),
            'customer_id' => (int)$customerId,
        ];

        try {
            $question = $this->questionsFactory->create();
            $question->setData($sanitizedData);
            $question->save();

            $templateVars = $this->prepareTemplateVars($sanitizedData);

            $this->emailSender->sendQuestionToAdmin($templateVars);
            $this->messageManager->addSuccessMessage(__('Your Question has been successfully sent.'));
            $this->dataPersistor->clear('questions_form');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('There was an error when sending the question: ') . $e->getMessage()
            );
            $this->dataPersistor->set('questions_form', $sanitizedData);
        }

        return $resultRedirect->setPath('store/questions');
    }

    /**
     * @param $sanitizedData
     * @return array|null
     */
    private function prepareTemplateVars($sanitizedData): array|null
    {
        return [
            'name' => $sanitizedData['name'],
            'email' => $sanitizedData['email'],
            'question' => $sanitizedData['question']
        ];
    }
}
