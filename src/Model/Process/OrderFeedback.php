<?php

namespace Sunnysideup\Ecommerce\Model\Process;






use CMSEditLinkField;

use CMSEditLinkAPI;
use Sunnysideup\Ecommerce\Model\Order;
use SilverStripe\Security\Member;
use SilverStripe\Core\Config\Config;
use Sunnysideup\Ecommerce\Model\Extensions\EcommerceRole;
use SilverStripe\Security\Permission;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataObject;
use Sunnysideup\Ecommerce\Interfaces\EditableEcommerceObject;




/***
 * Class used to describe the steps in the checkout
 *
 */

class OrderFeedback extends DataObject implements EditableEcommerceObject
{
    /**
     * standard SS variable.
     *
     * @Var Array
     */

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * OLD: private static $db (case sensitive)
  * NEW: 
    private static $table_name = '[SEARCH_REPLACE_CLASS_NAME_GOES_HERE]';

    private static $db (COMPLEX)
  * EXP: Check that is class indeed extends DataObject and that it is not a data-extension!
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
    
    private static $table_name = 'OrderFeedback';


/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: private static $db = (case sensitive)
  * NEW: private static $db = (COMPLEX)
  * EXP: Make sure to add a private static $table_name!
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
    private static $db = [
        'Rating' => 'Varchar',
        'Note' => 'Text',
        'Actioned' => 'Boolean',
    ];

    /**
     * standard SS variable.
     *
     * @Var Array
     */

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: private static $has_one = (case sensitive)
  * NEW: private static $has_one = (COMPLEX)
  * EXP: Make sure to add a private static $table_name!
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
    private static $has_one = [
        'Order' => Order::class,
    ];

    /**
     * standard SS variable.
     *
     * @Var Array
     */
    private static $searchable_fields = [
        'Rating' => 'PartialMatchFilter',
        'Note' => 'PartialMatchFilter',
        'OrderID' => [

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: NumericField (case sensitive)
  * NEW: NumericField (COMPLEX)
  * EXP: check the number of decimals required and add as ->Step(123)
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
            'field' => 'NumericField',
            'title' => 'Order Number',
        ],
    ];

    /**
     * standard SS variable.
     *
     * @Var Array
     */
    private static $summary_fields = [
        'Order.Title' => 'Order',
        'Created' => 'When',
        'Rating' => 'Rating',
        'Note' => 'Note',
    ];

    /**
     * standard SS variable.
     *
     * @Var Array
     */
    private static $casting = [
        'Title' => 'Varchar',
    ];

    /**
     * standard SS variable.
     *
     * @Var Array
     */
    private static $default_sorting = [
        'Created' => 'DESC',
    ];

    /**
     * standard SS variable.
     *
     * @Var String
     */
    private static $singular_name = 'Order Feedback';

    /**
     * standard SS variable.
     *
     * @Var String
     */
    private static $plural_name = 'Checkout Feedback Entries';

    /**
     * Standard SS variable.
     *
     * @var string
     */
    private static $description = 'Customer Order Feedback';

    /**
     * standard SS variable.
     *
     * @return bool
     */
    private static $can_create = false;

    public function i18n_singular_name()
    {
        return _t('OrderFeedback.SINGULAR_NAME', 'Order Feedback');
    }

    public function i18n_plural_name()
    {
        return _t('OrderFeedback.PLURAL_NAME', 'Order Feedback Entries');
    }

    /**
     * these are only created programmatically
     * standard SS method.
     *
     * @param Member $member
     *
     * @return bool
     */
    public function canCreate($member = null, $context = [])
    {
        return false;
    }

    /**
     * standard SS method.
     *
     * @param Member $member
     *
     * @return bool
     */
    public function canView($member = null, $context = [])
    {
        if (! $member) {
            $member = Member::currentUser();
        }
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        if (Permission::checkMember($member, Config::inst()->get(EcommerceRole::class, 'admin_permission_code'))) {
            return true;
        }

        return parent::canView($member);
    }

    /**
     * standard SS method.
     *
     * @param Member $member
     *
     * @return bool
     */
    public function canEdit($member = null, $context = [])
    {
        return false;
    }

    /**
     * standard SS method.
     *
     * @param Member $member
     *
     * @return bool
     */
    public function canDelete($member = null, $context = [])
    {
        return false;
    }

    /**
     * standard SS method.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->replaceField(
            'OrderID',
            CMSEditLinkField::create(
                'OrderIDLink',
                Injector::inst()->get(Order::class)->singular_name(),
                $this->Order()
            )
        );
        return $fields;
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
        return CMSEditLinkAPI::find_edit_link_for_object($this, $action);
    }

    /**
     * casted variable.
     *
     * @return string
     */
    public function Title()
    {
        return $this->getTitle();
    }

    public function getTitle()
    {
        $string = $this->Created;
        if ($this->Order()) {
            $string .= ' (' . $this->Order()->getTitle() . ')';
        }
        $string .= ' - ' . $this->Rating;
        if ($this->Note) {
            $string .= ' / ' . substr($this->Note, 0, 25);
        }
        return $string;
    }

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->Note = str_replace(["\n", "\r"], ' ¶ ', $this->Note);
        $this->Note = str_replace(['¶  ¶'], ' ¶ ', $this->Note);
        $this->Note = str_replace(['¶  ¶'], ' ¶ ', $this->Note);
        $this->Note = str_replace(['¶  ¶'], ' ¶ ', $this->Note);
    }
}
