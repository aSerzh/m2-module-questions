<?php

declare(strict_types=1);

namespace Neklo\Questions\Plugin;

use Closure;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\RequestInterface;

class CustomerSessionContext
{
    /**
     * @param Session $customerSession
     * @param Context $httpContext
     */
    public function __construct(
        private Session $customerSession,
        private Context $httpContext
    ) {
    }

    /**
     * @param ActionInterface $subject
     * @param Closure $proceed
     * @param RequestInterface $request
     * @return mixed
     */
    public function aroundDispatch(
        ActionInterface $subject,
        Closure $proceed,
        RequestInterface $request
    ): mixed {
        $this->httpContext->setValue(
            'customer_id',
            $this->customerSession->getCustomerId(),
            false
        );

        return $proceed($request);
    }
}
