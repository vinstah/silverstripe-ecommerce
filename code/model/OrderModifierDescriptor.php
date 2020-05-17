<?php


class OrderModifierDescriptor extends DataObject implements EditableEcommerceObject
{
    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $db = [
        'ModifierClassName' => 'Varchar(100)',
        'Heading' => 'Varchar',
        'Description' => 'Text',
    ];

    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $has_one = [
        'Link' => 'SiteTree',
    ];

    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $indexes = [
        'ModifierClassName' => true,
    ];

    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $searchable_fields = [
        'Heading' => 'PartialMatchFilter',
        'Description' => 'PartialMatchFilter',
    ];

    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $field_labels = [
        'ModifierClassName' => 'Code',
    ];

    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $summary_fields = [
        'RealName' => 'Code',
        'Heading' => 'Heading',
        'Description' => 'Description',
    ];

    /**
     * standard SS variable.
     *
     * @var array
     */
    private static $casting = [
        'RealName' => 'Varchar',
    ];

    /**
     * standard SS variable.
     *
     * @var string
     */
    private static $singular_name = 'Order Modifier Description';

    /**
     * standard SS variable.
     *
     * @var string
     */
    private static $plural_name = 'Order Modifier Descriptions';

    public function i18n_singular_name()
    {
        return _t('OrderModifier.ORDEREXTRADESCRIPTION', 'Order Modifier Description');
    }

    public function i18n_plural_name()
    {
        return _t('OrderModifier.ORDEREXTRADESCRIPTIONS', 'Order Modifier Descriptions');
    }

    /**
     * standard SS method.
     *
     * @param Member $member
     *
     * @return bool
     **/
    public function canCreate($member = null)
    {
        return false;
    }

    /**
     * standard SS method.
     *
     * @param Member $member
     *
     * @return bool
     **/
    public function canEdit($member = null)
    {
        if (! $member) {
            $member = Member::currentUser();
        }
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }
        if (Permission::checkMember($member, Config::inst()->get('EcommerceRole', 'admin_permission_code'))) {
            return true;
        }

        return parent::canEdit($member);
    }

    /**
     * standard SS method.
     *
     * @param Member $member
     *
     * @return bool
     **/
    public function canDelete($member = null)
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
        $fields->replaceField('ModifierClassName', new ReadonlyField('RealName', 'Name'));
        $fields->replaceField('LinkID', new TreeDropdownField('LinkID', 'More info link (optional)', 'SiteTree'));
        $fields->replaceField('Description', new TextareaField('Description', 'Description'));

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
     * casted Variable.
     *
     * @return String.
     */
    public function RealName()
    {
        return $this->getRealName();
    }

    public function getRealName()
    {
        if (class_exists($this->ModifierClassName)) {
            $singleton = singleton($this->ModifierClassName);

            return $singleton->i18n_singular_name() . ' (' . $this->ModifierClassName . ')';
        }

        return $this->ModifierClassName;
    }

    /**
     * stardard SS method.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (isset($_REQUEST['NoLinkForOrderModifierDescriptor']) && $_REQUEST['NoLinkForOrderModifierDescriptor']) {
            $this->LinkID = 0;
        }
    }

    /**
     * Adds OrderModifierDescriptors and deletes the irrelevant ones
     * stardard SS method.
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        $arrayOfModifiers = EcommerceConfig::get('Order', 'modifiers');
        if (! is_array($arrayOfModifiers)) {
            $arrayOfModifiers = [];
        }
        if (count($arrayOfModifiers)) {
            foreach ($arrayOfModifiers as $className) {
                $orderModifier_Descriptor = DataObject::get_one(
                    'OrderModifierDescriptor',
                    ['ModifierClassName' => $className],
                    $cacheDataObjectGetOne = false
                );
                if (! $orderModifier_Descriptor) {
                    $modifier = Injector::inst()->get($className);
                    $orderModifier_Descriptor = OrderModifierDescriptor::create();
                    $orderModifier_Descriptor->ModifierClassName = $className;
                    $orderModifier_Descriptor->Heading = $modifier->i18n_singular_name();
                    $orderModifier_Descriptor->write();
                    DB::alteration_message('Creating description for ' . $className, 'created');
                }
            }
        }
        //delete the ones that are not relevant
        $orderModifierDescriptors = OrderModifierDescriptor::get();
        if ($orderModifierDescriptors && $orderModifierDescriptors->count()) {
            foreach ($orderModifierDescriptors as $orderModifierDescriptor) {
                if (! in_array($orderModifierDescriptor->ModifierClassName, $arrayOfModifiers, true)) {
                    $orderModifierDescriptor->delete();
                    DB::alteration_message('Deleting description for ' . $orderModifierDescriptor->ModifierClassName, 'deleted');
                }
            }
        }
    }
}