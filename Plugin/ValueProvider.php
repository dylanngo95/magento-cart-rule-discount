<?php

declare(strict_types=1);

namespace Smart\CustomSalesRule\Plugin;


use Magento\SalesRule\Model\Rule;
use Smart\CustomSalesRule\Model\Rule\Action\Discount\BXGY;

/**
 * Class ValueProvider
 * @package Smart\CustomSalesRule\Plugin
 */
class ValueProvider
{

    /**
     * @param \Magento\SalesRule\Model\Rule\Metadata\ValueProvider $subject
     * @param $result
     * @param Rule $rule
     * @return mixed
     */
    public function afterGetMetadataValues(\Magento\SalesRule\Model\Rule\Metadata\ValueProvider $subject, $result, Rule $rule)
    {
        $applyOptions = [
            'label' => __('Difference Product'),
            'value' => [
                [
                    'label' => BXGY::ACTION_LABEL,
                    'value' => BXGY::ACTION_NAME,
                ],
            ],
        ];
        array_push($result['actions']['children']['simple_action']['arguments']['data']['config']['options'], $applyOptions);
        return $result;
    }
}
