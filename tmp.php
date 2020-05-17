<?php

$array = [
    './code/api/EcommerceCountry_VisitorCountryProvider.php',
    './code/reports/EcommerceSideReport_FeaturedProducts.php',
    './code/reports/EcommerceSideReport_EcommercePages.php',
    './code/reports/EcommerceSideReport_NoInternalIDProducts.php',
    './code/reports/EcommerceSideReport_NoPriceProducts.php',
    './code/reports/EcommerceSideReport_AllProducts.php',
    './code/reports/EcommerceSideReport_NotForSale.php',
    './code/reports/EcommerceSideReport_NoImageProducts.php',
    './code/tasks/EcommerceTaskBuilding_Extending.php',
    './code/tasks/EcommerceTaskBuilding_Model.php',
    './code/forms/OrderForm_Payment.php',
    './code/forms/OrderForm_Feedback.php',
    './code/forms/validation/OrderForm_Feedback_Validator.php',
    './code/forms/validation/OrderFormAddress_Validator.php',
    './code/forms/validation/ProductSearchForm_Validator.php',
    './code/forms/validation/ShopAccountForm_PasswordValidator.php',
    './code/forms/validation/ShopAccountForm_Validator.php',
    './code/forms/validation/OrderModifierForm_Validator.php',
    './code/forms/validation/OrderForm_Cancel_Validator.php',
    './code/forms/validation/OrderStatusLogForm_Validator.php',
    './code/forms/fields/Product_ProductImageUploadField.php',
    './code/forms/ProductSearchForm_Short.php',
    './code/forms/OrderForm_Cancel.php',
    './code/email/Order_StatusEmail.php',
    './code/email/Order_InvoiceEmail.php',
    './code/email/Order_ErrorEmail.php',
    './code/email/Order_Email.php',
    './code/email/Ecommerce_Dummy_Mailer.php',
    './code/email/Order_ReceiptEmail.php',
    './code/control/OrderEmailRecord_Review.php',
    './code/control/OrderModifierForm_Controller.php',
    './code/control/OrderStatusLogForm_Controller.php',
    './code/control/ShoppingCart_Controller.php',
    './code/cms/CMSPageAddController_Products.php',
    './code/search/filters/OrderFilters_MemberAndAddress.php',
    './code/search/filters/OrderFilters_MustHaveAtLeastOnePayment.php',
    './code/search/filters/OrderFilters_AroundDateFilter.php',
    './code/search/filters/OrderFilters_HasBeenCancelled.php',
    './code/search/filters/OrderFilters_MultiOptionsetStatusIDFilter.php',
    './code/filesystem/Product_Image.php',
    './code/model/ProductOrderItem.php',
    './code/model/process/OrderSteps/OrderStepSent.php',
    './code/model/process/OrderSteps/OrderStepSubmitted.php',
    './code/model/process/OrderSteps/OrderStepSendAdminNotification.php',
    './code/model/process/OrderSteps/OrderStepArchived.php',
    './code/model/process/OrderSteps/OrderStepConfirmed.php',
    './code/model/process/OrderSteps/OrderStepPaid.php',
    './code/model/process/OrderSteps/OrderStepSentReceipt.php',
    './code/model/process/OrderSteps/OrderStepSentInvoice.php',
    './code/model/process/OrderSteps/OrderStepCreated.php',
    './code/model/process/OrderStatusLogs/OrderStatusLogDispatchElectronicOrder.php',
    './code/model/process/OrderStatusLogs/OrderStatusLogArchived.php',
    './code/model/process/OrderStatusLogs/OrderStatusLogSubmitted.php',
    './code/model/process/OrderStatusLogs/OrderStatusLogCancel.php',
    './code/model/process/OrderStatusLogs/OrderStatusLogPaymentCheck.php',
    './code/model/process/OrderStatusLogs/OrderStatusLogDispatch.php',
    './code/model/process/OrderStatusLogs/OrderStatusLogDispatchPhysicalOrder.php',
    './code/model/process/CheckoutPageStepDescription.php',
    './code/model/OrderAttributeGroup.php',
    './code/model/OrderModifierDescriptor.php',
    './code/model/money/payment_types/EcommercePaymentTest.php',
    './code/model/money/payment_types/EcommercePaymentTestSuccess.php',
    './code/model/money/payment_types/EcommercePaymentTestFailure.php',
    './code/model/money/payment_types/EcommercePaymentTestPending.php',
    './code/money/payment/payment_results/EcommercePaymentSuccess.php',
    './code/money/payment/payment_results/EcommercePaymentFailure.php',
    './code/money/payment/payment_results/EcommercePaymentProcessing.php',
    './code/money/payment/EcommercePaymentResult.php',
    './code/money/ExchangeRateProvider_Dummy.php',
];

foreach($array as $item) {
    $items = explode('/', $item);
    $name = array_pop($items);
    $name = str_replace('.php', '', $name);
    $newName = str_replace('_', '', $name);
    echo '\''. $name .'\': \''.$newName.'\''."\n";
}