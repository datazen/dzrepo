<?php
/*
  $Id: idealm.php,v 1.2 2006/01/14 22:50:52 jb Exp $

  Released under the GNU General Public License

  Parts may be copyrighted by osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
*/

  define('HEADING_TITLE', 'iDeal Orders');
  define('HEADING_TITLE_SEARCH', 'Order ID:');
  define('HEADING_TITLE_STATUS', 'Status:');

  define('TABLE_HEADING_COMMENTS', 'Commentsr');
  define('TABLE_HEADING_CUSTOMERS', 'Customers/Transactions');
  define('TABLE_HEADING_ORDER_TOTAL', 'Total Order');
  define('TABLE_HEADING_DATE_PURCHASED', 'Purchase date');
  define('TABLE_HEADING_DATE_CHECKED', 'Date last status');
  define('TABLE_HEADING_STATUS', 'Status');
  define('TABLE_HEADING_ACTION', 'Action');
  define('TABLE_HEADING_QUANTITY', 'Amount');
  define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
  define('TABLE_HEADING_PRODUCTS', 'Products');
  define('TABLE_HEADING_TAX', 'Tax');
  define('TABLE_HEADING_TOTAL', 'Total');
  define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price (excl.)');
  define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (incl.)');
  define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (excl.)');
  define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (incl.)');

  define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer notified');
  define('TABLE_HEADING_DATE_ADDED', 'Date added');

  define('ENTRY_CUSTOMER', 'Customer:');
  define('ENTRY_SOLD_TO', 'Sold to:');
  define('ENTRY_DELIVERY_TO', 'Deliver to:');
  define('ENTRY_SHIP_TO', 'Send to:');
  define('ENTRY_SHIPPING_ADDRESS', 'Delivery address:');
  define('ENTRY_BILLING_ADDRESS', 'Invoice address:');
  define('ENTRY_PAYMENT_METHOD', 'Payment method:');
  define('ENTRY_PAYMENT_TRANSACTION', 'Transaction:');
  define('ENTRY_PAYMENT_ISSUER', 'Bank:');
  define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
  define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');
  define('ENTRY_SUB_TOTAL', 'Subtotal:');
  define('ENTRY_TAX', 'Tax:');
  define('ENTRY_SHIPPING', 'Shippingcosts:');
  define('ENTRY_TOTAL', 'Total:');
  define('ENTRY_DATE_PURCHASED', 'Purchase Date:');
  define('ENTRY_STATUS', 'Status:');
  define('ENTRY_DATE_LAST_UPDATED', 'Date last update:');
  define('ENTRY_NOTIFY_CUSTOMER', 'Notify customer:');
  define('ENTRY_NOTIFY_COMMENTS', 'Add comments:');
  define('ENTRY_PRINTABLE', 'Print Invoice');

  define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');
  define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order ?');
  define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Update Stock');
  define('TEXT_DATE_ORDER_CREATED', 'Creation Date:');
  define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last modified:');
  define('TEXT_INFO_PAYMENT_METHOD', 'Payment method:');

  define('TEXT_ALL_ORDERS', 'All iDeal Orders');
  define('TEXT_NO_ORDER_HISTORY', 'No order history available');

  define('EMAIL_SEPARATOR', '------------------------------------------------------');
  define('EMAIL_TEXT_SUBJECT', 'Order Update');
  define('EMAIL_TEXT_ORDER_NUMBER', 'Ordernumber:');
  define('EMAIL_TEXT_INVOICE_URL', 'Detailled Invoice:');
  define('EMAIL_TEXT_DATE_ORDERED', 'Orderdate:');
  define('EMAIL_TEXT_STATUS_UPDATE', 'Your Order is updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please respond to this email if you have questions.' . "\n");
  define('EMAIL_TEXT_COMMENTS_UPDATE', 'Comments about your order are' . "\n\n%s\n\n");

  define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: Order doesn\'t exist.');
  define('SUCCESS_ORDER_UPDATED', 'Succes: Order is succesfully updated.');
  define('WARNING_ORDER_NOT_UPDATED', 'Warning: There is nothing to change. No order update processed.');
?>