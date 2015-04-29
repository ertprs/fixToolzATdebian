<?php
/*
  $Id: export_data.php,v 1.1 07/08/2008 Geoffrey Walton

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Set Standard File And Directory Permissions v 1.4');
define('HEADING_TITLE_AUTHOR', 'by Geoffrey Walton from <a href="http://www.oscfreelancers.com/geoffrey.htm" target="_blank"><span style="font-family: Verdana, Arial, sans-serif; color: sienna; font-size: 12px;">oscfreelancers.com</span></a>');
define('HEADING_TITLE_SUPPORT_THREAD', '<a href="http://forums.oscommerce.com/topic/311123-checking-file-permissions/" target="_blank"><span style="color: sienna;">(visit the support thread)</span></a>');
define('TEXT_MISSING_VERSION_CHECKER', 'Version Checker is not installed. See <a href="http://addons.oscommerce.com/info/7148" target="_blank">here</a> for details.');
define('TEXT_CHECK_PERMISSIONS', 'To check the permission of all the files and directories in your store set the values you believe are correct below. The values it will accept are 777, 755, 744, 644, 444, 440, 400. Rather than validate the settings you input, if you get it wrong, 644 is assumed.<br /><br />Click on "Check".<br /><br />The first 5000 files and directories are then listed showing any updates required.<br /><br />Make a note of the changes for use in case of problems.<br /><br />To apply those changes, return to this screen, reset the values and click on "Update".<br /><br />Then, on another screen, test your site to see if still works. In case of problems you can still see the original values and then use this script to reset the permissions or use your ISP\'s file manager.<br /><br />Tested on a LINUX server, feed back on what happens on changing permissions on a Windows server appreciated.<br /><br />If you start getting error messages, Apache is being run as a user that doesn\'t have permissions to change permissions.<br /><br /><strong>Note:</strong> If /admin/backup does not exist it it created as some templates do not contain this directory. Even though you set value for a directory, no check is made to see if it exists.<br /><br />');
define('TEXT_CHECK_PERMISSIONS_QUESTION_1', 'Set BOTH configure.php to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_1', 'If you get the warning message at the top of the page after setting the configure.php files to 644, then set them 444, which is read only for everyone - this happens on some servers that have been updated for security reasons. 400 may work.');
define('TEXT_CHECK_PERMISSIONS_QUESTION_2', 'Set php files NOT in ADMIN to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_2', '');
define('TEXT_CHECK_PERMISSIONS_QUESTION_3', 'Set All other files NOT in ADMIN to (gif, jpg, etc.)');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_3', '');
define('TEXT_CHECK_PERMISSIONS_QUESTION_4', 'Set php files in ADMIN to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_4', '');
define('TEXT_CHECK_PERMISSIONS_QUESTION_5', 'Set All other files in ADMIN to (gif, jpg, etc.)');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_5', '');
define('TEXT_CHECK_PERMISSIONS_QUESTION_6', 'Set /images directory to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_6', '');
define('TEXT_CHECK_PERMISSIONS_QUESTION_7', 'Set /images/graphs directory to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_7', '');
define('TEXT_CHECK_PERMISSIONS_QUESTION_8', 'Set /admin/backup directory to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_8', '');
define('TEXT_CHECK_PERMISSIONS_QUESTION_9', 'Set /temp directory to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_9', 'Used by Easy Populate');
define('TEXT_CHECK_PERMISSIONS_QUESTION_10', 'Set /tmp directory to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_10', '');
define('TEXT_CHECK_PERMISSIONS_QUESTION_11', 'Set all other directories to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_11', '');
define('TEXT_CHECK_PERMISSIONS_QUESTION_12', 'Set /pub directory to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_12', 'Used for downloads.');
define('TEXT_CHECK_PERMISSIONS_QUESTION_13', 'Set /download directory to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_13', 'Used for downloads.');
define('TEXT_CHECK_PERMISSIONS_QUESTION_14', 'Set /banned directory to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_14', 'Used by IP Trap.');
define('TEXT_CHECK_PERMISSIONS_QUESTION_15', 'Set IP_Trapped.txt to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_15', '');
define('TEXT_CHECK_PERMISSIONS_QUESTION_16', 'Set sitemap files to');
define('TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_16', 'Used by siteMap SEOs.');
define('TEXT_CHECK_PERMISSIONS_QUESTION_20', 'Would you like to check the permissions?');
define('TEXT_CHECK_PERMISSIONS_QUESTION_21', 'UPDATE the permissions now?');
?>