<?php

declare(strict_types=1);

namespace Smart\CustomSalesRule\Model\Rule\Action\Discount;

use Magento\SalesRule\Model\Rule\Action\Discount\AbstractDiscount;
use Magento\SalesRule\Model\Validator;
use Smart\CustomSalesRule\Helper\QuoteCache;

/**
 * Class GroupNByXGetYPercent
 * @package Smart\CustomSalesRule\Model\Rule\Action\Discount
 */
class GroupNByXGetYPercent extends AbstractDiscount
{
    const ACTION_NAME = 'group_n_by_x_get_y_percent';
    const ACTION_LABEL = 'Group N By X items discount Y percent';
    const RULE_VERSION = '1.0.0';
    const DEFAULT_SORT_ORDER = 'asc';

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var QuoteCache
     */
    private $quoteCache;

    public function __construct(
        \Magento\SalesRule\Model\Validator $validator,
        \Magento\SalesRule\Model\Rule\Action\Discount\DataFactory $discountDataFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        QuoteCache $quoteCache
    ) {
        parent::__construct($validator, $discountDataFactory, $priceCurrency);
        $this->quoteCache = $quoteCache;
    }

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

        if (!$discountAmountRule || !$discountStepRule || is_null($item->getAddress())) {
            return $discountData;
        }

        $allItems = $item->getAddress()->getAllVisibleItems();

        $itemsValidated = $this->validateQuoteItems($allItems, $discountStepRule);

        $priceTotalItem = $itemsValidated[$item->getId()] * $itemPrice;
        $discountAmount = $priceTotalItem * $discountAmountRule / 100;

        $discountData->setAmount($discountAmount);
        $discountData->setBaseAmount($maxQtyToApplyRule * $baseItemPrice);
        $discountData->setOriginalAmount($maxQtyToApplyRule * $itemOriginalPrice);
        $discountData->setBaseOriginalAmount($maxQtyToApplyRule * $baseItemOriginalPrice);

        return $discountData;
    }


    /**
     * Validate Quote Items.
     *
     * @param $allItems
     * @param $discountStepRule
     * @return array
     */
    public function validateQuoteItems($allItems, $discountStepRule): array
    {
        if ($this->quoteCache->getItemsValidated()) {
            return $this->quoteCache->getItemsValidated();
        }

        $allItemsCount = 0;
        foreach ($allItems as $quoteItem) {
            $allItemsCount += $quoteItem->getQty();
        }

        $qtyCanApplied = (int)($allItemsCount / $discountStepRule) * $discountStepRule;
        $itemsValidated = [];

        foreach ($allItems as $quoteItem) {
            $qty = $quoteItem->getQty();
            if ($qty < $qtyCanApplied) {
                $qtyCanApplied = $qtyCanApplied - $qty;
                $itemsValidated[$quoteItem->getId()] = $qty;
            } else {
                $itemsValidated[$quoteItem->getId()] = $qtyCanApplied;
                $qtyCanApplied =  $qty - $qtyCanApplied;
            }
        }
        $this->quoteCache->setItemsValidated($itemsValidated);

        return $itemsValidated;
    }
}
