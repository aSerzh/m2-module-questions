<?php

declare(strict_types=1);

namespace Neklo\Questions\Controller\Questions;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Neklo\Questions\Model\HelperData;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Add implements HttpGetActionInterface
{
    /**
     * @param PageFactory $pageFactory
     * @param RedirectFactory $resultRedirectFactory
     * @param ManagerInterface $messageManager
     * @param HelperData $helperData
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private PageFactory $pageFactory,
        private RedirectFactory $resultRedirectFactory,
        private ManagerInterface $messageManager,
        private HelperData $helperData,
        private ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * @return Page|ResultInterface|ResponseInterface|Redirect
     */
    public function execute(): Page|ResultInterface|ResponseInterface|Redirect
    {
        $allowGuestQuestions = $this->scopeConfig->getValue(
            'neklo_questions/general/allow_guest_questions',
            ScopeInterface::SCOPE_STORE
        );

        if (!$allowGuestQuestions && !$this->helperData->isCustomerLoggedIn()) {
            $this->messageManager->addNoticeMessage(__('You must be logged in to submit the form'));
            return $this->resultRedirectFactory->create()->setPath('customer/account/login');
        }
        return $this->pageFactory->create();
    }
}
