<?php
/*
  $Id: cresecure.php,v 1.0 2009/01/27 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('MODULE_PAYMENT_CRESECURE_TEXT_TITLE', '<b>Carte de cr�dit par GTPay�Secure</b>');
define('MODULE_PAYMENT_CRESECURE_TEXT_SUBTITLE', 'Traiter les paiements par carte de cr�dit avec plusieurs passerelles et paiement express PayPal');
define('MODULE_PAYMENT_CRESECURE_TEXT_DESCRIPTION', '<div align="center"><img src="images/cre_secure.png"/></div><div style="padding:10px;"> <b>Syst�me de paiement universelle</b><br/>Voyez vous-m�me pourquoi la CRE Secure est la meilleure option pour les d�taillants en ligne qui veulent une conformit� PCI, concepteur convivial fa�on d\'accepter les cartes de cr�dit.<br/><a href="http://cresecure.com/from_admin" target="_blank">Cliquez ici pour en savoir plus >></a><p>Version 1.6</p><p><a href="' . tep_href_link('cc_purge.php', '', 'SSL') . '">Purger l\'utilitaire de carte de cr�dit >></a></p></div>');
define('MODULE_PAYMENT_CRESECURE_BUTTON_DESCRIPTION', '</b>Votre paiement est prot�g� par GTPay�Secure. Les donn�es du titulaire de la carte ne sont ni enregistr�es, ni partag�es. Payez en toute confiance.<b>');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_OWNER', 'Titulaire de la carte de cr�dit:');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_NUMBER', 'Num�ro de carte de cr�dit:');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_EXPIRES', 'Date d\'expiration de carte de cr�dit:');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_TYPE', 'Type de carte de cr�dit:');
define('MODULE_PAYMENT_CRESECURE_TEXT_JS_CC_OWNER', '* Le nom du propri�taire de la carte de cr�dit doit �tre au moins ' . CC_OWNER_MIN_LENGTH . ' caract�res.');
define('MODULE_PAYMENT_CRESECURE_TEXT_CVV_LINK', 'Qu\'est-ce que c\'est?');
define('MODULE_PAYMENT_CRESECURE_TEXT_JS_CC_NUMBER', '* Le num�ro de carte de cr�dit doit �tre au moins ' . CC_NUMBER_MIN_LENGTH . ' caract�res.');
define('MODULE_PAYMENT_CRESECURE_TEXT_ERROR', 'Erreur de carte de cr�dit!');
define('MODULE_PAYMENT_CRESECURE_TEXT_JS_CC_CVV', '* Vous devez entrer un num�ro CVC de proc�der.');
define('TEXT_CCVAL_ERROR_CARD_TYPE_MISMATCH', 'Le type de carte de cr�dit que vous avez choisi ne correspond pas au num�ro de carte de cr�dit conclue. S\'il vous pla�t v�rifier le nombre et le type de carte de cr�dit et essayez de nouveau.');
define('TEXT_CCVAL_ERROR_CVV_LENGTH', 'Le num�ro CVC saisi est incorrect. S\'il vous pla�t essayez de nouveau.');
?>