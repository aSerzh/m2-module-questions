<?php

declare(strict_types=1);

namespace Neklo\Questions\Model;

use Neklo\Questions\Model\AnswersFactory;
use Neklo\Questions\Model\ResourceModel\Answers as AnswersResource;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\Http\Context;

class HelperData
{
    /**
     * @param AnswersFactory $answersFactory
     * @param AnswersResource $answersResource
     * @param RequestInterface $request
     * @param Context $httpContext
     * @param SessionFactory $sessionFactory
     */
    public function __construct(
        private AnswersFactory $answersFactory,
        private AnswersResource $answersResource,
        private RequestInterface $request,
        private Context $httpContext,
        private readonly SessionFactory $sessionFactory
    ) {
    }

    /**
     * @param int $questionId
     * @return array|null
     */
    public function loadAnswerDataByQuestionId(int $questionId): array|null
    {
        $answer = $this->answersFactory->create();
        $this->answersResource->load($answer, $questionId, 'question_id');
        return $answer->getId() ? $answer->getData() : null;
    }

    /**
     * Checks 'customer_id'from Session.
     *
     * '$this->sessionFactory->create()' used to retrieve Customer data ('id') from a Session
     * without disabling Full Page Caching
     *
     * @return int|string|null
     */
    public function getCustomerIdFromSession(): int|null|string
    {
        return $this->sessionFactory->create()->getCustomerId();
    }

    /**
     * Checks from Session if the customer is currently logged in.
     *
     * '$this->sessionFactory->create()' used to retrieve Customer data ('id') from a Session
     * without disabling Full Page Caching
     *
     * @return bool
     */
    public function isCustomerLoggedIn(): bool
    {
        return $this->sessionFactory->create()->isLoggedIn();
    }

//    public function isCustomerLoggedIn(): bool
//    {
//        return $this->customerSession->isLoggedIn();
//    }

//    public function getCustomerIdFromSession(): int|null|string
//    {
//        return $this->customerSession->getCustomerId();
//    }


//    public function getCustomerIdFromSession(): int|null|string
//    {
//        return $this->httpContext->getValue('customer_id');
//    }

//    public function isCustomerLoggedIn(): bool
//    {
//        return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
//    }
}
