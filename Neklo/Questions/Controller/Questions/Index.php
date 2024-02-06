<?php

declare(strict_types=1);

namespace Neklo\Questions\Controller\Questions;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{
    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        private PageFactory $pageFactory
    ) {
    }

    /**
     * @return Page
     */
    public function execute(): Page
    {
//        $page = $this->pageFactory->create();
//        $page->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);

        return $this->pageFactory->create();
    }
}
