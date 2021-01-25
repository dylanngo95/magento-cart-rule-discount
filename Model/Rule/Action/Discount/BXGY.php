<?php

declare(strict_types=1);

namespace Smart\CustomSalesRule\Model\Rule\Action\Discount;

use Magento\SalesRule\Model\Rule\Action\Discount\AbstractDiscount;
use Magento\SalesRule\Model\Validator;

/**
 * Class BXGY
 * @package Smart\CustomSalesRule\Model\Rule\Action\Discount
 */
class BXGY extends AbstractDiscount
{
    const ACTION_NAME = 'by_x_get_y_percent';
    const ACTION_LABEL = 'By X items discount Y %';
    const RULE_VERSION = '1.0.0';
    const DEFAULT_SORT_ORDER = 'asc';

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param \Magento\SalesRule\Model\Rule $rule
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @param float $qty
     * @return \Magento\SalesRule\Model\Rule\Action\Discount\Data
     */
    public function calculate($rule, $item, $qty)
    {
        $discountData = $this->discountFactory->create();

        $itemPrice = $this->validator->getItemPrice($item);
        $baseItemPrice = $this->validator->getItemBasePrice($item);
        $itemOriginalPrice = $this->validator->getItemOriginalPrice($item);
        $baseItemOriginalPrice = $this->validator->getItemBaseOriginalPrice($item);

        $discountStepRule = $rule->getDiscountStep();
        $maxQtyToApplyRule = $rule->getDiscountQty();
        $discountAmountRule = $rule->getDiscountAmount();

        if (!$discountAmountRule) {
            return $discountData;
        }

        $priceTotalItem = $qty * $itemPrice;
        $discountAmount = $priceTotalItem;
        $restDiscountAmount = 0;
        if ($priceTotalItem > $discountAmountRule) {
            $discountAmount = $discountAmountRule;
            $rule->setDiscountAmount($restDiscountAmount);
        } else {
            $restDiscountAmount = $discountAmountRule - $discountAmount;
            $rule->setDiscountAmount($restDiscountAmount);
        }

        $discountData->setAmount($discountAmount);
        $discountData->setBaseAmount($maxQtyToApplyRule * $baseItemPrice);
        $discountData->setOriginalAmount($maxQtyToApplyRule * $itemOriginalPrice);
        $discountData->setBaseOriginalAmount($maxQtyToApplyRule * $baseItemOriginalPrice);

        return $discountData;
    }
}
