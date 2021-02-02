<?php

declare(strict_types=1);

namespace Smart\CustomSalesRule\Helper;


/**
 * Class QuoteCache
 * @package Smart\CustomSalesRule\Helper
 */
class QuoteCache
{
    private $itemsValidated;

    /**
     * @return mixed
     */
    public function getItemsValidated()
    {
        return $this->itemsValidated;
    }

    /**
     * @param mixed $itemsValidated
     */
    public function setItemsValidated($itemsValidated): void
    {
        $this->itemsValidated = $itemsValidated;
    }

}
