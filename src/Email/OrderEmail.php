<?php

namespace Sunnysideup\Ecommerce\Email;










use SilverStripe\Control\Director;
use Sunnysideup\Ecommerce\Email\OrderEmail;
use Sunnysideup\Ecommerce\Config\EcommerceConfig;
use SilverStripe\Control\HTTP;
use Sunnysideup\Ecommerce\Model\Config\EcommerceDBConfig;
use SilverStripe\Control\Email\Email;
use SilverStripe\SiteConfig\SiteConfig;
use Sunnysideup\Ecommerce\Model\Order;
use Sunnysideup\Ecommerce\Model\Process\OrderStep;
use Sunnysideup\Ecommerce\Model\Process\OrderEmailRecord;
use SilverStripe\Core\Config\Config;




/**
 * @Description: Email specifically for communicating with customer about order.
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 * @sub-package: email

 **/
abstract class OrderEmail extends Email
{
    /**
     * @var Order
     */
    protected $order = null;

    /**
     * @var bool
     */
    protected $resend = false;

    /**
     * turns an html document into a formatted html document
     * using the emogrify method.
     *
     * @param $html
     *
     * @return string HTML
     */
    public static function emogrify_html($html)
    {
        //get required files
        $baseFolder = Director::baseFolder();
        if (! class_exists('\Pelago\Emogrifier')) {
            require_once $baseFolder . '/ecommerce/thirdparty/Emogrifier.php';
        }
        $cssFileLocation = Director::baseFolder() . '/' . EcommerceConfig::get(OrderEmail::class, 'css_file_location');
        $cssFileHandler = fopen($cssFileLocation, 'r');
        $css = fread($cssFileHandler, filesize($cssFileLocation));
        fclose($cssFileHandler);
        $emogrifier = new \Pelago\Emogrifier($html, $css);
        $html = $emogrifier->emogrify();
        //make links absolute!
        $html = HTTP::absoluteURLs($html);

        return $html;
    }

    /**
     * returns the standard from email address (e.g. the shop admin email address).
     *
     * @return string
     */
    public static function get_from_email()
    {
        $ecommerceConfig = EcommerceDBConfig::current_ecommerce_db_config();
        if ($ecommerceConfig && $ecommerceConfig->ReceiptEmail) {
            $email = $ecommerceConfig->ReceiptEmail;
        } else {
            $email = Email::config()->admin_email;
        }

        return trim($email);
    }

    /**
     * returns the subject for the email (doh!).
     *
     * @return string
     */
    public static function get_subject()
    {
        $siteConfig = SiteConfig::current_site_config();
        if ($siteConfig && $siteConfig->Title) {
            return _t('OrderEmail.SALEUPDATE', 'Sale Update for Order #[OrderNumber] from ') . $siteConfig->Title;
        }
        return _t('OrderEmail.SALEUPDATE', 'Sale Update for Order #[OrderNumber] ');
    }

    /**
     * set the order associated with the email.
     *
     * @param Order $order - the order to which the email relates
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * sets resend to true, which means that the email
     * is sent even if it has already been sent.
     */
    public function setResend($resend = true)
    {
        $this->resend = $resend;
    }

    /**
     * @param string|null $messageID - ID for the message, you can leave this blank
     * @param bool        $returnBodyOnly - rather than sending the email, only return the HTML BODY
     *
     * @return bool - TRUE for success and FALSE for failure.
     */
    public function send($messageID = null, $returnBodyOnly = false)
    {
        if (! $this->order) {
            user_error('Must set the order (OrderEmail::setOrder()) before the message is sent (OrderEmail::send()).', E_USER_NOTICE);
        }
        if (! $this->subject) {
            $this->subject = self::get_subject();
        }
        $this->subject = str_replace('[OrderNumber]', $this->order->ID, $this->subject);
        if (! $this->hasBeenSent() || ($this->resend)) {
            if (EcommerceConfig::get(OrderEmail::class, 'copy_to_admin_for_all_emails') && ($this->to !== self::get_from_email())) {
                if ($memberEmail = self::get_from_email()) {
                    $array = [$memberEmail];
                    if ($bcc = $this->Bcc()) {
                        $array[] = $bcc;
                    }
                    $this->setBcc(implode(', ', $array));
                }
            }
            //last chance to adjust
            $this->extend('adjustOrderEmailSending', $this, $order);
            if ($returnBodyOnly) {
                return $this->Body();
            }

            if (EcommerceConfig::get(OrderEmail::class, 'send_all_emails_plain')) {
                $result = parent::sendPlain($messageID);
            } else {
                $result = parent::send($messageID);
            }

            $orderEmailRecord = $this->createRecord($result);
            if (Director::isDev()) {
                $result = true;
            }
            $orderEmailRecord->Result = $result ? true : false;
            $orderEmailRecord->write();

            return $result;
        }
    }

    /**
     * converts an Email to A Varchar.
     *
     * @param string $email - email address
     *
     * @return string - returns email address without &gt; and &lt;
     */
    public function emailToVarchar($email)
    {
        return str_replace(['<', '>', '"', "'"], ' - ', $email);
    }

    /**
     * Checks if an email has been sent for this Order for this status (order step).
     *
     * @return bool
     **/
    public function hasBeenSent(): bool
    {
        $orderStep = $this->order->Status();
        if (is_a($orderStep, Object::getCustomClass(OrderStep::class))) {
            return $orderStep->hasBeenSent($this->order);
        }

        user_error('expects orderstep');
    }

    /**
     * returns the instance of EcommerceDBConfig.
     *
     * @return EcommerceDBConfig
     **/
    public function EcomConfig()
    {
        return EcommerceDBConfig::current_ecommerce_db_config();
    }

    /**
     * @param bool $result: how did the email go? 1 = sent, 0 = not sent
     *
     * @return DataObject (OrderEmailRecord)
     **/
    protected function createRecord($result)
    {
        $orderEmailRecord = OrderEmailRecord::create();
        $orderEmailRecord->From = $this->emailToVarchar($this->from);
        $orderEmailRecord->To = $this->emailToVarchar($this->to);
        if ($this->Cc()) {
            $orderEmailRecord->To .= ', CC: ' . $this->emailToVarchar($this->Cc());
        }
        if ($this->Bcc()) {
            $orderEmailRecord->To .= ', BCC: ' . $this->emailToVarchar($this->Bcc());
        }
        //always set result to try if
        $orderEmailRecord->Subject = $this->subject;
        if (! $result) {
            if (Director::isDev()) {
                $orderEmailRecord->Subject .= _t('OrderEmail.FAKELY_RECORDED_AS_SENT', ' - FAKELY RECORDED AS SENT ');
            }
        }
        $orderEmailRecord->Content = $this->body;
        $orderEmailRecord->Result = $result ? 1 : 0;
        $orderEmailRecord->OrderID = $this->order->ID;
        $orderEmailRecord->OrderStepID = $this->order->StatusID;
        if ($sendAllEmailsTo = Config::inst()->get(Email::class, 'send_all_emails_to')) {
            $orderEmailRecord->To .=
                _t('OrderEmail.ACTUALLY_SENT_TO', ' | actually sent to: ')
                . $sendAllEmailsTo
                . _t('OrderEmail.CONFIG_EXPLANATION', ' - (Email::send_all_emails_to)');
        }
        $orderEmailRecord->write();

        return $orderEmailRecord;
    }

    /**
     * moves CSS to inline CSS in email.
     *
     * @param bool $isPlain - should we send the email as HTML or as TEXT
     */
    protected function parseVariables($isPlain = false)
    {
        //start parsing
        parent::parseVariables($isPlain);
        if (! $isPlain) {
            $this->body = self::emogrify_html($this->body);
        }
    }
}
