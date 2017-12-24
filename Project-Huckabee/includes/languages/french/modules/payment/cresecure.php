<?php
/*
  $Id: cresecure.php,v 1.0 2009/01/27 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('MODULE_PAYMENT_CRESECURE_TEXT_TITLE', '<b>Carte de crédit par GTPay Secure</b>');
define('MODULE_PAYMENT_CRESECURE_TEXT_SUBTITLE', 'Traiter les paiements par carte de crédit avec plusieurs passerelles et paiement express PayPal');
define('MODULE_PAYMENT_CRESECURE_TEXT_DESCRIPTION', '<div align="center"><img src="images/cre_secure.png"/></div><div style="padding:10px;"> <b>Système de paiement universelle</b><br/>Voyez vous-même pourquoi la CRE Secure est la meilleure option pour les détaillants en ligne qui veulent une conformité PCI, concepteur convivial façon d\'accepter les cartes de crédit.<br/><a href="http://cresecure.com/from_admin" target="_blank">Cliquez ici pour en savoir plus >></a><p>Version 1.6</p><p><a href="' . tep_href_link('cc_purge.php', '', 'SSL') . '">Purger l\'utilitaire de carte de crédit >></a></p></div>');
define('MODULE_PAYMENT_CRESECURE_BUTTON_DESCRIPTION', '</b>Votre paiement est protégé par GTPay Secure. Les données du titulaire de la carte ne sont ni enregistrées, ni partagées. Payez en toute confiance.<b>');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_OWNER', 'Titulaire de la carte de crédit:');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_NUMBER', 'Numéro de carte de crédit:');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_EXPIRES', 'Date d\'expiration de carte de crédit:');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_TYPE', 'Type de carte de crédit:');
define('MODULE_PAYMENT_CRESECURE_TEXT_JS_CC_OWNER', '* Le nom du propriétaire de la carte de crédit doit être au moins ' . CC_OWNER_MIN_LENGTH . ' caractères.');
define('MODULE_PAYMENT_CRESECURE_TEXT_CVV_LINK', 'Qu\'est-ce que c\'est?');
define('MODULE_PAYMENT_CRESECURE_TEXT_JS_CC_NUMBER', '* Le numéro de carte de crédit doit être au moins ' . CC_NUMBER_MIN_LENGTH . ' caractères.');
define('MODULE_PAYMENT_CRESECURE_TEXT_ERROR', 'Erreur de carte de crédit!');
define('MODULE_PAYMENT_CRESECURE_TEXT_JS_CC_CVV', '* Vous devez entrer un numéro CVC de procéder.');
define('TEXT_CCVAL_ERROR_CARD_TYPE_MISMATCH', 'Le type de carte de crédit que vous avez choisi ne correspond pas au numéro de carte de crédit conclue. S\'il vous plaît vérifier le nombre et le type de carte de crédit et essayez de nouveau.');
define('TEXT_CCVAL_ERROR_CVV_LENGTH', 'Le numéro CVC saisi est incorrect. S\'il vous plaît essayez de nouveau.');
?>