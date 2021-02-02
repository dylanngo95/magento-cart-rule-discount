<?php

declare(strict_types=1);

namespace Smart\CustomSalesRule\Plugin;


use Magento\SalesRule\Model\Rule;
use Smart\CustomSalesRule\Model\Rule\Action\Discount\ByXGetYAmount;
use Smart\CustomSalesRule\Model\Rule\Action\Discount\ByXGetYPercent;

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
                    'label' => ByXGetYPercent::ACTION_LABEL,
                    'value' => ByXGetYPercent::ACTION_NAME,
                ],
                [
                    'label' => ByXGetYAmount::ACTION_LABEL,
                    'value' => ByXGetYAmount::ACTION_NAME,
                ],
            ],
        ];
        array_push($result['actions']['children']['simple_action']['arguments']['data']['config']['options'], $applyOptions);
        return $result;
    }
}
