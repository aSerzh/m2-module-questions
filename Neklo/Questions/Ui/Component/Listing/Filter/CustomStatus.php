<?php

declare(strict_types=1);

namespace Neklo\Questions\Ui\Component\Listing\Filter;

use Magento\Framework\Data\OptionSourceInterface;

class CustomStatus implements OptionSourceInterface
{
    /**
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'pending', 'label' => __('Pending')],
            ['value' => 'confirmed', 'label' => __('Confirmed')],
            ['value' => 'rejected', 'label' => __('Rejected')],
        ];
    }
}
