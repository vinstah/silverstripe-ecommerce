<?php


/**
 * Payment object representing a TEST = FAILURE.
 */
class EcommercePayment_TestFailure extends EcommercePayment_Test
{


    /**
     * standard SS variable.
     *
     * @Var String
     */
    private static $singular_name = 'Ecommerce Test Failure Payment';
    public function i18n_singular_name()
    {
        return $this->Config()->get('singular_name');
    }

    /**
     * standard SS variable.
     *
     * @Var String
     */
    private static $plural_name = 'Ecommerce Test Failuer Payments';
    public function i18n_plural_name()
    {
        return $this->Config()->get('plural_name');
    }

    /**
     * @param array     $data The form request data - see OrderForm
     * @param OrderForm $form The form object submitted on
     *
     * @return EcommercePayment_Result
     */
    public function processPayment($data, $form)
    {
        $this->Status = 'Failure';
        $this->Message = '<div>PAYMENT TEST: FAILURE</div>';
        $this->write();

        return new EcommercePayment_Failure();
    }

    public function getPaymentFormFields($amount = 0, $order = null)
    {
        return new FieldList(
            new LiteralField('SuccessBlurb', '<div>FAILURE PAYMENT TEST</div>')
        );
    }
}
