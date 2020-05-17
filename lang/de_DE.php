<?php
/**
 * German (Germany) language pack.
 */
i18n::include_locale_file('modules: ecommerce', 'en_US');

global $lang;

if (array_key_exists('de_DE', $lang) && is_array($lang['de_DE'])) {
    $lang['de_DE'] = array_merge($lang['en_US'], $lang['de_DE']);
} else {
    $lang['de_DE'] = $lang['en_US'];
}

$lang['de_DE']['Account']['COMPLETEORDERS'] = 'Abgeschlossene Bestellungen';
$lang['de_DE']['Account']['COPYONLY'] = '-- Nur Kopie --';
$lang['de_DE']['Account']['DETAILSSAVED'] = 'Ihre Daten wurden gespeichert';
$lang['de_DE']['Account']['HISTORY'] = 'Ihr Bestellhistorie';
$lang['de_DE']['Account']['INCOMPLETEORDERS'] = 'Offene Bestellungen';
$lang['de_DE']['Account']['INPROCESSORDERS'] = 'Bearbeitete Bestellungen';
$lang['de_DE']['Account']['LINKTOACCOUNTPAGE'] = 'Gehen Sie zum ';
$lang['de_DE']['Account']['LOGINGAGAIN'] = 'Sie wurden automatisch ausgeloggt. Bitte loggen Sie sich wieder ein.';
$lang['de_DE']['Account']['LOGGEDIN'] = 'Sie sind angemeldet als ';
$lang['de_DE']['Account']['LOGOUT'] = 'Klicken Sie <a href="Security/logout" title="Klicken Sie hier um sich abzumelden">hier</a> um sich abzumelden.';
$lang['de_DE']['Account']['LOGINDETAILS'] = 'Konto Details';
$lang['de_DE']['Account']['MESSAGE'] = 'Sie müssen sich einloggen um auf Ihr Konto zugreifen zu können. Falls Sie nicht registriert sind, können Sie erst nach Ihrer ersten Bestellung auf Ihr Konto zugreifen. Fall Sie bereits registriert sind, geben Sie folgend Ihre Daten ein.';
$lang['de_DE']['Account']['NOPAGE'] = 'Keine AccountPage auf dieser Website - erstellen Sie bitte eine!';
$lang['de_DE']['Account']['NOCOMPLETED'] = 'Es konnten keine abgeschlossenen Bestellungen gefunden werden.';
$lang['de_DE']['Account']['NOHISTORY'] = 'Sie haben keine getätigten Bestellungen.';
$lang['de_DE']['Account']['NOINCOMPLETE'] = 'Es konnten keine offenen Bestellungen gefunden werden.';
$lang['de_DE']['Account']['ORDER'] = 'Bestellung Nr.';
$lang['de_DE']['Account']['ORDERNOTFOUND'] = 'Bestellung wurde nicht gefunden!';
$lang['de_DE']['Account']['ORDERNOTFOUNDGOTO'] = 'Bestellung wurde nicht gefunden! Gehe zu ';
$lang['de_DE']['Account']['PASSWORD'] = 'Passwort';
$lang['de_DE']['Account']['READMORE'] = 'Zur Detail-Ansicht der Bestellung #%s';
$lang['de_DE']['Account']['RECEIPTSENT'] = 'Eine Eingangsbestätigung wurde versendet an ';
$lang['de_DE']['Account']['RECEIPTNOTSENTNOEMAIL'] = 'Für diesen Empfänger wurde keine Email gefunden.';
$lang['de_DE']['Account']['RECEIPTNOTSENTNOORDER'] = 'Bestellung wurde nicht gefunden.';
$lang['de_DE']['Account']['SENDCOPYRECEIPT'] = 'Kopie der Bestellbestätigung senden an #%s';
$lang['de_DE']['Account']['SAVE'] = 'Speichern';
$lang['de_DE']['Account']['SAVEANDPROCEED'] = 'Speichern und Bestellung abschließen';
$lang['de_DE']['Account']['STATUS'] = 'Status';
$lang['de_DE']['Cart']['ADDONE'] = '1 &quot;%s&quot; zum Warenkorb hinzufügen';
$lang['de_DE']['Cart']['CHECKOUTCLICK'] = 'Hier klicken um zur Kasse zu gehen.';
$lang['de_DE']['Cart']['CHECKOUTGOTO'] = 'Zur Kasse';
$lang['de_DE']['Cart']['CONTINUESHOPPING'] = 'Einkauf fortsetzen';
$lang['de_DE']['Cart']['HEADLINE'] = 'Mein Warenkorb';
$lang['de_DE']['Cart']['NOITEMS'] = 'In Ihrem Warenkorb befinden sich zur Zeit keine Artikel';
$lang['de_DE']['Cart']['PRICE'] = 'Preis';
$lang['de_DE']['Cart']['QUANTITY'] = 'Menge';
$lang['de_DE']['Cart']['READMORE'] = 'Erfahren Sie hier mehr über &quot;%s&quot;';
$lang['de_DE']['Cart']['REMOVE'] = '&quot;%s&quot; aus Ihrem Warenkorb entfernen';
$lang['de_DE']['Cart']['REMOVEALL'] = 'Alle &quot;%s&quot; aus Warenkorb entfernen';
$lang['de_DE']['Cart']['REMOVEONE'] = '1 &quot;%s&quot; aus dem Warenkorb entfernen';
$lang['de_DE']['Cart']['SHIPPING'] = 'Versandkosten';
$lang['de_DE']['Cart']['SUBTOTAL'] = 'Zwischensumme';
$lang['de_DE']['Cart']['TOTAL'] = 'Summe';
$lang['de_DE']['Checkout']['NOPAGE'] = 'Auf dieser Site existiert keine Kasse-Seite - bitte erstellen Sie eine neue Seite!';
$lang['de_DE']['Checkout']['CHECKOUT'] = 'Kasse';
$lang['de_DE']['Checkout']['ORDERSTATUS'] = 'Bestellstatus';
$lang['de_DE']['Checkout']['PROCESS'] = 'Bestellvorgang';
$lang['de_DE']['Checkout']['ORDERSTEP'] = 'Bestellstatus';
//german defaults for CheckoutPage, after run dev/build
$lang['de_DE']['Checkout']['PURCHASECOMPLETE'] = 'Ihre Bestellung ist vollständig.';
$lang['de_DE']['Checkout']['CHEQUEMESSAGE'] = 'Bitte beachten: Der Versand der Produkte erfolgt erst nach Zahlungseingang.';
$lang['de_DE']['Checkout']['ALREADYCOMPLETEDMESSAGE'] = 'Diese Bestellung wurde bereits abgeschloßen.';
$lang['de_DE']['Checkout']['FINALIZEDORDERLINKLABEL'] = 'Abgeschloßene Bestellung ansehen:';
$lang['de_DE']['Checkout']['CURRENTORDERLINKLABEL'] = 'Aktuelle Bestellung ansehen:';
$lang['de_DE']['Checkout']['STARTNEWDORDERLINKLABEL'] = 'Neue Bestellung';
$lang['de_DE']['Checkout']['NONEXISTINGORDERMESSAGE'] = 'Diese Bestellung existiert nicht.';
$lang['de_DE']['Checkout']['NONITEMSINORDERMESSAGE'] = 'Es sind keine Artikel in dieser Bestellung.';
$lang['de_DE']['Checkout']['MUSTLOGINTOCHECKOUTMESSAGE'] = 'Sie müssen eingeloggt sein, um die Bestellung anzusehen.';
$lang['de_DE']['Checkout']['LOGINTOORDERLINKLABEL'] = 'Einloggen und Bestellung ansehen.';
$lang['de_DE']['ChequePayment']['MESSAGE'] = 'Bezahlung per Scheck (Vorkasse). Bitte beachten: Der Versand der Produkte erfolgt erst nach Zahlungseingang.';
$lang['de_DE']['EcommerceRole']['PERSONALINFORMATION'] = 'Ihre Daten';
$lang['de_DE']['EcommerceRole']['COUNTRY'] = 'Land';
$lang['de_DE']['EcommerceRole']['FIRSTNAME'] = 'Vorname';
$lang['de_DE']['EcommerceRole']['SURNAME'] = 'Nachname';
$lang['de_DE']['EcommerceRole']['PHONE'] = 'Tel.';
$lang['de_DE']['EcommerceRole']['MOBILEPHONE'] = 'Mobil';
$lang['de_DE']['EcommerceRole']['EMAIL'] = 'Email';
$lang['de_DE']['EcommerceRole']['ADDRESS'] = 'Adresse';
$lang['de_DE']['EcommerceRole']['ADDRESSLINE2'] = '&nbsp;';
$lang['de_DE']['EcommerceRole']['CITY'] = 'Stadt';
$lang['de_DE']['EcommerceRole']['POSTALCODE'] = 'PLZ';
$lang['de_DE']['Order']['ACCOUNTINFO'] = 'Bitte wählen Sie ein Passwort, damit Sie sich zukünftig einloggen können und Ihre Bestellhistorie anschauen können.';
$lang['de_DE']['Order']['ADDRESS'] = 'Adresse';
$lang['de_DE']['Order']['ADDRESS2'] = '&nbsp;';
$lang['de_DE']['Order']['AGREEWITHTERMS1'] = 'Ich habe die Informationen zur Bestellung und die ';
$lang['de_DE']['Order']['AGREEWITHTERMS2'] = ' gelesen und verstanden.';
$lang['de_DE']['Order']['AMOUNT'] = 'Betrag';
$lang['de_DE']['Order']['CANCELORDER'] = 'Bestellung stornieren';
$lang['de_DE']['Order']['CITY'] = 'Stadt';
$lang['de_DE']['Order']['COMPLETEORDER'] = 'Bestellung abschließen';
$lang['de_DE']['Order']['COUNTRY'] = 'Land';
$lang['de_DE']['Order']['CUSTOMERORDERNOTE'] = 'Kunden Bemerkung';
$lang['de_DE']['Order']['CUSTOMERNOTE'] = 'Bemerkungen';
$lang['de_DE']['Order']['DATE'] = 'Datum';
$lang['de_DE']['Order']['ERRORINFORM'] = 'Es ist ein Fehler aufgetreten. Wir konnten Ihre Bestellung nicht ausführen!';
$lang['de_DE']['Order']['LOGIN'] = 'Loggen Sie sich ein.';
$lang['de_DE']['Order']['MAKEPAYMENT'] = 'Zahlart auswählen';
$lang['de_DE']['Order']['MEMBEREXISTS'] = 'Ein Konto mit dieser E-Mail Adresse gibt es bereits. Wenn das Ihre E-mail Adresse ist, loggen Sie sich bitte ein, um Ihre Bestellung abzuschließen.';
$lang['de_DE']['Order']['MEMBERINFO'] = 'Haben Sie bereits ein Kunden-Konto?';
$lang['de_DE']['Order']['MEMBERSHIPDETAILS'] = 'Kunden-Konto Details';
$lang['de_DE']['Order']['NAME'] = 'Name';
$lang['de_DE']['Order']['NOITEMS'] = 'Ihre Bestellung weist <strong>keine</strong> Artikel auf';
$lang['de_DE']['Order']['NOITEMSINCART'] = 'Sie haben keine Produkte ausgewählt. Bitte legen Sie Produkte in den Warenkorb.';
$lang['de_DE']['Order']['NOPAYMENTS'] = 'Es gibt keine Zahlungen für diese Bestellung.';
$lang['de_DE']['Order']['ORDER'] = 'Bestellung';
$lang['de_DE']['Order']['ORDERHASBEENCANCELLED'] = 'Bestellung wurde storniert!';
$lang['de_DE']['Order']['ORDERINFORMATION'] = 'Bestellinformationen';
$lang['de_DE']['Order']['PASSWORD'] = 'Passwort';
$lang['de_DE']['Order']['PAYMENTMETHOD'] = 'Zahlart';
$lang['de_DE']['Order']['PAYMENTSTATUS'] = 'Bezahlstatus';
$lang['de_DE']['Order']['PAYMENTS'] = 'Zahlart';
$lang['de_DE']['Order']['PAYMENTNOTE'] = 'Anmerkung';
$lang['de_DE']['Order']['PAYORDER'] = 'Bestellung bezahlen';
$lang['de_DE']['Order']['PRICE'] = 'Preis';
$lang['de_DE']['Order']['PRICEUPDATED'] = 'Die Bestellsumme wurde aktualisiert.';
$lang['de_DE']['Order']['PROCESSORDER'] = 'Bestellung ausführen';
$lang['de_DE']['Order']['PRODUCT'] = 'Produkt';
$lang['de_DE']['Order']['PURCHASEDBY'] = 'Rechnungsadresse';
$lang['de_DE']['Order']['QUANTITY'] = 'Menge';
$lang['de_DE']['Order']['READMORE'] = 'Wenn zu mehr über &quot;%s&quot; erfahren willst, klick hier';
$lang['de_DE']['Order']['READTERMSANDCONDITIONS'] = 'Bitte lesen und bestätigen Sie die AGB!';
$lang['de_DE']['Order']['REMOVE'] = '&quot;%s&quot; aus Ihrem Warenkorb entfernen';
$lang['de_DE']['Order']['REMOVEALL'] = '&quot;%s&quot; komplett aus dem Warenkorb entfernen';
$lang['de_DE']['Order']['SENDGOODSTODIFFERENTADDRESS'] = 'Abweichende Lieferadresse';
$lang['de_DE']['Order']['SHIPTO'] = 'Lieferadresse (falls abweichend)';
$lang['de_DE']['Order']['SHIPPING'] = 'Versandkosten';
$lang['de_DE']['Order']['SHIPPINGHELP'] = 'Sie können dies für Geschenke benutzen. Es werden keine Rechnungsinformationen mit versandt.';
$lang['de_DE']['Order']['SHIPPINGNOTE'] = 'Die Bestellung wird an folgende Adresse versendet.';
$lang['de_DE']['Order']['SHIPPINGTO'] = 'an';
$lang['de_DE']['Order']['SHIPPINGPOSTALCODE'] = 'PLZ';
$lang['de_DE']['Order']['SUBTOTAL'] = 'Zwischensumme';
$lang['de_DE']['Order']['TABLESUMMARY'] = 'Hier werden alle Artikel im Warenkorb und eine Zusammenfassung aller für die Bestellung anfallender Gebühren angezeigt. Außerdem wird ein Überblick aller Zahlungsmöglichkeiten angezeigt.';
$lang['de_DE']['Order']['TOTAL'] = 'Gesamt';
$lang['de_DE']['Order']['TOTALPRICE'] = 'Gesamtpreis';
$lang['de_DE']['Order']['TOTALOUTSTANDING'] = 'Gesamt ausstehend';
$lang['de_DE']['Order']['USEDIFFERENTSHIPPINGADDRESS'] = 'andere Lieferadresse wählen';
$lang['de_DE']['OrderReceiptEmail.ss']['HEADLINE'] = 'Auftragsbestätigung';
$lang['de_DE']['OrderReceiptEmail.ss']['TITLE'] = 'Shop Empfangsbestätigung';
$lang['de_DE']['OrderStatusEmail.ss']['HEADLINE'] = 'Shop-Status Änderung';
$lang['de_DE']['OrderStatusEmail.ss']['STATUSCHANGE'] = 'Status geändert zu "%s" für Bestellung Nr.';
$lang['de_DE']['OrderStatusEmail.ss']['TITLE'] = 'Shop-Status Änderung';
//TODO the Payment translation should actually go in the Payment module
$lang['de_DE']['Payment']['AMOUNT'] = 'Gesamt';
$lang['de_DE']['Payment']['Incomplete'] = 'Unvollständig';
$lang['de_DE']['Payment']['Success'] = 'Erfolg';
$lang['de_DE']['Payment']['Failure'] = 'Misserfolg';
$lang['de_DE']['Payment']['Pending'] = 'Warteschleife';
$lang['de_DE']['Payment']['Paid'] = 'Bezahlt';
$lang['de_DE']['Product']['ADD'] = '&quot;%s&quot; zum Warenkorb hinzufügen';
$lang['de_DE']['Product']['ADDLINK'] = 'Diesen Artikel zum Warenkorb hinzufügen';
$lang['de_DE']['Product']['ADDONE'] = '&quot;%s&quot; zum Warenkorb hinzufügen';
$lang['de_DE']['Product']['ADDVARIATIONSLINK'] = 'Varianten anzeigen';
$lang['de_DE']['Product']['AUTHOR'] = 'Autor';
$lang['de_DE']['Product']['FEATURED'] = 'Wir empfehlen diesen Artikel.';
$lang['de_DE']['Product']['GOTOPAGE'] = 'Zur %s Seite';
$lang['de_DE']['Product']['GOTOCHECKOUT'] = 'Jetzt zur Kasse gehen';
$lang['de_DE']['Product']['GOTOCHECKOUTLINK'] = '&#187; Zur Kasse';
$lang['de_DE']['Product']['IMAGE'] = '%s Bild';
$lang['de_DE']['Product']['ITEMID'] = 'Artikel Nr.';
$lang['de_DE']['Product']['MODEL'] = 'Typ';
$lang['de_DE']['Product']['NOIMAGE'] = 'Keine Produktabbildung vorhanden für &quot;%s&quot;';
$lang['de_DE']['Product']['QUANTITYCART'] = 'Menge im Warenkorb';
$lang['de_DE']['Product']['QUANTITY'] = 'Menge';
$lang['de_DE']['Product']['REMOVE'] = '&quot;%s&quot; aus dem Warenkorb entfernen';
$lang['de_DE']['Product']['REMOVEALL'] = '&quot;%s&quot; aus dem Warenkorb entfernen';
$lang['de_DE']['Product']['REMOVELINK'] = '&#187; aus dem Warenkorb entfernen';
$lang['de_DE']['Product']['REMOVEONE'] = '1 Einheit von &quot;%s&quot; aus dem Warenkorb entferrnen';
$lang['de_DE']['Product']['READMORE'] = 'Erfahren Sie hier mehr über &quot;%s&quot;';
$lang['de_DE']['Product']['READMORECONTENT'] = 'mehr &#187;';
$lang['de_DE']['Product']['SIZE'] = 'Größe';
$lang['de_DE']['ProductGroup']['GOTOPAGE'] = 'Gehe zu Seite %s';
$lang['de_DE']['ProductGroup']['OTHER'] = 'Andere Produkte';
$lang['de_DE']['ProductGroup']['PREVIOUS'] = 'Vorherige';
$lang['de_DE']['ProductGroup']['SORTBY'] = 'Sortieren nach';
$lang['de_DE']['ProductGroup']['SORTBYALPHABETICAL'] = 'Alphabet';
$lang['de_DE']['ProductGroup']['SORTBYFEATURED'] = 'Empfohlene Artikel';
$lang['de_DE']['ProductGroup']['SORTBYLOWESTPRICE'] = 'billigstem Preis';
$lang['de_DE']['ProductGroup']['SORTBYMOSTPOPULAR'] = 'Beliebtheit';
$lang['de_DE']['ProductGroup']['SHOWNEXTPAGE'] = 'Nächste Seite';
$lang['de_DE']['ProductGroup']['SHOWPREVIOUSPAGE'] = 'Vorherige Seite';
$lang['de_DE']['ProductGroup']['VIEWGROUP'] = 'Produktgruppe &quot;%s&quot; anzeigen';
$lang['de_DE']['Report']['ALLCLICKHERE'] = 'Klicken Sie hier, um alle Produkte anzuzeigen';
$lang['de_DE']['Report']['INVOICE'] = 'Rechnung';
$lang['de_DE']['Report']['PRINT'] = 'drucken';
$lang['de_DE']['Report']['VIEW'] = 'anzeigen';
$lang['de_DE']['Report']['EXPORTCSV'] = 'Export als CSV';
$lang['de_DE']['Report']['DATERANGE'] = 'Zeitraum';
$lang['de_DE']['EcommerceSideReport']['FEATUREDPRODUCTS'] = 'Vorhandene Produkte';
$lang['de_DE']['EcommerceSideReport']['ECOMMERCEGROUP'] = 'Ecommerce';
$lang['de_DE']['EcommerceSideReport']['ALLPRODUCTS'] = 'Alle Produkte';
$lang['de_DE']['EcommerceSideReport']['NOIMAGE'] = 'Produkte ohne Bilder';
