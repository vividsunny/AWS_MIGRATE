<?php

return array (
  0 => 
  array (
    'code' => 'I00001',
    'text' => 'Successful.',
    'description' => 'The request was processed successfully.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  1 => 
  array (
    'code' => 'I00002',
    'text' => 'The subscription has already been canceled.',
    'description' => 'The subscription has already been canceled.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  2 => 
  array (
    'code' => 'I00003',
    'text' => 'The record has already been deleted.',
    'description' => 'The record has already been deleted.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  3 => 
  array (
    'code' => 'I00004',
    'text' => 'No records found.',
    'description' => 'No records have been found that match your query.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  4 => 
  array (
    'code' => 'I00005',
    'text' => 'The mobile device has been submitted for approval by the account administrator.',
    'description' => 'The mobile device was successfully inserted into the database.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  5 => 
  array (
    'code' => 'I00006',
    'text' => 'The mobile device is approved and ready for use.',
    'description' => 'The mobile device was successfully registered and approved by the account administrator.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  6 => 
  array (
    'code' => 'I00007',
    'text' => 'The Payment Gateway Account service (id&#x3D;8) has already been accepted.',
    'description' => 'The Payment Gateway Account service (id&#x3D;8) has already been accepted.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  7 => 
  array (
    'code' => 'I00008',
    'text' => 'The Payment Gateway Account service (id&#x3D;8) has already been declined.',
    'description' => 'The Payment Gateway Account service (id&#x3D;8) has already been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  8 => 
  array (
    'code' => 'I00009',
    'text' => 'The APIUser already exists.',
    'description' => 'The APIUser already exists.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  9 => 
  array (
    'code' => 'I00010',
    'text' => 'The merchant is activated successfully.',
    'description' => 'The merchant is activated successfully.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  10 => 
  array (
    'code' => 'I00011',
    'text' => 'The merchant is not activated.',
    'description' => 'The merchant is not activated.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  11 => 
  array (
    'code' => 'I99999',
    'text' => 'This feature has not yet been completed. One day it will be but it looks like today is not that day.',
    'description' => 'This is a work in progress. This message will not be released to production. It&#x27;s just a dev placeholder.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  12 => 
  array (
    'code' => 'E00001',
    'text' => 'An error occurred during processing. Please try again.',
    'description' => 'An unexpected system error occurred while processing this request.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  13 => 
  array (
    'code' => 'E00002',
    'text' => 'The content-type specified is not supported.',
    'description' => 'The only supported content-types are text/xml and application/xml.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  14 => 
  array (
    'code' => 'E00003',
    'text' => 'An error occurred while parsing the XML request.',
    'description' => 'This is the result of an XML parser error.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  15 => 
  array (
    'code' => 'E00004',
    'text' => 'The name of the requested API method is invalid.',
    'description' => 'The name of the root node of the XML request is the API method being called. It is not valid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  16 => 
  array (
    'code' => 'E00005',
    'text' => 'The transaction key or API key is invalid or not present.',
    'description' => 'User authentication requires a valid value for transaction key or API key.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  17 => 
  array (
    'code' => 'E00006',
    'text' => 'The API user name is invalid or not present.',
    'description' => 'User authentication requires a valid value for API user name.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  18 => 
  array (
    'code' => 'E00007',
    'text' => 'User authentication failed due to invalid authentication values.',
    'description' => 'The API user name is invalid and/or the transaction key or API key is invalid.',
    'integration_suggestions' => 'This error indicates that invalid credentials, the API Login ID or Transaction Key, are being submitted. If you have confirmed that your API login ID and Transaction Key are accurate, you may need to confirm that you are submitting to the correct URL. If you are using a test account, please make sure to post to the sandbox URL. If you’re using a live account, make sure to post to the production URL.',
    'other_suggestions' => '',
  ),
  19 => 
  array (
    'code' => 'E00008',
    'text' => 'User authentication failed. The account or API user is inactive.',
    'description' => 'The payment gateway, reseller, or user account is not currently active.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  20 => 
  array (
    'code' => 'E00009',
    'text' => 'The payment gateway account is in Test Mode. The request cannot be processed.',
    'description' => 'The requested API method cannot be executed while the payment gateway account is in Test Mode.',
    'integration_suggestions' => '',
    'other_suggestions' => 'To disable Test Mode, log into the Merchant Interface at https://account.authorize.net/ and click &amp;lt;strong&amp;gt;Account &amp;gt; Test Mode &amp;gt; Turn Test OFF&amp;lt;/strong&amp;gt;.',
  ),
  21 => 
  array (
    'code' => 'E00010',
    'text' => 'User authentication failed. You do not have the appropriate permissions.',
    'description' => 'The user does not have permission to call the API.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  22 => 
  array (
    'code' => 'E00011',
    'text' => 'Access denied. You do not have the appropriate permissions.',
    'description' => 'The user does not have permission to call the API method.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  23 => 
  array (
    'code' => 'E00012',
    'text' => 'A duplicate subscription already exists.',
    'description' => 'A duplicate of the subscription was already submitted.',
    'integration_suggestions' => 'The recurring billing system checks a new subscription for duplicates, using these fields:&amp;lt;br /&amp;gt;    &amp;lt;br /&amp;gt;&amp;lt;code&amp;gt;subscription.article.merchantID&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.payment.creditCard.cardNumber&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.payment.eCheck.routingNumber&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.payment.eCheck.accountNumber&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.customerID&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.billingInfo.billToAddress.firstName&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.billingInfo.billToAddress.lastName&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.billingInfo.billToAddress.company&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.billingInfo.billToAddress.streetAddress&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.billingInfo.billToAddress.city&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.billingInfo.billToAddress.stateProv&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.article.customerInfo.billingInfo.billToAddress.zip&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;code&amp;gt;subscription.orderInfo.amount&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;code&amp;gt;subscription.orderInfo.invoice&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;code&amp;gt;subscription.recurrence.startDate&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;code&amp;gt;subscription.recurrence.interval&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;    &amp;lt;code&amp;gt;subscription.recurrence.unit&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;If all of these fields are duplicated in an existing subscription, error code E00012 will result. Modifying any of these fields should result in a unique subscription.',
    'other_suggestions' => '',
  ),
  24 => 
  array (
    'code' => 'E00013',
    'text' => 'The field is invalid.',
    'description' => 'One of the field values is not valid.',
    'integration_suggestions' => 'One of the field values is not valid. The response text field should provide you the details of which &amp;quot;field&amp;quot; exactly is invalid so check the response text.',
    'other_suggestions' => '',
  ),
  25 => 
  array (
    'code' => 'E00014',
    'text' => 'A required field is not present.',
    'description' => 'One of the required fields was not present.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  26 => 
  array (
    'code' => 'E00015',
    'text' => 'The field length is invalid.',
    'description' => 'One of the fields has an invalid length.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  27 => 
  array (
    'code' => 'E00016',
    'text' => 'The field type is invalid.',
    'description' => 'The field type is not valid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  28 => 
  array (
    'code' => 'E00017',
    'text' => 'The start date cannot occur in the past.',
    'description' => 'The subscription start date cannot occur before the subscription submission date.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  29 => 
  array (
    'code' => 'E00018',
    'text' => 'The credit card expires before the subscription start date.',
    'description' => 'The credit card is not valid as of the start date of the subscription.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  30 => 
  array (
    'code' => 'E00019',
    'text' => 'The customer tax id or drivers license information is required.',
    'description' => 'The customer tax ID or driver&#x27;s license information (driver&#x27;s license number, driver&#x27;s license state, driver&#x27;s license DOB) is required for the subscription.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  31 => 
  array (
    'code' => 'E00020',
    'text' => 'The payment gateway account is not enabled for eCheck.Net subscriptions.',
    'description' => 'The payment gateway account is not set up to process eCheck.Net subscriptions.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  32 => 
  array (
    'code' => 'E00021',
    'text' => 'The payment gateway account is not enabled for credit card subscriptions.',
    'description' => 'The payment gateway account is not set up to process credit card subscriptions.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  33 => 
  array (
    'code' => 'E00022',
    'text' => 'The interval length cannot exceed 365 days or 12 months.',
    'description' => 'The interval length must be 7 to 365 days or 1 to 12 months.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  34 => 
  array (
    'code' => 'E00023',
    'text' => 'The subscription duration cannot exceed three years.',
    'description' => 'The number of total occurrences cannot extend the duration of the subscription beyond three years from the start date.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  35 => 
  array (
    'code' => 'E00024',
    'text' => 'Trial Occurrences is required when Trial Amount is specified.',
    'description' => 'The number of trial occurrences cannot be zero if a valid trial amount is submitted.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  36 => 
  array (
    'code' => 'E00025',
    'text' => 'Automated Recurring Billing is not enabled.',
    'description' => 'The payment gateway account is not enabled for Automated Recurring Billing.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  37 => 
  array (
    'code' => 'E00026',
    'text' => 'Both Trial Amount and Trial Occurrences are required.',
    'description' => 'If either a trial amount or number of trial occurrences is specified then values for both must be submitted.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  38 => 
  array (
    'code' => 'E00027',
    'text' => 'The transaction was unsuccessful.',
    'description' => 'An approval was not returned for the transaction.',
    'integration_suggestions' => 'This error may occur for merchants on the HSBC or FDI Australia processors when setting &amp;lt;strong&amp;gt;validationMode&amp;lt;/strong&amp;gt; to &amp;lt;strong&amp;gt;liveMode&amp;lt;/strong&amp;gt; as these processors do not support authorization reversals. We recommend HSBC and FDI Australia merchants set &amp;lt;strong&amp;gt;validationMode&amp;lt;/strong&amp;gt; to &amp;lt;strong&amp;gt;testMode&amp;lt;/strong&amp;gt; instead.',
    'other_suggestions' => 'For more information, check the errorCode field in the response.',
  ),
  39 => 
  array (
    'code' => 'E00028',
    'text' => 'Trial Occurrences must be less than Total Occurrences.',
    'description' => 'The number of trial occurrences specified must be less than the number of total occurrences specified.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  40 => 
  array (
    'code' => 'E00029',
    'text' => 'Payment information is required.',
    'description' => 'Payment information is required when creating a subscription or payment profile.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  41 => 
  array (
    'code' => 'E00030',
    'text' => 'The payment schedule is required.',
    'description' => 'A payment schedule is required when creating a subscription.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  42 => 
  array (
    'code' => 'E00031',
    'text' => 'The amount is required.',
    'description' => 'The subscription amount is required when creating a subscription.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  43 => 
  array (
    'code' => 'E00032',
    'text' => 'The start date is required.',
    'description' => 'The subscription start date is required to create a subscription.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  44 => 
  array (
    'code' => 'E00033',
    'text' => 'The start date cannot be changed.',
    'description' => 'Once a subscription is created the start date cannot be changed.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  45 => 
  array (
    'code' => 'E00034',
    'text' => 'The interval information cannot be changed.',
    'description' => 'Once a subscription is created the interval cannot be changed.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  46 => 
  array (
    'code' => 'E00035',
    'text' => 'The subscription cannot be found.',
    'description' => 'The subscription ID for this request is not valid for this merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  47 => 
  array (
    'code' => 'E00036',
    'text' => 'The payment type cannot be changed.',
    'description' => 'Changing the subscription payment type between credit card and eCheck.Net is not currently supported.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  48 => 
  array (
    'code' => 'E00037',
    'text' => 'The subscription cannot be updated.',
    'description' => 'Subscriptions that are expired, canceled or terminated cannot be updated.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  49 => 
  array (
    'code' => 'E00038',
    'text' => 'The subscription cannot be canceled.',
    'description' => 'Subscriptions that are expired or terminated cannot be canceled.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  50 => 
  array (
    'code' => 'E00039',
    'text' => 'A duplicate record already exists.',
    'description' => 'A duplicate of the customer profile, customer payment profile, or customer address was already submitted.',
    'integration_suggestions' => 'For information about the rules that Authorize.Net uses to check for duplicate profiles, see the &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/api/reference/features/customer_profiles.html#Duplicate_Profile_Verification&amp;quot;&amp;gt;Customer Profiles API Documentation&amp;lt;/a&amp;gt;.',
    'other_suggestions' => '',
  ),
  51 => 
  array (
    'code' => 'E00040',
    'text' => 'The record cannot be found.',
    'description' => 'The customer profile ID, payment profile ID, shipping address ID, or transaction ID for this request is not valid for this merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  52 => 
  array (
    'code' => 'E00041',
    'text' => 'One or more fields must contain a value.',
    'description' => 'All of the fields were empty or missing.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  53 => 
  array (
    'code' => 'E00042',
    'text' => 'You cannot add more than {0} payment profiles.',
    'description' => 'The maximum number of payment profiles for the customer profile has been reached.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  54 => 
  array (
    'code' => 'E00043',
    'text' => 'You cannot add more than {0} shipping addresses.',
    'description' => 'The maximum number of shipping addresses for the customer profile has been reached.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  55 => 
  array (
    'code' => 'E00044',
    'text' => 'Customer Information Manager is not enabled.',
    'description' => 'The payment gateway account is not enabled for Customer Information Manager (CIM).',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  56 => 
  array (
    'code' => 'E00045',
    'text' => 'The root node does not reference a valid XML namespace.',
    'description' => 'The root node does not reference a valid XML namespace.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  57 => 
  array (
    'code' => 'E00046',
    'text' => 'Generic InsertNewMerchant failure.',
    'description' => 'Generic InsertNewMerchant failure.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  58 => 
  array (
    'code' => 'E00047',
    'text' => 'Merchant Boarding API is not enabled.',
    'description' => 'The reseller account is not enabled for Merchant Boarding API.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  59 => 
  array (
    'code' => 'E00048',
    'text' => 'At least one payment method must be set in payment types or an echeck service must be provided.',
    'description' => 'The merchant account must be set up to accept credit card payments, eCheck payments, or both.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  60 => 
  array (
    'code' => 'E00049',
    'text' => 'The operation timed out before it could be completed.',
    'description' => 'The database operation timed out before it could be completed.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  61 => 
  array (
    'code' => 'E00050',
    'text' => 'Sell Rates cannot be less than Buy Rates',
    'description' => 'Cannot set a buyrate to less than the sellrate',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  62 => 
  array (
    'code' => 'E00051',
    'text' => 'The original transaction was not issued for this payment profile.',
    'description' => 'If customer profile ID, payment profile ID, and shipping address ID are included, they must match the original transaction.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  63 => 
  array (
    'code' => 'E00052',
    'text' => 'The maximum number of elements for an array {0} is {1}.',
    'description' => 'The maximum number of elements for an array has been reached.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  64 => 
  array (
    'code' => 'E00053',
    'text' => 'Server too busy',
    'description' => 'The server is currently too busy, please try again later.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  65 => 
  array (
    'code' => 'E00054',
    'text' => 'The mobile device is not registered with this merchant account.',
    'description' => 'The mobile device identifier is not associated with the merchant account.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  66 => 
  array (
    'code' => 'E00055',
    'text' => 'The mobile device has already been registered but is pending approval by the account administrator.',
    'description' => 'The mobile device exists but is in a pending status.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  67 => 
  array (
    'code' => 'E00056',
    'text' => 'The mobile device has been disabled for use with this account.',
    'description' => 'The mobile device exists but has a status of disabled.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  68 => 
  array (
    'code' => 'E00057',
    'text' => 'The user does not have permissions to submit requests from a mobile device.',
    'description' => 'The user does not have sufficient permissions to use a mobile device with this merchant account.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  69 => 
  array (
    'code' => 'E00058',
    'text' => 'The merchant has met or exceeded the number of pending mobile devices permitted for this account.',
    'description' => 'The merchant has too many devices in a pending status.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  70 => 
  array (
    'code' => 'E00059',
    'text' => 'The authentication type is not allowed for this method call.',
    'description' => 'The authentication type is not allowed for the requested method call.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  71 => 
  array (
    'code' => 'E00060',
    'text' => 'The transaction type is invalid.',
    'description' => 'The transaction type is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  72 => 
  array (
    'code' => 'E00061',
    'text' => '{0}({1}).',
    'description' => 'Could not decrypt DUKPT blobs and returned error.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  73 => 
  array (
    'code' => 'E00062',
    'text' => 'Fatal error when calling web service.',
    'description' => 'Fatal error when calling web service.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  74 => 
  array (
    'code' => 'E00063',
    'text' => 'Calling web service return error.',
    'description' => 'Calling web service return error.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  75 => 
  array (
    'code' => 'E00064',
    'text' => 'Client authorization denied.',
    'description' => 'Client authorization denied.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  76 => 
  array (
    'code' => 'E00065',
    'text' => 'Prerequisite failed.',
    'description' => 'Prerequisite failed.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  77 => 
  array (
    'code' => 'E00066',
    'text' => 'Invalid value.',
    'description' => 'Invalid value.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  78 => 
  array (
    'code' => 'E00067',
    'text' => 'An error occurred while parsing the XML request.  Too many {0} specified.',
    'description' => 'This is the result of an XML parser error.  Too many nodes specified.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  79 => 
  array (
    'code' => 'E00068',
    'text' => 'An error occurred while parsing the XML request.  {0} is invalid.',
    'description' => 'This is the result of an XML parser error.  The node is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  80 => 
  array (
    'code' => 'E00069',
    'text' => 'The Payment Gateway Account service (id&#x3D;8) has already been accepted.  Decline is not allowed.',
    'description' => 'The Payment Gateway Account service (id&#x3D;8) has already been accepted.  Decline is not allowed.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  81 => 
  array (
    'code' => 'E00070',
    'text' => 'The Payment Gateway Account service (id&#x3D;8) has already been declined.  Agree is not allowed.',
    'description' => 'The Payment Gateway Account service (id&#x3D;8) has already been declined.  Agree is not allowed.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  82 => 
  array (
    'code' => 'E00071',
    'text' => '{0} must contain data.',
    'description' => 'All of the fields were empty or missing.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  83 => 
  array (
    'code' => 'E00072',
    'text' => 'Node {0} is required.',
    'description' => 'Required node missing.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  84 => 
  array (
    'code' => 'E00073',
    'text' => '{0} is invalid.',
    'description' => 'One of the field values is not valid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  85 => 
  array (
    'code' => 'E00074',
    'text' => 'This merchant is not associated with this reseller.',
    'description' => 'This merchant is not associated with this reseller.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  86 => 
  array (
    'code' => 'E00075',
    'text' => 'An error occurred while parsing the XML request.  Missing field(s) {0}.',
    'description' => 'This is the result of an XML parser error.  Missing field(s).',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  87 => 
  array (
    'code' => 'E00076',
    'text' => '{0} contains invalid value.',
    'description' => 'Invalid value.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  88 => 
  array (
    'code' => 'E00077',
    'text' => 'The value of {0} is too long.  The length of value should be {1}',
    'description' => 'Value too long.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  89 => 
  array (
    'code' => 'E00078',
    'text' => 'Pending Status (not completed).',
    'description' => 'Pending Status (not completed).',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  90 => 
  array (
    'code' => 'E00079',
    'text' => 'The impersonation login ID is invalid or not present.',
    'description' => 'Impersonation partner login ID is invalid or not present.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  91 => 
  array (
    'code' => 'E00080',
    'text' => 'The impersonation API Key is invalid or not present.',
    'description' => 'Impersonation API Key is invalid or not present.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  92 => 
  array (
    'code' => 'E00081',
    'text' => 'Partner account is not authorized to impersonate the login account.',
    'description' => 'The partner account is not authorized to impersonate the login account.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  93 => 
  array (
    'code' => 'E00082',
    'text' => 'Country for {0} is not valid.',
    'description' => 'Country is not valid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  94 => 
  array (
    'code' => 'E00083',
    'text' => 'Bank payment method is not accepted for the selected business country.',
    'description' => 'Bank payment method is not accepted for the selected business country.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  95 => 
  array (
    'code' => 'E00084',
    'text' => 'Credit card payment method is not accepted for the selected business country.',
    'description' => 'Credit card payment method is not accepted for the selected business country.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  96 => 
  array (
    'code' => 'E00085',
    'text' => 'State for {0} is not valid.',
    'description' => 'State is not valid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  97 => 
  array (
    'code' => 'E00086',
    'text' => 'Merchant has declined authorization to resource.',
    'description' => 'Merchant has declined authorization to resource.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  98 => 
  array (
    'code' => 'E00087',
    'text' => 'No subscriptions found for the given request.',
    'description' => 'There are no subscriptions available for the merchant account for the type of subscriptions requested.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  99 => 
  array (
    'code' => 'E00088',
    'text' => 'ProfileIds cannot be sent when requesting CreateProfile.',
    'description' => 'CreateProfile and profileIds are mutually exclusive, only one of them can be provided at a time.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  100 => 
  array (
    'code' => 'E00089',
    'text' => 'Payment data is required when requesting CreateProfile.',
    'description' => 'When requesting CreateProfile payment data cannot be null.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  101 => 
  array (
    'code' => 'E00090',
    'text' => 'PaymentProfile cannot be sent with payment data.',
    'description' => 'PaymentProfile and PaymentData are mutually exclusive, only one of them can be provided at a time.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  102 => 
  array (
    'code' => 'E00091',
    'text' => 'PaymentProfileId cannot be sent with payment data.',
    'description' => 'PaymentProfileId and payment data are mutually exclusive, only one of them can be provided at a time.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  103 => 
  array (
    'code' => 'E00092',
    'text' => 'ShippingProfileId cannot be sent with ShipTo data.',
    'description' => 'ShippingProfileId and ShipToAddress are mutually exclusive, only one of them can be provided at a time.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  104 => 
  array (
    'code' => 'E00093',
    'text' => 'PaymentProfile cannot be sent with billing data.',
    'description' => 'PaymentProfile and Billing information are mutually exclusive, only one of them can be provided at a time.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  105 => 
  array (
    'code' => 'E00094',
    'text' => 'Paging Offset exceeds the maximum allowed value.',
    'description' => 'Paging Offset exceeds allowed value. Check and lower the value.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  106 => 
  array (
    'code' => 'E00095',
    'text' => 'ShippingProfileId is not provided within Customer Profile.',
    'description' => 'When using Customer Profile with Credit Card Info to specify Shipping Profile, Shipping Profile Id must be included.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  107 => 
  array (
    'code' => 'E00096',
    'text' => 'Finger Print value is not valid.',
    'description' => 'Finger Print value is not valid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  108 => 
  array (
    'code' => 'E00097',
    'text' => 'Finger Print can&#x27;t be generated.',
    'description' => 'Finger Print can&#x27;t be generated.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  109 => 
  array (
    'code' => 'E00098',
    'text' => 'Customer Profile ID or Shipping Profile ID not found.',
    'description' => 'Search for shipping profile using customer profile id and shipping profile id did not find any records.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  110 => 
  array (
    'code' => 'E00099',
    'text' => 'Customer profile creation failed. This transaction ID is invalid.',
    'description' => 'Customer profile creation failed. This transaction ID is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  111 => 
  array (
    'code' => 'E00100',
    'text' => 'Customer profile creation failed. This transaction type does not support profile creation.',
    'description' => 'Customer profile creation failed. This transaction type does not support profile creation.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  112 => 
  array (
    'code' => 'E00101',
    'text' => 'Customer profile creation failed.',
    'description' => 'Error creating a customer payment profile from transaction.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  113 => 
  array (
    'code' => 'E00102',
    'text' => 'Customer Info is missing.',
    'description' => 'Error creating a customer profile from transaction.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  114 => 
  array (
    'code' => 'E00103',
    'text' => 'Customer profile creation failed. This payment method does not support profile creation.',
    'description' => 'Customer profile creation failed. This payment method does not support profile creation.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  115 => 
  array (
    'code' => 'E00104',
    'text' => 'Server in maintenance. Please try again later.',
    'description' => 'The server is in maintenance, so the requested method is unavailable. Please try again later.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  116 => 
  array (
    'code' => 'E00105',
    'text' => 'The specified payment profile is associated with an active or suspended subscription and cannot be deleted.',
    'description' => 'The specified payment profile is associated with an active or suspended subscription and cannot be deleted.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  117 => 
  array (
    'code' => 'E00106',
    'text' => 'The specified customer profile is associated with an active or suspended subscription and cannot be deleted.',
    'description' => 'The specified customer profile is associated with an active or suspended subscription and cannot be deleted.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  118 => 
  array (
    'code' => 'E00107',
    'text' => 'The specified shipping profile is associated with an active or suspended subscription and cannot be deleted.',
    'description' => 'The specified shipping profile is associated with an active or suspended subscription and cannot be deleted.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  119 => 
  array (
    'code' => 'E00108',
    'text' => 'CustomerProfileId cannot be sent with customer data.',
    'description' => 'CustomerProfileId and Customer data are mutually exclusive, only one of them can be provided for any single request.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  120 => 
  array (
    'code' => 'E00109',
    'text' => 'CustomerAddressId cannot be sent with shipTo data.',
    'description' => ' Shipping Address ID and Shipping data are mutually exclusive, only one of them can be provided for any single request.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  121 => 
  array (
    'code' => 'E00110',
    'text' => 'CustomerPaymentProfileId is not provided within Customer Profile.',
    'description' => 'When using Customer Profile, CustomerPaymentProfileId must be included.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  122 => 
  array (
    'code' => 'E00111',
    'text' => 'The original subscription was not created with this Customer Profile.',
    'description' => 'If Customer Profile ID is included, it must match the Customer Profile ID used for the original subscription.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  123 => 
  array (
    'code' => 'E00112',
    'text' => 'The specified month should not be in the future.',
    'description' => 'Reports cannot be generated for future dates, thus the specified date is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  124 => 
  array (
    'code' => 'E00113',
    'text' => 'Invalid OTS Token Data.',
    'description' => 'The specified OTS Token Data is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  125 => 
  array (
    'code' => 'E00114',
    'text' => 'Invalid OTS Token.',
    'description' => 'The specified OTS Token is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  126 => 
  array (
    'code' => 'E00115',
    'text' => 'Expired OTS Token.',
    'description' => 'The specified OTS Token has expired.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  127 => 
  array (
    'code' => 'E00116',
    'text' => 'OTS Token access violation',
    'description' => 'The authenticated merchant does not have access to the specified OTS Token.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  128 => 
  array (
    'code' => 'E00117',
    'text' => 'OTS Service Error &#x27;{0}&#x27;',
    'description' => 'The OTS Service cannot complete the request due to a validation or configuration error.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  129 => 
  array (
    'code' => 'E00118',
    'text' => 'The transaction has been declined.',
    'description' => 'The transaction was submitted from a blocked IP address.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  130 => 
  array (
    'code' => 'E00119',
    'text' => 'Payment information should not be sent to Hosted Payment Page request.',
    'description' => 'Hosted Payment Page will capture the payment (bank/card) information so this information should not be included with this request.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  131 => 
  array (
    'code' => 'E00120',
    'text' => 'Payment and Shipping Profile IDs cannot be specified when creating new profiles.',
    'description' => 'Payment and Shipping Profile IDs cannot be specified when creating new profiles.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  132 => 
  array (
    'code' => 'E00121',
    'text' => 'No default payment/shipping profile found.',
    'description' => 'The customer profile does not have a default payment/shipping profile.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  133 => 
  array (
    'code' => 'E00122',
    'text' => 'Please use Merchant Interface settings (API Credentials and Keys) to generate a signature key.',
    'description' => 'Signature key missing.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  134 => 
  array (
    'code' => 'E00123',
    'text' => 'The provided access token has expired',
    'description' => 'The access token provided has expired.',
    'integration_suggestions' => 'Applicable only to the Authorize.Net API when using OAuth as an authentication method. Access tokens expire after 10 minutes, and a new access token should be requested by the solution.',
    'other_suggestions' => 'See the &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/api/reference/features/oauth.html&amp;quot;&amp;gt;OAuth documentation&amp;lt;/a&amp;gt; for details.',
  ),
  135 => 
  array (
    'code' => 'E00124',
    'text' => 'The provided access token is invalid',
    'description' => 'The access token used to validate the request is insufficient to do so.',
    'integration_suggestions' => 'Applicable only to the Authorize.Net API when using OAuth as an authentication method.',
    'other_suggestions' => 'See the &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/api/reference/features/oauth.html&amp;quot;&amp;gt;OAuth documentation&amp;lt;/a&amp;gt; for more details.',
  ),
  136 => 
  array (
    'code' => 'E00125',
    'text' => 'Hash doesnâ€™t match',
    'description' => 'Hash doesnâ€™t match.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  137 => 
  array (
    'code' => 'E00126',
    'text' => 'Failed shared key validation',
    'description' => 'Failed shared key validation.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  138 => 
  array (
    'code' => 'E00127',
    'text' => 'Invoice does not exist',
    'description' => 'Invoice number did not find any records.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  139 => 
  array (
    'code' => 'E00128',
    'text' => 'Requested action is not allowed',
    'description' => 'Requested action is not allowed due to current status of the object.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  140 => 
  array (
    'code' => 'E00129',
    'text' => 'Failed sending email',
    'description' => 'Failed sending email.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  141 => 
  array (
    'code' => 'E00130',
    'text' => 'Valid Customer Profile ID or Email is required',
    'description' => 'Valid Customer Profile ID or Email is required',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  142 => 
  array (
    'code' => 'E00131',
    'text' => 'Invoice created but not processed completely',
    'description' => 'Invoice created but failed send email and update status',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  143 => 
  array (
    'code' => 'E00132',
    'text' => 'Invoicing or CIM service is not enabled.',
    'description' => 'The payment gateway account is not enabled for Invoicing or CIM service.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  144 => 
  array (
    'code' => 'E00133',
    'text' => 'Server error.',
    'description' => 'Server error',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  145 => 
  array (
    'code' => 'E00134',
    'text' => 'Due date is invalid',
    'description' => 'Due date is past date or not specified.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  146 => 
  array (
    'code' => 'E00135',
    'text' => 'Merchant has not provided processor information.',
    'description' => 'Merchant has not yet provided processor information to set test mode flag to false.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  147 => 
  array (
    'code' => 'E00136',
    'text' => 'Processor account is still in process, please try again later.',
    'description' => 'Processor account has not been setup to set test mode flag to false.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  148 => 
  array (
    'code' => 'E00137',
    'text' => 'Multiple payment types are not allowed.',
    'description' => 'Only either CreditCard or Bank is allowed.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  149 => 
  array (
    'code' => 'E00138',
    'text' => 'Payment and Shipping Profile IDs cannot be specified when requesting a hosted payment page.',
    'description' => 'Payment and Shipping Profile IDs cannot be specified when requesting a hosted payment page.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  150 => 
  array (
    'code' => 'E00139',
    'text' => 'Access denied. Access Token does not have correct permissions for this API.',
    'description' => 'The Access token does not have permission to call the API method.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  151 => 
  array (
    'code' => 'E00140',
    'text' => 'Reference Id not found',
    'description' => 'Reference Id not found.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  152 => 
  array (
    'code' => 'E00141',
    'text' => 'Payment Profile creation with this OpaqueData descriptor requires transactionMode to be set to liveMode.',
    'description' => 'Payment Profile creation with this OpaqueData descriptor requires transactionMode to be set to liveMode.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  153 => 
  array (
    'code' => 'E00142',
    'text' => 'RecurringBilling setting is a required field for recurring tokenized payment transactions.',
    'description' => 'RecurringBilling setting is a required field for recurring tokenized payment transactions.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  154 => 
  array (
    'code' => 'E00143',
    'text' => 'Failed to parse MerchantId to integer',
    'description' => 'Failed to parse the MerchantId to integer',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  155 => 
  array (
    'code' => 'E00144',
    'text' => 'We are currently holding the last transaction for review. Before you reactivate the subscription, review the transaction.',
    'description' => 'We are currently holding the last transaction for review. Before you reactivate the subscription, review the transaction.',
    'integration_suggestions' => 'Use &amp;lt;strong&amp;gt;getUnsettledTransactionListRequest&amp;lt;/strong&amp;gt; with &amp;lt;strong&amp;gt;status&amp;lt;/strong&amp;gt; set to &amp;lt;strong&amp;gt;pendingApproval&amp;lt;/strong&amp;gt; to review held transactions. Use &amp;lt;strong&amp;gt;updateHeldTransactionRequest&amp;lt;/strong&amp;gt; to approve or decline individual held transactions. See the &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/api/reference/#fraud-management&amp;quot;&amp;gt;Fraud Management&amp;lt;/a&amp;gt; section of the API Reference for details.',
    'other_suggestions' => 'Alternately, the merchant can log into the &amp;lt;a href&amp;#x3D;&amp;quot;https://account.authorize.net/&amp;quot;&amp;gt;Merchant Interface&amp;lt;/a&amp;gt; and click &amp;lt;strong&amp;gt;Tools &amp;gt; Fraud Detection Suite&amp;lt;/strong&amp;gt; to view the &amp;lt;strong&amp;gt;Suspicious Transaction Reports&amp;lt;/strong&amp;gt; and approve or decline the held transaction.',
  ),
  156 => 
  array (
    'code' => 'E00145',
    'text' => 'This invoice has been canceled by the sender. Please contact the sender directly if you have questions. ',
    'description' => 'This invoice has been canceled by the sender. Please contact the sender directly if you have questions. ',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  157 => 
  array (
    'code' => '0',
    'text' => 'Unknown Error.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  158 => 
  array (
    'code' => '1',
    'text' => 'This transaction has been approved.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  159 => 
  array (
    'code' => '2',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  160 => 
  array (
    'code' => '3',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => 'A referral to a voice authorization center was received.  Please call the appropriate number below for a voice authorization.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;For American Express call: (800) 528-2121&amp;lt;br /&amp;gt;For Diners Club call: (800) 525-9040&amp;lt;br /&amp;gt;For Discover/Novus call: (800) 347-1111&amp;lt;br /&amp;gt;For JCB call : (800) 522-9345&amp;lt;br /&amp;gt;For Visa/Mastercard call: (800) 228-1122&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Once an authorization is issued, you can then submit the transaction through your Virtual Terminal as a Capture Only transaction.',
    'other_suggestions' => '',
  ),
  161 => 
  array (
    'code' => '4',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The code returned from the processor indicating that the card used needs to be picked up.',
  ),
  162 => 
  array (
    'code' => '5',
    'text' => 'A valid amount is required.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in the amount field did not pass validation for a number.',
  ),
  163 => 
  array (
    'code' => '6',
    'text' => 'The credit card number is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  164 => 
  array (
    'code' => '7',
    'text' => 'Credit card expiration date is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The format of the date submitted was incorrect.',
  ),
  165 => 
  array (
    'code' => '8',
    'text' => 'The credit card has expired.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  166 => 
  array (
    'code' => '9',
    'text' => 'The ABA code is invalid',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in the routingNumber field did not pass validation or was not for a valid financial institution.',
  ),
  167 => 
  array (
    'code' => '10',
    'text' => 'The account number is invalid',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in the &amp;lt;code&amp;gt;accountNumber&amp;lt;/code&amp;gt; field did not pass validation.',
  ),
  168 => 
  array (
    'code' => '11',
    'text' => 'A duplicate transaction has been submitted.',
    'integration_suggestions' => 'The error message &amp;quot;Duplicate Transaction&amp;quot; indicates that a transaction request with the same information has been submitted within two minutes of a previous attempt. Authorize.Net looks for transactions which are likely to be duplicates by matching the data provided with the transaction.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;The fields that are validated include:&amp;lt;br /&amp;gt;&amp;lt;table cellpadding&amp;#x3D;&amp;quot;0&amp;quot; cellpadding&amp;#x3D;&amp;quot;0&amp;quot;&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td width&amp;#x3D;&amp;quot;160&amp;quot;&amp;gt;API Login ID&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;login&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Credit Card Number&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;cardNumber&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Expiration Date&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;expirationDate&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Transaction Type&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;transactionType&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Bank Account Number&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;accountNumber&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Routing Number&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;routingNumber&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Purchase Order Number&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;poNumber&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Amount&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;amount&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Invoice Number&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;invoiceNumber&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Customer ID&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;id&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;First Name&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;firstName&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Last Name&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;lastName&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Company&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;company&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Address&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;address&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;City&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;city&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;State&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;state&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Postal Code&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;zip&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Country&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;country&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;tr&amp;gt;&amp;lt;td&amp;gt;Duplicate Window&amp;lt;/td&amp;gt;&amp;lt;td&amp;gt;&amp;lt;code&amp;gt;duplicateWindow&amp;lt;/code&amp;gt;&amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;&amp;lt;/table&amp;gt;&amp;lt;br /&amp;gt;If any of the fields change from one transaction to the next, Authorize.Net will not view the transactions as duplicates.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;The duplicate transaction window will always be two minutes for all transactions submitted through the Virtual Terminal. If you wish to adjust the duplicate transaction window for transactions submitted from your software, such as a website or shopping cart, you may do so by adding the field &amp;lt;code&amp;gt;duplicateWindow&amp;lt;/code&amp;gt; to your website script.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;If you are unable to adjust or add this variable to your shopping cart settings, please contact your shopping cart support team for additional assistance in this regard. The variable &amp;lt;code&amp;gt;duplicateWindow&amp;lt;/code&amp;gt; tells us, in seconds, how much time we should check for duplicates after a transaction is submitted.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;The largest value we will accept for &amp;lt;code&amp;gt;duplicateWindow&amp;lt;/code&amp;gt; is 28800, which equals eight hours. If a value greater than 28800 sent, the payment gateway will default to 28800. If &amp;lt;code&amp;gt;duplicateWindow&amp;lt;/code&amp;gt; is set to 0 or to a negative number, no duplicate transaction window will be enforced for your software&amp;#x27;s transactions. If no value is sent, the default value of 120 (two minutes) would be used.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;For example, if you wished to set a duplicate transaction window of five minutes (&amp;#x3D; 300 seconds) you would set &amp;lt;code&amp;gt;duplicateWindow&amp;lt;/code&amp;gt; to 300.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;strong&amp;gt;Note&amp;lt;/strong&amp;gt;: By submitting &amp;lt;code&amp;gt;duplicateWindow&amp;lt;/code&amp;gt; with your online transactions, we will return further details along with this error response, including:&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;The original transaction ID that was duplicated;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;AVS and CVV responses for the original transaction;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;The original authorization code, if the transaction was authorized;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;The MD5 hash, if a MD5 hash value was generated for the original transaction.&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;&amp;lt;p&amp;gt;If you do not submit the &amp;lt;code&amp;gt;duplicateWindow&amp;lt;/code&amp;gt; field, we will not return any details from the original transaction, even if you submit a duplicate transaction.',
    'other_suggestions' => 'A transaction with identical amount and credit card information was submitted within the previous two minutes.',
  ),
  169 => 
  array (
    'code' => '12',
    'text' => 'An authorization code is required but not present.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction request required the field &amp;lt;code&amp;gt;authCode&amp;lt;/code&amp;gt; but either it was not submitted, or it was submitted without a value.',
  ),
  170 => 
  array (
    'code' => '13',
    'text' => 'The merchant login ID or password is invalid or the account is inactive.',
    'integration_suggestions' => 'This error indicates you are either posting the incorrect API Login ID within your script, connecting to a server that does not recognize your account, or using an account which is inactive. Please follow these steps to ensure that your software is connecting correctly:&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;If you are posting your transaction requests to the gateway URLs https://test.authorize.net/gateway/transact.dll and you are using an account given to you by an Authorize.Net Reseller or from Authorize.Net Sales, you may encounter this error. The gateway URLs mentioned above only work with specific test accounts, available upon request by completing the form at &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/hello_world/sandbox/&amp;quot;&amp;gt;https://developer.authorize.net/hello_world/sandbox/&amp;lt;/a&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Please check your script and verify you are posting the API Login ID for the account in question. If you are not posting the correct API Login ID, or if you are not sending an API Login ID, please edit the script and confirm that the field &amp;lt;code&amp;gt;name&amp;lt;/code&amp;gt; is set to the API Login ID that you may obtain from the Authorize.Net Merchant Interface. Please see the Getting Started Guide at &amp;lt;a href&amp;#x3D;&amp;quot;https://www.authorize.net/content/dam/authorize/documents/gettingstarted.pdf&amp;quot;&amp;gt;https://www.authorize.net/content/dam/authorize/documents/gettingstarted.pdf&amp;lt;/a&amp;gt; for instructions on obtaining the API Login ID.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;If you are unable to log into your Authorize.Net Merchant Interface, this could indicate that your account is inactive. Please contact &amp;lt;a href&amp;#x3D;&amp;quot;https://www.authorize.net/support/&amp;quot;&amp;gt;Merchant Support&amp;lt;/a&amp;gt; for assistance.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;If the account is active and the API Login ID is correct, please use the &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/support/paramdump/&amp;quot;&amp;gt;Data Validation Tool&amp;lt;/a&amp;gt; to validate the fields you are passing to us.',
    'other_suggestions' => '',
  ),
  171 => 
  array (
    'code' => '14',
    'text' => 'The referrer, relay response or receipt link URL is invalid.',
    'integration_suggestions' => '&amp;lt;para&amp;gt;The Invalid Referrer or Relay Response URL message can be the result of a few different situations that could impact SIM  users.&amp;lt;/para&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;para&amp;gt;&amp;lt;u&amp;gt;SIM Integration Suggestions&amp;lt;/u&amp;gt;&amp;lt;/para&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;para&amp;gt;Error 14 occurs for SIM users when one or more URLs are specified in your Default Response/Receipt URL settings, but we receive a URL with your transaction that does not match any listed here. In this case the transaction would include the field &amp;lt;code&amp;gt;x_receipt_link_url&amp;lt;/code&amp;gt; if you prefer to view our receipt page but provide a link for the customer to use to return to your site. Alternately, the transaction would include the field &amp;lt;code&amp;gt;x_relay_url&amp;lt;/code&amp;gt; to specify which web page on your server should be used as the receipt page your customers see.&amp;lt;/para&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;para&amp;gt;By designating a Default Response/Receipt URL, you are telling our system to only return results to one of the listed URLs. If the value of either &amp;lt;code&amp;gt;x_receipt_link_url&amp;lt;/code&amp;gt; or &amp;lt;code&amp;gt;x_relay_url&amp;lt;/code&amp;gt; does not match one of these designated Default Response/Receipt URLs, Error 14 will occur.&amp;lt;br /&amp;gt;While &amp;lt;code&amp;gt;x_receipt_link_url&amp;lt;/code&amp;gt; and &amp;lt;code&amp;gt;x_relay_url&amp;lt;/code&amp;gt; will work without specifying a Default Response/Receipt URL, it is strongly suggested that you set Default Response/Receipt URLs if you are sending either of these fields, to ensure that only these URLs can be used.&amp;lt;/para&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;para&amp;gt;To add a valid Response/Receipt URL:&amp;lt;/para&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt;&amp;lt;br /&amp;gt;  &amp;lt;li&amp;gt;Login to your Merchant Interface at &amp;lt;a href&amp;#x3D;&amp;quot;https://account.authorize.net/&amp;quot;&amp;gt;https://account.authorize.net/&amp;lt;/a &amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Settings&amp;lt;/strong&amp;gt; in the main left side menu&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Response/Receipt URLs&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Add URL&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Enter your &amp;lt;strong&amp;gt;Response URL&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Submit&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;',
    'other_suggestions' => '&amp;lt;para&amp;gt;Applicable only to SIM API. The Relay Response or Referrer URL does not match the merchant&amp;#x27;s configured value(s) or is absent.&amp;lt;/para&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;para&amp;gt;&amp;lt;b&amp;gt;NOTE:&amp;lt;/b&amp;gt; Parameterized URLs are not permitted.&amp;lt;/para&amp;gt;',
  ),
  172 => 
  array (
    'code' => '15',
    'text' => 'The transaction ID is invalid or not present.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction ID value is non-numeric or was not present for a transaction that requires it (i.e., VOID, PRIOR_AUTH_CAPTURE, and CREDIT).',
  ),
  173 => 
  array (
    'code' => '16',
    'text' => 'The transaction cannot be found.',
    'integration_suggestions' => 'This error may be caused by a refund request if the referenced transaction ID (&amp;lt;code&amp;gt;refTransId&amp;lt;/code&amp;gt;) was originally processed through a different Authorize.Net account than the one being used for the refund request. Please submit refund transactions using the gateway account that generated the original transaction.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;The error could also indicate a setup problem with a particular card type. Please contact your Merchant Service Provider (MSP) to check on your payment processing setup and to confirm that there are no issues with the configuration for the card type being submitted in the transaction.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Your MSP is the company that manages your merchant account, which is used to receive funds from credit card companies during settlement. The MSP is also responsible for the processor setup which lets Authorize.Net indirectly access your merchant accounts.',
    'other_suggestions' => 'The transaction ID sent in was properly formatted but the gateway had no record of the transaction.',
  ),
  174 => 
  array (
    'code' => '17',
    'text' => 'The merchant does not accept this type of credit card.',
    'integration_suggestions' => 'The merchant&amp;#x27;s Authorize.Net Payment Gateway is not configured to accept this card type. The merchant should contact their Merchant Service Provider to request any needed merchant accounts for the card type, and to have the card type enabled on the processor. The merchant should then contact &amp;lt;a href&amp;#x3D;&amp;quot;https://www.authorize.net/support/&amp;quot;&amp;gt;Merchant Support&amp;lt;/a&amp;gt; to enable the card type on their payment gateway account.',
    'other_suggestions' => 'If you encounter this error on an Authorize.Net Sandbox account, please contact &amp;lt;a href&amp;x3D;&amp;quot;https://developer.authorize.net/support/contact_us/&amp;quot&amp;gt;Developer Support&amp;lt;/a&amp;gt; to enable this card type on your account.&amp;lt;br /&amp;gt; ',
  ),
  175 => 
  array (
    'code' => '18',
    'text' => 'ACH transactions are not accepted by this merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The merchant does not accept electronic checks.',
  ),
  176 => 
  array (
    'code' => '19',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => 'This error message is displayed when there is a connection issue between Authorize.Net and the credit card processor. It results from Authorize.Net not receiving data in response to the transaction request we sent to the credit card processor. This type of issue is usually fixed quickly and we continue to work towards eliminating these types of connectivity issues. In some cases it may also be due to Internet congestion, and not related to either of our systems.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Repeated attempts to process a transaction during this connectivity breakdown may result in multiple authorizations to the credit card. To prevent this from happening, you can use the following test card:&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Test Visa Card Number: 4012888818888&amp;lt;br /&amp;gt;Expiration Date: 04/10&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;When a decline message appears for this card in Virtual Terminal, rather than the &amp;quot;Try again in 5 minutes&amp;quot; error message, this means the connectivity problem has been resolved and transactions can be processed again normally. ',
    'other_suggestions' => '',
  ),
  177 => 
  array (
    'code' => '20',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => 'This error message is displayed when there is a connection issue between Authorize.Net and the credit card processor. It results from Authorize.Net not receiving data in response to the transaction request we sent to the credit card processor. This type of issue is usually fixed quickly and we continue to work towards eliminating these types of connectivity issues. In some cases it may also be due to Internet congestion, and not related to either of our systems.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Repeated attempts to process a transaction during this connectivity breakdown may result in multiple authorizations to the credit card. To prevent this from happening, you can use the following test card:&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Test Visa Card Number: 4012888818888&amp;lt;br /&amp;gt;Expiration Date: 04/10&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;When a decline message appears for this card in Virtual Terminal, rather than the &amp;quot;Try again in 5 minutes&amp;quot; error message, this means the connectivity problem has been resolved and transactions can be processed again normally. ',
    'other_suggestions' => '',
  ),
  178 => 
  array (
    'code' => '21',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  179 => 
  array (
    'code' => '22',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  180 => 
  array (
    'code' => '23',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  181 => 
  array (
    'code' => '24',
    'text' => 'The Elavon bank number or terminal ID is incorrect. Call Merchant Service Provider.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  182 => 
  array (
    'code' => '25',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  183 => 
  array (
    'code' => '26',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  184 => 
  array (
    'code' => '27',
    'text' => 'The transaction has been declined because of an AVS mismatch. The address provided does not match billing address of cardholder.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  185 => 
  array (
    'code' => '28',
    'text' => 'The merchant does not accept this type of credit card.',
    'integration_suggestions' => 'The merchant&amp;#x27;s processor platform is not configured to accept this card type. The merchant should contact their Merchant Service Provider to request any needed merchant accounts for the card type, and to have the card type enabled on the processor',
    'other_suggestions' => 'If you encounter this error on an Authorize.Net Sandbox account, please contact &amp;lt;a href&amp;x3D;&amp;quot;https://developer.authorize.net/support/contact_us/&amp;quot&amp;gt;Developer Support&amp;lt;/a&amp;gt; for assistance.&amp;lt;br /&amp;gt; ',
  ),
  186 => 
  array (
    'code' => '29',
    'text' => 'The Paymentech identification numbers are incorrect. Call Merchant Service Provider.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Invalid Paymentech client number, merchant number or terminal number.',
  ),
  187 => 
  array (
    'code' => '30',
    'text' => 'The configuration with processor is invalid. Call Merchant Service Provider.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  188 => 
  array (
    'code' => '31',
    'text' => 'The FDC Merchant ID or Terminal ID is incorrect. Call Merchant Service Provider.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The merchant was incorrectly set up at the processor.',
  ),
  189 => 
  array (
    'code' => '32',
    'text' => 'The merchant password is invalid or not present.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  190 => 
  array (
    'code' => '33',
    'text' => '%s cannot be left blank.',
    'integration_suggestions' => 'The field is set as Required in the Merchant Interface but is not being submitted in the transaction request. Ensure that you are submitting the field, or set the field as not required.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;To change the field value to not required:&amp;lt;br /&amp;gt;&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt;&amp;lt;br /&amp;gt;  &amp;lt;li&amp;gt;Login to your Merchant Interface at &amp;lt;a href&amp;#x3D;&amp;quot;https://account.authorize.net/&amp;quot;&amp;gt;https://account.authorize.net/&amp;lt;/a &amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Settings&amp;lt;/strong&amp;gt; in the main left side menu&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Payment Form&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Form Fields&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Uncheck the field provided in the Error 33 text.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Submit&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;',
    'other_suggestions' => 'This error indicates that a field the merchant specified as required was not filled in.',
  ),
  191 => 
  array (
    'code' => '34',
    'text' => 'The VITAL identification numbers are incorrect. Call Merchant Service Provider.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The merchant was incorrectly set up at the processor.',
  ),
  192 => 
  array (
    'code' => '35',
    'text' => 'An error occurred during processing. Call Merchant Service Provider.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The merchant was incorrectly set up at the processor.',
  ),
  193 => 
  array (
    'code' => '36',
    'text' => 'The authorization was approved but settlement failed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The customer was approved at the time of authorization, but failed at settlement.',
  ),
  194 => 
  array (
    'code' => '37',
    'text' => 'The credit card number is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  195 => 
  array (
    'code' => '38',
    'text' => 'The Global Payment System identification numbers are incorrect. Call Merchant Service Provider.',
    'integration_suggestions' => 'The merchant has invalid Global Payment System NDS numbers.',
    'other_suggestions' => 'The merchant was incorrectly set up at the processor.',
  ),
  196 => 
  array (
    'code' => '39',
    'text' => 'The supplied currency code is either invalid, not supported, not allowed for this merchant or doesnt have an exchange rate.',
    'integration_suggestions' => '&amp;lt;para&amp;gt;The supplied currency code is either invalid, not supported, not allowed for this merchant or doesn&amp;#x27;t have an exchange rate.&amp;lt;/para&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;para&amp;gt;There are two possible causes of this error:&amp;lt;/para&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;para&amp;gt;1. This error may occur if you use the field currencyCode in your scripting, and you are setting it to a currency code other than what your account is set up for. Only one currency can be set for one account. At this time, Authorize.Net only supports the following currencies: AUD, CAD, CHF, DKK, EUR, GBP, NOK, NZD, PLN, SEK, USD, ZAR.&amp;lt;/para&amp;gt;&amp;lt;br /&amp;gt;  &amp;lt;br /&amp;gt;&amp;lt;para&amp;gt;2. This error may occur when an Authorize.Net account is created without a valid Currency ID. In this situation, processing transactions is not possible through the API or through the Virtual Terminal, regardless of the currency you choose.&amp;lt;/para&amp;gt;',
    'other_suggestions' => '',
  ),
  197 => 
  array (
    'code' => '40',
    'text' => 'This transaction must be encrypted.',
    'integration_suggestions' => 'Insecure transaction.',
    'other_suggestions' => '',
  ),
  198 => 
  array (
    'code' => '41',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Only merchants set up for the FraudScreen.Net service would receive this decline. This code will be returned if a given transaction&amp;#x27;s fraud score is higher than the threshold set by the merchant.',
  ),
  199 => 
  array (
    'code' => '42',
    'text' => 'There is missing or invalid information in a required field.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This is applicable only to merchants processing through the Wells Fargo SecureSource product who have requirements for transaction submission that are different from merchants not processing through Wells Fargo.',
  ),
  200 => 
  array (
    'code' => '43',
    'text' => 'The merchant was incorrectly set up at the processor. Call Merchant Service Provider.',
    'integration_suggestions' => 'The merchant has an invalid Terminal ID.',
    'other_suggestions' => 'The merchant was incorrectly set up at the processor.',
  ),
  201 => 
  array (
    'code' => '44',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => 'Regardless of the Card Code Verification filter settings configured for the payment gateway account in the Merchant Interface, the processor may decline transactions submitted with a card code value that does not match the card code on file for the cardholder at the issuing bank. To avoid unnecessary errors when processing live transactions, only valid card code values should be submitted in the card code field (&amp;lt;code&amp;gt;cardCode&amp;lt;/code&amp;gt;). If the merchant does not wish to submit card code information, the card code field should not be submitted.',
    'other_suggestions' => 'The card code submitted with the transaction did not match the card code on file at the card issuing bank and the transaction was declined.',
  ),
  202 => 
  array (
    'code' => '45',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error would be returned if the transaction received a code from the processor that matched the rejection criteria set by the merchant for both the AVS and Card Code filters.',
  ),
  203 => 
  array (
    'code' => '46',
    'text' => 'Your session has expired or does not exist. You must log in again to continue working.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  204 => 
  array (
    'code' => '47',
    'text' => 'The amount requested for settlement cannot be greater than the original amount authorized.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This occurs if the merchant tries to capture funds greater than the amount of the original authorization-only transaction.',
  ),
  205 => 
  array (
    'code' => '48',
    'text' => 'This processor does not accept partial reversals.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The merchant attempted to settle for less than the originally authorized amount.',
  ),
  206 => 
  array (
    'code' => '49',
    'text' => 'The transaction amount submitted was greater than the maximum amount allowed.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  207 => 
  array (
    'code' => '50',
    'text' => 'This transaction is awaiting settlement and cannot be refunded.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Credits or refunds may only be performed against settled transactions. The transaction against which the credit/refund was submitted has not been settled, so a credit cannot be issued.',
  ),
  208 => 
  array (
    'code' => '51',
    'text' => 'The sum of all credits against this transaction is greater than the original transaction amount.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  209 => 
  array (
    'code' => '52',
    'text' => 'The transaction was authorized but the client could not be notified; it will not be settled.',
    'integration_suggestions' => 'When Authorize.Net is responding back to a script on your server, our system waits up to 10 seconds for a response. If we do not get a response in 10 seconds, our server will time out and display an error page. The first thing that you will need to look for is the order that your script executes. It is very important that something is printed to the screen before any other process is started. If your script prints to the screen first, we will recognize that you are receiving the information. The most effective method would be to print the headers, and a line of text such as &amp;quot;Processing, please wait.&amp;quot; &amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;To resolve this issue:&amp;lt;br /&amp;gt;&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;Check that your script permissions are correct and that it can accept an HTTPS POST.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Check that the script is not completing other functions before writing to the screen, such as writing to a database or sending emails.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Please check to see if there are different processes that are used in your script for approvals, declines, or errors. Check each process to be sure that they will write to the screen before any other functions.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Check if your script is using redirects immediately upon receipt of the response from our servers. Redirects are discouraged because they can potentially interfere with the process.&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;&amp;lt;p&amp;gt;On occasion, timeouts will occur that are outside of the control of your script or our servers. Typical reasons for these timeouts include Internet traffic, your server is overloaded or malfunctioning, or Internet routing issues. Depending upon your server location and what route is used to send data, it is possible that you may occasionally receive the message you are seeing.&amp;lt;/p&amp;gt;',
    'other_suggestions' => '',
  ),
  210 => 
  array (
    'code' => '53',
    'text' => 'The transaction type is invalid for ACH transactions.',
    'integration_suggestions' => 'The &amp;lt;code&amp;gt;transactionType&amp;lt;/code&amp;gt; value is not valid for ACH transactions.',
    'other_suggestions' => 'If payment type is &amp;lt;code&amp;gt;bankAccount&amp;lt;/code&amp;gt;, &amp;lt;code&amp;gt;transactionType&amp;lt;/code&amp;gt; cannot be set to &amp;lt;code&amp;gt;captureOnlyTransaction&amp;lt;/code&amp;gt;.',
  ),
  211 => 
  array (
    'code' => '54',
    'text' => 'The referenced transaction does not meet the criteria for issuing a credit.',
    'integration_suggestions' => 'The referenced transaction does not meet the criteria for issuing a credit. It may be unsettled, an invalid type, the wrong currency, an invalid reference transaction ID or settled more than 120 days ago.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Consider applying for Expanded Credit-Return Capabilities if the merchant needs to refund transactions older than 120 days.',
    'other_suggestions' => '',
  ),
  212 => 
  array (
    'code' => '55',
    'text' => 'The sum of credits against the referenced transaction would exceed original debit amount.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction is rejected if the sum of this credit and prior credits exceeds the original debit amount.',
  ),
  213 => 
  array (
    'code' => '56',
    'text' => 'Credit card transactions are not accepted by this merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The merchant processes eCheck.Net transactions only and does not accept credit cards.',
  ),
  214 => 
  array (
    'code' => '57',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => 'This error is caused when a transaction is submitted with data that the credit card processor does not recognize or is unable to interpret. In most cases our system will prevent this from happening with front-end safeguards, but since every processor is unique in the way they handle data, some transactions could get through to the processor with invalid or missing data. Examples of these types of discrepancies include placing the incorrect number of characters in the Card Verification Value (Card Code), or sending non-alphanumeric characters in the Zip Code.',
    'other_suggestions' => '',
  ),
  215 => 
  array (
    'code' => '58',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  216 => 
  array (
    'code' => '59',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  217 => 
  array (
    'code' => '60',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  218 => 
  array (
    'code' => '61',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  219 => 
  array (
    'code' => '62',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  220 => 
  array (
    'code' => '63',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => 'If you receive an Error 63 repeatedly, please check that the Merchant Business country is set correctly. This is especially pertinent on accounts which use TSYS (formerly Vital or Visanet) as the payment processor, as we have to transmit a number of the Business Information fields on each Transaction attempt. TSYS/Vital/Visanet transactions will fail if all the information is not set correctly.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;To update the Business Information details for your account:&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;Login to the Merchant Interface at &amp;lt;a href&amp;#x3D;&amp;quot;https://account.authorize.net&amp;quot;&amp;gt;https://account.authorize.net&amp;lt;/a&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Merchant Profile&amp;lt;/strong&amp;gt; in the main left side menu&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Edit Business Information&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Update your Address, Phone Number, Fax Number, Products/Services Description, Web Site Address, and Shopping Cart Solution as necessary.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Submit&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Continue&amp;lt;/strong&amp;gt; to return to the Merchant Profile page.&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;&amp;lt;p&amp;gt;If the Merchant Profile has your correct address-including the country-then you may need to contact your Merchant Service Provider to confirm that TSYS/Vital/Visanet has the correct address for validation.',
    'other_suggestions' => '',
  ),
  221 => 
  array (
    'code' => '64',
    'text' => 'The referenced transaction was not approved.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error is applicable to Wells Fargo SecureSource merchants only. Credits or refunds cannot be issued against transactions that were not authorized.',
  ),
  222 => 
  array (
    'code' => '65',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction was declined because the merchant configured their account through the Merchant Interface to reject transactions with certain values for a Card Code mismatch.',
  ),
  223 => 
  array (
    'code' => '66',
    'text' => 'This transaction cannot be accepted for processing.',
    'integration_suggestions' => 'Transaction was submitted using HTTP GET which does not meet payment gateway security guidelines.',
    'other_suggestions' => 'If you are using the SIM connection method, make sure your code is providing values for the SIM required fields listed below:&amp;lt;br /&amp;gt;  &amp;lt;ul&amp;gt;      &amp;lt;li&amp;gt;The sequence number of the transaction (x_fp_sequence)&amp;lt;/li&amp;gt;       &amp;lt;li&amp;gt;The time when the sequence number was generated (x_fp_timestamp)&amp;lt;/li&amp;gt;    &amp;lt;li&amp;gt;The Fingerprint Hash (x_fp_hash)&amp;lt;/li&amp;gt;',
  ),
  224 => 
  array (
    'code' => '67',
    'text' => 'The given transaction type is not supported for this merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code is applicable to merchants using the Wells Fargo SecureSource product only. This product does not allow transactions of type CAPTURE_ONLY.',
  ),
  225 => 
  array (
    'code' => '68',
    'text' => 'The version parameter is invalid',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;x_version&amp;lt;/code&amp;gt; was invalid.',
  ),
  226 => 
  array (
    'code' => '69',
    'text' => 'The transaction type is invalid',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;transactionType&amp;lt;/code&amp;gt; was invalid.',
  ),
  227 => 
  array (
    'code' => '70',
    'text' => 'The transaction method is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;x_method&amp;lt;/code&amp;gt; was invalid.',
  ),
  228 => 
  array (
    'code' => '71',
    'text' => 'The bank account type is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;accountType&amp;lt;/code&amp;gt; was invalid.',
  ),
  229 => 
  array (
    'code' => '72',
    'text' => 'The authorization code is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;authCode&amp;lt;/code&amp;gt; was more than six characters in length.',
  ),
  230 => 
  array (
    'code' => '73',
    'text' => 'The drivers license date of birth is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The format of the value submitted in &amp;lt;code&amp;gt;x_drivers_license_num&amp;lt;/code&amp;gt; was invalid.',
  ),
  231 => 
  array (
    'code' => '74',
    'text' => 'The duty amount is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;duty&amp;lt;/code&amp;gt; failed format validation.',
  ),
  232 => 
  array (
    'code' => '75',
    'text' => 'The freight amount is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;freight&amp;lt;/code&amp;gt; failed format validation.',
  ),
  233 => 
  array (
    'code' => '76',
    'text' => 'The tax amount is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;tax&amp;lt;/code&amp;gt; failed format validation.',
  ),
  234 => 
  array (
    'code' => '77',
    'text' => 'The SSN or tax ID is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;x_customer_tax_id&amp;lt;/code&amp;gt; failed validation.',
  ),
  235 => 
  array (
    'code' => '78',
    'text' => 'The card code is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;cardCode&amp;lt;/code&amp;gt; failed format validation.',
  ),
  236 => 
  array (
    'code' => '79',
    'text' => 'The drivers license number is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;x_drivers_license_num&amp;lt;/code&amp;gt; failed format validation.',
  ),
  237 => 
  array (
    'code' => '80',
    'text' => 'The drivers license state is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted in &amp;lt;code&amp;gt;x_drivers_license_state&amp;lt;/code&amp;gt; failed format validation.',
  ),
  238 => 
  array (
    'code' => '81',
    'text' => 'The requested form type is invalid.',
    'integration_suggestions' => 'Invalid value for &amp;lt;code&amp;gt;x_show_form&amp;lt;/code&amp;gt;.',
    'other_suggestions' => 'The merchant requested an integration method not compatible with the AIM API.',
  ),
  239 => 
  array (
    'code' => '82',
    'text' => 'Scripts are only supported in version 2.5.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The system no longer supports version 2.5; requests cannot be posted to scripts.',
  ),
  240 => 
  array (
    'code' => '83',
    'text' => 'The requested script is either invalid or no longer supported.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The system no longer supports version 2.5; requests cannot be posted to scripts.',
  ),
  241 => 
  array (
    'code' => '84',
    'text' => 'The device type is invalid or missing.',
    'integration_suggestions' => 'deviceType is either missing or invalid. deviceType is required when the retail element is submitted.',
    'other_suggestions' => 'Invalid value for &amp;lt;code&amp;gt;deviceType&amp;lt;/code&amp;gt;.',
  ),
  242 => 
  array (
    'code' => '85',
    'text' => 'The market type is invalid',
    'integration_suggestions' => 'marketType is either missing or invalid. marketType is required when the retail element is submitted.',
    'other_suggestions' => 'Invalid value for &amp;lt;code&amp;gt;marketType&amp;lt;/code&amp;gt;.',
  ),
  243 => 
  array (
    'code' => '86',
    'text' => 'The Response Format is invalid',
    'integration_suggestions' => '',
    'other_suggestions' => 'Invalid value for &amp;lt;code&amp;gt;x_response_format&amp;lt;/code&amp;gt;.',
  ),
  244 => 
  array (
    'code' => '87',
    'text' => 'Transactions of this market type cannot be processed on this system.',
    'integration_suggestions' => 'This can happen for four reasons:&amp;lt;br /&amp;gt;1) You are attempting to process a type of transaction that your account is not designed for. An example would be using a card swipe machine to process transactions on an e-commerce or mail order/telephone order (MOTO) account.&amp;lt;br /&amp;gt;2) You are passing an incorrect value for &amp;quot;marketType&amp;quot; parameter. For a merchant account processing &amp;quot;retail&amp;quot; type transactions, you would want to pass a value of &amp;quot;2&amp;quot;. The possible values for the &amp;quot;marketType&amp;quot; parameter can be found in our &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/api/reference/#payment-transactions&amp;quot;&amp;gt;API Reference Guide&amp;lt;/a&amp;gt;.&amp;lt;br /&amp;gt;3) Your Merchant Service Provider may be incorrectly setup for this account.&amp;lt;br /&amp;gt;4) Your account may be configured incorrectly with an incorrect Merchant Service Provider or an incorrect MCC or SIC code.',
    'other_suggestions' => '',
  ),
  245 => 
  array (
    'code' => '88',
    'text' => 'Track1 data is not in a valid format.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Invalid value for &amp;lt;code&amp;gt;track1&amp;lt;/code&amp;gt;.',
  ),
  246 => 
  array (
    'code' => '89',
    'text' => 'Track2 data is not in a valid format.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Invalid value for &amp;lt;code&amp;gt;track2&amp;lt;/code&amp;gt;.',
  ),
  247 => 
  array (
    'code' => '90',
    'text' => 'ACH transactions cannot be accepted by this system.',
    'integration_suggestions' => 'ACH transactions cannot be processed using a card present account.',
    'other_suggestions' => '',
  ),
  248 => 
  array (
    'code' => '91',
    'text' => 'Version 2.5 is no longer supported.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  249 => 
  array (
    'code' => '92',
    'text' => 'The gateway no longer supports the requested method of integration.',
    'integration_suggestions' => 'This error can occur for several possible reasons, depending on which method your software uses to connect to your Authorize.Net account. Usually it is due to mixing methods in unsupported ways.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;u&amp;gt;Advanced Integration Method (AIM) Suggestions&amp;lt;/u&amp;gt;:&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;1. When using AIM to integrate with Authorize.Net, the HTTP POST request must be made from a script located in a secure location on your server, and not through an HTML page. Submitting an AIM request from an unsecured HTML page may cause this error.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;2. Be sure that your account settings allow delimited responses.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;To check your settings:&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;Login to the Merchant Interface at &amp;lt;a href&amp;#x3D;&amp;quot;https://account.authorize.net/&amp;quot;&amp;gt;https://account.authorize.net/&amp;lt;/a&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click on &amp;lt;strong&amp;gt;Settings&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click on &amp;lt;strong&amp;gt;Direct Response&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Change Delimited Response to &amp;quot;True&amp;quot;&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;3. Confirm that you are sending us the field &amp;lt;code&amp;gt;x_delim_data&amp;lt;/code&amp;gt;, and that it is set to &amp;lt;code&amp;gt;TRUE&amp;lt;/code&amp;gt;.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;4. Also confirm that you are sending the field, &amp;lt;code&amp;gt;x_relay_response&amp;lt;/code&amp;gt;, set to &amp;lt;code&amp;gt;FALSE&amp;lt;/code&amp;gt;. Otherwise, we will attempt to use any default Relay Response or receipt links listed in your &amp;lt;strong&amp;gt;Response/Receipt URL&amp;lt;/strong&amp;gt; settings in the Merchant Interface, which causes this error.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;u&amp;gt;Simple Integration Method (SIM) Suggestions&amp;lt;/u&amp;gt;:&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;1. You will receive this error if there are variables being sent that are not applicable to SIM. Two of the variables that are most commonly incorrect include:&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;code&amp;gt;x_adc_relay_response&amp;lt;/code&amp;gt; - the variable name should be sent as &amp;lt;code&amp;gt;x_relay_response&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;code&amp;gt;x_adc_relay_url&amp;lt;/code&amp;gt; - the variable name should be sent as &amp;lt;code&amp;gt;x_relay_url&amp;lt;/code&amp;gt;.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;2. You will receive this error if the proper fingerprint hash variables are not being sent with the transaction request. The variables that need to be included (with appropriate values):&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;code&amp;gt;x_fp_hash&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;code&amp;gt;x_fp_sequence&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;code&amp;gt;x_fp_timestamp&amp;lt;/code&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;These variables are used in creating and then passing the fingerprint hash with the transaction request. Authorize.Net then uses the passed variables and the stored transaction key to attempt to create the same fingerprint hash. If the two fingerprints match, we accept the transaction. If not, the transaction request is refused.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;We highly recommend that you upgrade your connection method.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Please visit &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/&amp;quot;&amp;gt;our Developer Center&amp;lt;/a&amp;gt; for up-to-date documentation.',
    'other_suggestions' => ' ',
  ),
  250 => 
  array (
    'code' => '93',
    'text' => 'A valid country is required.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This code is applicable to Wells Fargo SecureSource merchants only. Country is a required field and must contain the value of a supported country.',
  ),
  251 => 
  array (
    'code' => '94',
    'text' => 'The shipping state or country is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This code is applicable to Wells Fargo SecureSource merchants only.',
  ),
  252 => 
  array (
    'code' => '95',
    'text' => 'A valid state is required.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This code is applicable to Wells Fargo SecureSource merchants only.',
  ),
  253 => 
  array (
    'code' => '96',
    'text' => 'This country is not authorized for buyers.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This code is applicable to Wells Fargo SecureSource merchants only. Country is a required field and must contain the value of a supported country.',
  ),
  254 => 
  array (
    'code' => '97',
    'text' => 'This transaction cannot be accepted.',
    'integration_suggestions' => 'Please use the &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/api/reference/responseCode97.html&amp;quot;&amp;gt;Response Code 97 Tool&amp;lt;/a&amp;gt;',
    'other_suggestions' => 'Applicable only to the SIM API. Fingerprints are only valid for a short period of time. This code indicates that the transaction fingerprint has expired.',
  ),
  255 => 
  array (
    'code' => '98',
    'text' => 'This transaction cannot be accepted.',
    'integration_suggestions' => 'Part of the security feature for SIM (Server Integration Method) includes the generation of a fingerprint hash. The fingerprint hash is generated by a function in the scripting that uses five parameters to generate the fingerprint hash.&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;The amount of the transaction;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;A sequence number&amp;#151;usually an invoice number generated by your scripting, but can be randomly generated as long as it doesn&amp;#x27;t repeat;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Your server&amp;#x27;s timestamp, expressed in Greenwich Mean Time (GMT) or Coordinated Universal Time (UTC);&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Your account&amp;#x27;s Transaction Key; and&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Your account&amp;#x27;s API Login ID.&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;&amp;lt;p&amp;gt;Any fingerprint hash can only be used once in 24 hours.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;If a customer fills in incorrect information and the transaction is declined, they cannot click Back and re-enter the information as this will attempt to use the same fingerprint hash in resubmitting the transaction request, which will result in error 98.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;The customer must be directed back to the page that sets the amount of the transaction request, then re-enter information from that point on.',
    'other_suggestions' => 'Applicable only to the SIM API. The transaction fingerprint has already been used.',
  ),
  256 => 
  array (
    'code' => '99',
    'text' => 'This transaction cannot be accepted.',
    'integration_suggestions' => 'Please use the &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/api/reference/responseCode99.html&amp;quot;&amp;gt;Response Code 99 Tool&amp;lt;/a&amp;gt;.',
    'other_suggestions' => 'Applicable only to the SIM API. The server-generated fingerprint does not match the merchant-specified fingerprint in the x_fp_hash field.',
  ),
  257 => 
  array (
    'code' => '100',
    'text' => 'The eCheck type parameter is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Applicable only to eCheck.Net. The value specified in the &amp;lt;code&amp;gt;echeckType&amp;lt;/code&amp;gt; field is invalid.',
  ),
  258 => 
  array (
    'code' => '101',
    'text' => 'The given name on the account and/or the account type does not match the actual account.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Applicable only to eCheck.Net. The specified name on the account and/or the account type do not match the NOC record for this account.',
  ),
  259 => 
  array (
    'code' => '102',
    'text' => 'This request cannot be accepted.',
    'integration_suggestions' => '&amp;lt;b&amp;gt;NOTE: This response is valid only for an integration method that has been sunset and is no longer available. If you encounter this error, please contact &amp;lt;a href&amp;#x3D;&amp;quot;https://www.authorize.net/support/&amp;quot;&amp;gt;Merchant Support&amp;lt;/a&amp;gt; for assistance.&amp;lt;/b&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;If you are receiving this error, it indicates that either &amp;lt;code&amp;gt;x_password&amp;lt;/code&amp;gt; or &amp;lt;code&amp;gt;x_tran_key&amp;lt;/code&amp;gt; is being submitted with your WebLink request. This represents a security risk as the password or transaction key could be viewed in your source code.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;We highly recommend that you upgrade your connection method.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Please visit &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/&amp;quot;&amp;gt;our Developer Center&amp;lt;/a&amp;gt; for up-to-date documentation.',
    'other_suggestions' => 'A transaction key was submitted with this WebLink request.',
  ),
  260 => 
  array (
    'code' => '103',
    'text' => 'This transaction cannot be accepted.',
    'integration_suggestions' => 'This error is no longer in use.',
    'other_suggestions' => '',
  ),
  261 => 
  array (
    'code' => '104',
    'text' => 'The transaction is currently under review.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Applicable only to eCheck.Net. The value submitted for &amp;lt;code&amp;gt;country&amp;lt;/code&amp;gt; failed validation.',
  ),
  262 => 
  array (
    'code' => '105',
    'text' => 'The transaction is currently under review.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Applicable only to eCheck.Net. The values submitted for &amp;lt;code&amp;gt;city&amp;lt;/code&amp;gt; and &amp;lt;code&amp;gt;country&amp;lt;/code&amp;gt; failed validation.',
  ),
  263 => 
  array (
    'code' => '106',
    'text' => 'The transaction is currently under review.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Applicable only to eCheck.Net. The value submitted for &amp;lt;code&amp;gt;company&amp;lt;/code&amp;gt; failed validation.',
  ),
  264 => 
  array (
    'code' => '107',
    'text' => 'The transaction is currently under review.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted for bank account name failed validation.',
  ),
  265 => 
  array (
    'code' => '108',
    'text' => 'The transaction is currently under review.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Applicable only to eCheck.Net. The values submitted for &amp;lt;code&amp;gt;firstName&amp;lt;/code&amp;gt; and &amp;lt;code&amp;gt;lastName&amp;lt;/code&amp;gt; failed validation.',
  ),
  266 => 
  array (
    'code' => '109',
    'text' => 'The transaction is currently under review.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Applicable only to eCheck.Net. The values submitted for &amp;lt;code&amp;gt;firstName&amp;lt;/code&amp;gt; and &amp;lt;code&amp;gt;lastName&amp;lt;/code&amp;gt; failed validation.',
  ),
  267 => 
  array (
    'code' => '110',
    'text' => 'The transaction is currently under review.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted for &amp;lt;code&amp;gt;accountName&amp;lt;/code&amp;gt; does not contain valid characters.',
  ),
  268 => 
  array (
    'code' => '111',
    'text' => 'A valid billing country is required.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This code is applicable to Wells Fargo SecureSource merchants only.',
  ),
  269 => 
  array (
    'code' => '112',
    'text' => 'A valid billing state/province is required.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This code is applicable to Wells Fargo SecureSource merchants only.',
  ),
  270 => 
  array (
    'code' => '113',
    'text' => 'The commercial card type is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  271 => 
  array (
    'code' => '114',
    'text' => 'The merchant account is in test mode. This automated payment will not be processed.',
    'integration_suggestions' => 'The merchant account is in test mode. All automated payments are skipped when in test mode.',
    'other_suggestions' => '',
  ),
  272 => 
  array (
    'code' => '115',
    'text' => 'The merchant account is not active. This automated payment will not be processed.',
    'integration_suggestions' => 'The merchant account is not active. All automated payments are skipped when an account is not active.',
    'other_suggestions' => '',
  ),
  273 => 
  array (
    'code' => '116',
    'text' => 'The authentication indicator is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This code is applicable only to merchants that include the &amp;lt;code&amp;gt;authenticationIndicator&amp;lt;/code&amp;gt; in the transaction request.  The ECI value for a Visa transaction; or the UCAF indicator for a Mastercard transaction submitted in the &amp;lt;code&amp;gt;authenticationIndicator&amp;lt;/code&amp;gt; field is invalid.',
  ),
  274 => 
  array (
    'code' => '117',
    'text' => 'The cardholder authentication value is invalid.',
    'integration_suggestions' => 'First, verify that the merchant&amp;#x27;s processor supports Visa Secure and Mastercard Identity Check authentication values through Authorize.Net.&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;Chase Paymentech&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;FDMS Nashville (formerly FDC)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Global Payments (GPS)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;TSYS (formerly Vital)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Wells Fargo (Visa Secure only)&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;&amp;lt;p&amp;gt;Also, this error can be received in the event that a special character is included in the cardholder authentication value. To resolve this issue, the special character must be URL encoded.&amp;lt;/p&amp;gt;',
    'other_suggestions' => 'This code is applicable only to merchants that include the &amp;lt;code&amp;gt;cardholderAuthenticationValue&amp;lt;/code&amp;gt; in the transaction request. The CAVV for a Visa transaction or the AVV/UCAF for a Mastercard transaction is invalid or contains an invalid character.',
  ),
  275 => 
  array (
    'code' => '118',
    'text' => 'The combination of card type, authentication indicator and cardholder authentication value is invalid.',
    'integration_suggestions' => 'For example, when the Mastercard value for &amp;lt;code&amp;gt;authenticationIndicator&amp;lt;/code&amp;gt; is 1, &amp;lt;code&amp;gt;cardholderAuthenticationValue&amp;lt;/code&amp;gt; must be null. In this scenario, if a value is submitted for &amp;lt;code&amp;gt;cardholderAuthenticationValue&amp;lt;/code&amp;gt;, the transaction fails validation and is rejected.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Also, verify that the merchant&amp;#x27;s processor supports Visa Secure and Mastercard Identity Check authentication values through Authorize.Net. &amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;Chase Paymentech&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;FDMS Nashville (formerly FDC)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Global Payments (GPS)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;TSYS (formerly Vital)&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;',
    'other_suggestions' => 'This code is applicable only to merchants that include the &amp;lt;code&amp;gt;authenticationIndicator&amp;lt;/code&amp;gt; and &amp;lt;code&amp;gt;cardholderAuthenticationValue&amp;lt;/code&amp;gt; in the transaction request. The combination of &amp;lt;code&amp;gt;authenticationIndicator&amp;lt;/code&amp;gt; and &amp;lt;code&amp;gt;cardholderAuthenticationValue&amp;lt;/code&amp;gt; is invalid.',
  ),
  276 => 
  array (
    'code' => '119',
    'text' => 'Transactions having cardholder authentication values cannot be marked as recurring.',
    'integration_suggestions' => 'Transactions that have Visa Secure type of input parameters cannot be recurring.',
    'other_suggestions' => 'This code is applicable only to merchants that include the &amp;lt;code&amp;gt;authenticationIndicator&amp;lt;/code&amp;gt; and &amp;lt;code&amp;gt;recurringBilling&amp;lt;/code&amp;gt; in the transaction request. Transactions submitted with a value in &amp;lt;code&amp;gt;authenticationIndicator&amp;lt;/code&amp;gt; while &amp;lt;code&amp;gt;recurringBilling&amp;lt;/code&amp;gt; is set to &amp;lt;code&amp;gt;true&amp;lt;/code&amp;gt; will be rejected.',
  ),
  277 => 
  array (
    'code' => '120',
    'text' => 'An error occurred during processing. Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The system-generated void for the original timed-out transaction failed. The original transaction timed out while waiting for a response from the authorizer.',
  ),
  278 => 
  array (
    'code' => '121',
    'text' => 'An error occurred during processing. Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The system-generated void for the original errored transaction failed. The original transaction experienced a database error.',
  ),
  279 => 
  array (
    'code' => '122',
    'text' => 'An error occurred during processing. Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The system-generated void for the original errored transaction failed. The original transaction experienced a processing error.',
  ),
  280 => 
  array (
    'code' => '123',
    'text' => 'This account has not been given the permission(s) required for this request.',
    'integration_suggestions' => 'This error indicates that a user&amp;#x27;s personal Login ID is being used to connect a website or billing software to the payment gateway. Personal login IDs may not be used to connect websites to Authorize.Net, for security reasons. For example, if an Account Owner, Account Administrator, Transaction Manager, or Account Analyst login ID is used for website or software implementation, this error will occur.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;To resolve this issue, the API Login ID and Transaction Key will need to be generated and added to your software&amp;#x27;s configuration, so that the website or software may connect to Authorize.Net properly.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;To obtain the API Login ID:&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;Log into the Merchant Interface at &amp;lt;a href&amp;#x3D;&amp;quot;https://account.authorize.net/&amp;quot;&amp;gt;https://account.authorize.net/&amp;lt;/a&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Settings&amp;lt;/strong&amp;gt; in the main left side menu&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;API Credentials &amp;amp; Keys&amp;lt;/strong&amp;gt;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;If an API login ID has already been generated, it will be visible here. If an API Login ID needs to be generated, request and verify a PIN in order to see the API Login ID.&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;',
    'other_suggestions' => 'The transaction request must include the API login ID associated with the payment gateway account.',
  ),
  281 => 
  array (
    'code' => '124',
    'text' => 'This processor does not accept recurring transactions.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  282 => 
  array (
    'code' => '125',
    'text' => 'The surcharge amount is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  283 => 
  array (
    'code' => '126',
    'text' => 'The Tip amount is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  284 => 
  array (
    'code' => '127',
    'text' => 'The transaction resulted in an AVS mismatch. The address provided does not match billing address of cardholder.',
    'integration_suggestions' => 'When merchants on the FDC Omaha platform encounter a decline due to AVS or CVV mismatch, we will attempt to void the transaction. If FDC Omaha does not reply to the void request, the merchant will see this error. As we did not receive a reply to the void request, there is a possibility that the original authorization will remain on the card for up to 30 days. If necessary, merchants may contact the card issuing bank, provide their merchant account number and the authorization code for the AVS/CVV declined transaction, and request a manual reversal of the authorization.',
    'other_suggestions' => 'The system-generated void for the original AVS-rejected transaction failed.',
  ),
  285 => 
  array (
    'code' => '128',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The customer&amp;#x27;s financial institution does not currently allow transactions for this account.',
  ),
  286 => 
  array (
    'code' => '130',
    'text' => 'This merchant account has been closed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The payment gateway account status is Blacklisted.',
  ),
  287 => 
  array (
    'code' => '131',
    'text' => 'This transaction cannot be accepted at this time.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The payment gateway account status is Suspended-STA.',
  ),
  288 => 
  array (
    'code' => '132',
    'text' => 'This transaction cannot be accepted at this time.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The payment gateway account status is Suspended - Blacklist.',
  ),
  289 => 
  array (
    'code' => '141',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The system-generated void for the original FraudScreen-rejected transaction failed.',
  ),
  290 => 
  array (
    'code' => '145',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The system-generated void for the original card code-rejected and AVS-rejected transaction failed.',
  ),
  291 => 
  array (
    'code' => '152',
    'text' => 'The transaction was authorized but the client could not be notified; it will not be settled.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The system-generated void for the original transaction failed. The response for the original transaction could not be communicated to the client.',
  ),
  292 => 
  array (
    'code' => '153',
    'text' => 'There was an error processing the payment data.',
    'integration_suggestions' => '',
    'other_suggestions' => '&amp;lt;ul&amp;gt;&amp;lt;li&amp;gt;Set &amp;lt;strong&amp;gt;marketType&amp;lt;/strong&amp;gt; to &amp;quot;0&amp;quot; to flag the transaction as e-commerce.&amp;lt;/li&amp;gt;&amp;lt;li&amp;gt;Set &amp;lt;strong&amp;gt;transactionType&amp;lt;/strong&amp;gt; to &amp;lt;strong&amp;gt;authCaptureTransaction&amp;lt;/strong&amp;gt; or &amp;lt;strong&amp;gt;authOnlyTransaction&amp;lt;/strong&amp;gt;.&amp;lt;/li&amp;gt;&amp;lt;li&amp;gt;Specify both opaque data parameters.&amp;lt;/li&amp;gt;&amp;lt;li&amp;gt;Do not include card number, expiration date, or track data.&amp;lt;/li&amp;gt;&amp;lt;li&amp;gt;Do not include 3DS data.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Ensure that you submit data that can be successfully decrypted.&amp;lt;/li&amp;gt;&amp;lt;li&amp;gt;Only submit decrypted data that belongs to the merchant submitting the request.&amp;lt;/li&amp;gt;&amp;lt;li&amp;gt;Encode the submitted data in Base64.&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;',
  ),
  293 => 
  array (
    'code' => '154',
    'text' => 'Processing Apple Payments is not enabled for this merchant account.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  294 => 
  array (
    'code' => '155',
    'text' => 'This processor does not support this method of submitting payment data.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  295 => 
  array (
    'code' => '156',
    'text' => 'The cryptogram is either invalid or cannot be used in combination with other parameters.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  296 => 
  array (
    'code' => '165',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => 'System void failed. CVV2 Code mismatch based on the CVV response and the merchant settings.',
  ),
  297 => 
  array (
    'code' => '170',
    'text' => 'An error occurred during processing. Please contact the merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Concord EFS - Provisioning at the processor has not been completed.',
  ),
  298 => 
  array (
    'code' => '171',
    'text' => 'An error occurred during processing. Please contact the merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Concord EFS - This request is invalid.',
  ),
  299 => 
  array (
    'code' => '172',
    'text' => 'An error occurred during processing. Please contact the merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Concord EFS - The store ID is invalid.',
  ),
  300 => 
  array (
    'code' => '173',
    'text' => 'An error occurred during processing. Please contact the merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Concord EFS - The store key is invalid.',
  ),
  301 => 
  array (
    'code' => '174',
    'text' => 'The transaction type is invalid. Please contact the merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Concord EFS - This transaction type is not accepted by the processor.',
  ),
  302 => 
  array (
    'code' => '175',
    'text' => 'This processor does not allow voiding of credits.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Concord EFS - This transaction is not allowed. The Concord EFS processing platform does not support voiding credit transactions. Please debit the credit card instead of voiding the credit.',
  ),
  303 => 
  array (
    'code' => '180',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => 'There are three different reasons that an Error 180 might occur:&amp;lt;ol&amp;gt; &amp;lt;li&amp;gt;There was an attempt to void a refund on a processor that does not allow that.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;A merchant on the Concord EFS platform is attempting to pass AMEX CID, when they are not signed up to validate this value with AMEX.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Transactions submitted from test credit card numbers (both ours and others&amp;#x27;) by merchants on the TSYS payment processing platform, will return a response of:  &amp;quot;(180) An error occurred during processing. Please try again. Invalid processor response format,&amp;quot; rather than &amp;quot;(2) Declined.  This transaction has been declined.&amp;quot;&amp;lt;/li&amp;gt; &amp;lt;/ol&amp;gt;&amp;lt;p&amp;gt;Note that the TSYS payment processing platform was formerly known as Vital or Visanet. On TSYS/Vital/Visanet, Error 180 is an valid response indicating that a transaction was submitted and correctly received, but rejected due to using a test card number.  If the processor is incorrectly configured, the response will be something more generic like a response of 30, 34, or 35.',
    'other_suggestions' => 'The processor response format is invalid.',
  ),
  304 => 
  array (
    'code' => '181',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The system-generated void for the original invalid transaction failed. (The original transaction included an invalid processor response format.)',
  ),
  305 => 
  array (
    'code' => '182',
    'text' => 'One or more of the sub-merchant values are invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  306 => 
  array (
    'code' => '183',
    'text' => 'One or more of the required sub-merchant values are missing.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  307 => 
  array (
    'code' => '184',
    'text' => 'Invalid Token Requestor Name.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  308 => 
  array (
    'code' => '185',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Merchant is not configured for VPOS.',
  ),
  309 => 
  array (
    'code' => '186',
    'text' => 'Invalid Token Requestor ID Length.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  310 => 
  array (
    'code' => '187',
    'text' => 'Invalid Token Requestor ECI Length.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  311 => 
  array (
    'code' => '191',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  312 => 
  array (
    'code' => '192',
    'text' => 'An error occurred during processing. Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  313 => 
  array (
    'code' => '193',
    'text' => 'The transaction is currently under review.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  314 => 
  array (
    'code' => '195',
    'text' => 'One or more of the HTML type configuration fields do not appear to be safe.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  315 => 
  array (
    'code' => '200',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The credit card number is invalid.',
  ),
  316 => 
  array (
    'code' => '201',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The expiration date is invalid.',
  ),
  317 => 
  array (
    'code' => '202',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The transaction type is invalid.',
  ),
  318 => 
  array (
    'code' => '203',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The value submitted in the amount field is invalid.',
  ),
  319 => 
  array (
    'code' => '204',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The department code is invalid.',
  ),
  320 => 
  array (
    'code' => '205',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The value submitted in the merchant number field is invalid.',
  ),
  321 => 
  array (
    'code' => '206',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The merchant is not on file.',
  ),
  322 => 
  array (
    'code' => '207',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The merchant account is closed.',
  ),
  323 => 
  array (
    'code' => '208',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The merchant is not on file.',
  ),
  324 => 
  array (
    'code' => '209',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. Communication with the processor could not be established.',
  ),
  325 => 
  array (
    'code' => '210',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The merchant type is incorrect.',
  ),
  326 => 
  array (
    'code' => '211',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The cardholder is not on file.',
  ),
  327 => 
  array (
    'code' => '212',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The bank configuration is not on file.',
  ),
  328 => 
  array (
    'code' => '213',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The merchant assessment code is incorrect.',
  ),
  329 => 
  array (
    'code' => '214',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. This function is currently unavailable.',
  ),
  330 => 
  array (
    'code' => '215',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The encrypted PIN field format is invalid.',
  ),
  331 => 
  array (
    'code' => '216',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The ATM term ID is invalid.',
  ),
  332 => 
  array (
    'code' => '217',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. This transaction experienced a general message format problem.',
  ),
  333 => 
  array (
    'code' => '218',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The PIN block format or PIN availability value is invalid.',
  ),
  334 => 
  array (
    'code' => '219',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The ETC void is unmatched.',
  ),
  335 => 
  array (
    'code' => '220',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The primary CPU is not available.',
  ),
  336 => 
  array (
    'code' => '221',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. The SE number is invalid.',
  ),
  337 => 
  array (
    'code' => '222',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. Duplicate auth request (from INAS).',
  ),
  338 => 
  array (
    'code' => '223',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. This transaction experienced an unspecified error.',
  ),
  339 => 
  array (
    'code' => '224',
    'text' => 'This transaction has been declined',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error code applies only to merchants on FDC Omaha. Please re-enter the transaction.',
  ),
  340 => 
  array (
    'code' => '225',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction has an invalid dynamic currency conversion (DCC) action.',
  ),
  341 => 
  array (
    'code' => '226',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Incomplete set of Dynamic Currency Conversion (DCC) parameters.',
  ),
  342 => 
  array (
    'code' => '227',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Merchant is not configured for Dynamic Currency Conversion (DCC). ',
  ),
  343 => 
  array (
    'code' => '228',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Dynamic Currency Conversion (DCC) is not allowed for this transaction type. ',
  ),
  344 => 
  array (
    'code' => '229',
    'text' => 'Conversion rate for this card is available.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  345 => 
  array (
    'code' => '230',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => 'Transaction could not be found.',
    'other_suggestions' => '',
  ),
  346 => 
  array (
    'code' => '231',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Incoming data is different than the referenced Dynamic Currency Conversion (DCC) transaction.',
  ),
  347 => 
  array (
    'code' => '232',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Merchant is not configured for Dynamic Currency Conversion (DCC). ',
  ),
  348 => 
  array (
    'code' => '233',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Cannot perform Dynamic Currency Conversion (DCC) action on this transaction.',
  ),
  349 => 
  array (
    'code' => '234',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This transaction has been voided. ',
  ),
  350 => 
  array (
    'code' => '235',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This transaction has been captured previously. ',
  ),
  351 => 
  array (
    'code' => '236',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Dynamic Currency Conversion (DCC) data for the referenced transaction is not available.',
  ),
  352 => 
  array (
    'code' => '237',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The referenced transaction has expired.',
  ),
  353 => 
  array (
    'code' => '238',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction version does not support Dynamic Currency Conversion (DCC). ',
  ),
  354 => 
  array (
    'code' => '239',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The response format for this transaction does not support Dynamic Currency Conversion (DCC).',
  ),
  355 => 
  array (
    'code' => '240',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Currency for Dynamic Currency Conversion (DCC) transactions must be US dollars.',
  ),
  356 => 
  array (
    'code' => '241',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Invalid response from card processor. ',
  ),
  357 => 
  array (
    'code' => '242',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Recurring billing flag not allowed on Dynamic Currency Conversion (DCC). ',
  ),
  358 => 
  array (
    'code' => '243',
    'text' => 'Recurring billing is not allowed for this eCheck.Net type.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The combination of values submitted for &amp;lt;code&amp;gt;recurringBilling&amp;lt;/code&amp;gt; and &amp;lt;code&amp;gt;echeckType&amp;lt;/code&amp;gt; is not allowed.',
  ),
  359 => 
  array (
    'code' => '244',
    'text' => 'This eCheck.Net type is not allowed for this Bank Account Type.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The combination of values submitted for &amp;lt;code&amp;gt;accountType&amp;lt;/code&amp;gt; and &amp;lt;code&amp;gt;echeckType&amp;lt;/code&amp;gt; is not allowed.',
  ),
  360 => 
  array (
    'code' => '245',
    'text' => 'This eCheck.Net type is not allowed when using the payment gateway hosted payment form.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The value submitted for &amp;lt;code&amp;gt;echeckType&amp;lt;/code&amp;gt; is not allowed when using the payment gateway hosted payment form.',
  ),
  361 => 
  array (
    'code' => '246',
    'text' => 'This eCheck.Net type is not allowed.',
    'integration_suggestions' => 'This error also occurs if you submit a check number for your WEB, TEL, CCD, or PPD eCheck.Net transaction. Check numbers are only valid for ARC and BOC eCheck.Net transactions. See the &amp;lt;a href&amp;#x3D;&amp;quot;https://www.authorize.net/content/dam/authorize/documents/echecknetuserguide.pdf&amp;gt; eCheck.Net User Guide&amp;lt;/a&amp;gt; for details on eCheck.Net transaction types and requirements.',
    'other_suggestions' => 'The merchant&amp;#x27;s payment gateway account is not enabled to submit the eCheck.Net type.',
  ),
  362 => 
  array (
    'code' => '247',
    'text' => 'This eCheck.Net type is not allowed.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The combination of values submitted for &amp;lt;code&amp;gt;transactionType&amp;lt;/code&amp;gt; and &amp;lt;code&amp;gt;echeckType&amp;lt;/code&amp;gt; is not allowed.',
  ),
  363 => 
  array (
    'code' => '248',
    'text' => 'The check number is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Invalid check number. Check numbers can only consist of letters and numbers, up to 15 characters.',
  ),
  364 => 
  array (
    'code' => '250',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This transaction was submitted from a blocked IP address.',
  ),
  365 => 
  array (
    'code' => '251',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => 'This transaction was submitted from a blocked IP address. ',
    'other_suggestions' => 'The transaction was declined as a result of triggering a Fraud Detection Suite filter.',
  ),
  366 => 
  array (
    'code' => '252',
    'text' => 'Your order has been received. Thank you for your business!',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction was accepted, but is being held for merchant review.  The merchant may customize the customer response in the Merchant Interface.',
  ),
  367 => 
  array (
    'code' => '253',
    'text' => 'Your order has been received. Thank you for your business!',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction was accepted and was authorized, but is being held for merchant review.  The merchant may customize the customer response in the Merchant Interface.',
  ),
  368 => 
  array (
    'code' => '254',
    'text' => 'This transaction has been declined.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction was declined after manual review.',
  ),
  369 => 
  array (
    'code' => '260',
    'text' => 'Reversal not supported for this transaction type.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Only authorizations and credits can be reversed. ',
  ),
  370 => 
  array (
    'code' => '261',
    'text' => 'An error occurred during processing.  Please try again.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction experienced an error during sensitive data encryption and was not processed.  Please try again.',
  ),
  371 => 
  array (
    'code' => '262',
    'text' => 'The PayformMask is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  372 => 
  array (
    'code' => '265',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => 'The transaction was submitted with an invalid Solution ID. For details on how to register and submit a Solution ID, please see &amp;lt;a href&amp;#x3D;&amp;quot;https://developer.authorize.net/api/solution_id/&amp;quot;&amp;gt;The Solution ID Implementation Guide&amp;lt;/a&amp;gt;.',
    'other_suggestions' => '',
  ),
  373 => 
  array (
    'code' => '270',
    'text' => 'Line item %1 is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'A value submitted in lineItem for the item referenced is invalid.',
  ),
  374 => 
  array (
    'code' => '271',
    'text' => 'The number of line items submitted is not allowed. A maximum of %1 line items can be submitted.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The number of line items submitted in lineItem exceeds the allowed maximum of 30.',
  ),
  375 => 
  array (
    'code' => '280',
    'text' => 'The auction platform name is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  376 => 
  array (
    'code' => '281',
    'text' => 'The auction platform ID is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  377 => 
  array (
    'code' => '282',
    'text' => 'The auction listing type is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  378 => 
  array (
    'code' => '283',
    'text' => 'The auction listing ID is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  379 => 
  array (
    'code' => '284',
    'text' => 'The auction seller ID is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  380 => 
  array (
    'code' => '285',
    'text' => 'The auction buyer ID is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  381 => 
  array (
    'code' => '286',
    'text' => 'One or more required auction values were not submitted.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  382 => 
  array (
    'code' => '287',
    'text' => 'The combination of auction platform ID and auction platform name is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  383 => 
  array (
    'code' => '288',
    'text' => 'This transaction cannot be accepted.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  384 => 
  array (
    'code' => '289',
    'text' => 'This processor does not accept zero dollar authorization for this card type.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  385 => 
  array (
    'code' => '290',
    'text' => 'There is one or more missing or invalid required fields.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  386 => 
  array (
    'code' => '295',
    'text' => 'The amount of this request was only partially approved on the given prepaid card. An additional payment is required to fulfill the balance of this transaction.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  387 => 
  array (
    'code' => '296',
    'text' => 'The specified SplitTenderID is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  388 => 
  array (
    'code' => '297',
    'text' => 'Transaction ID and Split Tender ID cannot both be used in the same request.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  389 => 
  array (
    'code' => '298',
    'text' => 'This order has already been released or voided therefore new transaction associations cannot be accepted.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  390 => 
  array (
    'code' => '300',
    'text' => 'The device ID is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Invalid &amp;lt;code&amp;gt;deviceId&amp;lt;/code&amp;gt; value. ',
  ),
  391 => 
  array (
    'code' => '301',
    'text' => 'The device batch ID is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Invalid &amp;lt;code&amp;gt;batchId&amp;lt;/code&amp;gt; value. ',
  ),
  392 => 
  array (
    'code' => '302',
    'text' => 'The reversal flag is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'Invalid &amp;lt;code&amp;gt;x_reversal&amp;lt;/code&amp;gt; value.',
  ),
  393 => 
  array (
    'code' => '303',
    'text' => 'The device batch is full. Please close the batch.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The current device batch must be closed manually from the POS device. ',
  ),
  394 => 
  array (
    'code' => '304',
    'text' => 'The original transaction is in a closed batch.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The original transaction has been settled and cannot be reversed. ',
  ),
  395 => 
  array (
    'code' => '305',
    'text' => 'The merchant is configured for auto-close.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This merchant is configured for auto-close and cannot manually close batches. ',
  ),
  396 => 
  array (
    'code' => '306',
    'text' => 'The batch is already closed.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  397 => 
  array (
    'code' => '307',
    'text' => 'The reversal was processed successfully.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  398 => 
  array (
    'code' => '308',
    'text' => 'Original transaction for reversal not found.',
    'integration_suggestions' => '',
    'other_suggestions' => 'The transaction submitted for reversal was not found. ',
  ),
  399 => 
  array (
    'code' => '309',
    'text' => 'The device has been disabled.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  400 => 
  array (
    'code' => '310',
    'text' => 'This transaction has already been voided.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  401 => 
  array (
    'code' => '311',
    'text' => 'This transaction has already been captured.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  402 => 
  array (
    'code' => '312',
    'text' => 'The specified security code was invalid.',
    'integration_suggestions' => 'The SIM hosted payment form features a Security Code option (sometimes called CAPTCHA) used to confirm that the payment is being entered by a human being. This feature helps protect your site from automated scripts that may try to test credit card numbers through the payment form.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;The Security Code works by generating an image that contains random numbers and letters that cannot be read by scripts. The customer is then prompted to enter the letters and numbers exactly as they appear in the image. If the customer enters the correct Security Code, the transaction is accepted as valid.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;Error 312 indicates that the customer had entered the wrong Security Code. Should this error occur, a new Security Code is generated, and the customer is prompted to try again until they are successful.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;To turn off the Security Code option:&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;Login into the Merchant Interface at &amp;lt;a href&amp;#x3D;&amp;quot;https://account.authorize.net/&amp;quot;&amp;gt;https://account.authorize.net/&amp;lt;/a&amp;gt;.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Account&amp;lt;/strong&amp;gt; from the main toolbar.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Settings&amp;lt;/strong&amp;gt; in the main left side menu.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Payment Form&amp;lt;/strong&amp;gt;.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Form Fields&amp;lt;/strong&amp;gt;.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Deselect the box labeled &amp;quot;&amp;lt;strong&amp;gt;Require the Security Code feature on the Payment Form&amp;lt;/strong&amp;gt;.&amp;quot;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Submit&amp;lt;/strong&amp;gt; to save the settings.&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;&amp;lt;p&amp;gt;Note: When using Simple Checkout, the customer is always required to verify a Security Code. Even if the Security Code is disabled from the payment form, the customer is required to verify a Security Code on the Simple Checkout order page.&amp;lt;/p&amp;gt;',
    'other_suggestions' => '',
  ),
  403 => 
  array (
    'code' => '313',
    'text' => 'A new security code was requested.',
    'integration_suggestions' => 'The SIM hosted payment form features a Security Code option (sometimes called CAPTCHA) used to confirm that the payment is being entered by a human being. This feature helps protect your site from automated scripts that may try to test credit card numbers through the payment form.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;The Security Code works by generating an image that contains random numbers and letters that cannot be read by scripts. The customer is then prompted to enter the letters and numbers exactly as they appear in the image. If the customer enters the correct Security Code, the transaction is accepted as valid.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;If they enter an incorrect value, the customer is prompted to try again until they are successful.&amp;lt;br /&amp;gt;&amp;lt;br /&amp;gt;To turn off the Security Code option:&amp;lt;ul class&amp;#x3D;&amp;quot;graydot&amp;quot;&amp;gt; &amp;lt;li&amp;gt;Log into the Merchant Interface at &amp;lt;a href&amp;#x3D;&amp;quot;https://account.authorize.net/&amp;quot; target&amp;#x3D;&amp;quot;_blank&amp;quot;&amp;gt;https://account.authorize.net/&amp;lt;/a&amp;gt;.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Account&amp;lt;/strong&amp;gt; from the main toolbar.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Settings&amp;lt;/strong&amp;gt; in the main left side menu.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Payment Form&amp;lt;/strong&amp;gt;.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Form Fields&amp;lt;/strong&amp;gt;.&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Deselect the box labeled &amp;quot;&amp;lt;strong&amp;gt;Require the Security Code feature on the Payment Form&amp;lt;/strong&amp;gt;.&amp;quot;&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Click &amp;lt;strong&amp;gt;Submit&amp;lt;/strong&amp;gt; to save the settings.&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;&amp;lt;p&amp;gt;Note: When using Simple Checkout, the customer is always required to verify a Security Code. Even if the Security Code is disabled from the payment form, the customer is required to verify a Security Code on the Simple Checkout order page.&amp;lt;/p&amp;gt;',
    'other_suggestions' => '',
  ),
  404 => 
  array (
    'code' => '314',
    'text' => 'This transaction cannot be processed.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  405 => 
  array (
    'code' => '315',
    'text' => 'The credit card number is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This is a processor-issued decline.',
  ),
  406 => 
  array (
    'code' => '316',
    'text' => 'Credit card expiration date is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This is a processor-issued decline.',
  ),
  407 => 
  array (
    'code' => '317',
    'text' => 'The credit card has expired.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This is a processor-issued decline.',
  ),
  408 => 
  array (
    'code' => '318',
    'text' => 'A duplicate transaction has been submitted.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This is a processor-issued decline.',
  ),
  409 => 
  array (
    'code' => '319',
    'text' => 'The transaction cannot be found.',
    'integration_suggestions' => '',
    'other_suggestions' => 'This is a processor-issued decline.',
  ),
  410 => 
  array (
    'code' => '320',
    'text' => 'The device identifier is either not registered or not enabled.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  411 => 
  array (
    'code' => '325',
    'text' => 'The request data did not pass the required fields check for this application.',
    'integration_suggestions' => 'The request is missing one or more required fields.',
    'other_suggestions' => '&amp;lt;para&amp;gt;Merchants processing transactions via one of the following processors (AIBMS UK, Barclays, Cardnet, HBOS, HSBC, Streamline, FdiAus and Westpac) are required to submit the following billing information fields:&amp;lt;/para&amp;gt;&amp;lt;ul&amp;gt; &amp;lt;li&amp;gt;First Name (firstName)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Last Name (lastName)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Address (address)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;City (city)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;State (state)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Postal Code&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Country (country)&amp;lt;/li&amp;gt; &amp;lt;li&amp;gt;Email Address (email)&amp;lt;/li&amp;gt; &amp;lt;/ul&amp;gt;    &amp;lt;para&amp;gt;&amp;lt;b&amp;gt;NOTE:&amp;lt;/b&amp;gt; State and Postal Code are optional if the billing address is not in the U.S. or Canada. If the address is in the U.S. or Canada, the two-digit State/Province code must be provided, along with the Zip/Postal Code.&amp;lt;/para&amp;gt;',
  ),
  412 => 
  array (
    'code' => '326',
    'text' => 'The request field(s) are either invalid or missing.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  413 => 
  array (
    'code' => '327',
    'text' => 'The void request failed. Either the original transaction type does not support void, or the transaction is in the process of being settled.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  414 => 
  array (
    'code' => '328',
    'text' => 'A validation error occurred at the processor.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  415 => 
  array (
    'code' => '330',
    'text' => 'V.me transactions are not accepted by this merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  416 => 
  array (
    'code' => '331',
    'text' => 'The x_call_id value is missing.',
    'integration_suggestions' => 'The &amp;lt;code&amp;gt;x_call_id&amp;lt;/code&amp;gt; value is missing.',
    'other_suggestions' => '',
  ),
  417 => 
  array (
    'code' => '332',
    'text' => 'The x_call_id value is not found or invalid.',
    'integration_suggestions' => 'The &amp;lt;code&amp;gt;x_call_id&amp;lt;/code&amp;gt; value is not found or invalid.',
    'other_suggestions' => '',
  ),
  418 => 
  array (
    'code' => '333',
    'text' => 'A validation error was returned from V.me.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  419 => 
  array (
    'code' => '334',
    'text' => 'The V.me transaction is in an invalid state.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  420 => 
  array (
    'code' => '339',
    'text' => 'Use x_method to specify method or send only x_call_id or card/account information.',
    'integration_suggestions' => 'Use &amp;lt;code&amp;gt;x_method&amp;lt;/code&amp;gt; to specify method or send only &amp;lt;code&amp;gt;x_call_id&amp;lt;/code&amp;gt; or card information.',
    'other_suggestions' => '',
  ),
  421 => 
  array (
    'code' => '340',
    'text' => 'V.me by Visa does not support voids on captured or credit transactions. Please allow the transaction to settle, then process a refund for the captured transaction.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  422 => 
  array (
    'code' => '341',
    'text' => 'The x_discount value is invalid.',
    'integration_suggestions' => 'The &amp;lt;code&amp;gt;x_discount&amp;lt;/code&amp;gt; value is invalid.',
    'other_suggestions' => '',
  ),
  423 => 
  array (
    'code' => '342',
    'text' => 'The x_giftwrap value is invalid.',
    'integration_suggestions' => 'The &amp;lt;code&amp;gt;x_giftwrap&amp;lt;/code&amp;gt; value is invalid.',
    'other_suggestions' => '',
  ),
  424 => 
  array (
    'code' => '343',
    'text' => 'The x_subtotal value is invalid.',
    'integration_suggestions' => 'The &amp;lt;code&amp;gt;x_subtotal&amp;lt;/code&amp;gt; value is invalid.',
    'other_suggestions' => '',
  ),
  425 => 
  array (
    'code' => '344',
    'text' => 'The x_misc value is invalid.',
    'integration_suggestions' => 'The &amp;lt;code&amp;gt;x_misc&amp;lt;/code&amp;gt; value is invalid.',
    'other_suggestions' => '',
  ),
  426 => 
  array (
    'code' => '350',
    'text' => 'Country must be a valid two or three-character value if specified.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  427 => 
  array (
    'code' => '351',
    'text' => 'Employee ID must be 1 to %x characters in length.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  428 => 
  array (
    'code' => '355',
    'text' => 'An error occurred while parsing the EMV data.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  429 => 
  array (
    'code' => '356',
    'text' => 'EMV-based transactions are not currently supported for this processor and card type.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  430 => 
  array (
    'code' => '357',
    'text' => 'Opaque Descriptor is required.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  431 => 
  array (
    'code' => '358',
    'text' => 'EMV data is not supported with this transaction type.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  432 => 
  array (
    'code' => '359',
    'text' => 'EMV data is not supported with this market type.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  433 => 
  array (
    'code' => '360',
    'text' => 'An error occurred while decrypting the EMV data.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  434 => 
  array (
    'code' => '361',
    'text' => 'The EMV version is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  435 => 
  array (
    'code' => '362',
    'text' => 'The EMV version is required.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  436 => 
  array (
    'code' => '363',
    'text' => 'The POS Entry Mode value is invalid.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  437 => 
  array (
    'code' => '370',
    'text' => 'Signature data is too large.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  438 => 
  array (
    'code' => '371',
    'text' => 'Signature must be PNG formatted data.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  439 => 
  array (
    'code' => '375',
    'text' => 'Terminal/lane number must be numeric.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  440 => 
  array (
    'code' => '380',
    'text' => 'KSN is duplicated.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  441 => 
  array (
    'code' => '901',
    'text' => 'This transaction cannot be accepted at this time due to system maintenance.  Please try again later.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  442 => 
  array (
    'code' => '2000',
    'text' => 'Need payer consent.',
    'integration_suggestions' => 'The value of the &amp;lt;code&amp;gt;secureAcceptanceURL&amp;lt;/code&amp;gt; field, provided in Authorization Only or Authorization and Capture service calls, is required for the follow-on calls such as Authorization Only, Continued and Authorization and Capture, Continued.',
    'other_suggestions' => '',
  ),
  443 => 
  array (
    'code' => '2001',
    'text' => 'PayPal transactions are not accepted by this merchant.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  444 => 
  array (
    'code' => '2002',
    'text' => 'PayPal transactions require x_version of at least 3.1.',
    'integration_suggestions' => 'PayPal transactions require that the field &amp;lt;code&amp;gt;x_version&amp;lt;/code&amp;gt; be set to &amp;lt;code&amp;gt;3.1&amp;lt;/code&amp;gt;',
    'other_suggestions' => '',
  ),
  445 => 
  array (
    'code' => '2003',
    'text' => 'Request completed successfully',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  446 => 
  array (
    'code' => '2004',
    'text' => 'Success  URL is required.',
    'integration_suggestions' => 'PayPal transactions require valid URL for &amp;lt;code&amp;gt;success_url&amp;lt;/code&amp;gt;',
    'other_suggestions' => '',
  ),
  447 => 
  array (
    'code' => '2005',
    'text' => 'Cancel URL is required.',
    'integration_suggestions' => 'PayPal transactions require valid URL for &amp;lt;code&amp;gt;cancel_url&amp;lt;/code&amp;gt;',
    'other_suggestions' => '',
  ),
  448 => 
  array (
    'code' => '2006',
    'text' => 'Payer ID is required.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  449 => 
  array (
    'code' => '2007',
    'text' => 'This processor does not accept zero dollar authorizations.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  450 => 
  array (
    'code' => '2008',
    'text' => 'The referenced transaction does not meet the criteria for issuing a Continued Authorization.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  451 => 
  array (
    'code' => '2009',
    'text' => 'The referenced transaction does not meet the criteria for issuing a Continued Authorization  w/ Auto Capture.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  452 => 
  array (
    'code' => '2100',
    'text' => 'PayPal transactions require valid URL for success_url',
    'integration_suggestions' => 'PayPal transactions require valid URL for &amp;lt;code&amp;gt;success_url&amp;lt;/code&amp;gt;',
    'other_suggestions' => '',
  ),
  453 => 
  array (
    'code' => '2101',
    'text' => 'PayPal transactions require valid URL for cancel_url',
    'integration_suggestions' => 'PayPal transactions require valid URL for &amp;lt;code&amp;gt;cancel_url&amp;lt;/code&amp;gt;',
    'other_suggestions' => '',
  ),
  454 => 
  array (
    'code' => '2102',
    'text' => 'Payment not authorized.  Payment has not been authorized by the user.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  455 => 
  array (
    'code' => '2103',
    'text' => 'This transaction has already been authorized.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  456 => 
  array (
    'code' => '2104',
    'text' => 'The totals of the cart item amounts do not match order amounts. Be sure the total of the payment detail item parameters add up to the order total.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  457 => 
  array (
    'code' => '2105',
    'text' => 'PayPal has rejected the transaction.Invalid Payer ID.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  458 => 
  array (
    'code' => '2106',
    'text' => 'PayPal has already captured this transaction.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  459 => 
  array (
    'code' => '2107',
    'text' => 'PayPal has rejected the transaction. This Payer ID belongs to a different customer.',
    'integration_suggestions' => '',
    'other_suggestions' => '',
  ),
  460 => 
  array (
    'code' => '2108',
    'text' => 'PayPal has rejected the transaction. x_paypal_hdrimg exceeds maximum allowable length.',
    'integration_suggestions' => 'The field &amp;lt;code&amp;gt;x_paypal_hdrimg&amp;lt;/code&amp;gt; exceeds maximum allowable length.',
    'other_suggestions' => '',
  ),
  461 => 
  array (
    'code' => '2109',
    'text' => 'PayPal has rejected the transaction. x_paypal_payflowcolor must be a 6 character hexadecimal value.',
    'integration_suggestions' => 'The field &amp;lt;code&amp;gt;x_paypal_payflowcolor&amp;lt;/code&amp;gt; must be a 6 character hexadecimal value.',
    'other_suggestions' => '',
  ),
  462 => 
  array (
    'code' => '2200',
    'text' => 'The amount requested for settlement cannot be different than the original amount authorized. Please void transaction if required',
    'integration_suggestions' => '',
    'other_suggestions' => 'This error applies to WePay merchants only if the Prior Authorization Capture request amount is different than the Authorization Only request amount.',
  ),
);
