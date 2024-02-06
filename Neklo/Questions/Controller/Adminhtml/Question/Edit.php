<?php

declare(strict_types=1);

namespace Neklo\Questions\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Neklo_Questions::questions_save';

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        private PageFactory $pageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute(): Page
    {
        $page = $this->pageFactory->create();

        /** @var \Magento\Backend\Model\View\Result\Page $page */
        $page->setActiveMenu('Neklo_Questions::questions');
        $page->getConfig()->getTitle()->prepend(__('Answer to the Question'));

        return $page;
    }
}
