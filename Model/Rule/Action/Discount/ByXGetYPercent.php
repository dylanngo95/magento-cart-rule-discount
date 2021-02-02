<?php

declare(strict_types=1);

namespace Smart\CustomSalesRule\Model\Rule\Action\Discount;

use Magento\SalesRule\Model\Rule\Action\Discount\AbstractDiscount;
use Magento\SalesRule\Model\Validator;
use Smart\CustomSalesRule\Helper\QuoteCache;

/**
 * Class ByXGetYPercent
 * @package Smart\CustomSalesRule\Model\Rule\Action\Discount
 */
class ByXGetYPercent extends AbstractDiscount
{
    const ACTION_NAME = 'by_x_get_y_percent';
    const ACTION_LABEL = 'By X items discount Y percent';
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

        $cartItemsQty = $this->quoteCache->getCartItemsQty();
        $cartProcessItems = $this->quoteCache->getProcessItems();

        if (!$discountAmountRule || !$discountStepRule || !$cartItemsQty) {
            return $discountData;
        }

        $priceTotalItem = $qty * $itemPrice;
        $discountAmount = $priceTotalItem * $discountAmountRule / 100;
        $restCartItemsQty = $cartProcessItems - $qty;
        $this->quoteCache->setProcessItems((int) $restCartItemsQty);

        $discountData->setAmount($discountAmount);
        $discountData->setBaseAmount($maxQtyToApplyRule * $baseItemPrice);
        $discountData->setOriginalAmount($maxQtyToApplyRule * $itemOriginalPrice);
        $discountData->setBaseOriginalAmount($maxQtyToApplyRule * $baseItemOriginalPrice);

        return $discountData;
    }
}
