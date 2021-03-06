<?php

namespace Sunnysideup\Ecommerce\Control;

use PageController;
use SilverStripe\ORM\DataObject;
use Sunnysideup\Ecommerce\Model\Order;
use Sunnysideup\Ecommerce\Model\Process\OrderStep;
use Sunnysideup\Ecommerce\Pages\Product;

/**
 * @description: used to display a random product in the Template Test.
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 * @sub-package: control

 **/
class EcommerceTemplateTest extends PageController
{
    public function index()
    {
        return $this->renderWith('Sunnysideup\Ecommerce\Layout\EcommerceTemplateTest');
    }

    /**
     * Goes through all products and find one that
     * "canPurchase".
     *
     * @return Product
     */
    public function RandomProduct()
    {
        $offSet = 0;
        $product = true;
        $notForSale = true;
        while ($product && $notForSale) {
            $notForSale = false;
            $product = Product::get()
                ->where('"AllowPurchase" = 1  AND "Price" > 0')
                ->sort('RAND()')
                ->limit(1, $offSet)
                ->First();
            if ($product) {
                $notForSale = $product->canPurchase() ? false : true;
            }
            ++$offSet;
        }

        return $product;
    }

    public function SubmittedOrder()
    {
        $lastStatusOrder = OrderStep::last_order_step();
        if ($lastStatusOrder) {
            return DataObject::get_one(
                Order::class,
                ['StatusID' => $lastStatusOrder->ID],
                $cacheDataObjectGetOne = true,
                'RAND()'
            );
        }
    }

    /**
     * This is used for template-ty stuff.
     *
     * @return bool
     */
    public function IsEcommercePage()
    {
        return true;
    }
}
