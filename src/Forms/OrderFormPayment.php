<?php

namespace Sunnysideup\Ecommerce\Forms;

use SilverStripe\Control\Controller;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use Sunnysideup\Ecommerce\Forms\Validation\OrderFormPaymentValidator;
use Sunnysideup\Ecommerce\Model\Money\EcommercePayment;
use Sunnysideup\Ecommerce\Model\Order;

class OrderFormPayment extends Form
{
    /**
     * @param Controller $controller
     * @param string     $name
     * @param Order      $order
     * @param string $returnToLink
     */
    public function __construct(Controller $controller, $name, Order $order, $returnToLink = '')
    {
        $requiredFields = [];
        $fields = new FieldList(
            new HiddenField('OrderID', '', $order->ID)
        );
        if ($returnToLink) {
            $fields->push(new HiddenField('returntolink', '', Convert::raw2att($returnToLink)));
        }
        $bottomFields = new CompositeField();
        $bottomFields->addExtraClass('bottomOrder');
        if ($order->Total() > 0) {
            $paymentFields = EcommercePayment::combined_form_fields($order->getTotalAsMoney()->NiceLongSymbol(false), $order);
            foreach ($paymentFields as $paymentField) {
                $bottomFields->push($paymentField);
            }
            if ($paymentRequiredFields = EcommercePayment::combined_form_requirements($order)) {
                $requiredFields = array_merge($requiredFields, $paymentRequiredFields);
            }
        } else {
            $bottomFields->push(new HiddenField('PaymentMethod', '', ''));
        }
        $fields->push($bottomFields);
        $actions = new FieldList(
            new FormAction('dopayment', _t('OrderForm.PAYORDER', 'Pay balance'))
        );

        $validator = OrderFormPaymentValidator::create($requiredFields);
        parent::__construct($controller, $name, $fields, $actions, $validator);

        //extension point
        $this->extend('updateFields', $fields);
        $this->setFields($fields);
        $this->extend('updateActions', $actions);
        $this->setActions($actions);
        $this->extend('updateValidator', $validator);
        $this->setValidator($validator);

        $this->setFormAction($controller->Link($name));
        $oldData = Controller::curr()->getRequest()->getSession()->get("FormInfo.{$this->FormName()}.data");
        if ($oldData && (is_array($oldData) || is_object($oldData))) {
            $this->loadDataFrom($oldData);
        }
        $this->extend('updateOrderFormPayment', $this);
    }

    /**
     * @param array $data
     * @param Form  $form
     *
     * @return mixed
     */
    public function dopayment($data, $form)
    {
        $SQLData = Convert::raw2sql($data);
        if (isset($SQLData['OrderID'])) {
            if ($orderID = intval($SQLData['OrderID'])) {
                $order = Order::get()->byID($orderID);
                if ($order && $order->canPay()) {
                    $formHelper = EcommercePayment::ecommerce_payment_form_setup_and_validation_object();
                    if ($formHelper->validatePayment($order, $data, $form)) {
                        return $formHelper->processPaymentFormAndReturnNextStep($order, $data, $form);
                    }
                    //error messages are set in validation
                    return $this->controller->redirectBack();
                }
                $form->sessionMessage(_t('OrderForm.NO_PAYMENTS_CAN_BE_MADE_FOR_THIS_ORDER', 'No payments can be made for this order.'), 'bad');

                return $this->controller->redirectBack();
            }
        }
        $form->sessionMessage(_t('OrderForm.COULDNOTPROCESSPAYMENT', 'Sorry, we could not find the Order for payment.'), 'bad');

        return $this->controller->redirectBack();
    }

    /**
     * saves the form into session.
     */
    public function saveDataToSession()
    {
        $data = $this->getData();
        unset($data['LoggedInAsNote']);
        Controller::curr()->getRequest()->getSession()->set("FormInfo.{$this->FormName()}.data", $data);
    }
}
