<?php

namespace Sunnysideup\Ecommerce\Model;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\Email\Email;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ErrorPage\ErrorPage;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldPageCount;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\Validator;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\RandomGenerator;
use SilverStripe\Security\Security;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;
use Sunnysideup\CmsEditLinkField\Api\CMSEditLinkAPI;
use Sunnysideup\Ecommerce\Api\ClassHelpers;
use Sunnysideup\Ecommerce\Api\ShoppingCart;
use Sunnysideup\Ecommerce\Cms\SalesAdmin;
use Sunnysideup\Ecommerce\Config\EcommerceConfig;
use Sunnysideup\Ecommerce\Config\EcommerceConfigAjax;
use Sunnysideup\Ecommerce\Config\EcommerceConfigClassNames;
use Sunnysideup\Ecommerce\Control\EcommercePaymentController;
use Sunnysideup\Ecommerce\Control\ShoppingCartController;
use Sunnysideup\Ecommerce\Email\OrderEmail;
use Sunnysideup\Ecommerce\Email\OrderErrorEmail;
use Sunnysideup\Ecommerce\Email\OrderInvoiceEmail;
use Sunnysideup\Ecommerce\Forms\Fields\EcommerceCMSButtonField;
use Sunnysideup\Ecommerce\Forms\Fields\OrderStepField;
use Sunnysideup\Ecommerce\Forms\Gridfield\Configs\GridFieldConfigForOrderItems;
use Sunnysideup\Ecommerce\Interfaces\EditableEcommerceObject;
use Sunnysideup\Ecommerce\Model\Address\BillingAddress;
use Sunnysideup\Ecommerce\Model\Address\EcommerceCountry;
use Sunnysideup\Ecommerce\Model\Address\EcommerceRegion;
use Sunnysideup\Ecommerce\Model\Address\OrderAddress;
use Sunnysideup\Ecommerce\Model\Address\ShippingAddress;
use Sunnysideup\Ecommerce\Model\Extensions\EcommerceRole;
use Sunnysideup\Ecommerce\Model\Money\EcommerceCurrency;
use Sunnysideup\Ecommerce\Model\Money\EcommercePayment;
use Sunnysideup\Ecommerce\Model\Process\OrderEmailRecord;
use Sunnysideup\Ecommerce\Model\Process\OrderFeedback;
use Sunnysideup\Ecommerce\Model\Process\OrderProcessQueue;
use Sunnysideup\Ecommerce\Model\Process\OrderStatusLog;
use Sunnysideup\Ecommerce\Model\Process\OrderStatusLogs\OrderStatusLogCancel;
use Sunnysideup\Ecommerce\Model\Process\OrderStep;
use Sunnysideup\Ecommerce\Model\Process\OrderSteps\OrderStepCreated;
use Sunnysideup\Ecommerce\Pages\CartPage;
use Sunnysideup\Ecommerce\Pages\CheckoutPage;
use Sunnysideup\Ecommerce\Pages\OrderConfirmationPage;
use Sunnysideup\Ecommerce\Search\Filters\OrderFiltersAroundDateFilter;
use Sunnysideup\Ecommerce\Search\Filters\OrderFiltersFromDateFilter;
use Sunnysideup\Ecommerce\Search\Filters\OrderFiltersHasBeenCancelled;
use Sunnysideup\Ecommerce\Search\Filters\OrderFiltersMemberAndAddress;
use Sunnysideup\Ecommerce\Search\Filters\OrderFiltersMultiOptionsetStatusIDFilter;
use Sunnysideup\Ecommerce\Search\Filters\OrderFiltersUntilDateFilter;
use Sunnysideup\Ecommerce\Tasks\EcommerceTaskDebugCart;

/**
 * @description:
 * The order class is a databound object for handling Orders within SilverStripe.
 * Note that it works closely with the ShoppingCart class, which accompanies the Order
 * until it has been paid for / confirmed by the user.
 *
 * CONTENTS:
 * ----------------------------------------------
 * 1. CMS STUFF
 * 2. MAIN TRANSITION FUNCTIONS
 * 3. STATUS RELATED FUNCTIONS / SHORTCUTS
 * 4. LINKING ORDER WITH MEMBER AND ADDRESS
 * 5. CUSTOMER COMMUNICATION
 * 6. ITEM MANAGEMENT
 * 7. CRUD METHODS (e.g. canView, canEdit, canDelete, etc...)
 * 8. GET METHODS (e.g. Total, SubTotal, Title, etc...)
 * 9. TEMPLATE RELATED STUFF
 * 10. STANDARD SS METHODS (requireDefaultRecords, onBeforeDelete, etc...)
 * 11. DEBUG
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 * @sub-package: model

 *
 * NOTE: This is the SQL for selecting orders in sequence of
 **/
class Order extends DataObject implements EditableEcommerceObject
{
    /**
     * Total Items : total items in cart
     * We start with -1 to easily identify if it has been run before.
     *
     * @var int
     */
    protected $totalItems = null;

    /**
     * Total Items : total items in cart
     * We start with -1 to easily identify if it has been run before.
     *
     * @var float
     */
    protected $totalItemsTimesQuantity = null;

    /**
     * Returns a set of modifier forms for use in the checkout order form,
     * Controller is optional, because the orderForm has its own default controller.
     *
     * This method only returns the Forms that should be included outside
     * the editable table... Forms within it can be called
     * from through the modifier itself.
     *
     * @param Controller $optionalController
     * @param Validator  $optionalValidator
     *
     * @return \SilverStripe\ORM\ArrayList (ArrayData) | Null
     **/
    protected static $_modifier_form_cache = null;

    /*******************************************************
       * 1. CMS STUFF
    *******************************************************/

    /**
     * fields that we remove from the parent::getCMSFields object set.
     *
     * @var array
     */
    protected $fieldsAndTabsToBeRemoved = [
        'MemberID',
        'Attributes',
        'SessionID',
        'Emails',
        'BillingAddressID',
        'ShippingAddressID',
        'UseShippingAddress',
        'OrderStatusLogs',
        'Payments',
        'OrderDate',
        'ExchangeRate',
        'CurrencyUsedID',
        'StatusID',
        'Currency',
    ];

    /**
     * speeds up processing by storing the IsSubmitted value
     * we start with -1 to know if it has been requested before.
     *
     * @var bool
     */
    protected $_isSubmittedTempVar = -1;

    /**
     * API Control.
     *
     * @var array
     */
    private static $api_access = [
        'view' => [
            'OrderEmail',
            'EmailLink',
            'PrintLink',
            'RetrieveLink',
            'ShareLink',
            'FeedbackLink',
            'Title',
            'Total',
            'SubTotal',
            'TotalPaid',
            'TotalOutstanding',
            'ExchangeRate',
            'CurrencyUsed',
            'TotalItems',
            'TotalItemsTimesQuantity',
            'IsCancelled',
            'Country',
            'FullNameCountry',
            'IsSubmitted',
            'CustomerStatus',
            'CanHaveShippingAddress',
            'CancelledBy',
            'CurrencyUsed',
            'BillingAddress',
            'UseShippingAddress',
            'ShippingAddress',
            'Status',
            'Attributes',
            'OrderStatusLogs',
            'MemberID',
        ],
    ];

    /**
     * @var array
     */
    private static $modifiers = [];

    /**
     * @var int
     */
    private static $minutes_an_order_can_be_viewed_without_logging_in = 4320;

    /**
     * @var float
     */
    private static $maximum_ignorable_sales_payments_difference = 0.01;

    /**
     * @var int
     */
    private static $order_id_start_number = 0;

    /**
     * @var string
     */
    private static $template_id_prefix = '';

    /**
     * @var array
     */
    private static $ajax_subtotal_format = [
        'SubTotalAsMoney',
        'NiceDefaultFormat',
    ];

    /**
     * @var array
     */
    private static $ajax_total_format = [
        'TotalAsMoney',
        'NiceDefaultFormat',
    ];

    /**
     * see: http://userguide.icu-project.org/formatparse/datetime
     * @var string
     */
    private static $date_format_for_title = 'd MMM Y, HH:mm ';

    /**
     * @var bool
     */
    private static $include_customer_name_in_title = true;

    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $table_name = 'Order';

    private static $db = [
        'SessionID' => 'Varchar(32)', //so that in the future we can link sessions with Orders.... One session can have several orders, but an order can onnly have one session
        'UseShippingAddress' => 'Boolean',
        'CustomerOrderNote' => 'Text',
        'ExchangeRate' => 'Double',
        //'TotalItems_Saved' => 'Double',
        //'TotalItemsTimesQuantity_Saved' => 'Double'
    ];

    private static $has_one = [
        'Member' => Member::class,
        'BillingAddress' => BillingAddress::class,
        'ShippingAddress' => ShippingAddress::class,
        'Status' => OrderStep::class,
        'CancelledBy' => Member::class,
        'CurrencyUsed' => EcommerceCurrency::class,
    ];

    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $has_many = [
        'Attributes' => OrderAttribute::class,
        'OrderStatusLogs' => OrderStatusLog::class,
        'Payments' => EcommercePayment::class,
        'Emails' => OrderEmailRecord::class,
        'OrderProcessQueue' => OrderProcessQueue::class, //there is usually only one.
    ];

    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $indexes = [
        'SessionID' => true,
        'LastEdited' => true,
    ];

    /**
     * standard SS variable.
     *
     * @var string
     */
    private static $default_sort = [
        'LastEdited' => 'DESC',
        'ID' => 'DESC',
    ];

    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $casting = [
        'OrderEmail' => 'Varchar',
        'EmailLink' => 'Varchar',
        'PrintLink' => 'Varchar',
        'ShareLink' => 'Varchar',
        'FeedbackLink' => 'Varchar',
        'RetrieveLink' => 'Varchar',
        'Title' => 'Varchar',
        'Total' => 'Currency',
        'TotalAsMoney' => 'Money',
        'SubTotal' => 'Currency',
        'SubTotalAsMoney' => 'Money',
        'TotalPaid' => 'Currency',
        'TotalPaidAsMoney' => 'Money',
        'TotalOutstanding' => 'Currency',
        'TotalOutstandingAsMoney' => 'Money',
        'HasAlternativeCurrency' => 'Boolean',
        'TotalItems' => 'Double',
        'TotalItemsTimesQuantity' => 'Double',
        'IsCancelled' => 'Boolean',
        'IsPaidNice' => 'Varchar',
        'Country' => 'Varchar(3)', //This is the applicable country for the order - for tax purposes, etc....
        'FullNameCountry' => 'Varchar',
        'IsSubmitted' => 'Boolean',
        'CustomerStatus' => 'Varchar',
        'CanHaveShippingAddress' => 'Boolean',
    ];

    /**
     * standard SS variable.
     *
     * @var string
     */
    private static $singular_name = 'Order';

    /**
     * standard SS variable.
     *
     * @var string
     */
    private static $plural_name = 'Orders';

    /**
     * Standard SS variable.
     *
     * @var string
     */
    private static $description = "A collection of items that together make up the 'Order'.  An order can be placed.";

    /**
     * Tells us if an order needs to be recalculated
     * can save one for each order...
     *
     * @var array
     */
    private static $_needs_recalculating = [];

    /**
     * STANDARD SILVERSTRIPE STUFF.
     * @var array
     **/
    private static $summary_fields = [
        'Title' => 'Title',
        'OrderItemsSummaryNice' => 'Order Items',
        'Status.Title' => 'Next Step',
        'Member.CustomerDetails' => 'Customer',
        'TotalAsMoney.Nice' => 'Total',
        'IsPaidNice' => 'Paid',
        'CustomerOrderNote' => 'Note',
    ];

    private static $csv_export_fields = [
        'Created' => 'Created',
        'LastEdited' => 'Last Updated',
        'Title' => 'Title',
        'Member.Email' => 'Email',
        'TotalAsMoney' => 'Total',
        'CurrencyUsed.Code' => 'Currency',
        'TotalItemsTimesQuantity' => 'Units',
        'IsPaidNice' => 'Paid',
        'IsCancelledNice' => 'Cancelled',
        'CancelledBy.Email' => 'Cancelled By',
    ];

    /**
     * STANDARD SILVERSTRIPE STUFF.
     *
     * @todo: how to translate this?
     **/
    private static $searchable_fields = [
        'ID' => [
            'field' => NumericField::class,
            'title' => 'Order Number',
        ],
        'MemberID' => [
            'field' => TextField::class,
            'filter' => OrderFiltersMemberAndAddress::class,
            'title' => 'Customer Details',
        ],
        'LastEdited' => [
            'field' => TextField::class,
            'filter' => OrderFiltersAroundDateFilter::class,
            'title' => 'Date (e.g. Today, 1 jan 2007, or last week)',
        ],
        'Created' => [
            'field' => TextField::class,
            'filter' => OrderFiltersFromDateFilter::class,
            'title' => 'From (e.g. Today, 1 jan 2007, or last week)',
        ],
        'SessionID' => [
            'field' => TextField::class,
            'filter' => OrderFiltersUntilDateFilter::class,
            'title' => 'Until (e.g. Today, 1 jan 2007, or last week)',
        ],
        //make sure to keep the items below, otherwise they do not show in form
        'StatusID' => [
            'filter' => OrderFiltersMultiOptionsetStatusIDFilter::class,
        ],
        'CancelledByID' => [
            'filter' => OrderFiltersHasBeenCancelled::class,
            'title' => 'Cancelled by ...',
        ],
    ];

    /**
     * @var array
     */
    private static $_try_to_finalise_order_is_running = [];

    /**
     * fields contains in CSV export for ModelAdmin GridField
     *
     * @return array
     **/
    public function getExportFields()
    {
        $exportFields = EcommerceConfig::get(Order::class, 'csv_export_fields');
        $this->extend('updateOrderExportFields', $exportFields);
        return $exportFields;
    }

    public function i18n_singular_name()
    {
        return _t('Order.ORDER', 'Order');
    }

    public function i18n_plural_name()
    {
        return _t('Order.ORDERS', 'Orders');
    }

    /**
     * @param bool (optional) $b
     * @param int (optional)  $orderID
     *
     * @return bool
     */
    public static function set_needs_recalculating($b = true, $orderID = 0)
    {
        self::$_needs_recalculating[$orderID] = $b;
    }

    /**
     * @param int (optional) $orderID
     *
     * @return bool
     */
    public static function get_needs_recalculating($orderID = 0)
    {
        return isset(self::$_needs_recalculating[$orderID]) ? self::$_needs_recalculating[$orderID] : false;
    }

    public function getModifierForms(Controller $optionalController = null, Validator $optionalValidator = null)
    {
        if (self::$_modifier_form_cache === null) {
            $formsDone = [];
            $arrayList = new ArrayList();
            $modifiers = $this->Modifiers();
            if ($modifiers->count()) {
                foreach ($modifiers as $modifier) {
                    if ($modifier->ShowForm()) {
                        if (! isset($formsDone[$modifier->ClassName])) {
                            $formsDone[$modifier->ClassName] = true;
                            $form = $modifier->getModifierForm($optionalController, $optionalValidator);
                            if ($form) {
                                $form->ShowFormInEditableOrderTable = $modifier->ShowFormInEditableOrderTable();
                                $form->ShowFormOutsideEditableOrderTable = $modifier->ShowFormOutsideEditableOrderTable();
                                $form->ModifierName = $modifier->ClassName;
                                //$Me is legacy
                                $obj = ArrayData::create(['Form' => $form, 'Modifier' => $modifier, 'Me' => $form]);
                                $arrayList->push($obj);
                            }
                        }
                    }
                }
            }
            self::$_modifier_form_cache = $arrayList;
        }
        return self::$_modifier_form_cache;
    }

    /**
     * This function returns the OrderSteps.
     *
     * @return \SilverStripe\ORM\ArrayList (OrderSteps)
     **/
    public static function get_order_status_options()
    {
        return OrderStep::get();
    }

    /**
     * Like the standard byID, but it checks whether we are allowed to view the order.
     *
     * @return: Order | Null
     **/
    public static function get_by_id_if_can_view($id)
    {
        $order = Order::get()->byID($id);
        if ($order && $order->canView()) {
            if ($order->IsSubmitted()) {
                // LITTLE HACK TO MAKE SURE WE SHOW THE LATEST INFORMATION!
                $order->tryToFinaliseOrder();
            }

            return $order;
        }

        return;
    }

    /**
     * returns a Datalist with the submitted order log included
     * this allows you to sort the orders by their submit dates.
     * You can retrieve this list and then add more to it (e.g. additional filters, additional joins, etc...).
     *
     * @param bool $onlySubmittedOrders - only include Orders that have already been submitted.
     * @param bool $includeCancelledOrders - only include Orders that have already been submitted.
     *
     * @return \SilverStripe\ORM\DataList (Orders)
     */
    public static function get_datalist_of_orders_with_submit_record($onlySubmittedOrders = true, $includeCancelledOrders = false)
    {
        if ($onlySubmittedOrders) {
            $submittedOrderStatusLogClassName = EcommerceConfig::get(OrderStatusLog::class, 'order_status_log_class_used_for_submitting_order');
            $submittedOrderStatusLogTableName = OrderStatusLog::getSchema()->tableName(OrderStatusLog::class);
            $list = Order::get()
                ->LeftJoin('OrderStatusLog', '"Order"."ID" = "OrderStatusLog"."OrderID"')
                ->LeftJoin($submittedOrderStatusLogTableName, '"OrderStatusLog"."ID" = "' . $submittedOrderStatusLogTableName . '"."ID"');
            $where = ' ("OrderStatusLog"."ClassName" = \'' . $submittedOrderStatusLogClassName . '\') ';
        } else {
            $list = Order::get();
            $where = ' ("StatusID" > 0) ';
        }
        if ($includeCancelledOrders) {
            //do nothing...
        } else {
            $where .= ' AND ("CancelledByID" = 0 OR "CancelledByID" IS NULL)';
        }
        return $list->where($where);
    }

    /**
     * Determine which properties on the DataObject are
     * searchable, and map them to their default {@link FormField}
     * representations. Used for scaffolding a searchform for {@link ModelAdmin}.
     *
     * Some additional logic is included for switching field labels, based on
     * how generic or specific the field type is.
     *
     * Used by {@link SearchContext}.
     *
     * @param array $_params
     *                       'fieldClasses': Associative array of field names as keys and FormField classes as values
     *                       'restrictFields': Numeric array of a field name whitelist
     *
     * @return \SilverStripe\Forms\FieldList
     */
    public function scaffoldSearchFields($_params = null)
    {
        $fieldList = parent::scaffoldSearchFields($_params);

        //for sales to action only show relevant ones ...
        if (Controller::curr() && Controller::curr()->class === SalesAdmin::class) {
            $statusOptions = OrderStep::admin_manageable_steps();
        } else {
            $statusOptions = OrderStep::get();
        }
        if ($statusOptions && $statusOptions->count()) {
            $preSelected = [];
            // $createdOrderStatus = $statusOptions->First();
            $arrayOfStatusOptions = clone $statusOptions->map('ID', 'Title');
            $arrayOfStatusOptionsFinal = [];
            if (count($arrayOfStatusOptions)) {
                foreach ($arrayOfStatusOptions as $key => $value) {
                    if (isset($_GET['q']['StatusID'][$key])) {
                        $preSelected[$key] = $key;
                    }
                    $count = Order::get()
                        ->Filter(['StatusID' => intval($key)])
                        ->count();
                    if ($count < 1) {
                        //do nothing
                    } else {
                        $arrayOfStatusOptionsFinal[$key] = $value . " (${count})";
                    }
                }
            }
            $statusField = new CheckboxSetField(
                'StatusID',
                Injector::inst()->get(OrderStep::class)->i18n_singular_name(),
                $arrayOfStatusOptionsFinal,
                $preSelected
            );
            $fieldList->push($statusField);
        }
        $fieldList->push(new DropdownField('CancelledByID', 'Cancelled', [-1 => '(Any)', 1 => 'yes', 0 => 'no']));

        //allow changes
        $this->extend('scaffoldSearchFields', $fieldList, $_params);

        return $fieldList;
    }

    /**
     * link to edit the record.
     *
     * @param string | Null $action - e.g. edit
     *
     * @return string
     */
    public function CMSEditLink($action = null)
    {
        return CMSEditLinkAPI::find_edit_link_for_object($this, $action, 'sales-advanced');
    }

    /**
     * STANDARD SILVERSTRIPE STUFF
     * broken up into submitted and not (yet) submitted.
     **/
    public function getCMSFields()
    {
        $fields = $this->scaffoldFormFields([
            // Don't allow has_many/many_many relationship editing before the record is first saved
            'includeRelations' => false,
            'tabbed' => true,
            'ajaxSafe' => true,
        ]);
        $fields->insertBefore(
            Tab::create(
                'Next',
                _t('Order.NEXT_TAB', 'Action')
            ),
            'Main'
        );
        $fields->addFieldsToTab(
            'Root',
            [
                Tab::create(
                    'Items',
                    _t('Order.ITEMS_TAB', 'Items')
                ),
                Tab::create(
                    'Extras',
                    _t('Order.MODIFIERS_TAB', 'Adjustments')
                ),
                Tab::create(
                    'Emails',
                    _t('Order.EMAILS_TAB', 'Emails')
                ),
                Tab::create(
                    'Payments',
                    _t('Order.PAYMENTS_TAB', 'Payment')
                ),
                Tab::create(
                    'Account',
                    _t('Order.ACCOUNT_TAB', 'Account')
                ),
                Tab::create(
                    'Currency',
                    _t('Order.CURRENCY_TAB', 'Currency')
                ),
                Tab::create(
                    'Addresses',
                    _t('Order.ADDRESSES_TAB', 'Addresses')
                ),
                Tab::create(
                    'Log',
                    _t('Order.LOG_TAB', 'Notes')
                ),
                Tab::create(
                    'Cancellations',
                    _t('Order.CANCELLATION_TAB', 'Cancel')
                ),
            ]
        );
        //as we are no longer using the parent:;getCMSFields
        // we had to add the updateCMSFields hook.
        $this->extend('updateCMSFields', $fields);
        $currentMember = Security::getCurrentUser();
        if (! $this->exists() || ! $this->StatusID) {
            $firstStep = DataObject::get_one(OrderStep::class);
            $this->StatusID = $firstStep->ID;
            $this->write();
        }
        $submitted = $this->IsSubmitted() ? true : false;
        if ($submitted) {
            //TODO
            //Having trouble here, as when you submit the form (for example, a payment confirmation)
            //as the step moves forward, meaning the fields generated are incorrect, causing an error
            //"I can't handle sub-URLs of a Form object." generated by the RequestHandler.
            //Therefore we need to try reload the page so that it will be requesting the correct URL to generate the correct fields for the current step
            //Or something similar.
            //why not check if the URL == $this->CMSEditLink()
            //and only tryToFinaliseOrder if this is true....
            if ($_SERVER['REQUEST_URI'] === $this->CMSEditLink() || $_SERVER['REQUEST_URI'] === $this->CMSEditLink('edit')) {
                $this->tryToFinaliseOrder();
            }
        } else {
            $this->init(true);
            $this->calculateOrderAttributes(true);
            Controller::curr()->getRequest()->getSession()->set('EcommerceOrderGETCMSHack', $this->ID);
        }
        if ($submitted) {
            $this->fieldsAndTabsToBeRemoved[] = 'CustomerOrderNote';
        } else {
            $this->fieldsAndTabsToBeRemoved[] = 'Emails';
        }
        foreach ($this->fieldsAndTabsToBeRemoved as $field) {
            $fields->removeByName($field);
        }
        $summaryFields = [];
        foreach ($this->summaryFields() as $fieldName => $label) {
            if ($fieldName !== 'AssignedAdminNice') {
                $value = '(error)';
                if ($this->hasMethod('relField')) {
                    $value = $this->relField($fieldName);
                } elseif ($this->hasMethod($fieldName)) {
                    $value = $this->{$fieldName}();
                } elseif ($this->hasMethod('get' . $fieldName)) {
                    $fieldName = 'get' . $fieldName;
                    $value = $this->{$fieldName}();
                }
                $summaryFields[] = ReadonlyField::create($fieldName . '_Summary', $label, $value);
            }
        }
        $nextFieldArray = array_merge(
            $summaryFields,
            [
                LiteralField::create(
                    'CssFix',
                    '<style>
                        #Root_Next h2.section-heading-for-order {padding: 0!important; margin: 0!important; padding-top: 3em!important; color: #0071c4;}
                    </style>'
                ),
                HeaderField::create('MyOrderStepHeader', _t('Order.CURRENT_STATUS', 'Current Status, Notes, and Actions'))->addExtraClass('section-heading-for-order'),
                $this->OrderStepField(),
            ]
        );
        $keyNotes = OrderStatusLog::get()->filter(
            [
                'OrderID' => $this->ID,
                'ClassName' => OrderStatusLog::class,
            ]
        );
        if ($keyNotes->count()) {
            $notesSummaryConfig = GridFieldConfig_RecordViewer::create();
            $notesSummaryConfig->removeComponentsByType(GridFieldToolbarHeader::class);
            $notesSummaryConfig->removeComponentsByType(GridFieldFilterHeader::class);
            // $orderSummaryConfig->removeComponentsByType('GridFieldSortableHeader');
            $notesSummaryConfig->removeComponentsByType(GridFieldPageCount::class);
            $notesSummaryConfig->removeComponentsByType(GridFieldPaginator::class);
            $nextFieldArray = array_merge(
                $nextFieldArray,
                [
                    HeaderField::create('KeyNotesHeader', _t('Order.KEY_NOTES_HEADER', 'Notes'), 4),
                    GridField::create(
                        'OrderStatusLogSummary',
                        _t('Order.CURRENT_KEY_NOTES', 'Key Notes'),
                        $keyNotes,
                        $notesSummaryConfig
                    ),
                ]
            );
        }
        $nextFieldArray = array_merge(
            $nextFieldArray,
            [
                EcommerceCMSButtonField::create(
                    'AddNoteButton',
                    '/admin/sales-advanced/Sunnysideup-Ecommerce-Model-Order/EditForm/field/Sunnysideup-Ecommerce-Model-Order/item/' . $this->ID . '/ItemEditForm/field/OrderStatusLog/item/new',
                    _t('Order.ADD_NOTE', 'Add Note')
                ),
            ]
        );

        //is the member is a shop admin they can always view it

        if (EcommerceRole::current_member_can_process_orders(Security::getCurrentUser())) {
            $lastStep = OrderStep::last_order_step();
            if ($this->StatusID !== $lastStep->ID) {
                $queueObjectSingleton = Injector::inst()->get(OrderProcessQueue::class);
                if ($queueObjectSingleton->getQueueObject($this)) {
                    $myQueueObjectField = GridField::create(
                        'MyQueueObjectField',
                        _t('Order.QUEUE_DETAILS', 'Queue Details'),
                        $this->OrderProcessQueue(),
                        GridFieldConfig_RecordEditor::create()
                    );
                } else {
                    $myQueueObjectField = LiteralField::create('MyQueueObjectField', '<p>' . _t('Order.NOT_QUEUED', 'This order is not queued for future processing.') . '</p>');
                }
                $nextFieldArray = array_merge(
                    $nextFieldArray,
                    [
                        HeaderField::create('OrderStepNextStepHeader', _t('Order.ACTION_NEXT_STEP', 'Action Next Step'))->addExtraClass('section-heading-for-order'),
                        $myQueueObjectField,
                        HeaderField::create('ActionNextStepManually', _t('Order.MANUAL_STATUS_CHANGE', 'Move Order Along'))->addExtraClass('section-heading-for-order'),
                        LiteralField::create('OrderStepNextStepHeaderExtra', '<p>' . _t('Order.NEEDTOREFRESH', 'Once you have made any changes to the order then you will have to refresh below or save it to move it along.') . '</p>'),
                        EcommerceCMSButtonField::create(
                            'StatusIDExplanation',
                            $this->CMSEditLink(),
                            _t('Order.REFRESH', 'refresh now')
                        ),
                    ]
                );
            }
        }
        $fields->addFieldsToTab(
            'Root.Next',
            $nextFieldArray
        );

        $this->MyStep()->addOrderStepFields($fields, $this);

        if ($submitted) {
            $permaLinkLabel = _t('Order.PERMANENT_LINK', 'Customer Link');
            $html = '<p>' . $permaLinkLabel . ': <a href="' . $this->getRetrieveLink() . '">' . $this->getRetrieveLink() . '</a></p>';
            $shareLinkLabel = _t('Order.SHARE_LINK', 'Share Link');
            $html .= '<p>' . $shareLinkLabel . ': <a href="' . $this->getShareLink() . '">' . $this->getShareLink() . '</a></p>';
            $feedbackLinkLabel = _t('Order.FEEDBACK_LINK', 'Feedback Link');
            $html .= '<p>' . $feedbackLinkLabel . ': <a href="' . $this->getFeedbackLink() . '">' . $this->getFeedbackLink() . '</a></p>';
            $js = "window.open(this.href, 'payment', 'toolbar=0,scrollbars=1,location=1,statusbar=1,menubar=0,resizable=1,width=800,height=600'); return false;";
            $link = $this->getPrintLink();
            $label = _t('Order.PRINT_INVOICE', 'invoice');
            $linkHTML = '<a href="' . $link . '" onclick="' . $js . '">' . $label . '</a>';
            $linkHTML .= ' | ';
            $link = $this->getPackingSlipLink();
            $label = _t('Order.PRINT_PACKING_SLIP', 'packing slip');
            $labelPrint = _t('Order.PRINT', 'Print');
            $linkHTML .= '<a href="' . $link . '" onclick="' . $js . '">' . $label . '</a>';
            $html .= '<h3>';
            $html .= $labelPrint . ': ' . $linkHTML;
            $html .= '</h3>';

            $fields->addFieldToTab(
                'Root.Main',
                LiteralField::create('getPrintLinkANDgetPackingSlipLink', $html)
            );

            //add order here as well.
            $fields->addFieldToTab(
                'Root.Main',
                new LiteralField(
                    'MainDetails',
                    '<iframe src="' . $this->getPrintLink() . '" width="100%" height="2500" style="border: 5px solid #2e7ead; border-radius: 2px;"></iframe>'
                )
            );
            $fields->addFieldsToTab(
                'Root.Items',
                [
                    GridField::create(
                        'Items_Sold',
                        'Items Sold',
                        $this->Items(),
                        new GridFieldConfig_RecordViewer()
                    ),
                ]
            );
            $fields->addFieldsToTab(
                'Root.Extras',
                [
                    GridField::create(
                        'Modifications',
                        'Price (and other) adjustments',
                        $this->Modifiers(),
                        new GridFieldConfig_RecordViewer()
                    ),
                ]
            );
            $fields->addFieldsToTab(
                'Root.Emails',
                [
                    $this->getEmailsTableField(),
                ]
            );
            $fields->addFieldsToTab(
                'Root.Payments',
                [
                    $this->getPaymentsField(),
                    new ReadonlyField('TotalPaidNice', _t('Order.TOTALPAID', 'Total Paid'), $this->TotalPaidAsCurrencyObject()->Nice()),
                    new ReadonlyField('TotalOutstandingNice', _t('Order.TOTALOUTSTANDING', 'Total Outstanding'), $this->getTotalOutstandingAsMoney()->Nice()),
                ]
            );
            if ($this->canPay()) {
                $link = EcommercePaymentController::make_payment_link($this->ID);
                $js = "window.open(this.href, 'payment', 'toolbar=0,scrollbars=1,location=1,statusbar=1,menubar=0,resizable=1,width=800,height=600'); return false;";
                $header = _t('Order.MAKEPAYMENT', 'make payment');
                $label = _t('Order.MAKEADDITIONALPAYMENTNOW', 'make additional payment now');
                $linkHTML = '<a href="' . $link . '" onclick="' . $js . '">' . $label . '</a>';
                $fields->addFieldToTab('Root.Payments', new HeaderField('MakeAdditionalPaymentHeader', $header, 3));
                $fields->addFieldToTab('Root.Payments', new LiteralField('MakeAdditionalPayment', $linkHTML));
            }
            //member
            $member = $this->Member();
            if ($member && $member->exists()) {
                $fields->addFieldToTab('Root.Account', new LiteralField('MemberDetails', $member->getEcommerceFieldsForCMS()));
            } else {
                $fields->addFieldToTab('Root.Account', new LiteralField(
                    'MemberDetails',
                    '<p>' . _t('Order.NO_ACCOUNT', 'There is no --- account --- associated with this order') . '</p>'
                ));
            }
            if ($this->getFeedbackLink()) {
                $fields->addFieldToTab(
                    'Root.Account',
                    GridField::create(
                        'OrderFeedback',
                        Injector::inst()->get(OrderFeedback::class)->singular_name(),
                        OrderFeedback::get()->filter(['OrderID' => $this->ID]),
                        GridFieldConfig_RecordViewer::create()
                    )
                );
            }
            $cancelledField = $fields->dataFieldByName('CancelledByID');
            $fields->removeByName('CancelledByID');
            $shopAdminAndCurrentCustomerArray = EcommerceRole::list_of_admins(true);
            if ($member && $member->exists()) {
                $shopAdminAndCurrentCustomerArray[$member->ID] = $member->getName();
            }
            if ($this->CancelledByID) {
                if ($cancellingMember = $this->CancelledBy()) {
                    $shopAdminAndCurrentCustomerArray[$this->CancelledByID] = $cancellingMember->getName();
                }
            }
            if ($this->canCancel()) {
                $fields->addFieldsToTab(
                    'Root.Cancellations',
                    [
                        DropdownField::create(
                            'CancelledByID',
                            $cancelledField->Title(),
                            $shopAdminAndCurrentCustomerArray
                        ),
                    ]
                );
            } else {
                $cancelledBy = isset($shopAdminAndCurrentCustomerArray[$this->CancelledByID]) && $this->CancelledByID ? $shopAdminAndCurrentCustomerArray[$this->CancelledByID] : _t('Order.NOT_CANCELLED', 'not cancelled');
                $fields->addFieldsToTab(
                    'Root.Cancellations',
                    ReadonlyField::create(
                        'CancelledByDisplay',
                        $cancelledField->Title(),
                        $cancelledBy
                    )
                );
            }
            $fields->addFieldToTab('Root.Log', $this->getOrderStatusLogsTableField_Archived());
            $submissionLog = $this->SubmissionLog();
            if ($submissionLog) {
                $fields->addFieldToTab(
                    'Root.Log',
                    ReadonlyField::create(
                        'SequentialOrderNumber',
                        _t('Order.SEQUENTIALORDERNUMBER', 'Consecutive order number'),
                        $submissionLog->SequentialOrderNumber
                    )->setDescription('e.g. 1,2,3,4,5...')
                );
            }
        } else {
            $linkText = _t(
                'Order.LOAD_THIS_ORDER',
                'load this order'
            );
            $message = _t(
                'Order.NOSUBMITTEDYET',
                'No details are shown here as this order has not been submitted yet. You can {link} to submit it... NOTE: For this, you will be logged in as the customer and logged out as (shop)admin .',
                ['link' => '<a href="' . $this->getRetrieveLink() . '" data-popup="true">' . $linkText . '</a>']
            );
            $fields->addFieldToTab('Root.Next', new LiteralField('MainDetails', '<p>' . $message . '</p>'));
            $fields->addFieldToTab('Root.Items', $this->getOrderItemsField());
            $fields->addFieldToTab('Root.Extras', $this->getModifierTableField());

            //MEMBER STUFF
            $specialOptionsArray = [];
            if ($this->MemberID) {
                $specialOptionsArray[0] = _t('Order.SELECTCUSTOMER', '--- Remover Customer ---');
                $specialOptionsArray[$this->MemberID] = _t('Order.LEAVEWITHCURRENTCUSTOMER', '- Leave with current customer: ') . $this->Member()->getTitle();
            } elseif ($currentMember) {
                $specialOptionsArray[0] = _t('Order.SELECTCUSTOMER', '--- Select Customers ---');
                $currentMemberID = $currentMember->ID;
                $specialOptionsArray[$currentMemberID] = _t('Order.ASSIGNTHISORDERTOME', '- Assign this order to me: ') . $currentMember->getTitle();
            }
            //MEMBER FIELD!!!!!!!
            $memberArray = $specialOptionsArray + EcommerceRole::list_of_customers(true);
            $fields->addFieldToTab('Root.Next', new DropdownField('MemberID', _t('Order.SELECTCUSTOMER', 'Select Customer'), $memberArray), 'CustomerOrderNote');
        }
        $fields->addFieldToTab('Root.Addresses', new HeaderField('BillingAddressHeader', _t('Order.BILLINGADDRESS', 'Billing Address')));

        $fields->addFieldToTab('Root.Addresses', $this->getBillingAddressField());

        if (EcommerceConfig::get(OrderAddress::class, 'use_separate_shipping_address')) {
            $fields->addFieldToTab('Root.Addresses', new HeaderField('ShippingAddressHeader', _t('Order.SHIPPINGADDRESS', 'Shipping Address')));
            $fields->addFieldToTab('Root.Addresses', new CheckboxField('UseShippingAddress', _t('Order.USESEPERATEADDRESS', 'Use separate shipping address?')));
            if ($this->UseShippingAddress) {
                $fields->addFieldToTab('Root.Addresses', $this->getShippingAddressField());
            }
        }
        $currencies = EcommerceCurrency::get_list();
        if ($currencies && $currencies->count()) {
            $currencies = $currencies->map()->toArray();
            $fields->addFieldToTab('Root.Currency', new ReadonlyField('ExchangeRate ', _t('Order.EXCHANGERATE', 'Exchange Rate'), $this->ExchangeRate));
            $fields->addFieldToTab('Root.Currency', $currencyField = new DropdownField('CurrencyUsedID', _t('Order.CurrencyUsed', 'Currency Used'), $currencies));
            if ($this->IsSubmitted()) {
                $fields->replaceField('CurrencyUsedID', $fields->dataFieldByName('CurrencyUsedID')->performReadonlyTransformation());
            }
        } else {
            $fields->addFieldToTab('Root.Currency', new LiteralField('CurrencyInfo', '<p>You can not change currencies, because no currencies have been created.</p>'));
            $fields->replaceField('CurrencyUsedID', $fields->dataFieldByName('CurrencyUsedID')->performReadonlyTransformation());
        }
        $fields->addFieldToTab('Root.Log', new ReadonlyField('Created', _t('Root.CREATED', 'Created')));
        $fields->addFieldToTab('Root.Log', new ReadonlyField('LastEdited', _t('Root.LASTEDITED', 'Last saved')));
        $this->extend('updateCMSFields', $fields);

        return $fields;
    }

    /**
     * Field to add and edit Modifiers.
     *
     * @return GridField
     */
    public function getModifierTableField()
    {
        $gridFieldConfig = GridFieldConfigForOrderItems::create();
        $source = $this->Modifiers();

        return new GridField('OrderModifiers', _t('OrderItems.PLURALNAME', 'Order Items'), $source, $gridFieldConfig);
    }

    /**
     * Needs to be public because the OrderStep::getCMSFIelds accesses it.
     *
     * @param string    $sourceClass
     * @param string    $title
     *
     * @return GridField
     **/
    public function getOrderStatusLogsTableField(
        $sourceClass = OrderStatusLog::class,
        $title = ''
    ) {
        $gridFieldConfig = GridFieldConfig_RecordViewer::create()->addComponents(
            new GridFieldAddNewButton('toolbar-header-right'),
            new GridFieldDetailForm()
        );
        $title ?: $title = _t('OrderStatusLog.PLURALNAME', 'Order Status Logs');
        $source = $this->OrderStatusLogs()->Filter(['ClassName' => $sourceClass]);
        $fieldName = ClassInfo::shortName($sourceClass);
        $gf = new GridField($fieldName, $title, $source, $gridFieldConfig);
        $gf->setModelClass($sourceClass);

        return $gf;
    }

    /**
     * Needs to be public because the OrderStep::getCMSFIelds accesses it.
     *
     * @param string    $sourceClass
     * @param string    $title
     *
     * @return GridField
     **/
    public function getOrderStatusLogsTableFieldEditable(
        $sourceClass = OrderStatusLog::class,
        $title = ''
    ) {
        $gf = $this->getOrderStatusLogsTableField($sourceClass, $title);
        $gf->getConfig()->addComponents(
            new GridFieldEditButton()
        );
        return $gf;
    }

    /**
     * @return GridField
     **/
    public function getEmailsTableField()
    {
        $gridFieldConfig = GridFieldConfig_RecordViewer::create()->addComponents(
            new GridFieldDetailForm()
        );

        return new GridField('Emails', _t('Order.CUSTOMER_EMAILS', 'Customer Emails'), $this->Emails(), $gridFieldConfig);
    }

    /**
     * @return OrderStepField
     */
    public function OrderStepField()
    {
        return OrderStepField::create(
            'MyOrderStep',
            $this,
            Security::getCurrentUser()
        );
    }

    /*******************************************************
       * 2. MAIN TRANSITION FUNCTIONS
    *******************************************************/

    /**
     * init runs on start of a new Order (@see onAfterWrite)
     * it adds all the modifiers to the orders and the starting OrderStep.
     *
     * @param bool $recalculate
     *
     * @return \SilverStripe\ORM\DataObject (Order)
     **/
    public function init($recalculate = false)
    {
        if ($this->IsSubmitted()) {
            user_error('Can not init an order that has been submitted', E_USER_NOTICE);
        } else {
            //to do: check if shop is open....
            if ($this->StatusID || $recalculate) {
                if (! $this->StatusID) {
                    $createdOrderStatus = DataObject::get_one(OrderStep::class);
                    if (! $createdOrderStatus) {
                        user_error('No ordersteps have been created', E_USER_WARNING);
                    }
                    $this->StatusID = $createdOrderStatus->ID;
                }
                $createdModifiersClassNames = [];
                $modifiersAsArrayList = new ArrayList();
                $modifiers = $this->modifiersFromDatabase($includingRemoved = true);
                if ($modifiers->count()) {
                    foreach ($modifiers as $modifier) {
                        $modifiersAsArrayList->push($modifier);
                    }
                }
                if ($modifiersAsArrayList->count()) {
                    foreach ($modifiersAsArrayList as $modifier) {
                        $createdModifiersClassNames[$modifier->ID] = $modifier->ClassName;
                    }
                }

                $modifiersToAdd = EcommerceConfig::get(Order::class, 'modifiers');
                if (is_array($modifiersToAdd) && count($modifiersToAdd) > 0) {
                    foreach ($modifiersToAdd as $numericKey => $className) {
                        if (! in_array($className, $createdModifiersClassNames, true)) {
                            if (class_exists($className)) {
                                $modifier = new $className();
                                //only add the ones that should be added automatically
                                if (! $modifier->DoNotAddAutomatically()) {
                                    if (is_a($modifier, OrderModifier::class)) {
                                        $modifier->OrderID = $this->ID;
                                        $modifier->Sort = $numericKey;
                                        //init method includes a WRITE
                                        $modifier->init();
                                        //IMPORTANT - add as has_many relationship  (Attributes can be a modifier OR an OrderItem)
                                        $this->Attributes()->add($modifier);
                                        $modifiersAsArrayList->push($modifier);
                                    }
                                }
                            } else {
                                user_error('reference to a non-existing class: ' . $className . ' in modifiers', E_USER_NOTICE);
                            }
                        }
                    }
                }
                $this->extend('onInit', $this);
                //careful - this will call "onAfterWrite" again
                $this->write();
            }
        }

        return $this;
    }

    /**
     * Goes through the order steps and tries to "apply" the next status to the order.
     *
     * @param bool $runAgain
     * @param bool $fromOrderQueue - is it being called from the OrderProcessQueue (or similar)
     *
     **/
    public function tryToFinaliseOrder($runAgain = false, $fromOrderQueue = false)
    {
        if (empty(self::$_try_to_finalise_order_is_running[$this->ID]) || $runAgain) {
            self::$_try_to_finalise_order_is_running[$this->ID] = true;

            //if the order has been cancelled then we do not process it ...
            if ($this->CancelledByID) {
                $this->Archive(true);

                return;
            }
            // if it is in the queue it has to run from the queue tasks
            // if it ruins from the queue tasks then it has to be one currently processing.
            $queueObjectSingleton = Injector::inst()->get(OrderProcessQueue::class);
            if ($myQueueObject = $queueObjectSingleton->getQueueObject($this)) {
                if ($fromOrderQueue) {
                    if (! $myQueueObject->InProcess) {
                        return;
                    }
                } else {
                    return;
                }
            }
            //a little hack to make sure we do not rely on a stored value
            //of "isSubmitted"
            $this->_isSubmittedTempVar = -1;
            //status of order is being progressed
            $nextStatusID = $this->doNextStatus();
            if ($nextStatusID) {
                $nextStatusObject = OrderStep::get()->byID($nextStatusID);
                if ($nextStatusObject) {
                    $delay = $nextStatusObject->CalculatedDeferTimeInSeconds($this);
                    if ($delay > 0) {
                        //adjust delay time from seconds since being submitted
                        if ($nextStatusObject->DeferFromSubmitTime) {
                            $delay -= $this->SecondsSinceBeingSubmitted();
                            if ($delay < 0) {
                                $delay = 0;
                            }
                        }
                        $queueObjectSingleton->AddOrderToQueue(
                            $this,
                            $delay
                        );
                    } else {
                        //status has been completed, so it can be released
                        self::$_try_to_finalise_order_is_running[$this->ID] = false;
                        $this->tryToFinaliseOrder($runAgain, $fromOrderQueue);
                    }
                }
            }
            self::$_try_to_finalise_order_is_running[$this->ID] = false;
        }
    }

    /**
     * Goes through the order steps and tries to "apply" the next step
     * Step is updated after the other one is completed...
     *
     * @return int (StatusID or false if the next status can not be "applied")
     **/
    public function doNextStatus()
    {
        if ($this->MyStep()->initStep($this)) {
            if ($this->MyStep()->doStep($this)) {
                /** @var OrderStep|null */
                $nextOrderStepObject = $this->MyStep()->nextStep($this);
                if ($nextOrderStepObject instanceof OrderStep) {
                    $this->StatusID = $nextOrderStepObject->ID;
                    $this->write();

                    return $this->StatusID;
                }
            }
        }

        return 0;
    }

    /**
     * cancel an order.
     *
     * @param \SilverStripe\Security\Member $member - (optional) the user cancelling the order
     * @param string $reason - (optional) the reason the order is cancelled
     *
     * @return OrderStatusLogCancel
     */
    public function Cancel($member = null, $reason = '')
    {
        if ($member && $member instanceof Member) {
            //we have a valid member
        } else {
            $member = EcommerceRole::get_default_shop_admin_user();
        }
        if ($member) {
            //archive and write
            $this->Archive($avoidWrites = true);
            if ($avoidWrites) {
                DB::query('Update "Order" SET CancelledByID = ' . $member->ID . ' WHERE ID = ' . $this->ID . ' LIMIT 1;');
            } else {
                $this->CancelledByID = $member->ID;
                $this->write();
            }
            //create log ...
            $log = OrderStatusLogCancel::create();
            $log->AuthorID = $member->ID;
            $log->OrderID = $this->ID;
            $log->Note = $reason;
            if ($member->IsShopAdmin()) {
                $log->InternalUseOnly = true;
            }
            $log->write();
            //remove from queue ...
            $queueObjectSingleton = Injector::inst()->get(OrderProcessQueue::class);
            $queueObjectSingleton->removeOrderFromQueue($this);
            $this->extend('doCancel', $member, $log);

            return $log;
        }
    }

    /**
     * returns true if successful.
     *
     * @param bool $avoidWrites
     *
     * @return bool
     */
    public function Archive($avoidWrites = true)
    {
        $this->extend('onBeforeArchive', $this);
        $lastOrderStep = OrderStep::last_order_step();
        if ($lastOrderStep) {
            if ($avoidWrites) {
                DB::query('
                    UPDATE "Order"
                    SET "Order"."StatusID" = ' . $lastOrderStep->ID . '
                    WHERE "Order"."ID" = ' . $this->ID . '
                    LIMIT 1
                ');

                return true;
            }
            $this->StatusID = $lastOrderStep->ID;
            $this->write();

            return true;
        }

        return false;
    }

    /*******************************************************
       * 3. STATUS RELATED FUNCTIONS / SHORTCUTS
    *******************************************************/

    /**
     * Avoids caching of $this->Status().
     *
     * @return \SilverStripe\ORM\DataObject (current OrderStep)
     */
    public function MyStep()
    {
        $step = null;
        if ($this->StatusID) {
            $step = OrderStep::get()->byID($this->StatusID);
        }
        if (! $step) {
            $step = DataObject::get_one(
                OrderStep::class,
                null,
                $cacheDataObjectGetOne = false
            );
        }
        if (! $step) {
            $step = OrderStepCreated::create();
        }
        if (! $step) {
            user_error('You need an order step in your Database.');
        }

        return $step;
    }

    /**
     * Return the OrderStatusLog that is relevant to the Order status.
     *
     * @return OrderStatusLog
     */
    public function RelevantLogEntry()
    {
        return $this->MyStep()->RelevantLogEntry($this);
    }

    /**
     * @return OrderStep (current OrderStep that can be seen by customer)
     */
    public function CurrentStepVisibleToCustomer()
    {
        $obj = $this->MyStep();
        if ($obj->HideStepFromCustomer) {
            $obj = OrderStep::get()->where('"OrderStep"."Sort" < ' . $obj->Sort . ' AND "HideStepFromCustomer" = 0')->Last();
            if (! $obj) {
                $obj = DataObject::get_one(OrderStep::class);
            }
        }

        return $obj;
    }

    /**
     * works out if the order is still at the first OrderStep.
     *
     * @return bool
     */
    public function IsFirstStep()
    {
        $firstStep = DataObject::get_one(OrderStep::class);
        $currentStep = $this->MyStep();
        if ($firstStep && $currentStep) {
            if ($firstStep->ID === $currentStep->ID) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is the order still being "edited" by the customer?
     *
     * @return bool
     */
    public function IsInCart()
    {
        return (bool) $this->IsSubmitted() ? false : true;
    }

    /**
     * The order has "passed" the IsInCart phase.
     *
     * @return bool
     */
    public function IsPastCart()
    {
        return (bool) $this->IsInCart() ? false : true;
    }

    /**
     * Are there still steps the order needs to go through?
     *
     * @return bool
     */
    public function IsUncomplete()
    {
        return (bool) $this->MyStep()->ShowAsUncompletedOrder;
    }

    /**
     * Is the order in the :"processing" phaase.?
     *
     * @return bool
     */
    public function IsProcessing()
    {
        return (bool) $this->MyStep()->ShowAsInProcessOrder;
    }

    /**
     * Is the order completed?
     *
     * @return bool
     */
    public function IsCompleted()
    {
        return (bool) $this->MyStep()->ShowAsCompletedOrder;
    }

    /**
     * Has the order been paid?
     * TODO: why do we check if there is a total at all?
     *
     * @return bool
     */
    public function IsPaid()
    {
        if ($this->IsSubmitted()) {
            return (bool) (($this->Total() >= 0) && ($this->TotalOutstanding() <= 0));
        }

        return false;
    }

    /**
     * @alias for getIsPaidNice
     * @return string
     */
    public function IsPaidNice()
    {
        return $this->getIsPaidNice();
    }

    public function getIsPaidNice()
    {
        return $this->IsPaid() ? 'yes' : 'no';
    }

    /**
     * Has the order been paid?
     * TODO: why do we check if there is a total at all?
     *
     * @return bool
     */
    public function PaymentIsPending()
    {
        if ($this->IsSubmitted()) {
            if ($this->IsPaid()) {
                //do nothing;
            } elseif (($payments = $this->Payments()) && $payments->count()) {
                foreach ($payments as $payment) {
                    if ($payment->Status === 'Pending') {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * shows payments that are meaningfull
     * if the order has been paid then only show successful payments.
     *
     * @return \SilverStripe\ORM\DataList
     */
    public function RelevantPayments()
    {
        if ($this->IsPaid()) {
            return $this->Payments()->filter(['Status' => 'Success']);
        }
        return $this->Payments();
    }

    /**
     * Has the order been cancelled?
     *
     * @return bool
     */
    public function IsCancelled()
    {
        return $this->getIsCancelled();
    }

    public function getIsCancelled()
    {
        return $this->CancelledByID ? true : false;
    }

    /**
     * @alias for getIsCancelledNice
     * @return string
     */
    public function IsCancelledNice()
    {
        return $this->getIsCancelledNice();
    }

    public function getIsCancelledNice()
    {
        return $this->IsCancelled() ? 'yes' : 'no';
    }

    /**
     * Has the order been cancelled by the customer?
     *
     * @return bool
     */
    public function IsCustomerCancelled()
    {
        if ($this->MemberID > 0 && $this->MemberID === $this->IsCancelledID) {
            return true;
        }

        return false;
    }

    /**
     * Has the order been cancelled by the  administrator?
     *
     * @return bool
     */
    public function IsAdminCancelled()
    {
        if ($this->IsCancelled()) {
            if (! $this->IsCustomerCancelled()) {
                $admin = Member::get()->byID($this->CancelledByID);
                if ($admin) {
                    if ($admin->IsShopAdmin()) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Is the Shop Closed for business?
     *
     * @return bool
     */
    public function ShopClosed()
    {
        return EcommerceConfig::inst()->ShopClosed;
    }

    /*******************************************************
       * 4. LINKING ORDER WITH MEMBER AND ADDRESS
    *******************************************************/

    /**
     * Returns a member linked to the order.
     * If a member is already linked, it will return the existing member.
     * Otherwise it will return a new Member.
     *
     * Any new member is NOT written, because we dont want to create a new member unless we have to!
     * We will not add a member to the order unless a new one is created in the checkout
     * OR the member is logged in / logs in.
     *
     * Also note that if a new member is created, it is not automatically written
     *
     * @param bool $forceCreation - if set to true then the member will always be saved in the database.
     *
     * @return \SilverStripe\Security\Member
     **/
    public function CreateOrReturnExistingMember($forceCreation = false)
    {
        if ($this->IsSubmitted()) {
            return $this->Member();
        }
        if ($this->MemberID) {
            $member = $this->Member();
        } elseif ($member = Security::getCurrentUser()) {
            if (! $member->IsShopAdmin()) {
                $this->MemberID = $member->ID;
                $this->write();
            }
        }
        $member = $this->Member();
        if (! $member) {
            $member = new Member();
        }
        if ($member && $forceCreation) {
            $member->write();
        }

        return $member;
    }

    /**
     * Returns either the existing one or a new Order Address...
     * All Orders will have a Shipping and Billing address attached to it.
     * Method used to retrieve object e.g. for $order->BillingAddress(); "BillingAddress" is the method name you can use.
     * If the method name is the same as the class name then dont worry about providing one.
     *
     * @param string $className             - ClassName of the Address (e.g. BillingAddress or ShippingAddress)
     * @param string $alternativeMethodName - method to retrieve Address
     **/
    public function CreateOrReturnExistingAddress($className = BillingAddress::class, $alternativeMethodName = '')
    {
        if ($this->exists()) {
            $methodName = Config::inst()->get($className, 'table_name');
            if ($alternativeMethodName) {
                $methodName = $alternativeMethodName;
            }
            if ($this->IsSubmitted()) {
                return $this->{$methodName}();
            }
            $variableName = $fieldName = Config::inst()->get($className, 'table_name') . 'ID';
            $address = null;
            if ($this->{$variableName}) {
                $address = $this->{$methodName}();
            }
            if (! $address) {
                $address = new $className();
                if ($member = $this->CreateOrReturnExistingMember()) {
                    if ($member->exists()) {
                        $address->FillWithLastAddressFromMember($member, $write = false);
                    }
                }
            }
            if ($address) {
                if (! $address->exists()) {
                    $address->write();
                }
                if ($address->OrderID !== $this->ID) {
                    $address->OrderID = $this->ID;
                    $address->write();
                }
                if ($this->{$variableName} !== $address->ID) {
                    if (! $this->IsSubmitted()) {
                        $this->{$variableName} = $address->ID;
                        $this->write();
                    }
                }

                return $address;
            }
        }

        return;
    }

    /**
     * Sets the country in the billing and shipping address.
     *
     * @param string $countryCode            - code for the country e.g. NZ
     * @param bool   $includeBillingAddress
     * @param bool   $includeShippingAddress
     **/
    public function SetCountryFields($countryCode, $includeBillingAddress = true, $includeShippingAddress = true)
    {
        if ($this->IsSubmitted()) {
            user_error('Can not change country in submitted order', E_USER_NOTICE);
        } else {
            if ($includeBillingAddress) {
                if ($billingAddress = $this->CreateOrReturnExistingAddress(BillingAddress::class)) {
                    $billingAddress->SetCountryFields($countryCode);
                }
            }
            if (EcommerceConfig::get(OrderAddress::class, 'use_separate_shipping_address')) {
                if ($includeShippingAddress) {
                    if ($shippingAddress = $this->CreateOrReturnExistingAddress(ShippingAddress::class)) {
                        $shippingAddress->SetCountryFields($countryCode);
                    }
                }
            }
        }
    }

    /**
     * Sets the region in the billing and shipping address.
     *
     * @param int $regionID - ID for the region to be set
     **/
    public function SetRegionFields($regionID)
    {
        if ($this->IsSubmitted()) {
            user_error('Can not change country in submitted order', E_USER_NOTICE);
        } else {
            if ($billingAddress = $this->CreateOrReturnExistingAddress(BillingAddress::class)) {
                $billingAddress->SetRegionFields($regionID);
            }
            if ($this->CanHaveShippingAddress()) {
                if ($shippingAddress = $this->CreateOrReturnExistingAddress(ShippingAddress::class)) {
                    $shippingAddress->SetRegionFields($regionID);
                }
            }
        }
    }

    /**
     * Stores the preferred currency of the order.
     * IMPORTANTLY we store the exchange rate for future reference...
     *
     * @param EcommerceCurrency $newCurrency
     */
    public function UpdateCurrency($newCurrency)
    {
        if ($this->IsSubmitted()) {
            user_error('Can not set the currency after the order has been submitted', E_USER_NOTICE);
        } else {
            if (! is_a($newCurrency, EcommerceConfigClassNames::getName(EcommerceCurrency::class))) {
                $newCurrency = EcommerceCurrency::default_currency();
            }
            $this->CurrencyUsedID = $newCurrency->ID;
            //getExchangeRate no longer works
            $this->ExchangeRate = $newCurrency->getExchangeRate();
            $this->write();
        }
    }

    /**
     * alias for UpdateCurrency.
     *
     * @param EcommerceCurrency $currency
     */
    public function SetCurrency($currency)
    {
        $this->UpdateCurrency($currency);
    }

    /*******************************************************
       * 5. CUSTOMER COMMUNICATION
    *******************************************************/

    /**
     * Send the invoice of the order by email.
     *
     * @param string $emailClassName     (optional) class used to send email
     * @param string $subject            (optional) subject for the email
     * @param string $message            (optional) the main message in the email
     * @param bool   $resend             (optional) send the email even if it has been sent before
     * @param bool   $adminOnlyOrToEmail (optional) sends the email to the ADMIN ONLY, if you provide an email, it will go to the email...
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function sendEmail(
        $emailClassName = OrderInvoiceEmail::class,
        $subject = '',
        $message = '',
        $resend = false,
        $adminOnlyOrToEmail = false
    ) {
        return $this->prepareAndSendEmail(
            $emailClassName,
            $subject,
            $message,
            $resend,
            $adminOnlyOrToEmail
        );
    }

    /**
     * Sends a message to the shop admin ONLY and not to the customer
     * This can be used by ordersteps and orderlogs to notify the admin of any potential problems.
     *
     * @param string         $emailClassName       - (optional) template to be used ...
     * @param string         $subject              - (optional) subject for the email
     * @param string         $message              - (optional) message to be added with the email
     * @param bool           $resend               - (optional) can it be sent twice?
     * @param bool | string  $adminOnlyOrToEmail   - (optional) sends the email to the ADMIN ONLY, if you provide an email, it will go to the email...
     *
     * @return bool TRUE for success, FALSE for failure (not tested)
     */
    public function sendAdminNotification(
        $emailClassName = OrderErrorEmail::class,
        $subject = '',
        $message = '',
        $resend = false,
        $adminOnlyOrToEmail = true
    ) {
        return $this->prepareAndSendEmail(
            $emailClassName,
            $subject,
            $message,
            $resend,
            $adminOnlyOrToEmail
        );
    }

    /**
     * returns the order formatted as an email.
     *
     * @param string $emailClassName - template to use.
     * @param string $subject        - (optional) the subject (which can be used as title in email)
     * @param string $message        - (optional) the additional message
     *
     * @return string (html)
     */
    public function renderOrderInEmailFormat(
        $emailClassName,
        $subject = '',
        $message = ''
    ) {
        $arrayData = $this->createReplacementArrayForEmail($subject, $message);
        Config::nest();
        Config::modify()->update(SSViewer::class, 'theme_enabled', true);
        $html = $arrayData->RenderWith($emailClassName);
        Config::unnest();

        return OrderEmail::emogrify_html($html);
    }

    /*******************************************************
       * 6. ITEM MANAGEMENT
    *******************************************************/

    /**
     * returns a list of Order Attributes by type.
     *
     * @param array | String $types
     *
     * @return \SilverStripe\ORM\ArrayList
     */
    public function getOrderAttributesByType($types)
    {
        if (! is_array($types) && is_string($types)) {
            $types = [$types];
        }
        if (! is_array($types)) {
            user_error('wrong parameter (types) provided in Order::getOrderAttributesByTypes');
        }
        $al = new ArrayList();
        $items = $this->Items();
        foreach ($items as $item) {
            if (in_array($item->OrderAttributeType(), $types, true)) {
                $al->push($item);
            }
        }
        $modifiers = $this->Modifiers();
        foreach ($modifiers as $modifier) {
            if (in_array($modifier->OrderAttributeType(), $types, true)) {
                $al->push($modifier);
            }
        }

        return $al;
    }

    /**
     * Returns the items of the order.
     * Items are the order items (products) and NOT the modifiers (discount, tax, etc...).
     *
     * N. B. this method returns Order Items
     * also see Buaybles

     *
     * @param string $filterOrClassName filter - where statement to exclude certain items OR ClassName (e.g. 'TaxModifier')
     *
     * @return \SilverStripe\ORM\DataList (OrderItems)
     */
    public function Items($filterOrClassName = '')
    {
        if (! $this->exists()) {
            $this->write();
        }

        return $this->itemsFromDatabase($filterOrClassName);
    }

    /**
     * @alias function of Items
     *
     * N. B. this method returns Order Items
     * also see Buaybles
     *
     * @param string $filterOrClassName filter - where statement to exclude certain items.
     * @alias for Items
     * @return \SilverStripe\ORM\DataList (OrderItems)
     */
    public function OrderItems($filterOrClassName = '')
    {
        return $this->Items($filterOrClassName);
    }

    /**
     * returns the buyables asscoiated with the order items.
     *
     * NB. this method retursn buyables
     *
     * @param string $filterOrClassName filter - where statement to exclude certain items.
     *
     * @return \SilverStripe\ORM\ArrayList (Buyables)
     */
    public function Buyables($filterOrClassName = '')
    {
        $items = $this->Items($filterOrClassName);
        $arrayList = new ArrayList();
        foreach ($items as $item) {
            $arrayList->push($item->Buyable());
        }

        return $arrayList;
    }

    /**
     * @alias for Modifiers
     *
     * @return \SilverStripe\ORM\DataList (OrderModifiers)
     */
    public function OrderModifiers()
    {
        return $this->Modifiers();
    }

    /**
     * Returns the modifiers of the order, if it hasn't been saved yet
     * it returns the modifiers from session, if it has, it returns them
     * from the DB entry. ONLY USE OUTSIDE ORDER.
     *
     * @param string $filterOrClassName filter - where statement to exclude certain items OR ClassName (e.g. 'TaxModifier')
     *
     * @return \SilverStripe\ORM\DataList (OrderModifiers)
     */
    public function Modifiers($filterOrClassName = '')
    {
        return $this->modifiersFromDatabase($filterOrClassName);
    }

    /**
     * Calculates and updates all the order attributes.
     *
     * @param bool $recalculate - run it, even if it has run already
     */
    public function calculateOrderAttributes($recalculate = false)
    {
        if ($this->IsSubmitted()) {
            //submitted orders are NEVER recalculated.
            //they are set in stone.
        } elseif (self::get_needs_recalculating($this->ID) || $recalculate) {
            if ($this->StatusID || $this->TotalItems()) {
                $this->ensureCorrectExchangeRate();
                $this->calculateOrderItems($recalculate);
                $this->calculateModifiers($recalculate);
                $this->extend('onCalculateOrder');
            }
        }
    }

    /**
     * Returns the subtotal of the modifiers for this order.
     * If a modifier appears in the excludedModifiers array, it is not counted.
     *
     * @param string|array $excluded               - Class(es) of modifier(s) to ignore in the calculation.
     * @param bool         $stopAtExcludedModifier - when this flag is TRUE, we stop adding the modifiers when we reach an excluded modifier.
     *
     * @return float
     */
    public function ModifiersSubTotal($excluded = null, $stopAtExcludedModifier = false)
    {
        $total = 0;
        $modifiers = $this->Modifiers();
        if ($modifiers->count()) {
            foreach ($modifiers as $modifier) {
                if (! $modifier->IsRemoved()) { //we just double-check this...
                    if (is_array($excluded) && in_array($modifier->ClassName, $excluded, true)) {
                        if ($stopAtExcludedModifier) {
                            break;
                        }
                        //do the next modifier
                        continue;
                    } elseif (is_string($excluded) && ($modifier->ClassName === $excluded)) {
                        if ($stopAtExcludedModifier) {
                            break;
                        }
                        //do the next modifier
                        continue;
                    }
                    $total += $modifier->CalculationTotal();
                }
            }
        }

        return $total;
    }

    /**
     * returns a modifier that is an instanceof the classname
     * it extends.
     *
     * @param string $className: class name for the modifier
     *
     * @return \SilverStripe\ORM\DataObject (OrderModifier)
     **/
    public function RetrieveModifier($className)
    {
        $modifiers = $this->Modifiers();
        if ($modifiers->count()) {
            foreach ($modifiers as $modifier) {
                if (is_a($modifier, EcommerceConfigClassNames::getName($className))) {
                    return $modifier;
                }
            }
        }
    }

    /**
     * @param \SilverStripe\Security\Member $member
     *
     * @return bool
     **/
    public function canCreate($member = null, $context = [])
    {
        $member = $this->getMemberForCanFunctions($member);
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        if ($member->exists()) {
            return $member->IsShopAdmin();
        }
    }

    /**
     * Standard SS method - can the current member view this order?
     *
     * @param \SilverStripe\Security\Member $member
     *
     * @return bool
     **/
    public function canView($member = null)
    {
        if (! $this->exists()) {
            return true;
        }
        $member = $this->getMemberForCanFunctions($member);
        //check if this has been "altered" in any DataExtension
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        //is the member is a shop admin they can always view it
        if (EcommerceRole::current_member_is_shop_admin($member)) {
            return true;
        }

        //is the member is a shop assistant they can always view it
        if (EcommerceRole::current_member_is_shop_assistant($member)) {
            return true;
        }
        //if the current member OWNS the order, (s)he can always view it.
        if ($member->exists() && $this->MemberID === $member->ID) {
            return true;
        }
        //it is the current order
        if ($this->IsInSession()) {
            //we do some additional CHECKS for session hackings!
            if ($member->exists() && $this->MemberID) {
                //can't view the order of another member!
                //shop admin exemption is already captured.
                //this is always true
                if ($this->MemberID !== $member->ID) {
                    return false;
                }
            } else {
                //order belongs to someone, but current user is NOT logged in...
                //this is allowed!
                //the reason it is allowed is because we want to be able to
                //add order to non-existing member
                return true;
            }
        }

        return false;
    }

    /**
     * @param \SilverStripe\Security\Member $member optional
     * @return bool
     */
    public function canOverridecanView($member = null, $context = [])
    {
        if ($this->canView($member)) {
            //can view overrides any concerns
            return true;
        }
        $tsOrder = strtotime($this->LastEdited);
        $tsNow = time();
        $minutes = EcommerceConfig::get(Order::class, 'minutes_an_order_can_be_viewed_without_logging_in');
        if ($minutes && ((($tsNow - $tsOrder) / 60) < $minutes)) {
            //has the order been edited recently?
            return true;
        } elseif ($orderStep = $this->MyStep()) {
            // order is being processed ...
            return $orderStep->canOverrideCanViewForOrder($this, $member);
        }

        return false;
    }

    /**
     * @return bool
     */
    public function IsInSession()
    {
        $orderInSession = ShoppingCart::session_order();

        return $orderInSession && $this->ID && $this->ID === $orderInSession->ID;
    }

    /**
     * returns a pseudo random part of the session id.
     *
     * @param int $size
     *
     * @return string
     */
    public function LessSecureSessionID($size = 7, $start = null)
    {
        if (! $start || $start < 0 || $start > (32 - $size)) {
            $start = 0;
        }

        return substr($this->SessionID, $start, $size);
    }

    /**
     * @param \SilverStripe\Security\Member (optional) $member
     *
     * @return bool
     **/
    public function canViewAdminStuff($member = null)
    {
        $member = $this->getMemberForCanFunctions($member);
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        if (Permission::checkMember($member, Config::inst()->get(EcommerceRole::class, 'admin_permission_code'))) {
            return true;
        }
    }

    /**
     * if we set canEdit to false then we
     * can not see the child records
     * Basically, you can edit when you can view and canEdit (even as a customer)
     * Or if you are a Shop Admin you can always edit.
     * Otherwise it is false...
     *
     * @param \SilverStripe\Security\Member $member
     *
     * @return bool
     **/
    public function canEdit($member = null)
    {
        $member = $this->getMemberForCanFunctions($member);
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        if ($this->canView($member) && $this->MyStep()->CustomerCanEdit) {
            return true;
        }
        if (Permission::checkMember($member, Config::inst()->get(EcommerceRole::class, 'admin_permission_code'))) {
            return true;
        }
        //is the member is a shop assistant they can always view it
        if (EcommerceRole::current_member_is_shop_assistant($member)) {
            return true;
        }
        return false;
    }

    /**
     * is the order ready to go through to the
     * checkout process.
     *
     * This method checks all the order items and order modifiers
     * If any of them need immediate attention then this is done
     * first after which it will go through to the checkout page.
     *
     * @param \SilverStripe\Security\Member (optional) $member
     *
     * @return bool
     **/
    public function canCheckout(Member $member = null)
    {
        $member = $this->getMemberForCanFunctions($member);
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        $submitErrors = $this->SubmitErrors();
        if ($submitErrors && $submitErrors->count()) {
            return false;
        }

        return true;
    }

    /**
     * Can the order be submitted?
     * this method can be used to stop an order from being submitted
     * due to something not being completed or done.
     *
     * @see Order::SubmitErrors
     *
     * @param \SilverStripe\Security\Member $member
     *
     * @return bool
     **/
    public function canSubmit(Member $member = null)
    {
        $member = $this->getMemberForCanFunctions($member);
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        if ($this->IsSubmitted()) {
            return false;
        }
        $submitErrors = $this->SubmitErrors();
        if ($submitErrors && $submitErrors->count()) {
            return false;
        }

        return true;
    }

    /**
     * Can a payment be made for this Order?
     *
     * @param \SilverStripe\Security\Member $member
     *
     * @return bool
     **/
    public function canPay(Member $member = null)
    {
        $member = $this->getMemberForCanFunctions($member);
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        if ($this->IsPaid() || $this->IsCancelled() || $this->PaymentIsPending()) {
            return false;
        }

        return $this->MyStep()->CustomerCanPay;
    }

    /**
     * Can the given member cancel this order?
     *
     * @param \SilverStripe\Security\Member $member
     *
     * @return bool
     **/
    public function canCancel(Member $member = null)
    {
        //if it is already cancelled it can not be cancelled again
        if ($this->CancelledByID) {
            return false;
        }
        $member = $this->getMemberForCanFunctions($member);
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        if (EcommerceRole::current_member_can_process_orders($member)) {
            return true;
        }

        return $this->MyStep()->CustomerCanCancel && $this->canView($member);
    }

    /**
     * @param \SilverStripe\Security\Member $member
     *
     * @return bool
     **/
    public function canDelete($member = null)
    {
        $member = $this->getMemberForCanFunctions($member);
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        if ($this->IsSubmitted()) {
            return false;
        }
        if (Permission::checkMember($member, Config::inst()->get(EcommerceRole::class, 'admin_permission_code'))) {
            return true;
        }

        return false;
    }

    /**
     * Returns all the order logs that the current member can view
     * i.e. some order logs can only be viewed by the admin (e.g. suspected fraud orderlog).
     *
     * @return \SilverStripe\ORM\ArrayList (OrderStatusLogs)
     **/
    public function CanViewOrderStatusLogs()
    {
        $canViewOrderStatusLogs = new ArrayList();
        $logs = $this->OrderStatusLogs();
        foreach ($logs as $log) {
            if ($log->canView()) {
                $canViewOrderStatusLogs->push($log);
            }
        }

        return $canViewOrderStatusLogs;
    }

    /**
     * returns all the logs that can be viewed by the customer.
     *
     * @return \SilverStripe\ORM\ArrayList (OrderStausLogs)
     */
    public function CustomerViewableOrderStatusLogs()
    {
        $customerViewableOrderStatusLogs = new ArrayList();
        $logs = $this->OrderStatusLogs();
        if ($logs) {
            foreach ($logs as $log) {
                if (! $log->InternalUseOnly) {
                    $customerViewableOrderStatusLogs->push($log);
                }
            }
        }

        return $customerViewableOrderStatusLogs;
    }

    /*******************************************************
       * 8. GET METHODS (e.g. Total, SubTotal, Title, etc...)
    *******************************************************/

    /**
     * returns the email to be used for customer communication.
     *
     * @return string
     */
    public function OrderEmail()
    {
        return $this->getOrderEmail();
    }

    public function getOrderEmail()
    {
        $email = '';
        if ($this->BillingAddressID && $this->BillingAddress()) {
            $email = $this->BillingAddress()->Email;
        }
        if (! $email) {
            if ($this->MemberID && $this->Member()) {
                $email = $this->Member()->Email;
            }
        }
        $extendedEmail = $this->extend('updateOrderEmail', $email);
        if ($extendedEmail !== null && is_array($extendedEmail) && count($extendedEmail)) {
            $email = implode(';', $extendedEmail);
        }

        return $email;
    }

    /**
     * Returns true if there is a prink or email link.
     *
     * @return bool
     */
    public function HasPrintOrEmailLink()
    {
        return $this->EmailLink() || $this->PrintLink();
    }

    /**
     * returns the absolute link to the order that can be used in the customer communication (email).
     *
     * @return string
     */
    public function EmailLink()
    {
        return $this->getEmailLink();
    }

    public function getEmailLink()
    {
        if (! isset($_REQUEST['print'])) {
            if ($this->IsSubmitted()) {
                return Director::AbsoluteURL(
                    OrderConfirmationPage::get_email_link(
                        $this->ID,
                        ClassHelpers::sanitise_class_name($this->MyStep()->getEmailClassName()),
                        $actuallySendEmail = true
                    )
                );
            }
        }
    }

    /**
     * returns the absolute link to the order for printing.
     *
     * @return string
     */
    public function PrintLink()
    {
        return $this->getPrintLink();
    }

    public function getPrintLink()
    {
        if (! isset($_REQUEST['print'])) {
            if ($this->IsSubmitted()) {
                return Director::AbsoluteURL(OrderConfirmationPage::get_order_link($this->ID)) . '?print=1';
            }
        }
    }

    /**
     * returns the absolute link to the order for printing.
     *
     * @return string
     */
    public function PackingSlipLink()
    {
        return $this->getPackingSlipLink();
    }

    public function getPackingSlipLink()
    {
        if ($this->IsSubmitted()) {
            return Director::AbsoluteURL(OrderConfirmationPage::get_order_link($this->ID)) . '?packingslip=1';
        }
    }

    /**
     * returns the absolute link that the customer can use to retrieve the email WITHOUT logging in.
     *
     * @return string
     */
    public function RetrieveLink()
    {
        return $this->getRetrieveLink();
    }

    public function getRetrieveLink()
    {
        //important to recalculate!
        if ($this->IsSubmitted($recalculate = true)) {
            //add session ID if not added yet...
            if (! $this->SessionID) {
                $this->write();
            }

            return Director::AbsoluteURL(OrderConfirmationPage::find_link()) . 'retrieveorder/' . $this->SessionID . '/' . $this->ID . '/';
        }
        return Director::AbsoluteURL('/shoppingcart/loadorder/' . $this->ID . '/');
    }

    public function ShareLink()
    {
        return $this->getShareLink();
    }

    public function getShareLink()
    {
        $orderItems = $this->itemsFromDatabase();
        $action = 'share';
        $array = [];
        foreach ($orderItems as $orderItem) {
            $array[] = implode(
                ',',
                [
                    ClassHelpers::sanitise_class_name($orderItem->BuyableClassName),
                    $orderItem->BuyableID,
                    $orderItem->Quantity,
                ]
            );
        }

        return Director::AbsoluteURL(CartPage::find_link($action . '/' . implode('-', $array)));
    }

    /**
     * @alias for getFeedbackLink
     * @return string
     */
    public function FeedbackLink()
    {
        return $this->getFeedbackLink();
    }

    /**
     * @return string|null
     */
    public function getFeedbackLink()
    {
        $orderConfirmationPage = DataObject::get_one(OrderConfirmationPage::class);
        if ($orderConfirmationPage->IsFeedbackEnabled) {
            return Director::AbsoluteURL($this->getRetrieveLink()) . '#OrderFormFeedback_FeedbackForm';
        }
    }

    /**
     * link to delete order.
     *
     * @return string
     */
    public function DeleteLink()
    {
        return $this->getDeleteLink();
    }

    public function getDeleteLink()
    {
        if ($this->canDelete()) {
            return ShoppingCartController::delete_order_link($this->ID);
        }
        return '';
    }

    /**
     * link to copy order.
     *
     * @return string
     */
    public function CopyOrderLink()
    {
        return $this->getCopyOrderLink();
    }

    public function getCopyOrderLink()
    {
        if ($this->canView() && $this->IsSubmitted()) {
            return ShoppingCartController::copy_order_link($this->ID);
        }
        return '';
    }

    /**
     * A "Title" for the order, which summarises the main details (date, and customer) in a string.
     *
     * @param string $dateFormat  - e.g. "D j M Y, G:i T"
     * @param bool   $includeName - e.g. by Mr Johnson
     *
     * @return string
     **/
    public function Title($dateFormat = null, $includeName = false)
    {
        return $this->getTitle($dateFormat, $includeName);
    }

    public function getTitle($dateFormat = null, $includeName = false)
    {
        if ($this->exists()) {
            if ($dateFormat === null) {
                $dateFormat = EcommerceConfig::get(Order::class, 'date_format_for_title');
            }
            if ($includeName === null) {
                $includeName = EcommerceConfig::get(Order::class, 'include_customer_name_in_title');
            }
            $title = $this->i18n_singular_name() . ' #' . number_format($this->ID);
            if ($dateFormat) {
                if ($submissionLog = $this->SubmissionLog()) {
                    $dateObject = $submissionLog->dbObject('Created');
                    $placed = _t('Order.PLACED', 'placed');
                } else {
                    $dateObject = $this->dbObject('Created');
                    $placed = _t('Order.STARTED', 'started');
                }
                $title .= ' - ' . $placed . ' ' . $dateObject->Format($dateFormat);
            }
            $name = '';
            if ($this->CancelledByID) {
                $name = ' - ' . _t('Order.CANCELLED', 'CANCELLED');
            }
            if ($includeName) {
                $by = _t('Order.BY', 'by');
                if (! $name) {
                    if ($this->BillingAddressID) {
                        if ($billingAddress = $this->BillingAddress()) {
                            $name = ' - ' . $by . ' ' . $billingAddress->Prefix . ' ' . $billingAddress->FirstName . ' ' . $billingAddress->Surname;
                        }
                    }
                }
                if (! $name) {
                    if ($this->MemberID) {
                        if ($member = $this->Member()) {
                            if ($member->exists()) {
                                if ($memberName = $member->getName()) {
                                    if (! trim($memberName)) {
                                        $memberName = _t('Order.ANONYMOUS', 'anonymous');
                                    }
                                    $name = ' - ' . $by . ' ' . $memberName;
                                }
                            }
                        }
                    }
                }
            }
            $title .= $name;
        } else {
            $title = _t('Order.NEW', 'New') . ' ' . $this->i18n_singular_name();
        }
        $extendedTitle = $this->extend('updateTitle', $title);
        if ($extendedTitle !== null && is_array($extendedTitle) && count($extendedTitle)) {
            $title = implode('; ', $extendedTitle);
        }

        return $title;
    }

    public function OrderItemsSummaryNice()
    {
        return $this->getOrderItemsSummaryNice();
    }

    public function getOrderItemsSummaryNice()
    {
        return DBField::create_field('HTMLText', $this->OrderItemsSummaryAsHTML());
    }

    public function OrderItemsSummaryAsHTML()
    {
        $html = '';
        $x = 0;
        $count = $this->owner->OrderItems()->count();
        if ($count > 0) {
            $html .= '<ul class="order-items-summary">';
            foreach ($this->owner->OrderItems() as $orderItem) {
                $x++;
                $buyable = $orderItem->Buyable();
                $html .= '<li style="font-family: monospace; font-size: 0.9em; color: #1F9433;">' . $orderItem->Quantity . 'x ';
                if ($buyable) {
                    $html .= $buyable->InternalItemID . ' ' . $buyable->Title;
                } else {
                    $html .= $orderItem->BuyableFullName;
                }
                $html .= '</li>';
                if ($x > 3) {
                    $html .= '<li style="font-family: monospace; font-size: 0.9em; color: black;">- open for more items</li>';
                    break;
                }
            }
            $html .= '</ul>';
        }
        return $html;
    }

    /**
     * Returns the subtotal of the items for this order.
     *
     * @return float
     */
    public function SubTotal()
    {
        return $this->getSubTotal();
    }

    public function getSubTotal()
    {
        $result = 0;
        $items = $this->Items();
        if ($items->count()) {
            foreach ($items as $item) {
                if (is_a($item, EcommerceConfigClassNames::getName(OrderAttribute::class))) {
                    $result += $item->Total();
                }
            }
        }

        return $result;
    }

    /**
     * @return \SilverStripe\ORM\FieldType\DBCurrency (DB Object)
     **/
    public function SubTotalAsCurrencyObject()
    {
        return DBField::create_field('Currency', $this->SubTotal());
    }

    /**
     * @return \SilverStripe\ORM\FieldType\DBMoney
     **/
    public function SubTotalAsMoney()
    {
        return $this->getSubTotalAsMoney();
    }

    public function getSubTotalAsMoney()
    {
        return EcommerceCurrency::get_money_object_from_order_currency($this->SubTotal(), $this);
    }

    /**
     * @param string|array $excluded               - Class(es) of modifier(s) to ignore in the calculation.
     * @param bool         $stopAtExcludedModifier - when this flag is TRUE, we stop adding the modifiers when we reach an excluded modifier.
     *
     * @return \SilverStripe\ORM\FieldType\DBCurrency (DB Object)
     **/
    public function ModifiersSubTotalAsCurrencyObject($excluded = null, $stopAtExcludedModifier = false)
    {
        return DBField::create_field('Currency', $this->ModifiersSubTotal($excluded, $stopAtExcludedModifier));
    }

    /**
     * @param string|array $excluded               - Class(es) of modifier(s) to ignore in the calculation.
     * @param bool         $stopAtExcludedModifier - when this flag is TRUE, we stop adding the modifiers when we reach an excluded modifier.
     *
     * @return \SilverStripe\ORM\FieldType\DBMoney (DB Object)
     **/
    public function ModifiersSubTotalAsMoneyObject($excluded = null, $stopAtExcludedModifier = false)
    {
        return EcommerceCurrency::get_money_object_from_order_currency($this->ModifiersSubTotal($excluded, $stopAtExcludedModifier), $this);
    }

    /**
     * Returns the total cost of an order including the additional charges or deductions of its modifiers.
     *
     * @return float
     */
    public function Total()
    {
        return $this->getTotal();
    }

    public function getTotal()
    {
        return $this->SubTotal() + $this->ModifiersSubTotal();
    }

    /**
     * @return \SilverStripe\ORM\FieldType\DBCurrency (DB Object)
     **/
    public function TotalAsCurrencyObject()
    {
        return DBField::create_field('Currency', $this->Total());
    }

    /**
     * @return \SilverStripe\ORM\FieldType\DBMoney
     **/
    public function TotalAsMoney()
    {
        return $this->getTotalAsMoney();
    }

    public function getTotalAsMoney()
    {
        return EcommerceCurrency::get_money_object_from_order_currency($this->Total(), $this);
    }

    /**
     * Checks to see if any payments have been made on this order
     * and if so, subracts the payment amount from the order.
     *
     * @return float
     **/
    public function TotalOutstanding()
    {
        return $this->getTotalOutstanding();
    }

    public function getTotalOutstanding()
    {
        if ($this->IsSubmitted()) {
            $total = $this->Total();
            $paid = $this->TotalPaid();
            $outstanding = $total - $paid;
            $maxDifference = EcommerceConfig::get(Order::class, 'maximum_ignorable_sales_payments_difference');
            if (abs($outstanding) < $maxDifference) {
                $outstanding = 0;
            }

            return floatval($outstanding);
        }
        return 0;
    }

    /**
     * @return \SilverStripe\ORM\FieldType\DBCurrency (DB Object)
     **/
    public function TotalOutstandingAsCurrencyObject()
    {
        return DBField::create_field('Currency', $this->TotalOutstanding());
    }

    /**
     * @return \SilverStripe\ORM\FieldType\DBMoney
     **/
    public function TotalOutstandingAsMoney()
    {
        return $this->getTotalOutstandingAsMoney();
    }

    public function getTotalOutstandingAsMoney()
    {
        return EcommerceCurrency::get_money_object_from_order_currency($this->TotalOutstanding(), $this);
    }

    /**
     * @return float
     */
    public function TotalPaid()
    {
        return $this->getTotalPaid();
    }

    public function getTotalPaid()
    {
        $paid = 0;
        if ($payments = $this->Payments()) {
            foreach ($payments as $payment) {
                if ($payment->Status === 'Success') {
                    $paid += $payment->Amount->getAmount();
                }
            }
        }
        $reverseExchange = 1;
        if ($this->ExchangeRate && $this->ExchangeRate !== 1) {
            $reverseExchange = 1 / $this->ExchangeRate;
        }

        return $paid * $reverseExchange;
    }

    /**
     * @return \SilverStripe\ORM\FieldType\DBCurrency (DB Object)
     **/
    public function TotalPaidAsCurrencyObject()
    {
        return DBField::create_field('Currency', $this->TotalPaid());
    }

    /**
     * @return \SilverStripe\ORM\FieldType\DBMoney
     **/
    public function TotalPaidAsMoney()
    {
        return $this->getTotalPaidAsMoney();
    }

    public function getTotalPaidAsMoney()
    {
        return EcommerceCurrency::get_money_object_from_order_currency($this->TotalPaid(), $this);
    }

    /**
     * returns the total number of OrderItems (not modifiers).
     * This is meant to run as fast as possible to quickly check
     * if there is anything in the cart.
     *
     * @param bool $recalculate - do we need to recalculate (value is retained during lifetime of Object)
     *
     * @return int
     **/
    public function TotalItems($recalculate = false)
    {
        return $this->getTotalItems($recalculate);
    }

    public function getTotalItems($recalculate = false)
    {
        if ($this->totalItems === null || $recalculate) {
            $this->totalItems = OrderItem::get()
                ->where('"OrderAttribute"."OrderID" = ' . $this->ID . ' AND "OrderItem"."Quantity" > 0')
                ->count();
        }

        return $this->totalItems;
    }

    /**
     * Little shorthand.
     *
     * @param bool $recalculate
     *
     * @return bool
     **/
    public function MoreThanOneItemInCart($recalculate = false)
    {
        return $this->TotalItems($recalculate) > 1 ? true : false;
    }

    /**
     * returns the total number of OrderItems (not modifiers) times their respectective quantities.
     *
     * @param bool $recalculate - force recalculation
     *
     * @return float
     **/
    public function TotalItemsTimesQuantity($recalculate = false)
    {
        return $this->getTotalItemsTimesQuantity($recalculate);
    }

    public function getTotalItemsTimesQuantity($recalculate = false)
    {
        if ($this->totalItemsTimesQuantity === null || $recalculate) {
            //to do, why do we check if you can edit ????
            $this->totalItemsTimesQuantity = DB::query(
                '
                SELECT SUM("OrderItem"."Quantity")
                FROM "OrderItem"
                    INNER JOIN "OrderAttribute" ON "OrderAttribute"."ID" = "OrderItem"."ID"
                WHERE
                    "OrderAttribute"."OrderID" = ' . $this->ID . '
                    AND "OrderItem"."Quantity" > 0'
            )->value();
        }

        return $this->totalItemsTimesQuantity - 0;
    }

    /**
     * @return string (country code)
     **/
    public function Country()
    {
        return $this->getCountry();
    }

    /**
     * Returns the country code for the country that applies to the order.
     * @alias  for getCountry
     *
     * @return string - country code e.g. NZ
     */
    public function getCountry()
    {
        $countryCodes = [
            'Billing' => '',
            'Shipping' => '',
        ];
        $code = null;
        if ($this->BillingAddressID) {
            $billingAddress = BillingAddress::get()->byID($this->BillingAddressID);
            if ($billingAddress) {
                if ($billingAddress->Country) {
                    $countryCodes['Billing'] = $billingAddress->Country;
                }
            }
        }
        if ($this->IsSeparateShippingAddress()) {
            $shippingAddress = ShippingAddress::get()->byID($this->ShippingAddressID);
            if ($shippingAddress) {
                if ($shippingAddress->ShippingCountry) {
                    $countryCodes['Shipping'] = $shippingAddress->ShippingCountry;
                }
            }
        }
        if ((EcommerceConfig::get(OrderAddress::class, 'use_shipping_address_for_main_region_and_country') && $countryCodes['Shipping'])
            ||
            (! $countryCodes['Billing'] && $countryCodes['Shipping'])
        ) {
            $code = $countryCodes['Shipping'];
        } elseif ($countryCodes['Billing']) {
            $code = $countryCodes['Billing'];
        } else {
            $code = EcommerceCountry::get_country_from_ip();
        }

        return $code;
    }

    /**
     * is this a gift / separate shippingAddress?
     * @return bool
     */
    public function IsSeparateShippingAddress()
    {
        return $this->ShippingAddressID && $this->UseShippingAddress;
    }

    /**
     * @alias for getFullNameCountry
     *
     * @return string - country name
     **/
    public function FullNameCountry()
    {
        return $this->getFullNameCountry();
    }

    /**
     * returns name of coutry.
     *
     * @return string - country name
     **/
    public function getFullNameCountry()
    {
        return EcommerceCountry::find_title($this->Country());
    }

    /**
     * @alis for getExpectedCountryName
     * @return string - country name
     **/
    public function ExpectedCountryName()
    {
        return $this->getExpectedCountryName();
    }

    /**
     * returns name of coutry that we expect the customer to have
     * This takes into consideration more than just what has been entered
     * for example, it looks at GEO IP.
     *
     * @todo: why do we dont return a string IF there is only one item.
     *
     * @return string - country name
     **/
    public function getExpectedCountryName()
    {
        return EcommerceCountry::find_title(EcommerceCountry::get_country(false, $this->ID));
    }

    /**
     * return the title of the fixed country (if any).
     *
     * @return string
     **/
    public function FixedCountry()
    {
        return $this->getFixedCountry();
    }

    public function getFixedCountry()
    {
        $code = EcommerceCountry::get_fixed_country_code();
        if ($code) {
            return EcommerceCountry::find_title($code);
        }

        return '';
    }

    /**
     * Returns the region that applies to the order.
     * we check both billing and shipping, in case one of them is empty.
     *
     * @return \SilverStripe\ORM\DataObject | Null (EcommerceRegion)
     **/
    public function Region()
    {
        return $this->getRegion();
    }

    public function getRegion()
    {
        $regionIDs = [
            'Billing' => 0,
            'Shipping' => 0,
        ];
        if ($this->BillingAddressID) {
            if ($billingAddress = $this->BillingAddress()) {
                if ($billingAddress->RegionID) {
                    $regionIDs['Billing'] = $billingAddress->RegionID;
                }
            }
        }
        if ($this->CanHaveShippingAddress()) {
            if ($this->ShippingAddressID) {
                if ($shippingAddress = $this->ShippingAddress()) {
                    if ($shippingAddress->ShippingRegionID) {
                        $regionIDs['Shipping'] = $shippingAddress->ShippingRegionID;
                    }
                }
            }
        }
        if (count($regionIDs)) {
            //note the double-check with $this->CanHaveShippingAddress() and get_use_....
            if ($this->CanHaveShippingAddress() && EcommerceConfig::get(OrderAddress::class, 'use_shipping_address_for_main_region_and_country') && $regionIDs['Shipping']) {
                return EcommerceRegion::get()->byID($regionIDs['Shipping']);
            }
            return EcommerceRegion::get()->byID($regionIDs['Billing']);
        }
        return EcommerceRegion::get()->byID(EcommerceRegion::get_region_from_ip());
    }

    /**
     * Casted variable
     * Currency is not the same as the standard one?
     *
     * @return bool
     **/
    public function HasAlternativeCurrency()
    {
        return $this->getHasAlternativeCurrency();
    }

    public function getHasAlternativeCurrency()
    {
        if ($currency = $this->CurrencyUsed()) {
            if ($currency->IsDefault()) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Makes sure exchange rate is updated and maintained before order is submitted
     * This method is public because it could be called from a shopping Cart Object.
     **/
    public function EnsureCorrectExchangeRate()
    {
        if (! $this->IsSubmitted()) {
            $oldExchangeRate = $this->ExchangeRate;
            if ($currency = $this->CurrencyUsed()) {
                if ($currency->IsDefault()) {
                    $this->ExchangeRate = 0;
                } else {
                    $this->ExchangeRate = $currency->getExchangeRate();
                }
            } else {
                $this->ExchangeRate = 0;
            }
            if ($this->ExchangeRate !== $oldExchangeRate) {
                $this->write();
            }
        }
    }

    /**
     * Casted variable - has the order been submitted?
     * alias
     * @param bool $recalculate
     *
     * @return bool
     **/
    public function IsSubmitted($recalculate = true)
    {
        return $this->getIsSubmitted($recalculate);
    }

    /**
     * Casted variable - has the order been submitted?
     *
     * @param bool $recalculate
     *
     * @return bool
     **/
    public function getIsSubmitted($recalculate = false)
    {
        if ($this->_isSubmittedTempVar === -1 || $recalculate) {
            if ($this->SubmissionLog()) {
                $this->_isSubmittedTempVar = true;
            } else {
                $this->_isSubmittedTempVar = false;
            }
        }

        return $this->_isSubmittedTempVar;
    }

    /**
     * @return bool
     */
    public function IsArchived()
    {
        $lastStep = OrderStep::last_order_step();
        if ($lastStep) {
            if ($lastStep->ID === $this->StatusID) {
                return true;
            }
        }
        return false;
    }

    /**
     * Submission Log for this Order (if any).
     *
     * @return OrderStatusLog (OrderStatusLogSubmitted) | Null
     **/
    public function SubmissionLog()
    {
        $className = EcommerceConfig::get(OrderStatusLog::class, 'order_status_log_class_used_for_submitting_order');

        return $className::get()
            ->Filter(['OrderID' => $this->ID])
            ->Last();
    }

    /**
     * Submission Log for this Order (if any).
     *
     * @return \SilverStripe\ORM\FieldType\DBDateTime
     **/
    public function OrderDate()
    {
        $object = $this->SubmissionLog();
        if ($object) {
            $created = $object->Created;
        } else {
            $created = $this->LastEdited;
        }

        return DBField::create_field(DBDatetime::class, $created);
    }

    /**
     * @return int
     */
    public function SecondsSinceBeingSubmitted()
    {
        if ($submissionLog = $this->SubmissionLog()) {
            return time() - strtotime($submissionLog->Created);
        }
        return 0;
    }

    /**
     * if the order can not be submitted,
     * then the reasons why it can not be submitted
     * will be returned by this method.
     *
     * @see Order::canSubmit
     *
     * @return \SilverStripe\ORM\ArrayList|null
     */
    public function SubmitErrors()
    {
        $al = null;
        $extendedSubmitErrors = $this->extend('updateSubmitErrors');
        if ($extendedSubmitErrors !== null && is_array($extendedSubmitErrors) && count($extendedSubmitErrors)) {
            $al = ArrayList::create();
            foreach ($extendedSubmitErrors as $returnResultArray) {
                foreach ($returnResultArray as $issue) {
                    if ($issue) {
                        $al->push(ArrayData::create(['Title' => $issue]));
                    }
                }
            }
        }
        return $al;
    }

    /**
     * Casted variable - has the order been submitted?
     *
     * @param bool $withDetail
     *
     * @return string
     **/
    public function CustomerStatus($withDetail = true)
    {
        return $this->getCustomerStatus($withDetail);
    }

    public function getCustomerStatus($withDetail = true)
    {
        $str = '';
        if ($this->MyStep()->ShowAsUncompletedOrder) {
            $str = _t('Order.UNCOMPLETED', 'Uncompleted');
        } elseif ($this->MyStep()->ShowAsInProcessOrder) {
            $str = _t('Order.IN_PROCESS', 'In Process');
        } elseif ($this->MyStep()->ShowAsCompletedOrder) {
            $str = _t('Order.COMPLETED', 'Completed');
        }
        if ($withDetail) {
            if (! $this->HideStepFromCustomer) {
                $str .= ' (' . $this->MyStep()->Name . ')';
            }
        }

        return $str;
    }

    /**
     * Casted variable - does the order have a potential shipping address?
     *
     * @return bool
     **/
    public function CanHaveShippingAddress()
    {
        return $this->getCanHaveShippingAddress();
    }

    public function getCanHaveShippingAddress()
    {
        return EcommerceConfig::get(OrderAddress::class, 'use_separate_shipping_address');
    }

    /**
     * returns the link to view the Order
     * WHY NOT CHECKOUT PAGE: first we check for cart page.
     *
     * @return CartPage | Null
     */
    public function DisplayPage()
    {
        if ($this->MyStep() && $this->MyStep()->AlternativeDisplayPage()) {
            $page = $this->MyStep()->AlternativeDisplayPage();
        } elseif ($this->IsSubmitted()) {
            $page = DataObject::get_one(OrderConfirmationPage::class);
        } else {
            $page = DataObject::get_one(
                CartPage::class,
                ['ClassName' => CartPage::class]
            );
            if (! $page) {
                $page = DataObject::get_one(CheckoutPage::class);
            }
        }

        return $page;
    }

    /**
     * returns the link to view the Order
     * WHY NOT CHECKOUT PAGE: first we check for cart page.
     * If a cart page has been created then we refer through to Cart Page.
     * Otherwise it will default to the checkout page.
     *
     * @param string $action - any action that should be added to the link.
     *
     * @return String(URLSegment)
     */
    public function Link($action = null)
    {
        $page = $this->DisplayPage();
        if ($page) {
            return $page->getOrderLink($this->ID, $action);
        }
        user_error('A Cart / Checkout Page + an Order Confirmation Page needs to be setup for the e-commerce module to work.', E_USER_NOTICE);
        $page = DataObject::get_one(
            ErrorPage::class,
            ['ErrorCode' => '404']
        );
        if ($page) {
            return $page->Link();
        }
    }

    /**
     * Returns to link to access the Order's API.
     *
     * @param string $version
     * @param string $extension
     *
     * @return String(URL)
     */
    public function APILink($version = 'v1', $extension = 'xml')
    {
        return Director::AbsoluteURL("/api/ecommerce/${version}/Order/" . $this->ID . "/.${extension}");
    }

    /**
     * returns the link to finalise the Order.
     *
     * @return String(URLSegment)
     */
    public function CheckoutLink()
    {
        $page = DataObject::get_one(CheckoutPage::class);
        if ($page) {
            return $page->Link();
        }
        $page = DataObject::get_one(
            ErrorPage::class,
            ['ErrorCode' => '404']
        );
        if ($page) {
            return $page->Link();
        }
    }

    /**
     * Converts the Order into HTML, based on the Order Template.
     *
     * @return \SilverStripe\ORM\FieldType\DBHTMLText
     **/
    public function ConvertToHTML()
    {
        Config::nest();
        Config::modify()->update(SSViewer::class, 'theme_enabled', true);
        $html = $this->renderWith('Sunnysideup\Ecommerce\Includes\Order');
        Config::unnest();
        $html = preg_replace('/(\s)+/', ' ', $html);

        return DBField::create_field('HTMLText', $html);
    }

    /**
     * Converts the Order into a serialized string
     * TO DO: check if this works and check if we need to use special sapphire serialization code.
     *
     * @return string - serialized object
     **/
    public function ConvertToString()
    {
        return serialize($this->addHasOneAndHasManyAsVariables());
    }

    /**
     * Converts the Order into a JSON object
     * TO DO: check if this works and check if we need to use special sapphire JSON code.
     *
     * @return string -  JSON
     **/
    public function ConvertToJSON()
    {
        return json_encode($this->addHasOneAndHasManyAsVariables());
    }

    /*******************************************************
       * 9. TEMPLATE RELATED STUFF
    *******************************************************/

    /**
     * returns the instance of EcommerceConfigAjax for use in templates.
     * In templates, it is used like this:
     * $EcommerceConfigAjax.TableID.
     *
     * @return EcommerceConfigAjax
     **/
    public function AJAXDefinitions()
    {
        return EcommerceConfigAjax::get_one($this);
    }

    /**
     * Collects the JSON data for an ajax return of the cart.
     *
     * @param array $js
     *
     * @return array (for use in AJAX for JSON)
     **/
    public function updateForAjax(array $js)
    {
        $function = EcommerceConfig::get(Order::class, 'ajax_subtotal_format');
        if (is_array($function)) {
            list($function, $format) = $function;
        }
        $subTotal = $this->{$function}();
        if (isset($format)) {
            $subTotal = $subTotal->{$format}();
            unset($format);
        }
        $function = EcommerceConfig::get(Order::class, 'ajax_total_format');
        if (is_array($function)) {
            list($function, $format) = $function;
        }
        $total = $this->{$function}();
        if (isset($format)) {
            $total = $total->{$format}();
        }
        $ajaxObject = $this->AJAXDefinitions();
        $js[] = [
            't' => 'id',
            's' => $ajaxObject->TableSubTotalID(),
            'p' => 'innerHTML',
            'v' => $subTotal,
        ];
        $js[] = [
            't' => 'id',
            's' => $ajaxObject->TableTotalID(),
            'p' => 'innerHTML',
            'v' => $total,
        ];
        $js[] = [
            't' => 'class',
            's' => $ajaxObject->TotalItemsClassName(),
            'p' => 'innerHTML',
            'v' => $this->TotalItems($recalculate = true),
        ];
        $js[] = [
            't' => 'class',
            's' => $ajaxObject->TotalItemsTimesQuantityClassName(),
            'p' => 'innerHTML',
            'v' => $this->TotalItemsTimesQuantity(),
        ];
        $js[] = [
            't' => 'class',
            's' => $ajaxObject->ExpectedCountryClassName(),
            'p' => 'innerHTML',
            'v' => $this->ExpectedCountryName(),
        ];

        return $js;
    }

    /**
     * @ToDO: move to more appropriate class
     *
     * @return float
     **/
    public function SubTotalCartValue()
    {
        return $this->SubTotal;
    }

    /*******************************************************
       * 10. STANDARD SS METHODS (requireDefaultRecords, onBeforeDelete, etc...)
    *******************************************************/

    /**
     *standard SS method.
     **/
    public function populateDefaults()
    {
        parent::populateDefaults();
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (! $this->getCanHaveShippingAddress()) {
            $this->UseShippingAddress = false;
        }
        if (! $this->CurrencyUsedID) {
            $this->CurrencyUsedID = EcommerceCurrency::default_currency_id();
        }
        if (! $this->SessionID) {
            $generator = Injector::inst()->create(RandomGenerator::class);
            $token = $generator->randomToken('sha1');
            $this->SessionID = substr($token, 0, 32);
        }
    }

    /**
     * standard SS method
     * adds the ability to update order after writing it.
     **/
    public function onAfterWrite()
    {
        parent::onAfterWrite();
        //crucial!
        self::set_needs_recalculating(true, $this->ID);
        // quick double-check
        if ($this->IsCancelled() && ! $this->IsArchived()) {
            $this->Archive($avoidWrites = true);
        }
        if ($this->IsSubmitted($recalculate = true)) {
            //do nothing
        } else {
            if ($this->StatusID) {
                $this->calculateOrderAttributes($recalculate = false);
                if (EcommerceRole::current_member_is_shop_admin()) {
                    if (isset($_REQUEST['SubmitOrderViaCMS'])) {
                        $this->tryToFinaliseOrder();
                        //just in case it writes again...
                        unset($_REQUEST['SubmitOrderViaCMS']);
                    }
                }
            }
        }
    }

    /**
     *standard SS method.
     *
     * delete attributes, statuslogs, and payments
     * THIS SHOULD NOT BE USED AS ORDERS SHOULD BE CANCELLED NOT DELETED
     */
    public function onBeforeDelete()
    {
        parent::onBeforeDelete();
        if ($attributes = $this->Attributes()) {
            foreach ($attributes as $attribute) {
                $attribute->delete();
                $attribute->destroy();
            }
        }

        //THE REST WAS GIVING ERRORS - POSSIBLY DUE TO THE FUNNY RELATIONSHIP (one-one, two times...)
        /*
        if($billingAddress = $this->BillingAddress()) {
            if($billingAddress->exists()) {
                $billingAddress->delete();
                $billingAddress->destroy();
            }
        }
        if($shippingAddress = $this->ShippingAddress()) {
            if($shippingAddress->exists()) {
                $shippingAddress->delete();
                $shippingAddress->destroy();
            }
        }

        if($statuslogs = $this->OrderStatusLogs()){
            foreach($statuslogs as $log){
                $log->delete();
                $log->destroy();
            }
        }
        if($payments = $this->Payments()){
            foreach($payments as $payment){
                $payment->delete();
                $payment->destroy();
            }
        }
        if($emails = $this->Emails()) {
            foreach($emails as $email){
                $email->delete();
                $email->destroy();
            }
        }
        */
    }

    /*******************************************************
       * 11. DEBUG
    *******************************************************/

    /**
     * Debug helper method.
     * Can be called from /shoppingcart/debug/.
     *
     * @return string
     */
    public function debug()
    {
        $this->calculateOrderAttributes(true);

        return EcommerceTaskDebugCart::debug_object($this);
    }

    /**
     * Field to add and edit Order Items.
     *
     * @return GridField
     */
    protected function getOrderItemsField()
    {
        $gridFieldConfig = GridFieldConfigForOrderItems::create();
        $source = $this->OrderItems();

        return new GridField('OrderItems', _t('OrderItems.PLURALNAME', 'Order Items'), $source, $gridFieldConfig);
    }

    /**
     *@return GridField
     **/
    protected function getBillingAddressField()
    {
        $this->CreateOrReturnExistingAddress(BillingAddress::class);
        $gridFieldConfig = GridFieldConfig::create()->addComponents(
            new GridFieldToolbarHeader(),
            new GridFieldSortableHeader(),
            new GridFieldDataColumns(),
            new GridFieldPaginator(10),
            new GridFieldEditButton(),
            new GridFieldDetailForm()
        );
        //$source = $this->BillingAddress();
        $source = BillingAddress::get()->filter(['OrderID' => $this->ID]);

        return new GridField('BillingAddress', _t('BillingAddress.SINGULARNAME', 'Billing Address'), $source, $gridFieldConfig);
    }

    /**
     *@return GridField
     **/
    protected function getShippingAddressField()
    {
        $this->CreateOrReturnExistingAddress(ShippingAddress::class);
        $gridFieldConfig = GridFieldConfig::create()->addComponents(
            new GridFieldToolbarHeader(),
            new GridFieldSortableHeader(),
            new GridFieldDataColumns(),
            new GridFieldPaginator(10),
            new GridFieldEditButton(),
            new GridFieldDetailForm()
        );
        //$source = $this->ShippingAddress();
        $source = ShippingAddress::get()->filter(['OrderID' => $this->ID]);

        return new GridField('ShippingAddress', _t('BillingAddress.SINGULARNAME', 'Shipping Address'), $source, $gridFieldConfig);
    }

    /**
     * @param string    $sourceClass
     * @param string    $title
     * @param FieldList $fieldList          (Optional)
     * @param FieldList $detailedFormFields (Optional)
     *
     * @return GridField
     **/
    protected function getOrderStatusLogsTableField_Archived(
        $sourceClass = OrderStatusLog::class,
        $title = '',
        FieldList $fieldList = null,
        FieldList $detailedFormFields = null
    ) {
        $title ?: $title = _t('OrderLog.PLURALNAME', 'Order Log');
        $source = $this->OrderStatusLogs();
        if ($sourceClass !== OrderStatusLog::class && class_exists($sourceClass)) {
            $source = $source->filter(['ClassName' => ClassInfo::subclassesFor($sourceClass)]);
        }
        $fieldName = ClassInfo::shortName($sourceClass);
        $gridField = GridField::create($fieldName, $title, $source, $config = GridFieldConfig_RelationEditor::create());
        $config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
        $config->removeComponentsByType(GridFieldDeleteAction::class);

        return $gridField;
    }

    /**
     * @return GridField
     */
    protected function getPaymentsField()
    {
        $gridFieldConfig = GridFieldConfig_RecordViewer::create()->addComponents(
            new GridFieldDetailForm(),
            new GridFieldEditButton()
        );

        return new GridField('Payments', _t('Order.PAYMENTS', 'Payments'), $this->Payments(), $gridFieldConfig);
    }

    /**
     * Send a mail of the order to the client (and another to the admin).
     *
     * @param string         $emailClassName       - (optional) template to be used ...
     * @param string         $subject              - (optional) subject for the email
     * @param string         $message              - (optional) message to be added with the email
     * @param bool           $resend               - (optional) can it be sent twice?
     * @param bool | string  $adminOnlyOrToEmail   - (optional) sends the email to the ADMIN ONLY, if you provide an email, it will go to the email...
     *
     * @return bool TRUE for success, FALSE for failure (not tested)
     */
    protected function prepareAndSendEmail(
        $emailClassName = OrderInvoiceEmail::class,
        $subject = '',
        $message = '',
        $resend = false,
        $adminOnlyOrToEmail = false
    ) {
        $arrayData = $this->createReplacementArrayForEmail($subject, $message);
        $from = OrderEmail::get_from_email();
        //why are we using this email and NOT the member.EMAIL?
        //for historical reasons????
        if ($adminOnlyOrToEmail) {
            if (filter_var($adminOnlyOrToEmail, FILTER_VALIDATE_EMAIL)) {
                $to = $adminOnlyOrToEmail;
            // invalid e-mail address
            } else {
                $to = OrderEmail::get_from_email();
            }
        } else {
            $to = $this->getOrderEmail();
        }
        if ($from && $to) {
            if (! class_exists($emailClassName)) {
                user_error('Invalid Email ClassName provided: ' . $emailClassName, E_USER_ERROR);
            }
            $email = new $emailClassName();
            if (! is_a($email, EcommerceConfigClassNames::getName(Email::class))) {
                user_error('No correct email class provided.', E_USER_ERROR);
            }
            $email->setFrom($from);
            $email->setTo($to);
            //we take the subject from the Array Data, just in case it has been adjusted.
            $email->setSubject($arrayData->getField('Subject'));
            //we also see if a CC and a BCC have been added

            if ($cc = $arrayData->getField('CC')) {
                $email->setCc($cc);
            }
            if ($bcc = $arrayData->getField('BCC')) {
                $email->setBcc($bcc);
            }

            foreach ($arrayData as $key => $value) {
                $email->addData($key, $value);
            }
            // This might be called from within the CMS,
            // so we need to restore the theme, just in case
            // templates within the theme exist
            Config::nest();
            Config::modify()->update(SSViewer::class, 'theme_enabled', true);
            $email->setOrder($this);
            $email->setResend($resend);
            $result = $email->send(null);
            Config::unnest();
            //todo: we are experiencing issues with emails, so we always return true!
            // return true;
            return $result;
        }

        return false;
    }

    /**
     * returns the Data that can be used in the body of an order Email
     * we add the subject here so that the subject, for example, can be added to the <title>
     * of the email template.
     * we add the subject here so that the subject, for example, can be added to the <title>
     * of the email template.
     *
     * @param string $subject  - (optional) subject for email
     * @param string $message  - (optional) the additional message
     *
     * @return ArrayData
     *                   - Subject - EmailSubject
     *                   - Message - specific message for this order
     *                   - Message - custom message
     *                   - OrderStepMessage - generic message for step
     *                   - Order
     *                   - EmailLogo
     *                   - ShopPhysicalAddress
     *                   - CurrentDateAndTime
     *                   - BaseURL
     *                   - CC
     *                   - BCC
     */
    protected function createReplacementArrayForEmail($subject = '', $message = '')
    {
        $step = $this->MyStep();
        $config = EcommerceConfig::inst();
        $replacementArray = [];
        //set subject
        if (! $subject) {
            $subject = $step->CalculatedEmailSubject($this);
        }
        if (! $message) {
            $message = $step->CalculatedCustomerMessage($this);
        }
        $subject = str_replace('[OrderNumber]', $this->ID, $subject);
        //set other variables
        $replacementArray['Subject'] = $subject;
        $replacementArray['To'] = '';
        $replacementArray['CC'] = '';
        $replacementArray['BCC'] = '';
        $replacementArray['OrderStepMessage'] = $message;
        $replacementArray['Order'] = $this;
        $replacementArray['EmailLogo'] = $config->EmailLogo();
        $replacementArray['ShopPhysicalAddress'] = $config->ShopPhysicalAddress;
        $replacementArray['CurrentDateAndTime'] = DBDatetime::now()->Rfc2822();
        $replacementArray['BaseURL'] = Director::baseURL();
        $arrayData = ArrayData::create($replacementArray);
        $this->extend('updateReplacementArrayForEmail', $arrayData);

        return $arrayData;
    }

    /**
     * Return all the {@link OrderItem} instances that are
     * available as records in the database.
     *
     * @param string $filterOrClassName filter - where statement to exclude certain items,
     *   you can also pass a classname (e.g. MyOrderItem), in which case only this class will be returned (and any class extending your given class)
     *
     * @return \SilverStripe\ORM\DataList (OrderItems)
     */
    protected function itemsFromDatabase($filterOrClassName = '')
    {
        $className = OrderItem::class;
        $extrafilter = '';
        if ($filterOrClassName) {
            if (class_exists($filterOrClassName)) {
                $className = $filterOrClassName;
            } else {
                $extrafilter = " AND ${filterOrClassName}";
            }
        }
        return $className::get()->filter(['OrderID' => $this->ID])->where($extrafilter);
    }

    /**
     * Get all {@link OrderModifier} instances that are
     * available as records in the database.
     * NOTE: includes REMOVED Modifiers, so that they do not get added again...
     *
     * @param string $filterOrClassName filter - where statement to exclude certain items OR ClassName (e.g. 'TaxModifier')
     *
     * @return \SilverStripe\ORM\DataList (OrderModifiers)
     */
    protected function modifiersFromDatabase($filterOrClassName = '')
    {
        $className = OrderModifier::class;
        $extrafilter = '';
        if ($filterOrClassName) {
            if (class_exists($filterOrClassName)) {
                $className = $filterOrClassName;
            } else {
                $extrafilter = " AND ${filterOrClassName}";
            }
        }
        return $className::get()->where('"OrderAttribute"."OrderID" = ' . $this->ID . " ${extrafilter}");
    }

    /**
     * Calculates and updates all the product items.
     *
     * @param bool $recalculate - run it, even if it has run already
     */
    protected function calculateOrderItems($recalculate = false)
    {
        //check if order has modifiers already
        //check /re-add all non-removable ones
        //$start = microtime();
        $orderItems = $this->itemsFromDatabase();
        if ($orderItems->count()) {
            foreach ($orderItems as $orderItem) {
                if ($orderItem) {
                    $orderItem->runUpdate($recalculate);
                }
            }
        }
        $this->extend('onCalculateOrderItems', $orderItems);
    }

    /**
     * Calculates and updates all the modifiers.
     *
     * @param bool $recalculate - run it, even if it has run already
     */
    protected function calculateModifiers($recalculate = false)
    {
        $createdModifiers = $this->modifiersFromDatabase();
        if ($createdModifiers->count()) {
            foreach ($createdModifiers as $modifier) {
                if ($modifier) {
                    $modifier->runUpdate($recalculate);
                }
            }
        }
        $this->extend('onCalculateModifiers', $createdModifiers);
    }

    /*******************************************************
       * 7. CRUD METHODS (e.g. canView, canEdit, canDelete, etc...)
    *******************************************************/

    /**
     * @param \SilverStripe\Security\Member $member
     *
     * @return \SilverStripe\ORM\DataObject (Member)
     **/
    //TODO: please comment why we make use of this function
    protected function getMemberForCanFunctions(Member $member = null)
    {
        if (! $member) {
            $member = Security::getCurrentUser();
        }
        if (! $member) {
            $member = new Member();
            $member->ID = 0;
        }

        return $member;
    }

    /**
     * returns itself wtih more data added as variables.
     * We add has_one and has_many as variables like this: $this->MyHasOne_serialized = serialize($this->MyHasOne()).
     *
     * @return Order - with most important has one and has many items included as variables.
     **/
    protected function addHasOneAndHasManyAsVariables()
    {
        $object = clone $this;
        $object->Member_serialized = serialize($this->Member());
        $object->BillingAddress_serialized = serialize($this->BillingAddress());
        $object->ShippingAddress_serialized = serialize($this->ShippingAddress());
        $object->Attributes_serialized = serialize($this->Attributes());
        $object->OrderStatusLogs_serialized = serialize($this->OrderStatusLogs());
        $object->Payments_serialized = serialize($this->Payments());
        $object->Emails_serialized = serialize($this->Emails());

        return $object;
    }
}
