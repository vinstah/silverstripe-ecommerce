<?php

namespace Sunnysideup\Ecommerce\Control;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\Form;
use SilverStripe\ORM\DataObject;
use Sunnysideup\Ecommerce\Model\Order;
use Sunnysideup\Ecommerce\Model\Process\OrderStep;

/**
 * This call can be used when you need input from the customer
 * in the order process.
 *
 * To use
 *
 * 1. create class that extends OrderStepController
 * 2. make sure the class has a $url_segment static var
 * 3. create content and/or form for page
 * 4. make sure you set up route (route.yml) to get to the
 */
abstract class OrderStepController extends Controller
{
    /**
     * @var string
     */
    protected $alternativeContent = '';

    private static $allowed_actions = [
        'error' => true,
    ];

    /**
     * @var Sunnysideup\Ecommerce\Model\Order
     */
    private static $_order = null;

    /**
     * when no action is selected
     * this action runs...
     */
    public function index($request)
    {
        $this->alternativeContent = '<p class="message bad">Sorry, we can not find the page you are looking for.</p>';

        return $this->renderWith('Page');
    }

    /**
     * there is an error ...
     */
    public function error($request)
    {
        $this->alternativeContent = '<p class="message bad">Sorry, an error occurred, please contact us for more information....</p>';

        return $this->renderWith('Page');
    }

    /**
     * main content ...
     *
     * @return string
     */
    public function Content($order = null)
    {
        if ($this->alternativeContent) {
            return $this->alternativeContent;
        }
        return $this->standardContent($order);
    }

    /**
     * @oaram string $action
     *
     * @return string
     */
    public function Link($action = null)
    {
        $link = '/' . Config::inst()->get($this->nameOfControllerClass(), 'url_segment') . '/';
        if ($action) {
            $link .= $action . '/';
        }

        return $link . $this->getOrderGetParams();
    }

    public function errorLink()
    {
        return $this->Link('error');
    }

    /**
     * @return string
     */
    protected static function name_of_controller_class()
    {
        return static::class;
    }

    /**
     * @param Order $order
     *
     * @return string
     */
    protected static function secure_hash($order)
    {
        $obj = Injector::inst()->get(self::name_of_controller_class());

        return $obj->secureHash($order);
    }

    /**
     * @return string
     */
    protected function nameOfControllerClass()
    {
        return self::name_of_controller_class();
    }

    /**
     * related OrderStatusLog class.
     *
     * @return string
     */
    abstract protected function nameOfLogClass();

    /**
     * @return string ($html)
     */
    protected function standardContent($order = null)
    {
        user_error('Make sure to put some content here in classes that extend ' . static::class);
    }

    /**
     * the form on the field.
     *
     * @return Form
     */
    protected function Form()
    {
        return $this->Form;
    }

    /**
     * code of related order step.
     *
     * @return string
     */
    abstract protected function codeOfRelevantOrderStep();

    /**
     * used to secure page.
     *
     * @param Order $order
     *
     * @return string
     */
    abstract protected function secureHash($order);

    /**
     * is the order valid?
     *
     * @return bool
     */
    protected function checkOrder($dataOrRequest = null)
    {
        $order = $this->Order($dataOrRequest);
        if ($order && $order->exists()) {
            return true;
        }
        return false;
    }

    /**
     * finds the order ...
     *
     * @param mixed $dataOrRequest
     *
     * @return Order
     */
    protected function Order($dataOrRequest = null)
    {
        if (! self::$_order) {
            if (is_array($dataOrRequest) &&
                isset($dataOrRequest['OrderID']) &&
                isset($dataOrRequest['OrderSessionID'])
            ) {
                $id = intval($dataOrRequest['OrderID']);
                $sessionID = Convert::raw2sql($dataOrRequest['OrderSessionID']);
            } elseif (isset($_POST['OrderID']) && isset($_POST['OrderSessionID'])) {
                $id = intval($_POST['OrderID']);
                $sessionID = Convert::raw2sql($_POST['OrderSessionID']);
            } elseif (isset($_GET['OrderID']) && isset($_GET['OrderSessionID'])) {
                $id = intval($_GET['OrderID']);
                $sessionID = Convert::raw2sql($_GET['OrderSessionID']);
            } elseif ($dataOrRequest instanceof HTTPRequest) {
                $id = intval($dataOrRequest->param('ID'));
                $sessionID = Convert::raw2sql($dataOrRequest->param('OtherID'));
            } else {
                $id = intval($this->request->param('ID'));
                $sessionID = Convert::raw2sql($this->request->param('OtherID'));
            }
            self::$_order = Order::get()->byID($id);
            if (self::$_order) {
                if ($this->secureHash(self::$_order) !== $sessionID) {
                    self::$_order = null;
                }
            }
        }

        return self::$_order;
    }

    /**
     * @return string
     */
    protected function getOrderGetParams()
    {
        if ($order = $this->Order()) {
            return '?OrderID=' . $order->ID . '&OrderSessionID=' . self::secure_hash($order);
        }
    }

    /**
     * @return \SilverStripe\ORM\DataObject | OrderStep|null
     */
    protected function orderStep()
    {
        return DataObject::get_one(
            OrderStep::class,
            ['Code' => $this->codeOfRelevantOrderStep()]
        );
    }
}
