<?php

declare(strict_types=1);

namespace Smart\CustomSalesRule\Helper;


/**
 * Class QuoteCache
 * @package Smart\CustomSalesRule\Helper
 */
class QuoteCache
{
    /**
     * @var int $cartItemsQty
     */
    private $cartItemsQty;

    /**
     * @var int $processItems
     */
    private $processItems;

    /**
     * @return int
     */
    public function getCartItemsQty(): int
    {
        return (int) $this->cartItemsQty;
    }

    /**
     * @param int $cartItemsQty
     */
    public function setCartItemsQty(int $cartItemsQty): void
    {
        $this->cartItemsQty = $cartItemsQty;
        $this->processItems = $cartItemsQty;
    }

    /**
     * @return int
     */
    public function getProcessItems(): int
    {
        return (int) $this->processItems;
    }

    /**
     * @param int $processItems
     */
    public function setProcessItems(int $processItems): void
    {
        $this->processItems = $processItems;
    }

}
