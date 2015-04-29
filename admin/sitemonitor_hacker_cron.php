<?php
/*
  $Id: sitemonitor_cron.php
  sitemonitor Originally Created by: Jack mcs at oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/

require('includes/configure.php');

$instance = ((isset($_GET['instance']) && (int)$_GET['instance'] >= 0) ? (int)$_GET['instance'] : '0');

/********************** BEGIN VQARIABLE SETTINGS ************************/
define('FILENAME_SITEMONITOR_CONFIGURE', 'sitemonitor_configure_' . $instance . '.php');
define('TEXT_HACK_RETURN_MSG', 'Checked a total of %d directories containing %d files. Skipped %d files. %d suspected hacked files found. %s');
define('TEXT_EMAIL_SUBJECT', 'SiteMonitor Hacker test results from cron for %s on %s');
define('TEXT_END_REPORT', '******************************* END OF REPORT *******************************');
define('TEXT_FOUND_FILE_DETAILS', 'Line %d in %s (suspect code: %s)');
define('TEXT_NO_HACKED_FILES_FOUND', 'All files appear to be clean');
define('ERROR_CONFIG_INVALID', 'Error: Configure file is invalid.');
define('ERROR_CONFIG_OPEN', 'Error: Failed to open configure file.');
define('ERROR_FAILED_DB_CONNECTION', 'Failed to connect to the database');
define('USE_EXCLUDE_FILE', 'true');  //use the saved excluded file to skip those files - set to false to check all files
define('VERBOSE', true);

$datestamp = date("Y-m-d");
$startDir = (substr(DIR_FS_CATALOG, -1) == '/' ? DIR_FS_CATALOG : DIR_FS_CATALOG . '/'); //may be changed later
$excludeFileArray = array(0 => 'sitemonitor_functions.php');
$filenameConfigure = FILENAME_SITEMONITOR_CONFIGURE;
$referenceFile = 'sitemonitor_reference_' . $instance . '.php';
/********************** END VQARIABLE SETTINGS ************************/

/***********************************************************************************
  EDITS SHOULD NOT BE MADE BELOW THIS LINE UNLESS YOU UNDERSTAND WHAT THEY WILL DO
***********************************************************************************/

/************** READ IN THE CONFIGURE FILE **************/
if (! ($fp = @file($filenameConfigure))) {
    exit(ERROR_CONFIG_OPEN);
}

/*********** LOAD THE SAVED IGNORE LIST FROM THE FILE **********/
$savedArray = array();
if (USE_EXCLUDE_FILE && file_exists('sitemonitor_hacker_excludes_' . $instance . '.txt')) {
    $savedArray = @file('sitemonitor_hacker_excludes_' . $instance . '.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

/************** GET THE HACKER SETTINGS **************/
for ($i = 0; $i < count($fp); ++$i) {
    if (strpos($fp[$i], '$start_dir') !== FALSE) {
        $startDir = str_replace("'", "", GetConfigureSetting($fp[$i], "'", "'"));
    } else if (strpos($fp[$i], "\$hackIgnoreList") !== FALSE) {
        $ignoreList = str_replace("'", "", stripslashes(GetConfigureSetting($fp[$i], "(", ")")));
    } else if (strpos($fp[$i], "\$hackCodeSegments") !== FALSE) {
        $hackerCodeList = stripslashes(GetConfigureSetting(htmlspecialchars($fp[$i]), "(", ")")); //&quot; is needed due to htmlspecialchars which is needed due to the hacker code
        break;
    }
}

if (empty($ignoreList) || empty($hackerCodeList)) {
    exit(ERROR_CONFIG_INVALID);
}

/************** SETUP THE ARRAYS **************/
$hackerCodeArray = explode(",", $hackerCodeList);
$ignoreTypeArray = explode(",", $ignoreList);
array_walk($hackerCodeArray, 'StripOutsideQuotes');

$filesChecked = 0;
$dirsChecked = 0;
$filesSkipped = 0;                                   //items in the ignore list
$hackedFilesSkipped = 0;                             //files in the hacker reference file that were not checked
$countExcluded = count($excludeFileArray);
$countInvalidCode = count($hackerCodeArray);

$refFilesOrig = @file($referenceFile);               //read in the saved file
$refFiles = $refFilesOrig;                           //make a copy
array_walk($refFiles, 'GetOnlyPath');                //strip the stats
$flipped_refFiles = array_flip($refFiles);           //speed up search


if (VERBOSE) $time_start = SiteMonitor_microtime_float();

$statsArray = array();
$aFiles = GetAllFiles($startDir, $statsArray, $excludeFileArray, $ignoreTypeArray, $savedArray);

if (VERBOSE) echo 'Time needed to find files: ' . (SiteMonitor_microtime_float() - $time_start) . "\r\n<br>";


/************** TEST THE FILES **************/
if (VERBOSE) $time_start_array = SiteMonitor_microtime_float();

$errMsg = '';
$startDirLength = strlen($startDir);

foreach ( array_keys($aFiles) as $key ) {            //speed up walk
    if (filesize($aFiles[$key]) < 2000000 && ($page = file_get_contents($aFiles[$key])) !== FALSE) {
        $found = false;

        for ($i = 0; $i < $countInvalidCode; ++$i) {             //cycle through the suspect text
            if (strpos($page, $hackerCodeArray[$i]) !== FALSE) { //this is a suspecious entry
                $contents = @file($aFiles[$key]);
                for ($line = 1; $line < count($contents); ++$line) {
                    if (strpos($contents[$line], $hackerCodeArray[$i]) !== FALSE) {
                        break;
                    }
                }

          //      $r = @stat($aFiles[$key]);
            //    $d = GetPart(TIME, $refFilesOrig[$flipped_refFiles[$aFiles[$key]]]);
              //  $hackedFiles[$ctr]['date_cmp'] = ((gmstrftime ("%A, %m-%d-%Y %Z", (int)$d) == gmstrftime ("%A, %m-%d-%Y %Z", $r[9])) ? true : false);
                //$hackedFiles[$ctr]['inref'] = (isset($flipped_refFiles[$aFiles[$key]]) ? true : false);

                $errMsg .= "\r\n" . sprintf(TEXT_FOUND_FILE_DETAILS, $line, substr($aFiles[$key], $startDirLength), $hackerCodeArray[$i]);
                $ctr++;
                $found = true;
                break;
            }
        }

        /******** Check if this is a php file in the iamges directory - shouldn't be *********/
        if (! $found && strpos(dirname($aFiles[$key]), 'images') !== FALSE && substr($aFiles[$key], -4) === '.php') {
         //   $r = @stat($aFiles[$key]);
       //     $d = GetPart(TIME, $refFilesOrig[$flipped_refFiles[$aFiles[$key]]]);
           // $hackedFiles[$ctr]['date_cmp'] = ((gmstrftime ("%A, %m-%d-%Y %Z", (int)$d) == gmstrftime ("%A, %m-%d-%Y %Z", $r[9])) ? true : false);
         //   $hackedFiles[$ctr]['inref'] = (isset($flipped_refFiles[$aFiles[$key]]) ? true : false);

            $errMsg .= "\r\n<br>" . sprintf(TEXT_FOUND_FILE_DETAILS, $line, substr($aFiles[$key], $startDirLength), '');
            $ctr++;
        }

        $filesChecked++;
    }
}
if (VERBOSE) echo 'Time needed for handling array: ' . (SiteMonitor_microtime_float() - $time_start_array) . "\r\n<br>";
if (VERBOSE) echo 'Time needed for comlete run: ' . (SiteMonitor_microtime_float() - $time_start) . "\r\n\r\n<br><br>";

$hackFilesSkippedMsg = ($statsArray['hackedFilesSkipped'] > 0) ? '( skipped ' . $statsArray['hackedFilesSkipped'] . ' more in hacker file )' : '';
$results = sprintf(TEXT_HACK_RETURN_MSG, $statsArray['dirsChecked'], $filesChecked, $statsArray['filesSkipped'], $ctr, $hackFilesSkippedMsg);
$results .= "\r\n\r\n" . (($ctr == 0) ? TEXT_NO_HACKED_FILES_FOUND : $errMsg);
$results .= "\r\n\r\n" . TEXT_END_REPORT;

if (VERBOSE) echo $results;

/*********************** GET SHOP INFORMATION FROM THE DATABASE **********************/
if (!($link = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die("Unable to connect to database server!"))) {
    if (VERBOSE) echo ERROR_FAILED_DB_CONNECTION . "\r\n<br>";
    exit(ERROR_FAILED_DB_CONNECTION);
}

mysql_select_db(DB_DATABASE);

$configuration_query = mysql_query("select configuration_value as store_name from configuration where configuration_key = 'STORE_NAME' limit 1") or die(mysql_error());
$configuration = mysql_fetch_array($configuration_query, MYSQL_ASSOC);

$config_query = mysql_query("select configuration_value as email_address from configuration where configuration_key = 'STORE_OWNER_EMAIL_ADDRESS' limit 1") or die(mysql_error());
$config = mysql_fetch_array($config_query, MYSQL_ASSOC);

mysql_close($link);

/*********************** SEND THE RESULTS **********************/
$subject = sprintf(TEXT_EMAIL_SUBJECT, $configuration['store_name'], $datestamp);
mail($config['email_address'], $subject, $results, $configuration['store_name']);

exit;

function GetConfigureSetting($str, $beginDelimiter, $endDelimiter) {
    if (($posStart = strpos($str, $beginDelimiter)) !== FALSE)
    {
        if (($posComment = strpos($str, "//")) !== FALSE)
            $str = substr($str, 0, $posComment);   //remove comment soit doesn't confuse search from right

        if (($posStop = strrpos($str, $endDelimiter)) !== FALSE)  //search right needed in case strihng contains a )
            return (substr($str, $posStart + 1, $posStop - $posStart - 1));
    }
    return '';
}

function GetOnlyPath(&$value) {
    if (($commaPos = strpos($value, ",")) !== FALSE)
        $value = substr($value, 0, $commaPos);
}

function GetPart($part, $path) {
    $parts = explode(",", $path);
    return trim($parts[$part]);
}

function GetAllFiles($dir, &$statsArray, $excludeFileArray, $ignoreTypeArray, $savedArray) {
    if (is_dir($dir)) {
        for ($list = array(),$handle = opendir($dir); (FALSE !== ($file = readdir($handle)));) {
            if (($file != '.' && $file != '..') && (file_exists($path = $dir.$file))) {
                if (is_dir($path)) {
                    if (substr($file,0,1) == "." )  { //skip anything that starts with a '.'
                        continue;
                    }

                    $statsArray['dirsChecked']++;
                    $list = array_merge($list, GetAllFiles($path.'/', $statsArray, $excludeFileArray, $ignoreTypeArray, $savedArray));
                } else {
                    do if (!is_dir($path)) {
                      if (in_array(substr($file, -3), $ignoreTypeArray)) {
                          $statsArray['filesSkipped']++;
                          continue;
                      }

                      if (in_array($file, $excludeFileArray)) {
                          continue;
                      }

                      if (in_array($dir . $file, $savedArray) !== FALSE)  { //skip these files
                          $statsArray['hackedFilesSkipped']++;
                          continue;
                      }

                      $list[] = $dir . $file;
                      break;
                    } else {
                      break;
                    } while (FALSE);
                }
            }
        }
        closedir($handle);
        return $list;
    } else
        return FALSE;
}

function SiteMonitor_microtime_float() { //just used for testing
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/**************************************************
Used by array_walk to remove outer quotes
**************************************************/
function StripOutsideQuotes(&$value) {
    $value = substr(trim($value), 1, -1);
}
