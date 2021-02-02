<?php

declare(strict_types=1);

namespace Smart\CustomSalesRule\Plugin\Magento\Model;

use Smart\CustomSalesRule\Helper\QuoteCache;

/**
 * Class Quote
 * @package Smart\CustomSalesRule\Plugin\Magento\Model
 */
class Quote
{
    /**
     * @var QuoteCache $quoteCache
     */
    private $quoteCache;

    public function __construct(
        QuoteCache $quoteCache
    ) {
        $this->quoteCache = $quoteCache;
    }

    /**
     * @param \Magento\Quote\Model\Quote $subject
     */
    public function beforeCollectTotals(\Magento\Quote\Model\Quote $subject)
    {
        $cartItemsQty = count($subject->getAllVisibleItems());
        $this->quoteCache->setCartItemsQty($cartItemsQty);
        return [];
    }
}
