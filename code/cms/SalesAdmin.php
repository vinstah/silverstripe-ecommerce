<?php


/**
 * @description: CMS management for everything you have sold and all related data (e.g. logs, payments)
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 * @sub-package: cms
 * @inspiration: Silverstripe Ltd, Jeremy
 **/
class SalesAdmin extends ModelAdminEcommerceBaseClass
{
    /**
     * standard SS variable.
     *
     * @var string
     */
    private static $url_segment = 'sales';

    /**
     * standard SS variable.
     *
     * @var string
     */
    private static $menu_title = 'Sales to Action';

    /**
     * standard SS variable.
     *
     * @var int
     */
    private static $menu_priority = 3.1;

    /**
     * Change this variable if you don't want the Import from CSV form to appear.
     * This variable can be a boolean or an array.
     * If array, you can list className you want the form to appear on. i.e. array('myClassOne','myClasstwo').
     */
    public $showImportForm = false;

    /**
     * standard SS variable.
     *
     * @var string
     */
    private static $menu_icon = 'ecommerce/images/icons/money-file.gif';

    public function init()
    {
        parent::init();
        Requirements::javascript('ecommerce/javascript/EcomBuyableSelectField.js');
        //Requirements::themedCSS("OrderReport", 'ecommerce'); // LEAVE HERE - NOT EASY TO INCLUDE VIA TEMPLATE
        //Requirements::themedCSS("Order_Invoice", 'ecommerce', "print"); // LEAVE HERE - NOT EASY TO INCLUDE VIA TEMPLATE
        //Requirements::themedCSS("Order_PackingSlip", 'ecommerce', "print"); // LEAVE HERE - NOT EASY TO INCLUDE VIA TEMPLATE
        //Requirements::themedCSS("OrderStepField",'ecommerce'); // LEAVE HERE
        //Requirements::javascript("ecommerce/javascript/EcomModelAdminExtensions.js"); // LEAVE HERE - NOT EASY TO INCLUDE VIA TEMPLATE
    }

    public function urlSegmenter()
    {
        return $this->config()->get('url_segment');
    }

    /**
     * @return array Map of class name to an array of 'title' (see {@link $managed_models})
     *               we make sure that the Order Admin is FIRST
     */
    public function getManagedModels()
    {
        $models = parent::getManagedModels();
        $orderModelManagement = isset($models['Order']) ? $models['Order'] : null;
        if ($orderModelManagement) {
            unset($models['Order']);

            return array('Order' => $orderModelManagement) + $models;
        }

        return $models;
    }


    /**
     * @return DataList
     */
    public function getList()
    {
        $list = parent::getList();
        if (is_subclass_of($this->modelClass, 'Order') || $this->modelClass === 'Order') {
            $queueObjectSingleton = Injector::inst()->get('OrderProcessQueue');
            $ordersinQueue = $queueObjectSingleton->OrdersInQueueThatAreNotReady();
            $list = $list
                ->filter(
                    array(
                        "CancelledByID" => 0,
                        "StatusID:greaterThan" => 0
                    )
                )
                ->exclude(
                    array(
                        'ID' => $ordersinQueue->column('ID'),
                    )
                );
            //you can only do one exclude at the same time.
            $list = $list
                ->exclude(
                    array(
                        'StatusID' => OrderStep::non_admin_manageable_steps()->column('ID')
                    )
                );
        }

        $newLists = $this->extend('updateGetList', $list);
        if (is_array($newLists) && count($newLists)) {
            foreach ($newLists as $newList) {
                if ($newList instanceof DataList) {
                    $list = $newList;
                }
            }
        }
        return $list;
    }


    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        if (is_subclass_of($this->modelClass, 'Order') || $this->modelClass === 'Order') {
            if ($gridField = $form->Fields()->dataFieldByName($this->sanitiseClassName($this->modelClass))) {
                if ($gridField instanceof GridField) {
                    $config = $gridField->getConfig();
                    $exportButton = new GridFieldExportSalesButton('buttons-before-left');
                    $exportButton->setExportColumns($this->getExportFields());
                    $config->addComponent($exportButton);
                    $printAllInvoices = new GridFieldPrintAllInvoicesButton('buttons-before-left');
                    $config->addComponent($printAllInvoices);
                    $printAllPackingSlips = new GridFieldPrintAllPackingSlipsButton('buttons-before-left');
                    $config->addComponent($printAllPackingSlips);
                    //per row ...
                    $config->addComponent(new GridFieldPrintInvoiceButton());
                    // $config->addComponent(new GridFieldPrintPackingSlipButton());
                }
            }
        }
        return $form;
    }
}
