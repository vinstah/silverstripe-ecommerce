<?php

namespace Sunnysideup\Ecommerce\Config;
use Sunnysideup\Ecommerce\Config\EcommerceConfigAjax;





/**
 * This class returns the Ajax Definitions class.
 * The Ajax Definitions class is an object that contains all the values
 * for ajax references in the templates.
 *
 * We need to have one per classname (e.g. Product)and requestor (Product A with ID = 1)
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 * @sub-package: configuration

 **/
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Config\Configurable;
/**
 * This class returns the Ajax Definitions class.
 * The Ajax Definitions class is an object that contains all the values
 * for ajax references in the templates.
 *
 * We need to have one per classname (e.g. Product)and requestor (Product A with ID = 1)
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 * @sub-package: configuration
 **/
class EcommerceConfigAjax
{
    use Extensible;
    use Injectable;
    use Configurable;
    /**
     * implements singleton pattern so that there is only ever one instance
     * of this class.
     * This is usually defined as $singleton[$ClassName][$Requestor->ID].
     *
     * @static object
     */
    private static $singleton = array();
    /**
     * Returns the singleton instance of the Ajax Config definitions class.
     * This class basically contains a bunch of methods that return
     * IDs and Classes for use with AJAX.
     *
     * @param DataObject $requestor the object requesting the Ajax Config Definitions
     *
     * @return EcommerceConfigAjaxDefinitions (or other object)
     */
    public static function get_one($requestor)
    {
        if (!isset(self::$singleton[$requestor->ClassName][$requestor->ID])) {
            /**
             * ### @@@@ START REPLACEMENT @@@@ ###
             * WHY: automated upgrade
             * OLD: $className (case sensitive)
             * NEW: $className (COMPLEX)
             * EXP: Check if the class name can still be used as such
             * ### @@@@ STOP REPLACEMENT @@@@ ###
             */
            $className = EcommerceConfig::get(EcommerceConfigAjax::class, 'definitions_class_name');
            /**
             * ### @@@@ START REPLACEMENT @@@@ ###
             * WHY: automated upgrade
             * OLD: $className (case sensitive)
             * NEW: $className (COMPLEX)
             * EXP: Check if the class name can still be used as such
             * ### @@@@ STOP REPLACEMENT @@@@ ###
             */
            self::$singleton[$requestor->ClassName][$requestor->ID] = new $className();
            self::$singleton[$requestor->ClassName][$requestor->ID]->setRequestor($requestor);
        }
        return self::$singleton[$requestor->ClassName][$requestor->ID];
    }
}
