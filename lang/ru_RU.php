<?php

/**
 * Russian (Russia) language pack.
 */
i18n::include_locale_file('modules: ecommerce', 'en_US');

global $lang;

if (array_key_exists('ru_RU', $lang) && is_array($lang['ru_RU'])) {
    $lang['ru_RU'] = array_merge($lang['en_US'], $lang['ru_RU']);
} else {
    $lang['ru_RU'] = $lang['en_US'];
}

$lang['ru_RU']['AccountPage']['Message'] = 'Вы должны авторизоваться до того, как получите доступ к странице счета. Если вы не зарегистрированы, то сможете зайти на нее только после того, как сделаете ваш первый заказ. Если зарегистрированы, введите ваши данные ниже.';
$lang['ru_RU']['AccountPage']['NOPAGE'] = 'На этом сайте нет страницы счета - пожалуйста, создайте ее!';
$lang['ru_RU']['AccountPage.ss']['COMPLETED'] = 'Выполненные заказы';
$lang['ru_RU']['AccountPage.ss']['HISTORY'] = 'История ваших заказов';
$lang['ru_RU']['AccountPage.ss']['INCOMPLETE'] = 'Невыполненные заказы';
$lang['ru_RU']['AccountPage.ss']['Message'] = 'Пожалуйста, введите ваши данные, чтобы зайти на страницу заказа.<br />Эта страница доступна только вашего после первого заказа, когда вам назначен пароль.';
$lang['ru_RU']['AccountPage.ss']['NOCOMPLETED'] = 'Выполненных заказов не найдено.';
$lang['ru_RU']['AccountPage.ss']['NOINCOMPLETE'] = 'Невыполненных заказов не найдено.';
$lang['ru_RU']['AccountPage.ss']['ORDER'] = 'Заказ #';
$lang['ru_RU']['AccountPage.ss']['READMORE'] = 'Подробнее о заказе #%s';
$lang['ru_RU']['AccountPage_order.ss']['ADDRESS'] = 'Адрес';
$lang['ru_RU']['AccountPage_order.ss']['AMOUNT'] = 'Итог';
$lang['ru_RU']['AccountPage_order.ss']['BACKTOCHECKOUT'] = 'Нажмите здесь для перхода на страницу платежа';
$lang['ru_RU']['AccountPage_order.ss']['CITY'] = 'Город';
$lang['ru_RU']['AccountPage_order.ss']['COUNTRY'] = 'Страна';
$lang['ru_RU']['AccountPage_order.ss']['DATE'] = 'Дата';
$lang['ru_RU']['AccountPage_order.ss']['DETAILS'] = 'Подробнее';
$lang['ru_RU']['AccountPage_order.ss']['EMAILDETAILS'] = 'Копия этого с подтверждением информации о заказе была выслана вам на email.';
$lang['ru_RU']['AccountPage_order.ss']['NAME'] = 'Имя';
$lang['ru_RU']['AccountPage_order.ss']['PAYMENTMETHOD'] = 'Способ оплаты';
$lang['ru_RU']['AccountPage_order.ss']['PAYMENTSTATUS'] = 'Статус платежа';
$lang['ru_RU']['Cart.ss']['ADDONE'] = 'Добавить еще одно наименование &quot;%s&quot; в вашу корзину';
$lang['ru_RU']['Cart.ss']['CheckoutClick'] = 'Нажмите здесь для подсчета стоимости';
$lang['ru_RU']['Cart.ss']['CheckoutGoTo'] = 'Перейти к подсчету стоимости';
$lang['ru_RU']['Cart.ss']['HEADLINE'] = 'Моя корзина';
$lang['ru_RU']['Cart.ss']['NOITEMS'] = 'Ваша корзина пуста';
$lang['ru_RU']['Cart.ss']['PRICE'] = 'Стоимость';
$lang['ru_RU']['Cart.ss']['READMORE'] = 'Нажмите здесь, чтобы узнать больше о &quot;%s&quot;';
$lang['ru_RU']['Cart.ss']['Remove'] = 'Убрать &quot;%s&quot; из вашей корзины';
$lang['ru_RU']['Cart.ss']['REMOVE'] = 'Удалить &laquo;%s&#187; из вашего заказа';
$lang['ru_RU']['Cart.ss']['REMOVEALL'] = 'Удалить все наименования &quot;%s&quot; из вашей корзины';
$lang['ru_RU']['Cart.ss']['RemoveAlt'] = 'Убрать';
$lang['ru_RU']['Cart.ss']['REMOVEONE'] = 'Удалить одно наименование &quot;%s&quot; из вашей корзины';
$lang['ru_RU']['Cart.ss']['SHIPPING'] = 'Доставка';
$lang['ru_RU']['Cart.ss']['SUBTOTAL'] = 'Промежуточная сумма';
$lang['ru_RU']['Cart.ss']['TOTAL'] = 'Итого';
$lang['ru_RU']['CheckoutPage']['NOPAGE'] = 'На сайте нет страницы подсчета стоимости. Пожалуйста, создайте ее!';
$lang['ru_RU']['CheckoutPage.ss']['CHECKOUT'] = 'Расчет стоимости';
$lang['ru_RU']['CheckoutPage.ss']['ORDERSTEP'] = 'Статус заказа';
$lang['ru_RU']['CheckoutPage.ss']['PROCESS'] = 'Оформить';
$lang['ru_RU']['CheckoutPage_OrderIncomplete.ss']['BACKTOCHECKOUT'] = 'Нажмите здесь для возврата к расчету стоимости';
$lang['ru_RU']['CheckoutPage_OrderIncomplete.ss']['CHECKOUT'] = 'Расчет стоимости';
$lang['ru_RU']['CheckoutPage_OrderIncomplete.ss']['CHEQUEINSTRUCTIONS'] = 'При заказе с оплатой чеком вы получите письмо с инструкциями.';
$lang['ru_RU']['CheckoutPage_OrderIncomplete.ss']['DETAILSSUBMITTED'] = 'Сводка введенных вами данных';
$lang['ru_RU']['CheckoutPage_OrderIncomplete.ss']['INCOMPLETE'] = 'Заказ не выполнен';
$lang['ru_RU']['CheckoutPage_OrderIncomplete.ss']['ORDERSTEP'] = 'Статус заказа';
$lang['ru_RU']['CheckoutPage_OrderIncomplete.ss']['PROCESS'] = 'Оформить';
$lang['ru_RU']['CheckoutPage_OrderSuccessful.ss']['BACKTOCHECKOUT'] = 'Нажмите здесь для возврата к расчету стоимости';
$lang['ru_RU']['CheckoutPage_OrderSuccessful.ss']['CHECKOUT'] = 'Расчет стоимости';
$lang['ru_RU']['CheckoutPage_OrderSuccessful.ss']['EMAILDETAILS'] = 'Данная информация с подтверждением деталей заказа была выслана вам на электр. почту.';
$lang['ru_RU']['CheckoutPage_OrderSuccessful.ss']['ORDERSTEP'] = 'Статус заказа';
$lang['ru_RU']['CheckoutPage_OrderSuccessful.ss']['PROCESS'] = 'Оформить';
$lang['ru_RU']['CheckoutPage_OrderSuccessful.ss']['SUCCESSFULl'] = 'Заказ сделан';
$lang['ru_RU']['ChequePayment']['MESSAGE'] = 'Принимается оплата чеком. Внимание: покупки будут отправлены только после получения оплаты.';
$lang['ru_RU']['DataReport']['EXPORTCSV'] = 'Экспорт в CSV';
$lang['ru_RU']['FindOrderReport']['DATERANGE'] = 'Диапазон дат';
$lang['ru_RU']['MemberForm']['DETAILSSAVED'] = 'Выши данные сохранены';
$lang['ru_RU']['MemberForm']['LOGGEDIN'] = 'Вы вошли в систему как';
$lang['ru_RU']['Order']['INCOMPLETE'] = 'Заказ незавершен';
$lang['ru_RU']['Order']['SUCCESSFULL'] = 'Заказ успешен';
$lang['ru_RU']['OrderInformation.ss']['ADDRESS'] = 'Адрес';
$lang['ru_RU']['OrderInformation.ss']['AMOUNT'] = 'Сумма';
$lang['ru_RU']['OrderInformation.ss']['BUYERSADDRESS'] = 'Адрес покупателя';
$lang['ru_RU']['OrderInformation.ss']['CITY'] = 'Город';
$lang['ru_RU']['OrderInformation.ss']['COUNTRY'] = 'Страна';
$lang['ru_RU']['OrderInformation.ss']['CUSTOMERDETAILS'] = 'Данные покупателя';
$lang['ru_RU']['OrderInformation.ss']['DATE'] = 'Дата';
$lang['ru_RU']['OrderInformation.ss']['DETAILS'] = 'Детали';
$lang['ru_RU']['OrderInformation.ss']['EMAIL'] = 'Электр. почта';
$lang['ru_RU']['OrderInformation.ss']['MOBILE'] = 'Мобильный';
$lang['ru_RU']['OrderInformation.ss']['NAME'] = 'Имя';
$lang['ru_RU']['OrderInformation.ss']['ORDERSUMMARY'] = 'Сводка заказа';
$lang['ru_RU']['OrderInformation.ss']['PAYMENTID'] = 'Идентификатор платежа';
$lang['ru_RU']['OrderInformation.ss']['PAYMENTINFORMATION'] = 'Платежная информация';
$lang['ru_RU']['OrderInformation.ss']['PAYMENTMETHOD'] = 'Способ';
$lang['ru_RU']['OrderInformation.ss']['PAYMENTSTATUS'] = 'Статус платежа';
$lang['ru_RU']['OrderInformation.ss']['PHONE'] = 'Телефон';
$lang['ru_RU']['OrderInformation.ss']['PRICE'] = 'Стоимость';
$lang['ru_RU']['OrderInformation.ss']['PRODUCT'] = 'Товар';
$lang['ru_RU']['OrderInformation.ss']['QUANTITY'] = 'Количество';
$lang['ru_RU']['OrderInformation.ss']['READMORE'] = 'Нажмите здесь для дополнительной информации о &quot;%s&quot;';
$lang['ru_RU']['OrderInformation.ss']['SHIPPING'] = 'Доставка';
$lang['ru_RU']['OrderInformation.ss']['SHIPPINGDETAILS'] = 'Информация о доставке';
$lang['ru_RU']['OrderInformation.ss']['SHIPPINGTO'] = 'для';
$lang['ru_RU']['OrderInformation.ss']['SUBTOTAL'] = 'Промежуточная сумма';
$lang['ru_RU']['OrderInformation.ss']['TABLESUMMARY'] = 'В этой форме отражены содержимое вашей корзины, список всех сборов, связанных с заказом, и сводка вариантов оплаты.';
$lang['ru_RU']['OrderInformation.ss']['TOTAL'] = 'Всего';
$lang['ru_RU']['OrderInformation.ss']['TOTALl'] = 'Итого';
$lang['ru_RU']['OrderInformation.ss']['TOTALOUTSTANDING'] = 'Итого не оплачено';
$lang['ru_RU']['OrderInformation.ss']['TOTALPRICE'] = 'Общая сумма';
$lang['ru_RU']['OrderInformation_Editable.ss']['ADDONE'] = 'Добавить ещё один экземпляр &quot;%s&quot; в вашу корзину';
$lang['ru_RU']['OrderInformation_Editable.ss']['NOITEMS'] = 'Ваша корзина <strong>пуста</strong>.';
$lang['ru_RU']['OrderInformation_Editable.ss']['ORDERINFORMATION'] = 'Информация о заказе';
$lang['ru_RU']['OrderInformation_Editable.ss']['PRICE'] = 'Стоимость';
$lang['ru_RU']['OrderInformation_Editable.ss']['PRODUCT'] = 'Товар';
$lang['ru_RU']['OrderInformation_Editable.ss']['QUANTITY'] = 'Количество';
$lang['ru_RU']['OrderInformation_Editable.ss']['READMORE'] = 'Нажмите, чтобы узнать больше о &quot;%s&quot;';
$lang['ru_RU']['OrderInformation_Editable.ss']['REMOVE'] = 'Удалить &laquo;%s&#187; из вашего заказа';
$lang['ru_RU']['OrderInformation_Editable.ss']['REMOVEALL'] = 'Удалить все наименования &quot;%s&quot; из вашей корзины';
$lang['ru_RU']['OrderInformation_Editable.ss']['REMOVEONE'] = 'Убрать один экземпляр &quot;%s&quot; из вашей корзины';
$lang['ru_RU']['OrderInformation_Editable.ss']['SHIPPING'] = 'Доставка';
$lang['ru_RU']['OrderInformation_Editable.ss']['SHIPPINGTO'] = 'для';
$lang['ru_RU']['OrderInformation_Editable.ss']['SUBTOTAL'] = 'Промежуточная сумма';
$lang['ru_RU']['OrderInformation_Editable.ss']['TABLESUMMARY'] = 'В этой форме отражены содержимое вашей корзины, список всех сборов, связанных с заказом, и сводка вариантов платежа.';
$lang['ru_RU']['OrderInformation_Editable.ss']['TOTAL'] = 'Итого';
$lang['ru_RU']['OrderInformation_Editable.ss']['TOTALPRICE'] = 'Итого';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['ADDRESS'] = 'Адрес';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['BUYERSADDRESS'] = 'Адрес покупателя';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['CITY'] = 'Город';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['COUNTRY'] = 'Страна';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['CUSTOMERDETAILS'] = 'Данные покупателя';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['EMAIL'] = 'Электр. почта';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['MOBILE'] = 'Мобильный';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['NAME'] = 'Имя';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['ORDERINFO'] = 'Информация о заказе #';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['PHONE'] = 'Телефон';
$lang['ru_RU']['OrderInformation_NoPricing.ss']['TABLESUMMARY'] = 'В этой форме отражены содержимое вашей корзины, список всех сборов, связанных с заказом, и сводка вариантов платежа.';
$lang['ru_RU']['OrderInformation_PackingSlip.ss']['DESCRIPTION'] = 'Описание';
$lang['ru_RU']['OrderInformation_PackingSlip.ss']['ITEM'] = 'Предмет';
$lang['ru_RU']['OrderInformation_PackingSlip.ss']['ORDERDATE'] = 'Дата заказа';
$lang['ru_RU']['OrderInformation_PackingSlip.ss']['ORDERNUMBER'] = 'Номер заказа';
$lang['ru_RU']['OrderInformation_PackingSlip.ss']['PAGETITLE'] = 'Печать заказов';
$lang['ru_RU']['OrderInformation_PackingSlip.ss']['QUANTITY'] = 'Количество';
$lang['ru_RU']['OrderInformation_PackingSlip.ss']['TABLESUMMARY'] = 'В этой форме отражены содержимое вашей корзины, список всех сборов, связанных с заказом, и сводка вариантов платежа.';
$lang['ru_RU']['OrderInformation_Print.ss']['PAGETITLE'] = 'Печать заказов';
$lang['ru_RU']['OrderReport']['CHANGESTATUS'] = 'Изменить статус заказа';
$lang['ru_RU']['OrderReport']['NOTEEMAIL'] = 'Извещение/Электр. почта';
$lang['ru_RU']['OrderReport']['PRINTEACHORDER'] = 'Распечатать все показанные заказы';
$lang['ru_RU']['OrderReport']['SENDNOTETO'] = 'Отправить извещение для: %s (%s)';
$lang['ru_RU']['Order_Content.ss']['NOITEMS'] = 'Ваша <strong>корзина пуста</strong>.';
$lang['ru_RU']['Order_Content.ss']['PRICE'] = 'Цена';
$lang['ru_RU']['Order_Content.ss']['PRODUCT'] = 'Товар';
$lang['ru_RU']['Order_Content.ss']['QUANTITY'] = 'Количество';
$lang['ru_RU']['Order_Content.ss']['READMORE'] = 'Нажмите сюда, чтобы узнать о &laquo;%s&#187; подробнее';
$lang['ru_RU']['Order_Content.ss']['SUBTOTAL'] = 'Подитог';
$lang['ru_RU']['Order_Content.ss']['TOTAL'] = 'Всего';
$lang['ru_RU']['Order_Content.ss']['TOTALOUTSTANDING'] = 'Общая сумма задолженности';
$lang['ru_RU']['Order_Content.ss']['TOTALPRICE'] = 'Итог';
$lang['ru_RU']['Order_Member.ss']['ADDRESS'] = 'Адрес';
$lang['ru_RU']['Order_Member.ss']['CITY'] = 'Город';
$lang['ru_RU']['Order_Member.ss']['COUNTRY'] = 'Страна';
$lang['ru_RU']['Order_Member.ss']['EMAIL'] = 'Email';
$lang['ru_RU']['Order_Member.ss']['MOBILE'] = 'Моб. тел.';
$lang['ru_RU']['Order_Member.ss']['NAME'] = 'Имя';
$lang['ru_RU']['Order_Member.ss']['PHONE'] = 'Тел.';
$lang['ru_RU']['Order_receiptEmail.ss']['HEADLINE'] = 'Квитанция заказа';
$lang['ru_RU']['Order_ReceiptEmail.ss']['HEADLINE'] = 'Квитанция заказа';
$lang['ru_RU']['Order_receiptEmail.ss']['TITLE'] = 'Квитанция магазина';
$lang['ru_RU']['Order_ReceiptEmail.ss']['TITLE'] = 'Квитанция магазина';
$lang['ru_RU']['Order_statusEmail.ss']['HEADLINE'] = 'Изменение статуса заказа';
$lang['ru_RU']['Order_StatusEmail.ss']['HEADLINE'] = 'Изменение статуса заказа';
$lang['ru_RU']['Order_statusEmail.ss']['STATUSCHANGE'] = 'Статус заказа # изменен на "%s"';
$lang['ru_RU']['Order_StatusEmail.ss']['STATUSCHANGE'] = 'Статус Заказа # изменен на "%s"';
$lang['ru_RU']['Order_statusEmail.ss']['TITLE'] = 'Изменение статуса заказа';
$lang['ru_RU']['Order_StatusEmail.ss']['TITLE'] = 'Изменение статуса заказа';
$lang['ru_RU']['PaymentInformation.ss']['AMOUNT'] = 'Сумма';
$lang['ru_RU']['PaymentInformation.ss']['DATE'] = 'Дата';
$lang['ru_RU']['PaymentInformation.ss']['DETAILS'] = 'Детали';
$lang['ru_RU']['PaymentInformation.ss']['PAYMENTID'] = 'Идентификатор платежа';
$lang['ru_RU']['PaymentInformation.ss']['PAYMENTINFORMATION'] = 'Платежная информация';
$lang['ru_RU']['PaymentInformation.ss']['PAYMENTMETHOD'] = 'Способ';
$lang['ru_RU']['PaymentInformation.ss']['PAYMENTSTATUS'] = 'Статус платежа';
$lang['ru_RU']['PaymentInformation.ss']['TABLESUMMARY'] = 'В этой форме отражены содержимое вашей корзины, список всех сборов, связанных с заказом, и сводка вариантов оплаты.';
$lang['ru_RU']['Product.ss']['ADD'] = 'Добавить &quot;%s&quot; в вашу корзину';
$lang['ru_RU']['Product.ss']['ADDLINK'] = 'Добавить это наименование в корзину';
$lang['ru_RU']['Product.ss']['ADDONE'] = 'Добавить ещё один экземпляр &quot;%s&quot; в вашу корзину';
$lang['ru_RU']['Product.ss']['AUTHOR'] = 'Автор';
$lang['ru_RU']['Product.ss']['FEATURED'] = 'Мы рекомендуем этот товар.';
$lang['ru_RU']['Product.ss']['GOTOCHECKOUT'] = 'Перейти к расчету стоимости';
$lang['ru_RU']['Product.ss']['GOTOCHECKOUTLINK'] = '&#187; Перейти к расчету стоимости';
$lang['ru_RU']['Product.ss']['IMAGE'] = 'Изображение: %s';
$lang['ru_RU']['Product.ss']['ItemID'] = 'Предмет #';
$lang['ru_RU']['Product.ss']['NOIMAGE'] = 'Извините, &quot;%s&quot; не имеет изображения';
$lang['ru_RU']['Product.ss']['QUANTITYCART'] = 'Количество в корзине';
$lang['ru_RU']['Product.ss']['REMOVE'] = 'Убрать &quot;%s&quot; из вашей корзины';
$lang['ru_RU']['Product.ss']['REMOVEALL'] = 'Убрать один экземпляр &quot;%s&quot; из вашей корзины';
$lang['ru_RU']['Product.ss']['REMOVELINK'] = '&#187; Убрать из корзины';
$lang['ru_RU']['Product.ss']['SIZE'] = 'Размер';
$lang['ru_RU']['ProductGroup.ss']['FEATURED'] = 'Рекомендованные товары';
$lang['ru_RU']['ProductGroup.ss']['OTHER'] = 'Другие товары';
$lang['ru_RU']['ProductGroup.ss']['VIEWGROUP'] = 'Просмотр группы товаров &quot;%s&quot;';
$lang['ru_RU']['ProductGroupItem.ss']['ADD'] = 'Добавить &quot;%s&quot; в вашу корзину';
$lang['ru_RU']['ProductGroupItem.ss']['ADDLINK'] = 'Добавить это наименование в вашу корзину';
$lang['ru_RU']['ProductGroupItem.ss']['ADDONE'] = 'Добавить в вашу корзину еще один экземпляр &quot;%s&quot;';
$lang['ru_RU']['ProductGroupItem.ss']['AUTHOR'] = 'Автор';
$lang['ru_RU']['ProductGroupItem.ss']['GOTOCHECKOUT'] = 'Перейти к платежу сейчас';
$lang['ru_RU']['ProductGroupItem.ss']['GOTOCHECKOUTLINK'] = '&#187; Перейти к платежу';
$lang['ru_RU']['ProductGroupItem.ss']['IMAGE'] = 'Изображение: %s';
$lang['ru_RU']['ProductGroupItem.ss']['NOIMAGE'] = 'Извините, &quot;%s&quot; не имеет изображения';
$lang['ru_RU']['ProductGroupItem.ss']['QUANTITY'] = 'Количество';
$lang['ru_RU']['ProductGroupItem.ss']['QUANTITYCART'] = 'Количество в корзине';
$lang['ru_RU']['ProductGroupItem.ss']['READMORE'] = 'Нажмите здесь, чтобы узнать больше о &quot;%s&quot;';
$lang['ru_RU']['ProductGroupItem.ss']['READMORECONTENT'] = 'Нажмите, чтобы узнать больше &#187;';
$lang['ru_RU']['ProductGroupItem.ss']['REMOVE'] = 'Убрать &quot;%s&quot; из вашей корзины';
$lang['ru_RU']['ProductGroupItem.ss']['REMOVEALL'] = 'Удалить одно наименование &quot;%s&quot; из вашей корзины';
$lang['ru_RU']['ProductGroupItem.ss']['REMOVELINK'] = '&#187; Убрать из корзины';
$lang['ru_RU']['ProductGroupItem.ss']['REMOVEONE'] = 'Убрать &quot;%s&quot; из вашей корзины';
$lang['ru_RU']['ProductMenu.ss']['GOTOPAGE'] = 'Перейти на страницу описания для %s';
$lang['ru_RU']['SSReport']['ALLCLICKHERE'] = 'Нажмите здесь для просмотра всех товаров';
$lang['ru_RU']['SSReport']['INVOICE'] = 'счет';
$lang['ru_RU']['SSReport']['PRINT'] = 'печать';
$lang['ru_RU']['SSReport']['VIEW'] = 'просмотр';
$lang['ru_RU']['ViewAllProducts.ss']['AUTHOR'] = 'Автор';
$lang['ru_RU']['ViewAllProducts.ss']['CATEGORIES'] = 'Категории';
$lang['ru_RU']['ViewAllProducts.ss']['IMAGE'] = 'Изображение: %s';
$lang['ru_RU']['ViewAllProducts.ss']['LASTEDIT'] = 'Последнее изменение:';
$lang['ru_RU']['ViewAllProducts.ss']['LINK'] = 'Ссылка';
$lang['ru_RU']['ViewAllProducts.ss']['NOCONTENT'] = 'Содержимое не задано.';
$lang['ru_RU']['ViewAllProducts.ss']['NOIMAGE'] = 'Извините, &quot;%s&quot; не имеет изображения';
$lang['ru_RU']['ViewAllProducts.ss']['NOSUBJECTS'] = 'Объекты не заданы';
$lang['ru_RU']['ViewAllProducts.ss']['PRICE'] = 'Стоимость';
$lang['ru_RU']['ViewAllProducts.ss']['PRODUCTID'] = 'Идентификатор продукта';
$lang['ru_RU']['ViewAllProducts.ss']['WEIGHT'] = 'Вес';
