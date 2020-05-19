<?php

namespace Sunnysideup\Ecommerce\Forms;













use SilverStripe\Control\Controller;
use Sunnysideup\Ecommerce\Model\Order;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormAction;
use Sunnysideup\Ecommerce\Forms\Validation\OrderFormCancelValidator;
use SilverStripe\Forms\Form;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Convert;
use SilverStripe\Security\Member;




/**
 * @Description: allows customer to cancel their order.
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 * @sub-package: forms

 **/
class OrderFormCancel extends Form
{
    public function __construct(Controller $controller, $name, Order $order)
    {
        $fields = new FieldList(
            [
                new HeaderField('CancelOrderHeading', _t('OrderForm.CANCELORDER', 'Changed your mind?'), 3),
                new TextField('CancellationReason', _t('OrderForm.CANCELLATIONREASON', 'Reason for cancellation')),
                new HiddenField('OrderID', '', $order->ID),
            ]
        );
        $actions = new FieldList(
            new FormAction('docancel', _t('OrderForm.CANCELORDER', 'Cancel this order'))
        );
        $requiredFields = [];
        $validator = OrderFormCancelValidator::create($requiredFields);
        parent::__construct($controller, $name, $fields, $actions, $validator);
        //extension point
        $this->extend('updateFields', $fields);
        $this->setFields($fields);
        $this->extend('updateActions', $actions);
        $this->setActions($actions);
        $this->extend('updateValidator', $validator);
        $this->setValidator($validator);


/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: Session:: (case sensitive)
  * NEW: SilverStripe\Control\Controller::curr()->getRequest()->getSession()-> (COMPLEX)
  * EXP: If THIS is a controller than you can write: $this->getRequest(). You can also try to access the HTTPRequest directly. 
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        $oldData = SilverStripe\Control\Controller::curr()->getRequest()->getSession()->get("FormInfo.{$this->FormName()}.data");
        if ($oldData && (is_array($oldData) || is_object($oldData))) {
            $this->loadDataFrom($oldData);
        }
        $this->extend('updateOrderFormCancel', $this);
    }

    /**
     * Form action handler for OrderFormCancel.
     *
     * Take the order that this was to be change on,
     * and set the status that was requested from
     * the form request data.
     *
     * @param array $data The form request data submitted
     * @param Form  $form The {@link Form} this was submitted on
     */
    public function docancel(array $data, Form $form, HTTPRequest $request)
    {
        $SQLData = Convert::raw2sql($data);
        $member = Member::currentUser();
        if ($member) {
            if (isset($SQLData['OrderID'])) {
                $order = Order::get()->byID(intval($SQLData['OrderID']));
                if ($order) {
                    if ($order->canCancel()) {
                        $reason = '';
                        if (isset($SQLData['CancellationReason'])) {
                            $reason = $SQLData['CancellationReason'];
                        }
                        $order->Cancel($member, $reason);
                        $form->sessionMessage(
                            _t(
                                'OrderForm.CANCELLED',
                                'Order has been cancelled.'
                            ),
                            'good'
                        );

                        return $this->controller->redirectBack();
                    }
                }
            }
        }
        $form->sessionMessage(
            _t(
                'OrderForm.COULDNOTCANCELORDER',
                'Sorry, order could not be cancelled.'
            ),
            'bad'
        );
        $this->controller->redirectBack();

        return false;
    }

    /**
     * saves the form into session.
     *
     * @param array $data - data from form.
     */
    public function saveDataToSession()
    {
        $data = $this->getData();

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: Session:: (case sensitive)
  * NEW: SilverStripe\Control\Controller::curr()->getRequest()->getSession()-> (COMPLEX)
  * EXP: If THIS is a controller than you can write: $this->getRequest(). You can also try to access the HTTPRequest directly. 
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        SilverStripe\Control\Controller::curr()->getRequest()->getSession()->set("FormInfo.{$this->FormName()}.data", $data);
    }
}
