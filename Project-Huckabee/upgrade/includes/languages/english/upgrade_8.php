<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

   Released under the GNU General Public License
*/

//Install text
define('TEXT_INSTALL_1', '<b>Your upgrade to ' . VERSION . ' is now complete!</b>');
define('TEXT_INSTALL_3', '<p align="center" style="width:600px;margin:0 auto 0 auto;">We hope you enjoy the Loaded Commerce ecommerce platform and benefit from it\'s use as much as we have from creating it!</p>');
define('TEXT_INSTALL_4', '<p style="color:red;text-align:center;">You must <b>delete</b> the upgrade directory. Failure to do so allows anyone to change your username and password.</p><br>');
define('TEXT_INSTALL_5', 'Go to the Catalog');
define('TEXT_INSTALL_6', 'Go to the Admin');
define('TEXT_INSTALL_8', 'ERROR: ');
define('TEXT_INSTALL_9', 'Possible solution: ');

//Text for email
define('TEXT_COMPANY_NAME','Company Name:');
define('TEXT_FULL_NAME','Full Name:');
define('TEXT_TELEPHONE','Day Time Phone:');
define('TEXT_NIGHT_PHONE','Night Time Phone:');
define('TEXT_COUNTRY','Country:');
define('TEXT_EMAIL_ADDRESS','Email:');
define('TEXT_YEARS_IN_BUSINESS','Years in Business:');
define('TEXT_WEBSITE','Web Site Address:');
define('TEXT_PROCESSING','Processing:');
define('TEXT_START_PROCESSING','Start Processing In:');
define('TEXT_COMMENTS','Comments:');
define('TEXT_DESCRIPTION_CREM','Your CRE Merchant Application has been filed.  A representative will contact you soon to answer any of your questions.');

//Errors
define('TEXT_ERROR_11', 'The Admin user is not a valid email');
define('TEXT_ERROR_12', 'The Admin user admin@localhost.com should not be used');
define('TEXT_ERROR_13', 'The Admin user or Admin password is empty');
define('TEXT_ERROR_14', 'There was a MySQL database error while trying to add admin info');
define('TEXT_ERROR_15', 'There was an error writing to the catalog or admin configure files');
define('TEXT_ERROR_16', 'There was an error connecting to the database during test connection');
define('TEXT_ERROR_17', 'This Email Address has already been added to the database');
define('TEXT_ERROR_18', 'This Admin password doesn\'t meet the requirements');

//Suggestion for fixing the error
define('TEXT_ERROR_1_S', 'Please click the back button below and make sure the Admin email is valid');
define('TEXT_ERROR_2_S', 'You have tried to use admin@localhost.com as the admin email, This is well known by hackers. Please click the back button and use a different email');
define('TEXT_ERROR_3_S', 'Click the back button, and make sure the Admn user and Admin password are not empty');
define('TEXT_ERROR_4_S', 'There was a MySQL database error while trying to add admin info');
define('TEXT_ERROR_5_S', 'The following error has occurred: <b>The configuration files do not exist, or permission levels are not set.</b><br><br>Please perform the following actions:
                          <ul class="boxMe"><li>cd %s includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul>
                          <ul class="boxMe"><li>cd %s admin/includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul></p>
                          <p class="noteBox">If <i>chmod 706</i> does not work, please try <i>chmod 777</i>.</p>
                          <p class="noteBox">If you are running this installation procedure under a Microsoft Windows environment, try renaming the existing configuration file so a new file can be created.</p>');
define('TEXT_ERROR_6_S', '<p>A test connection made to the database was <b>NOT</b> successful.</p>
                          <p>The error message returned is:</p>
                          <p class="boxme">' . $db_error . '</p>
                          <p>Please click on the <i>Back</i> button below to review your database server settings.</p>
                          <p>If you require help with your database server settings, please consult your hosting company.</p>');
define('TEXT_ERROR_7_S', 'You will need to use a different Email Address');
define('TEXT_ERROR_8_S', 'Passwords must be at least 8 characters.  At least one captical character, one lower case character and one numeric character must be used.');

?>