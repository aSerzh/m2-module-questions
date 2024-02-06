<?php

declare(strict_types=1);

namespace Neklo\Questions\Block\Adminhtml\Questions\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;

class Back implements ButtonProviderInterface
{

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        private UrlInterface $urlBuilder
    ) {
    }

    /**
     * @return array
     */
    public function getButtonData(): array
    {
        $url = $this->urlBuilder->getUrl('*/*/');
        return [
            'label' => __('Back'),
            'class' => 'back',
            'on_click' => sprintf("location.href = '%s';", $url),
        ];
    }
}
