<?php
/************** THE OPTIONS AND SETTINGS ****************/
$always_email = 1; //set to 1 to always email the results
$verbose = 1; //set to 1 to see the results displayed on the page (for when running manually)
$logfile = 1; //set to 1 to see to track results in a log file
$logfile_size = 100000; //set the maximum size of the logfile
$logfile_delete = 30; //set of days to wait before a previous log file is deleted - leave blank to never delete
$reference_reset = 3; //delete the reference file this many days apart
$quarantine = 0; //set to 1 to move new files found to the quarantine directory
$to = 'ovidiu_manta@yahoo.com'; //where email is sent to
$from = 'c.coman@yahoo.com'; //where email is sent from

$start_dir = '/home/toolz/public_html/'; //your shops root
$admin_dir = 'admin'; //your shops admin
$admin_username = 'toolz_catalog'; //your admin username
$admin_password = ''; //your admin password
$excludeList = array('admin/quarantine', 'cgi-bin','admin'); //don't check these directories - change to your liking - must be set prior to first run
$hackIgnoreList = array('jpg', 'jpeg','gif','png','txt','zip'); //don't check these types of files - change to your liking
$hackCodeSegments = array('error_reporting(0)', 'base64_decode','<iframe','gzdecode','eval','ob_start("security_update")', 'Goog1e_analist_up', 'eval(gzinflate(base64_decode', 'Web Shell', '@eval', ' header;', 'shell_exec', 'system','SetCookie','Meher Assel', 'nt02', '<script src','r57shell'); //enter any hacker code that you would like to check for
?>
