<?php

declare(strict_types=1);

namespace Neklo\Questions\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class EmailSender
{
    /**
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private TransportBuilder $transportBuilder,
        private StoreManagerInterface $storeManager,
        private StateInterface $inlineTranslation,
        private ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * @param string $templateIdentifier
     * @param array $templateVars
     * @param array|string $addToAddress
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    private function prepareDataAndSendEmail(
        string $templateIdentifier,
        array $templateVars,
        array|string $addToAddress
    ): void {
        $this->inlineTranslation->suspend();
        $store = $this->storeManager->getStore()->getId();

        $transport = $this->transportBuilder
            ->setTemplateIdentifier($templateIdentifier)
            ->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => $store
                ]
            )
            ->setTemplateVars($templateVars)
            ->setFromByScope('general', $store)
            ->addTo($addToAddress)
            ->getTransport();

        $transport->sendMessage();

        $this->inlineTranslation->resume();
    }

    /**
     * @return array|string
     */
    private function getStoreOwner(): array|string
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_general/email',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $templateVars
     * @return void
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function sendQuestionToAdmin($templateVars): void
    {
        $this->prepareDataAndSendEmail('question_email_template', $templateVars, $this->getStoreOwner());
    }

    /**
     * @param $templateVars
     * @return void
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function sendAnswerToQuestion($templateVars): void
    {
        $this->prepareDataAndSendEmail('answer_email_template', $templateVars, $templateVars['email']);
    }
}
