<?php

declare(strict_types=1);

namespace Neklo\Questions\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CustomStatus implements OptionSourceInterface
{
    /**
     * Return array of options as value-label pairs for  Answer's Form
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'default', 'label' => __('Select...')],
            ['value' => 'confirmed', 'label' => __('Confirmed')],
            ['value' => 'rejected', 'label' => __('Rejected')]
        ];
    }
}
