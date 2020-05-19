<?php

namespace Sunnysideup\Ecommerce\Control;








use Sunnysideup\Ecommerce\Control\EcommercePaymentController;
use Sunnysideup\Ecommerce\Config\EcommerceConfig;
use SilverStripe\Control\Director;
use SilverStripe\Control\Controller;
use SilverStripe\View\Requirements;
use Sunnysideup\Ecommerce\Model\Order;
use SilverStripe\Core\Config\Config;
use Sunnysideup\Ecommerce\Forms\OrderFormPayment;




/**
 * @description: Used to diplay the payment form.
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 * @sub-package: control
 **/
class EcommercePaymentController extends Controller
{
    /**
     * @var Order
     */
    protected $currentOrder = null;

    /**
     * @var string
     */
    protected $errorMessage = '';

    /**
     * @var string
     */
    protected $goodMessage = '';

    private static $allowed_actions = [
        'thankyou',
        'index',
        'pay',
        'PaymentForm',
    ];

    /**
     * @param string | Int $orderID
     *
     * @return string (Link)
     */
    public static function make_payment_link($orderID)
    {
        $urlSegment = EcommerceConfig::get(EcommercePaymentController::class, 'url_segment');
        return Controller::join_links(
            Director::baseURL(),
            $urlSegment . '/pay/' . $orderID . '/'
        );
    }


/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * OLD:     public function init() (ignore case)
  * NEW:     protected function init() (COMPLEX)
  * EXP: Controller init functions are now protected  please check that is a controller.
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
    protected function init()
    {
        parent::init();
        isset($project) ? $themeBaseFolder = $project : $themeBaseFolder = 'app';
        Requirements::themedCSS('sunnysideup/ecommerce: typography', $themeBaseFolder);

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: THIRDPARTY_DIR . '/jquery/jquery.js' (case sensitive)
  * NEW: 'silverstripe/admin: thirdparty/jquery/jquery.js' (COMPLEX)
  * EXP: Check for best usage and inclusion of Jquery
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        Requirements::javascript('sunnysideup/ecommerce: silverstripe/admin: thirdparty/jquery/jquery.js');
        //Requirements::block(THIRDPARTY_DIR."/jquery/jquery.js");
        //Requirements::javascript(Director::protocol()."ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
        $id = intval($this->request->param('ID'));
        if (! $id && isset($_REQUEST['OrderID'])) {
            $id = intval($_REQUEST['OrderID']);
        }
        if ($id) {
            $order = Order::get_by_id_if_can_view($id);
            if ($order) {
                $this->currentOrder = $order;
            }
        }
    }

    public function index()
    {
        return [];
    }

    public function pay()
    {
        return [];
    }

    /**
     * TO DO: TEST!!!
     */
    public function thankyou()
    {
        $this->goodMessage = _t('EcommercePaymentController.THANKYOU', 'Thank you for your payment.');
        $this->currentOrder = null;

        return [];
    }

    /**
     * @param string $action
     *
     * @return string (Link)
     */
    public function Link($action = null)
    {

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: $this->class (case sensitive)
  * NEW: $this->class (COMPLEX)
  * EXP: Check if the class name can still be used as such
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        $URLSegment = Config::inst()->get($this->class, 'url_segment');
        if (! $URLSegment) {
            $URLSegment = get_class($this);
        }

        return Controller::join_links(
            Director::baseURL(),
            $URLSegment,
            $action
        );
    }

    /**
     * @return Form (OrderFormPayment) | Array
     **/
    public function PaymentForm()
    {
        if ($this->currentOrder) {
            if ($this->currentOrder->canPay()) {
                Requirements::javascript('ecommerce/javascript/EcomPayment.js');

                return OrderFormPayment::create($this, 'PaymentForm', $this->currentOrder, $this->Link('thankyou'));
            }
            $this->errorMessage = _t('EcommercePaymentController.CANNOTMAKEPAYMENT', 'You can not make a payment for this order.');
        } else {
            $this->errorMessage = _t('EcommercePaymentController.ORDERCANNOTBEFOUND', 'Order can not be found.');
        }

        return [];
    }

    public function ErrorMessage()
    {
        return $this->errorMessage;
    }

    public function GoodMessage()
    {
        return $this->goodMessage;
    }
}
