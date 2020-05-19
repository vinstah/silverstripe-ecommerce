<?php

use SilverStripe\i18n\i18n;
use Sunnysideup\Ecommerce\Model\Order;
use Sunnysideup\Ecommerce\Pages\AccountPage;
use Sunnysideup\Ecommerce\Pages\CheckoutPage;

/**
 * Norwegian Bokmal (Norway) language pack.
 */
i18n::include_locale_file('modules: ecommerce', 'en_US');

global $lang;

if (array_key_exists('nb_NO', $lang) && is_array($lang['nb_NO'])) {
    $lang['nb_NO'] = array_merge($lang['en_US'], $lang['nb_NO']);
} else {
    $lang['nb_NO'] = $lang['en_US'];
}

$lang['nb_NO'][AccountPage::class]['Message'] = 'Du må logge inn for å få tilgang til denne kontosiden. Hvis du er registrert, vil du ikke kunne få tilgang til denne før du har opprettet din første ordre. Ellers tast inn detaljene nedenunder.';
$lang['nb_NO'][AccountPage::class]['NOPAGE'] = 'Ingen kontoside på denne nettsiden, vennligst opprett en!';
$lang['nb_NO']['AccountPage.ss']['COMPLETED'] = 'Fullførte bestillinger';
$lang['nb_NO']['AccountPage.ss']['HISTORY'] = 'Din ordrehistorikk';
$lang['nb_NO']['AccountPage.ss']['INCOMPLETE'] = 'Ukomplette Ordre';
$lang['nb_NO']['AccountPage.ss']['Message'] = 'Vennligst skriv inn dine detaljer for å logge på oppgjørssiden.<br />Denne siden er bare tilgjengelig etter din første ordre, når du har fått tildelt passord.';
$lang['nb_NO']['AccountPage.ss']['NOCOMPLETED'] = 'Ingen komplette ordre ble funnet';
$lang['nb_NO']['AccountPage.ss']['NOINCOMPLETE'] = 'Ingen ukomplette ordre ble funnet';
$lang['nb_NO']['AccountPage.ss']['ORDER'] = 'Bestilling #';
$lang['nb_NO']['AccountPage.ss']['READMORE'] = 'Les mer på Ordre #%s';
$lang['nb_NO']['AccountPage_order.ss']['ADDRESS'] = 'Adresse';
$lang['nb_NO']['AccountPage_order.ss']['AMOUNT'] = 'Beløp';
$lang['nb_NO']['AccountPage_order.ss']['BACKTOCHECKOUT'] = 'klikk her for å gå til kasse';
$lang['nb_NO']['AccountPage_order.ss']['CITY'] = 'By';
$lang['nb_NO']['AccountPage_order.ss']['COUNTRY'] = 'Land';
$lang['nb_NO']['AccountPage_order.ss']['DATE'] = 'Dato';
$lang['nb_NO']['AccountPage_order.ss']['DETAILS'] = 'Detaljer';
$lang['nb_NO']['AccountPage_order.ss']['EMAILDETAILS'] = 'En kopi av denne er sendt til din epost adresse for å bekrefte bestillingens innhold.';
$lang['nb_NO']['AccountPage_order.ss']['NAME'] = 'Navn';
$lang['nb_NO']['AccountPage_order.ss']['PAYMENTMETHOD'] = 'Metode';
$lang['nb_NO']['AccountPage_order.ss']['PAYMENTSTATUS'] = 'Betalingsstatus';
$lang['nb_NO']['Cart.ss']['ADDONE'] = 'Legg til en mer av &quot;%s&quot; til handlekurven.';
$lang['nb_NO']['Cart.ss']['CheckoutClick'] = 'Klikk her for å gå til kassen';
$lang['nb_NO']['Cart.ss']['CheckoutGoTo'] = 'Gå til kassen';
$lang['nb_NO']['Cart.ss']['HEADLINE'] = 'Min handlevogn';
$lang['nb_NO']['Cart.ss']['NOITEMS'] = 'Handlevognen er tom';
$lang['nb_NO']['Cart.ss']['PRICE'] = 'Pris';
$lang['nb_NO']['Cart.ss']['READMORE'] = 'Klikk her for å lese mer om  &quot;%s&quot;';
$lang['nb_NO']['Cart.ss']['Remove'] = 'Fjern &quot;%s&quot; fra handlevognen';
$lang['nb_NO']['Cart.ss']['REMOVE'] = 'Fjern &quot;%s&quot; fra din bestilling
';
$lang['nb_NO']['Cart.ss']['REMOVEALL'] = 'Fjern alle av &quot;%s&quot; fra handlekurven';
$lang['nb_NO']['Cart.ss']['RemoveAlt'] = 'Fjern';
$lang['nb_NO']['Cart.ss']['REMOVEONE'] = 'Fjern en av &quot;%s&quot; fra handlekurven';
$lang['nb_NO']['Cart.ss']['SHIPPING'] = 'Frakt';
$lang['nb_NO']['Cart.ss']['SUBTOTAL'] = 'Delsum';
$lang['nb_NO']['Cart.ss']['TOTAL'] = 'Sum';
$lang['nb_NO'][CheckoutPage::class]['NOPAGE'] = 'Finnes ingen ChecoutPage for dette nettstedet - vennligst opprett en!';
$lang['nb_NO']['CheckoutPage.ss']['CHECKOUT'] = 'Sjekk ut';
$lang['nb_NO']['CheckoutPage.ss']['ORDERSTEP'] = 'Bestillingsstatus';
$lang['nb_NO']['CheckoutPage.ss']['PROCESS'] = 'Prosess';
$lang['nb_NO']['CheckoutPage_OrderIncomplete.ss']['BACKTOCHECKOUT'] = 'Trykk her for å gå tilbake til kassen';
$lang['nb_NO']['CheckoutPage_OrderIncomplete.ss']['CHECKOUT'] = 'Sjekk ut';
$lang['nb_NO']['CheckoutPage_OrderIncomplete.ss']['CHEQUEINSTRUCTIONS'] = 'Hvis du handlet med sjekk, vil du motta en epost med instruksjoner.';
$lang['nb_NO']['CheckoutPage_OrderIncomplete.ss']['DETAILSSUBMITTED'] = 'Her er detaljene du sendte inn';
$lang['nb_NO']['CheckoutPage_OrderIncomplete.ss']['INCOMPLETE'] = 'Ordre ikke ferdig';
$lang['nb_NO']['CheckoutPage_OrderIncomplete.ss']['ORDERSTEP'] = 'Bestillingsstatus';
$lang['nb_NO']['CheckoutPage_OrderIncomplete.ss']['PROCESS'] = 'Prosess';
$lang['nb_NO']['CheckoutPage_OrderSuccessful.ss']['BACKTOCHECKOUT'] = 'Trykk her for å gå tilbake til kassen';
$lang['nb_NO']['CheckoutPage_OrderSuccessful.ss']['CHECKOUT'] = 'Sjekk ut';
$lang['nb_NO']['CheckoutPage_OrderSuccessful.ss']['EMAILDETAILS'] = 'En kopi av denne har blitt sendt til din epostadresse, som bekrefter alle ordredetaljer';
$lang['nb_NO']['CheckoutPage_OrderSuccessful.ss']['ORDERSTEP'] = 'Ordrestatus';
$lang['nb_NO']['CheckoutPage_OrderSuccessful.ss']['PROCESS'] = 'Prosess';
$lang['nb_NO']['CheckoutPage_OrderSuccessful.ss']['SUCCESSFULl'] = 'Ordre Vellykket';
$lang['nb_NO']['ChequePayment']['MESSAGE'] = 'Betalingen er akspetert med sjekk. Merk: varene vil ikke bli sendt før betalingen er motatt';
$lang['nb_NO']['DataReport']['EXPORTCSV'] = 'Eksporter til CSV';
$lang['nb_NO']['FindOrderReport']['DATERANGE'] = 'Datoområde';
$lang['nb_NO']['MemberForm']['DETAILSSAVED'] = 'Detaljene er lagret';
$lang['nb_NO']['MemberForm']['LOGGEDIN'] = 'Du er logget inn som';
$lang['nb_NO'][Order::class]['INCOMPLETE'] = 'Ordre Ufullkommen';
$lang['nb_NO'][Order::class]['SUCCESSFULL'] = 'Ordre Vellykket';
$lang['nb_NO']['OrderInformation.ss']['ADDRESS'] = 'Adresse';
$lang['nb_NO']['OrderInformation.ss']['AMOUNT'] = 'Beløp';
$lang['nb_NO']['OrderInformation.ss']['BUYERSADDRESS'] = 'Kundeadresse';
$lang['nb_NO']['OrderInformation.ss']['CITY'] = 'By';
$lang['nb_NO']['OrderInformation.ss']['COUNTRY'] = 'Land';
$lang['nb_NO']['OrderInformation.ss']['CUSTOMERDETAILS'] = 'Kundedetaljer';
$lang['nb_NO']['OrderInformation.ss']['DATE'] = 'Dato';
$lang['nb_NO']['OrderInformation.ss']['DETAILS'] = 'Detaljer';
$lang['nb_NO']['OrderInformation.ss']['EMAIL'] = 'E-postadresse';
$lang['nb_NO']['OrderInformation.ss']['MOBILE'] = 'Mobilnr.';
$lang['nb_NO']['OrderInformation.ss']['NAME'] = 'NAvn';
$lang['nb_NO']['OrderInformation.ss']['ORDERSUMMARY'] = 'Sammendrag av bestillingen';
$lang['nb_NO']['OrderInformation.ss']['PAYMENTID'] = 'Betalings-ID';
$lang['nb_NO']['OrderInformation.ss']['PAYMENTINFORMATION'] = 'Betalingsinformasjon';
$lang['nb_NO']['OrderInformation.ss']['PAYMENTMETHOD'] = 'Metode';
$lang['nb_NO']['OrderInformation.ss']['PAYMENTSTATUS'] = 'Betalingsstatus';
$lang['nb_NO']['OrderInformation.ss']['PHONE'] = 'Telefonnr.';
$lang['nb_NO']['OrderInformation.ss']['PRICE'] = 'Pris';
$lang['nb_NO']['OrderInformation.ss']['PRODUCT'] = 'Produkt';
$lang['nb_NO']['OrderInformation.ss']['QUANTITY'] = 'Antall';
$lang['nb_NO']['OrderInformation.ss']['READMORE'] = 'Klikk her for å lese mer om &quot;%s&quot;';
$lang['nb_NO']['OrderInformation.ss']['SHIPPING'] = 'Frakt';
$lang['nb_NO']['OrderInformation.ss']['SHIPPINGDETAILS'] = 'Fraktdetaljer';
$lang['nb_NO']['OrderInformation.ss']['SHIPPINGTO'] = 'til';
$lang['nb_NO']['OrderInformation.ss']['SUBTOTAL'] = 'Delsum';
$lang['nb_NO']['OrderInformation.ss']['TABLESUMMARY'] = 'Innholdet i handlevognen blir vist i dette skjemaet sammen med et sammendrag av avgiftene i forbindelse med en bestilling og et sammendrag av betalingsmulighetene.';
$lang['nb_NO']['OrderInformation.ss']['TOTAL'] = 'Total';
$lang['nb_NO']['OrderInformation.ss']['TOTALl'] = 'Sum';
$lang['nb_NO']['OrderInformation.ss']['TOTALOUTSTANDING'] = 'Totalt utestående';
$lang['nb_NO']['OrderInformation.ss']['TOTALPRICE'] = 'Totalpris';
$lang['nb_NO']['OrderInformation_Editable.ss']['ADDONE'] = 'Legg til en til av &quot;%s&quot; til handelvognen';
$lang['nb_NO']['OrderInformation_Editable.ss']['NOITEMS'] = 'Det er <strong>ingenting</strong> i handlekurven.';
$lang['nb_NO']['OrderInformation_Editable.ss']['ORDERINFORMATION'] = 'Bestillingsinformasjon';
$lang['nb_NO']['OrderInformation_Editable.ss']['PRICE'] = 'Pris';
$lang['nb_NO']['OrderInformation_Editable.ss']['PRODUCT'] = 'Produkt';
$lang['nb_NO']['OrderInformation_Editable.ss']['QUANTITY'] = 'Antall';
$lang['nb_NO']['OrderInformation_Editable.ss']['READMORE'] = 'Klikk her for å lese mer om &quot;%s&quot;';
$lang['nb_NO']['OrderInformation_Editable.ss']['REMOVE'] = 'Fjern &quot;%s&quot; fra din bestilling';
$lang['nb_NO']['OrderInformation_Editable.ss']['REMOVEALL'] = 'Fjern alle &quot;%s&quot; fra din handlevogn';
$lang['nb_NO']['OrderInformation_Editable.ss']['REMOVEONE'] = 'Fjern en av &quot;%s&quot; fra handlevognen';
$lang['nb_NO']['OrderInformation_Editable.ss']['SHIPPING'] = 'Frakt';
$lang['nb_NO']['OrderInformation_Editable.ss']['SHIPPINGTO'] = 'til';
$lang['nb_NO']['OrderInformation_Editable.ss']['SUBTOTAL'] = 'Delsum';
$lang['nb_NO']['OrderInformation_Editable.ss']['TABLESUMMARY'] = 'Innholdet i handlekurven vises i denne seksjonen pluss oppsummering av alle avgifter og valg assossiert med en ordre.';
$lang['nb_NO']['OrderInformation_Editable.ss']['TOTAL'] = 'Sum';
$lang['nb_NO']['OrderInformation_Editable.ss']['TOTALPRICE'] = 'Totalpris';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['ADDRESS'] = 'Adresse';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['BUYERSADDRESS'] = 'Kjøpers adresse';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['CITY'] = 'By';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['COUNTRY'] = 'Land';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['CUSTOMERDETAILS'] = 'Kundedetaljer';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['EMAIL'] = 'E-postadresse';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['MOBILE'] = 'Mobilnummer';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['NAME'] = 'Navn';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['ORDERINFO'] = 'Informasjon om bestilling #';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['PHONE'] = 'Telefonnummer';
$lang['nb_NO']['OrderInformation_NoPricing.ss']['TABLESUMMARY'] = 'Innholdet i handlekurven vises i denne seksjonen pluss oppsummering av alle avgifter og valg assossiert med en ordre.';
$lang['nb_NO']['OrderInformation_PackingSlip.ss']['DESCRIPTION'] = 'Beskrivelse';
$lang['nb_NO']['OrderInformation_PackingSlip.ss']['ITEM'] = 'Enhet';
$lang['nb_NO']['OrderInformation_PackingSlip.ss']['ORDERDATE'] = 'Bestillingsdato';
$lang['nb_NO']['OrderInformation_PackingSlip.ss']['ORDERNUMBER'] = 'Bestillingsnummer';
$lang['nb_NO']['OrderInformation_PackingSlip.ss']['PAGETITLE'] = 'Butikk Print Ordre';
$lang['nb_NO']['OrderInformation_PackingSlip.ss']['QUANTITY'] = 'Antall';
$lang['nb_NO']['OrderInformation_PackingSlip.ss']['TABLESUMMARY'] = 'Innholdet i handlekurven vises i denne seksjonen pluss oppsummering av alle avgifter og valg assossiert med en ordre.';
$lang['nb_NO']['OrderInformation_Print.ss']['PAGETITLE'] = 'Skriv ut bestillinger';
$lang['nb_NO']['OrderReport']['CHANGESTATUS'] = 'Endre bestillingsstatus';
$lang['nb_NO']['OrderReport']['NOTEEMAIL'] = 'Merknad/e-post';
$lang['nb_NO']['OrderReport']['PRINTEACHORDER'] = 'Skriv ut alle bestillinger';
$lang['nb_NO']['OrderReport']['SENDNOTETO'] = 'Send merknaden til %s (%s)';
$lang['nb_NO']['Order_Content.ss']['NOITEMS'] = 'Det er <strong>ingen</strong>varer i din bestilling';
$lang['nb_NO']['Order_Content.ss']['PRICE'] = 'Pris';
$lang['nb_NO']['Order_Content.ss']['PRODUCT'] = 'Produkt';
$lang['nb_NO']['Order_Content.ss']['QUANTITY'] = 'Antall';
$lang['nb_NO']['Order_Content.ss']['READMORE'] = 'Klikk her for å lese mere på &quot;%s&quot;';
$lang['nb_NO']['Order_Content.ss']['SUBTOTAL'] = 'Sub-total';
$lang['nb_NO']['Order_Content.ss']['TOTAL'] = 'Total';
$lang['nb_NO']['Order_Content.ss']['TOTALOUTSTANDING'] = 'Totalt utestående';
$lang['nb_NO']['Order_Content.ss']['TOTALPRICE'] = 'Samlet pris';
$lang['nb_NO']['Order_Member.ss']['ADDRESS'] = 'Adresse';
$lang['nb_NO']['Order_Member.ss']['CITY'] = 'By';
$lang['nb_NO']['Order_Member.ss']['COUNTRY'] = 'Land';
$lang['nb_NO']['Order_Member.ss']['EMAIL'] = 'Epost';
$lang['nb_NO']['Order_Member.ss']['MOBILE'] = 'Mobil';
$lang['nb_NO']['Order_Member.ss']['NAME'] = 'Navn';
$lang['nb_NO']['Order_Member.ss']['PHONE'] = 'Telefon';
$lang['nb_NO']['Order_receiptEmail.ss']['HEADLINE'] = 'Ordrekvittering';
$lang['nb_NO']['OrderReceiptEmail.ss']['HEADLINE'] = 'Bestillingskvittering';
$lang['nb_NO']['Order_receiptEmail.ss']['TITLE'] = 'Kvittering';
$lang['nb_NO']['OrderReceiptEmail.ss']['TITLE'] = 'Handle kvittering';
$lang['nb_NO']['Order_statusEmail.ss']['HEADLINE'] = 'Endring i butikkstatus';
$lang['nb_NO']['OrderStatusEmail.ss']['HEADLINE'] = 'Handlestatus endres';
$lang['nb_NO']['Order_statusEmail.ss']['STATUSCHANGE'] = 'Status endret til "%s" for Ordre #';
$lang['nb_NO']['OrderStatusEmail.ss']['STATUSCHANGE'] = 'Status endret til "%s" for bestilling #';
$lang['nb_NO']['Order_statusEmail.ss']['TITLE'] = 'Endring i butikkstatus';
$lang['nb_NO']['OrderStatusEmail.ss']['TITLE'] = 'Handlestatus endres';
$lang['nb_NO']['PaymentInformation.ss']['AMOUNT'] = 'Beløp';
$lang['nb_NO']['PaymentInformation.ss']['DATE'] = 'Dato';
$lang['nb_NO']['PaymentInformation.ss']['DETAILS'] = 'Detaljer';
$lang['nb_NO']['PaymentInformation.ss']['PAYMENTID'] = 'Betalings-ID';
$lang['nb_NO']['PaymentInformation.ss']['PAYMENTINFORMATION'] = 'Betalingsinformasjon';
$lang['nb_NO']['PaymentInformation.ss']['PAYMENTMETHOD'] = 'Metode';
$lang['nb_NO']['PaymentInformation.ss']['PAYMENTSTATUS'] = 'Betalingsstatus';
$lang['nb_NO']['PaymentInformation.ss']['TABLESUMMARY'] = 'Innholdet i handlekurven vises i denne seksjonen pluss oppsummering av alle avgifter og valg assossiert med en ordre.';
$lang['nb_NO']['Product.ss']['ADD'] = 'Legg til &quot;%s&quot; til din handlekurv';
$lang['nb_NO']['Product.ss']['ADDLINK'] = 'Legg i handlekurv';
$lang['nb_NO']['Product.ss']['ADDONE'] = 'Legg til en mer av &quot;%s&quot; til din handlekurv';
$lang['nb_NO']['Product.ss']['AUTHOR'] = 'Forfatter';
$lang['nb_NO']['Product.ss']['FEATURED'] = 'Dette er et anbefalt produkt';
$lang['nb_NO']['Product.ss']['GOTOCHECKOUT'] = 'Gå til kassen';
$lang['nb_NO']['Product.ss']['GOTOCHECKOUTLINK'] = '&#187; Gå til kassen';
$lang['nb_NO']['Product.ss']['IMAGE'] = '%s bilde';
$lang['nb_NO']['Product.ss']['ItemID'] = 'Vare #';
$lang['nb_NO']['Product.ss']['NOIMAGE'] = 'Beklager, ingen produktbilde for &quot;%s&quot;';
$lang['nb_NO']['Product.ss']['QUANTITYCART'] = 'Antall i handlekurv';
$lang['nb_NO']['Product.ss']['REMOVE'] = 'Fjern &quot;%s&quot; fra din handlekurv';
$lang['nb_NO']['Product.ss']['REMOVEALL'] = 'Fjern en av &quot;%s&quot; fra din handlekurv';
$lang['nb_NO']['Product.ss']['REMOVELINK'] = '&#187; Fjern fra handlekurv';
$lang['nb_NO']['Product.ss']['SIZE'] = 'Størrelse';
$lang['nb_NO']['ProductGroup.ss']['FEATURED'] = 'Anbefalte produkter';
$lang['nb_NO']['ProductGroup.ss']['OTHER'] = 'Andre produkter';
$lang['nb_NO']['ProductGroup.ss']['VIEWGROUP'] = 'Vis produktgruppen &quot;%s&quot;';
$lang['nb_NO']['ProductGroupItem.ss']['ADD'] = 'Legg &quot;%s&quot; i handlekurven';
$lang['nb_NO']['ProductGroupItem.ss']['ADDLINK'] = 'Legg i handlevognen';
$lang['nb_NO']['ProductGroupItem.ss']['ADDONE'] = 'Legg til en til av &quot;%s&quot; i handlevognen';
$lang['nb_NO']['ProductGroupItem.ss']['AUTHOR'] = 'Forfatter';
$lang['nb_NO']['ProductGroupItem.ss']['GOTOCHECKOUT'] = 'Gå til kassa nå';
$lang['nb_NO']['ProductGroupItem.ss']['GOTOCHECKOUTLINK'] = '&#187; Gå til kassa';
$lang['nb_NO']['ProductGroupItem.ss']['IMAGE'] = '%s bilde';
$lang['nb_NO']['ProductGroupItem.ss']['NOIMAGE'] = 'Beklager, det finnes ikke noe bilde av &quot;%s&quot;';
$lang['nb_NO']['ProductGroupItem.ss']['QUANTITY'] = 'Antall';
$lang['nb_NO']['ProductGroupItem.ss']['QUANTITYCART'] = 'Antall i handlevogn';
$lang['nb_NO']['ProductGroupItem.ss']['READMORE'] = 'Trykk her for å lese mer om &quot;%s&quot;';
$lang['nb_NO']['ProductGroupItem.ss']['READMORECONTENT'] = 'Trykk her for å lese mer &#187;';
$lang['nb_NO']['ProductGroupItem.ss']['REMOVE'] = 'Fjern &quot;%s&quot; fra handlekurven';
$lang['nb_NO']['ProductGroupItem.ss']['REMOVEALL'] = 'Fjern en &quot;%s&quot; fra din handlevogn';
$lang['nb_NO']['ProductGroupItem.ss']['REMOVELINK'] = '&#187; Fjern fra handlevognen';
$lang['nb_NO']['ProductGroupItem.ss']['REMOVEONE'] = 'Fjern en av &quot;%s&quot; fra handlevognen';
$lang['nb_NO']['ProductMenu.ss']['GOTOPAGE'] = 'Gå til %s';
$lang['nb_NO']['SSReport']['ALLCLICKHERE'] = 'Klikk her for å se alle produktene';
$lang['nb_NO']['SSReport']['INVOICE'] = 'Faktura';
$lang['nb_NO']['SSReport']['PRINT'] = 'Skriv ut';
$lang['nb_NO']['SSReport']['VIEW'] = 'Vis';
$lang['nb_NO']['ViewAllProducts.ss']['AUTHOR'] = 'Forfatter';
$lang['nb_NO']['ViewAllProducts.ss']['CATEGORIES'] = 'Kategorier';
$lang['nb_NO']['ViewAllProducts.ss']['IMAGE'] = '%s bilde';
$lang['nb_NO']['ViewAllProducts.ss']['LASTEDIT'] = 'Sist redigert';
$lang['nb_NO']['ViewAllProducts.ss']['LINK'] = 'Lenke';
$lang['nb_NO']['ViewAllProducts.ss']['NOCONTENT'] = 'Ingen innhold satt';
$lang['nb_NO']['ViewAllProducts.ss']['NOIMAGE'] = 'Beklager, ingen bilde for &quot;%s&quot;';
$lang['nb_NO']['ViewAllProducts.ss']['NOSUBJECTS'] = 'Ingen emner satt';
$lang['nb_NO']['ViewAllProducts.ss']['PRICE'] = 'Pris';
$lang['nb_NO']['ViewAllProducts.ss']['PRODUCTID'] = 'Produkt-ID';
$lang['nb_NO']['ViewAllProducts.ss']['WEIGHT'] = 'Vekt';
