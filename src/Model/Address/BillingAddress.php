<?php

namespace Sunnysideup\Ecommerce\Model\Address;

use GoogleAddressField;
use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Member;
use Sunnysideup\Ecommerce\Config\EcommerceConfig;
use Sunnysideup\Ecommerce\Forms\Fields\SelectOrderAddressField;
use Sunnysideup\Ecommerce\Model\Order;

/**
 * @description: each order has a billing address.
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 * @sub-package: address

 **/
class BillingAddress extends OrderAddress
{
    /**
     * what variables are accessible through  http://mysite.com/api/ecommerce/v1/BillingAddress/.
     *
     * @var array
     */
    private static $api_access = [
        'view' => [
            'Prefix',
            'FirstName',
            'Surname',
            'Address',
            'Address2',
            'City',
            'PostalCode',
            'RegionCode',
            'Country',
            'Phone',
            Email::class,
        ],
    ];

    /**
     * standard SS variable.
     *
     * @return array
     */

    /**
     * ### @@@@ START REPLACEMENT @@@@ ###
     * OLD: private static $db (case sensitive)
     * NEW:
    private static $db (COMPLEX)
     * EXP: Check that is class indeed extends DataObject and that it is not a data-extension!
     * ### @@@@ STOP REPLACEMENT @@@@ ###
     */
    private static $table_name = 'BillingAddress';

    /**
     * ### @@@@ START REPLACEMENT @@@@ ###
     * WHY: automated upgrade
     * OLD: private static $db = (case sensitive)
     * NEW: private static $db = (COMPLEX)
     * EXP: Make sure to add a private static $table_name!
     * ### @@@@ STOP REPLACEMENT @@@@ ###
     */
    private static $db = [
        'Prefix' => 'Varchar(10)',
        'FirstName' => 'Varchar(100)',
        'Surname' => 'Varchar(100)',
        'Address' => 'Varchar(255)',
        'Address2' => 'Varchar(200)',
        'City' => 'Varchar(100)',
        'PostalCode' => 'Varchar(30)',
        'Country' => 'Varchar(4)',
        'RegionCode' => 'Varchar(100)',
        'Phone' => 'Varchar(50)',
        'Email' => 'Varchar(250)',
        'Obsolete' => 'Boolean',
        'OrderID' => 'Int', //NOTE: we have this here for faster look-ups and to make addresses behave similar to has_many dataobjects
    ];

    /**
     * HAS_ONE =array(ORDER => ORDER);
     * we place this relationship here
     * (rather than in the parent class: OrderAddress)
     * because that makes for a cleaner relationship
     * (otherwise we ended up with a "has two" relationship in Order).
     **/
    private static $has_one = [
        'Region' => EcommerceRegion::class,
    ];

    /**
     * standard SS static definition.
     **/
    private static $belongs_to = [
        'Order' => Order::class,
    ];

    /**
     * standard SS static definition.
     */
    private static $default_sort = [
        'ID' => 'DESC',
    ];

    /**
     * standard SS variable.
     *
     * @return array
     */
    private static $indexes = [
        'Obsolete' => true,
        'OrderID' => true,
        'Country' => true,
    ];

    /**
     * standard SS variable.
     *
     * @return array
     */
    private static $casting = [
        'FullCountryName' => 'Varchar',
    ];

    /**
     * standard SS variable.
     *
     * @return array
     */
    private static $searchable_fields = [
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
        'Email' => 'PartialMatchFilter',
        'FirstName' => 'PartialMatchFilter',
        'Surname' => 'PartialMatchFilter',
        'Address' => 'PartialMatchFilter',
        'City' => 'PartialMatchFilter',
        'Country' => 'PartialMatchFilter',
        'Obsolete',
    ];

    /**
     * standard SS variable.
     *
     * @return array
     */
    private static $summary_fields = [
        'Order.Title',
        'FirstName',
        'Surname',
        'City',
        'PostalCode',
        'Country',
        'Phone',
    ];

    /**
     * standard SS variable.
     *
     * @return array
     */
    private static $field_labels = [
        'Order.Title' => Order::class,
        'Obsolete' => 'Do not use for future transactions',
        'Email' => Email::class,
    ];

    /**
     * standard SS variable.
     *
     * @return string
     */
    private static $singular_name = 'Billing Address';

    /**
     * standard SS variable.
     *
     * @return string
     */
    private static $plural_name = 'Billing Addresses';

    /**
     * Standard SS variable.
     *
     * @var string
     */
    private static $description = 'The details of the person buying the order.';

    public function i18n_singular_name()
    {
        return _t('BillingAddress.BILLINGADDRESS', 'Billing Address');
    }

    public function i18n_plural_name()
    {
        return _t('BillingAddress.BILLINGADDRESSES', 'Billing Addresses');
    }

    /**
     * method for casted variable.
     *
     *@return string
     **/
    public function FullCountryName()
    {
        return $this->getFullCountryName();
    }

    public function getFullCountryName()
    {
        return EcommerceCountry::find_title($this->Country);
    }

    /**
     *@return FieldList
     **/
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->replaceField('OrderID', new ReadonlyField('OrderID', _t('BillingAddress.ORDERID', 'Order #')));
        $fields->replaceField(Email::class, new EmailField(Email::class, _t('BillingAddress.EMAIL', Email::class)));
        //We remove both the RegionCode and RegionID field and then add only the one we need directly after the country field.
        $fields->removeByName('RegionCode');
        $fields->removeByName('RegionID');
        $fields->insertBefore($this->getRegionField('RegionID'), 'Country');
        $fields->replaceField('Country', $this->getCountryField('Country'));

        return $fields;
    }

    /**
     * @param Member $member
     *
     * @return FieldList
     **/
    public function getFields(Member $member = null)
    {
        $fields = parent::getEcommerceFields();
        $headerTitle = _t('BillingAddress.DELIVERY_AND_BILLING_ADDRESS', 'Delivery and Billing Address');
        $fields->push(
            HeaderField::create(
                'BillingDetails',
                $headerTitle,
                3
            )
                ->setAttribute('data-title-with-shipping-address', _t('BillingAddress.BILLING_ADDRESS_ONLY', 'Billing Address Only'))
                ->setAttribute('data-title-with-shipping-address_default', $headerTitle)
        );
        $fields->push(new TextField('Phone', _t('BillingAddress.PHONE', 'Phone')));
        $billingFields = new CompositeField();
        if ($member && Member::currentUser()) {
            if ($member->exists() && ! $member->IsShopAdmin()) {
                $this->FillWithLastAddressFromMember($member, true);
                if (EcommerceConfig::get(BillingAddress::class, 'allow_selection_of_previous_addresses_in_checkout')) {
                    $addresses = $member->previousOrderAddresses($this->baseClassLinkingToOrder(), $this->ID, $onlyLastRecord = false, $keepDoubles = false);
                    //we want MORE than one here not just one.
                    if ($addresses->count() > 1) {
                        $fields->push(
                            SelectOrderAddressField::create(
                                'SelectBillingAddressField',
                                _t('BillingAddress.SELECTBILLINGADDRESS', 'Select Billing Address'),
                                $addresses
                            )
                        );
                    }
                }
            }
        }

        $mappingArray = $this->Config()->get('fields_to_google_geocode_conversion');
        if (is_array($mappingArray) && count($mappingArray)) {
            if (! class_exists('GoogleAddressField')) {
                user_error('You must install the Sunny Side Up google_address_field module OR remove entries from: BillingAddress.fields_to_google_geocode_conversion');
            }
            $billingFields->push(
                $billingEcommerceGeocodingField = GoogleAddressField::create(
                    'BillingEcommerceGeocodingField',
                    _t('BillingAddress.FIND_ADDRESS', 'Find address'),
                    /**
                     * ### @@@@ START REPLACEMENT @@@@ ###
                     * WHY: automated upgrade
                     * OLD: Session:: (case sensitive)
                     * NEW: SilverStripe\Control\Controller::curr()->getRequest()->getSession()-> (COMPLEX)
                     * EXP: If THIS is a controller than you can write: $this->getRequest(). You can also try to access the HTTPRequest directly.
                     * ### @@@@ STOP REPLACEMENT @@@@ ###
                     */
                    SilverStripe\Control\Controller::curr()->getRequest()->getSession()->get('BillingEcommerceGeocodingFieldValue')
                )
            );
            $billingEcommerceGeocodingField->setFieldMap($mappingArray);
            //$billingFields->push(new HiddenField('Address2', "NOT SET", "NOT SET"));
            //$billingFields->push(new HiddenField('City', "NOT SET", "NOT SET"));
        }
        $billingFields->push(new TextField('Address', _t('BillingAddress.ADDRESS', 'Address')));
        $billingFields->push(new TextField('Address2', _t('BillingAddress.ADDRESS2', '')));
        $billingFields->push(new TextField('City', _t('BillingAddress.CITY', 'Town')));
        $billingFields->push($this->getPostalCodeField('PostalCode'));
        $billingFields->push($this->getRegionField('RegionID', 'RegionCode'));
        $billingFields->push($this->getCountryField('Country'));
        $billingFields->addExtraClass('billingFields');
        $billingFields->addExtraClass('orderAddressHolder');
        $this->makeSelectedFieldsReadOnly($billingFields);
        $fields->push($billingFields);
        $this->extend('augmentEcommerceBillingAddressFields', $fields);

        return $fields;
    }

    /**
     * Return which billing address fields should be required on {@link OrderFormAddress}.
     *
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->Config()->get('required_fields');
    }

    /*
     * standard SS method
     * sets the country to the best known country {@link EcommerceCountry}
     **/
    //function populateDefaults() {
        //parent::populateDefaults();
        //$this->Country = EcommerceCountry::get_country(false, $this->OrderID);
    //}
}
