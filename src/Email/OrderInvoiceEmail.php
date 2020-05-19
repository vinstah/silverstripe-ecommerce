<?php

namespace Sunnysideup\Ecommerce\Email;
use Sunnysideup\Ecommerce\Email\OrderInvoiceEmail;





/**
 * @Description: This class handles the invoice email which gets sent once an order is made.
 * You can call it like this: $Order->sendInvoice();
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 * @sub-package: forms

 **/
class OrderInvoiceEmail extends OrderEmail
{
    /**
     * @param string $ss_template The name of the used template (without *.ss extension)
     */
    protected $ss_template = OrderInvoiceEmail::class;
}

