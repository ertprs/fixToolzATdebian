<?php
/*
  $Id: sitemonitor_functions.php
  sitemonitor Originally Created by: Jack mcs at oscommerce-solution.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/

function AddToExcludeList(&$curList, $newItem, $admin)  //check to see if the new path is already in the list
{
  $comma = (empty($curList) ? '' : ", ");               //add a comma separator where needed
  $parts = explode(",", $curList);

  for ($i = 0; $i < count($parts); ++$i)
  {
     $item = trim(str_replace("'", "", $parts[$i])); //contains the directory name
     if ($item === ltrim($admin . "quarantine", "/"))
       continue;

     if ($item === $newItem)  //file already in list
     {
        $dirName = sprintf("\"%s\",", $newItem);
        $curList = str_replace($dirName, "", $curList);
        return ''; //ERROR_ALREADY_EXISTS;
     }

     if (substr($newItem, 0, strlen($item)) == $item ) //parent in list
        return ERROR_PARENT_EXISTS; //parent exists in list

     if ($newItem == substr($item, 0, strlen($newItem)))
       return ERROR_CHILD_EXISTS; //child exists in list
  }

  $curList = stripslashes(sprintf("%s%s'%s'",$curList, $comma, $newItem));
  return '';
}

/**************************************************
Used by array_walk to remove quotes and add the
main path to directories that should not be in the
exclude list
**************************************************/
function BuildSkipArray(&$value)
{
  $path = substr(DIR_FS_CATALOG, -1) == '/' ? DIR_FS_CATALOG : DIR_FS_CATALOG . '/';
  $value = $path . trim(str_replace("\"","",$value));
}

/**************************************************
Used by array_walk to remove outer quotes
**************************************************/
function StripOutsideQuotes(&$value)
{
  $value = substr(trim($value), 1, -1);
}

function CheckExcludeList($str)
{
  if (empty($str)) // || $str[0] != '"')     //list does not begin with a quote
    return "FAILED: Exclude list does not begin with quotes.";

  $parts = explode(",", htmlspecialchars($str));
  for ($i = 0; $i < count($parts); ++$i)
  {
    $parts[$i] = trim($parts[$i]);
    if ($parts[$i][0] != "'" || $parts[$i][strlen($parts[$i])-1] != "'")
      return (sprintf("FAILED: %s isn't enclosed in quotation marks.",$parts[$i]));   //each item is not surrounded by quotes
  }

  $cleanstring = preg_replace("/[\t\r\n]+/","",trim(htmlspecialchars($str)));
  return $cleanstring;                   //remove spaces, tabs and newlines
}

/*******************************************************************************
Used by hacker checking code to see if a file has been changed.
Strips all data from the reference file other than the full path.
*******************************************************************************/
function GetOnlyPath(&$value) {
   if (($commaPos = strpos($value, ",")) !== FALSE)
      $value = substr($value, 0, $commaPos);
}

function CheckForHackerCode(&$hackedFiles, $useExcludeFile, $instance) { //reads all files in the shop and admin and reports what it thinks is hacked files
    global $referenceFile;
    define('TIME', 2);

    $excludeFileArray = array();
    $excludeFileArray[] = 'sitemonitor_functions.php';

    /*********** LOAD THE IGNORE LIST FROM THE CONFIGURE SETTINGS **********/
    $filenameConfigure = DIR_FS_ADMIN . FILENAME_SITEMONITOR_CONFIGURE;
    $filenameConfigure = str_replace('.php', '_' . $instance . '.php', $filenameConfigure);
    if (! ($fp = @file($filenameConfigure)))
        return ERROR_CONFIGURE_FILE_FAILED_OPEN;

    /*********** LOAD THE SAVED IGNORE LIST FROM THE FILE **********/
    $savedArray = array();
    if ($useExcludeFile && file_exists('sitemonitor_hacker_excludes_' . $instance . '.txt'))
        $savedArray = @file('sitemonitor_hacker_excludes_' . $instance . '.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $ignoreList = '';
    for ($i = 0; $i < count($fp); ++$i) {
        if (strpos($fp[$i], "\$hackIgnoreList") !== FALSE) {
            $ignoreList = str_replace("'", "", stripslashes(GetConfigureSetting($fp[$i], "(", ")")));
        }
        else if (strpos($fp[$i], "\$hackCodeSegments") !== FALSE) {
            $hackerCodeList = stripslashes(GetConfigureSetting(htmlspecialchars($fp[$i]), "(", ")")); //&quot; is needed due to htmlspecialchars which is needed due to the hacker code
            break;
        }
    }

    if (! tep_not_null($ignoreList) || ! tep_not_null($hackerCodeList))
        return ERROR_CONFIGURE_FILE_INVALID;

    $hackerCodeArray = explode(",", $hackerCodeList);
    $ignoreTypeArray = explode(",", $ignoreList);
    array_walk($hackerCodeArray, 'StripOutsideQuotes');

    $filesChecked = 0;
    $dirsChecked = 0;
    $filesSkipped = 0;                                   //items in the ignore list
    $hackedFilesSkipped = 0;                             //files in the hacker reference file that were not checked
    $countExcluded = count($excludeFileArray);
    $countInvalidCode = count($hackerCodeArray);

    //$referenceFile = "sitemonitor_reference_' . $instance . '.php";
    $refFilesOrig = GetReferenceFiles($referenceFile);   //read in the saved file
    $refFiles = $refFilesOrig;                           //make a copy
    array_walk($refFiles, 'GetOnlyPath');                //strip the stats
    $flipped_refFiles = array_flip($refFiles);           //speed up search

    $dir = (substr(DIR_FS_CATALOG, -1) == '/' ? DIR_FS_CATALOG : substr(DIR_FS_CATALOG, 0, -1));
    $aFiles = rglob($dir, '*');  //get all files and directories
    $ctr = 0;

    foreach ($aFiles as $file) {
        if (is_dir($file)) {
            $dirsChecked++;

        } else {
            if (in_array(substr($file, -3), $ignoreTypeArray) !== FALSE) { //skip these types of files
                $filesSkipped++;
                continue;
            }

            if (in_array(basename($file), $excludeFileArray) !== FALSE)  //skip these files
                continue;

            if (in_array($file, $savedArray) !== FALSE)  { //skip these files
                $hackedFilesSkipped++;
                continue;
            }

            if (filesize($file) < 2000000 && ($page = file_get_contents($file)) !== FALSE) {
                $found = false;

                for ($i = 0; $i < $countInvalidCode; ++$i) {             //cycle through the suspect text
                    if (strpos($page, $hackerCodeArray[$i]) !== FALSE) { //this is a suspecious entry
                        $contents = @file($file);
                        for ($line = 1; $line < count($contents); ++$line) {
                            if (strpos($contents[$line], $hackerCodeArray[$i]) !== FALSE) {
                                break;
                            }
                        }

                        $r = @stat($file);
                        $d = GetPart(TIME, $refFilesOrig[$flipped_refFiles[$file]]);
                        $hackedFiles[$ctr]['color'] = (isset($flipped_refFiles[$file]) ? HACKER_COLOR_1 : HACKER_COLOR_2);
                        $hackedFiles[$ctr]['date_cmp'] = ((gmstrftime ("%A, %m-%d-%Y %Z", (int)$d) == gmstrftime ("%A, %m-%d-%Y %Z", $r[9])) ? true : false);
                        $hackedFiles[$ctr]['inref'] = (isset($flipped_refFiles[$file]) ? true : false);
                        $hackedFiles[$ctr]['line'] = $line;
                        $hackedFiles[$ctr]['file'] = $file;
                        $hackedFiles[$ctr]['hackercode'] = $hackerCodeArray[$i];
                        $ctr++;
                        $found = true;
                        break;
                    }
                }

                /******** Check if this is a php file in the iamges directory - shouldn't be *********/
                if (! $found && strpos(dirname($file), 'images') !== FALSE && substr($file, -4) === '.php') {
                    $r = @stat($file);
                    $d = GetPart(TIME, $refFilesOrig[$flipped_refFiles[$file]]);
                    $hackedFiles[$ctr]['color'] = (isset($flipped_refFiles[$file]) ? HACKER_COLOR_3: HACKER_COLOR_4);
                    $hackedFiles[$ctr]['date_cmp'] = ((gmstrftime ("%A, %m-%d-%Y %Z", (int)$d) == gmstrftime ("%A, %m-%d-%Y %Z", $r[9])) ? true : false);
                    $hackedFiles[$ctr]['inref'] = (isset($flipped_refFiles[$file]) ? true : false);
                    $hackedFiles[$ctr]['line'] = 0;
                    $hackedFiles[$ctr]['file'] = $file;
                    $hackedFiles[$ctr]['hackercode'] = '';
                    $ctr++;
                }
            }

            $filesChecked++;
        }
    }

    $hackFilesSkippedMsg = ($hackedFilesSkipped > 0) ? '( skipped ' . $hackedFilesSkipped . ' more in hacker file )' : '';
    return sprintf(TEXT_HACK_RETURN_MSG, $dirsChecked, $filesChecked, $filesSkipped, count($hackedFiles), $hackFilesSkippedMsg);
}

/**************************************************************************************
Delete log files if they are older than the days set in the setting $logfile_delete
**************************************************************************************/
function CheckLogDelete($logFile) {
    global $logfile, $logfile_delete;

    if (3 > (int)$logfile_delete) { //arbitrary number - don't delete logs less than this old else it defeats the purpose of the logs
        return;
    }

    $files = glob("sitemonitor_log*.txt");
    foreach ($files as $file) {
        if (($lastDash = strrpos($file, '_')) !== FALSE) {
            $fileDate = substr($file, $lastDash + 1, 4);
            if (2009 < (int)$fileDate) {  //this is a backup log file
                $date1 = substr($file, -14, 10);
                $date2 = time();
                $dateArray  = explode("_",$date1);

                $date1Int = mktime(0,0,0, $dateArray[1], $dateArray[0], $dateArray[2]);
                $dateDiff = $date1Int - $date2;
                $numbDays = abs(floor($dateDiff/(60*60*24)));
                if ($numbDays > $logfile_delete) {
                    unlink($file);
                }
            }
        }
    }
}

function CheckLogSize($logFile)  //create a new log file if current one is too large
{
    global $logfile, $logfile_size;

    if ($logfile) { //notice case - poor choice of names - to be changed in a future release
        if (file_exists($logFile) && (int)$logfile_size > 0 && filesize($logFile) >= $logfile_size)  {
            $newLogFile = str_replace('.txt', @date("d_m_Y") . '.txt', $logFile);
            if (! copy($logFile, $newLogFile)) {
                echo 'Failed to create backup log file';
            }
        }
    }
}

function CreateDirectories($base, $backupLocn, $mode = 0755)
{
  $dirs = explode('/' , $backupLocn);
  $count = count($dirs);

  if (strpos($dirs[$count - 1], ".php") !== FALSE)
    unset($dirs[$count - 1]);

  $subDir = '';

  for ($i = 0; $i < $count; ++$i)
  {
    $path = (tep_not_null($subDir)) ? $subDir . '/' . $dirs[$i] : $base . $subDir . $dirs[$i];

    if (!is_dir($path) && ! @mkdir($path, $mode))
      return false;
    else
      $subDir = $path;
  }
  return true;
}

function CreateReferenceFile($dir,$level,$last,&$files, $main_dir) {
    $dp = opendir($dir);

    if ($dp !== FALSE) {
        while (false!=($file=readdir($dp)) && $level == $last) {
            if ($file!="." && $file!="..") {
                $path = (substr($dir, -1) == '/') ? $dir.$file : $dir . "/" . $file;

                if (is_dir($path)) {
                    if (ExcludeDirectory($path, $main_dir)) {
                        continue;
                    }

                    CreateReferenceFile($path,$level+1,$last+1,$files, $main_dir); // uses recursion
                } else {
                    if ((strcmp($file, 'error_log') == 0) ||
                        (strcmp($file, 'sitemonitor.php') == 0) ||                 //exclude all sitemonitor files by name so that files named like sitemonitor_hack.php are not ignored
                        (strcmp($file, 'sitemonitor_admin.php') == 0) ||
                        (preg_match('/sitemonitor_configure_(?P<digit>\d+).php/', $file, $matches)) ||
                        (strcmp($file, 'sitemonitor_configure_setup.php') == 0) ||
                        (strcmp($file, 'sitemonitor_functions.php') == 0) ||
                        (preg_match('/sitemonitor_log_(?P<digit>\d+).php/', $file, $matches)) ||
                        (preg_match('/sitemonitor_reference_(?P<digit>\d+).php/', $file, $matches)) ||
                        (strpos($file, 'query_store_') !== FALSE) ||
                        (substr($file, -4) === ".xml"))
                      continue;

                    $r = @stat($path);
                    $files[] = sprintf("%s,%d,%d,%d", $path, $r[7], $r[9],substr(sprintf('%o', @fileperms($path)), -3));  // reads the file into an array
                }
            }
        }
        closedir($dp);
    }
}

function DisplayMessage($verbose, $msg)
{
  $str = $msg;
  if ($verbose) echo $str . '<br>';
  return ($str . "\n");
}

function ExcludeDirectory($dir, $mainDir)
{
  global $excludeList;

  $main_dir = substr($mainDir, -1) == '/' ? $mainDir : $mainDir .'/';

  foreach ($excludeList as $ex)  {
    $curDir = $main_dir.$ex;
    if (strpos($dir, $curDir) !== FALSE)
       return true;
  }
  return false;
}

/*******************************************************************************
 Retrieves a specific configure file setting
*******************************************************************************/
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

function GetDirectoryName($start_dir, $path)  //return the partial directory
{
  if (strpos($path, $start_dir) !== FALSE)
   $path = substr($path, strlen($start_dir));

  return $path;
}

/*******************************************************************************
Return the configure file if it exists. Otherwise build a new one with default settings
*******************************************************************************/
function GetDefaultConfigureFile($filenameConfigure) {
    if (file_exists($filenameConfigure)) {
        return @file($filenameConfigure);
    }

    $fp = array();

    $fp[] = "<?php" . PHP_EOL;
    $fp[] = "/************** THE OPTIONS AND SETTINGS ****************/" . PHP_EOL;
    $fp[] = "\$always_email = 1; //set to 1 to always email the results" . PHP_EOL;
    $fp[] = "\$verbose = 1; //set to 1 to see the results displayed on the page (for when running manually)" . PHP_EOL;
    $fp[] = "\$logfile = 1; //set to 1 to see to track results in a log file" . PHP_EOL;
    $fp[] = "\$logfile_size = 100000; //set the maximum size of the logfile" . PHP_EOL;
    $fp[] = "\$logfile_delete = 30; //set of days to wait before a previous log file is deleted - leave blank to never delete" . PHP_EOL;
    $fp[] = "\$reference_reset = 3; //delete the reference file this many days apart" . PHP_EOL;
    $fp[] = "\$quarantine = 0; //set to 1 to move new files found to the quarantine directory" . PHP_EOL;
    $fp[] = "\$to = 'some_address@your_domain.com'; //where email is sent to" . PHP_EOL;
    $fp[] = "\$from = 'From: some_address@your_domain.com'; //where email is sent from" . PHP_EOL;
    $fp[] = "\$start_dir = '/home/username/public_html'; //your shops root" . PHP_EOL;
    $fp[] = "\$admin_dir = 'http://www.yourdomain.com/admin'; //your shops admin" . PHP_EOL;
    $fp[] = "\$admin_username = 'username'; //your admin username" . PHP_EOL;
    $fp[] = "\$admin_password = 'password'; //your admin password" . PHP_EOL;
    $fp[] = "\$excludeList = array('admin/quarantine', 'cgi-bin','admin'); //don't check these directories - change to your liking - must be set prior to first run" . PHP_EOL;
    $fp[] = "\$hackIgnoreList = array('jpg', 'jpeg','gif','png','txt','zip'); //don't check these types of files - change to your liking" . PHP_EOL;
    $fp[] = "\$hackCodeSegments = array('error_reporting(0)', 'base64_decode','<iframe','gzdecode','eval','ob_start(\"security_update\")', 'Goog1e_analist_up', 'eval(gzinflate(base64_decode', 'Web Shell', '@eval', ' header;', 'shell_exec', 'system','SetCookie','Meher Assel', 'nt02', '<script src','r57shell'); //enter any hacker code that you would like to check for" . PHP_EOL;
    $fp[] = "?>" . PHP_EOL;

    return $fp;
}

function GetFileName($full_path)
{
  global $start_dir;
  return substr($full_path, strlen($start_dir));
}

/*******************************************************************************
Common function to build an array of possible configure files
*******************************************************************************/
function GetInstancesArray() {
    $instances = array();
    for ($i = 0; $i < 10; ++$i) {
        $instances[] = array('id' => $i, 'text' => $i);
    }
    return $instances;
}

/*******************************************************************************
Get a lost of all log files for the log reader
*******************************************************************************/
function GetLogFiles() {
    $files = glob("sitemonitor_log*.txt");
    $logArray = array();
    $logArray[] = array('id' => TEXT_SELECT_LOG_FILE, 'text' => TEXT_SELECT_LOG_FILE);
    foreach ($files as $file) {
        $logArray[] = array('id' => $file, 'text' => $file);
    }
    return $logArray;
}

/*******************************************************************************
Prevents any directory that is already in the exclude list from being added to
the dropdown list for easier access
*******************************************************************************/
function NotInArrayPath($dir, $skipArray)
{
  foreach ($skipArray as $skip)
  {
     if (strpos($dir, $skip) !== FALSE)
       return false;
  }
  return true;
}

function BuildExcludeSelection($start_dir, $exclude_list, &$exclude_array)
{
  $skipArray = explode(",", $exclude_list);
  array_walk($skipArray, 'BuildSkipArray');

  $exclude_array = GetList(DIR_FS_CATALOG, 1, 1, $exclude_array, $skipArray);
  $exclude_selector = array();

  $exclude_selector[] = array('id' => 0, 'text' => TEXT_MAKE_SELECTION);
  for ($i = 0; $i < count($exclude_array); ++$i)
  {
    $exclude_selector[] = array('id' => $i+1, 'text' => GetDirectoryName($start_dir, $exclude_array[$i]));
  }

  return $exclude_selector;
}

function GetList($dir, $level, $last, $dir_list, $skipArray) { //build list of site for exclude list selector
    $dp = opendir($dir);

    if ($dp !== FALSE) {
        while (false!=($file=readdir($dp)) && $level == $last) {
            if ($file!="." && $file!="..") {
                $path = $dir.$file;
                if (is_dir($path)) {
                   if (NotInArrayPath($path, $skipArray)) {
                       $dir_list[] = ltrim(substr($dir, strlen(DIR_FS_CATALOG)-1).$file, "/");
                   }
                   $dir_list = GetList($path."/",$level+1,$last+1, $dir_list, $skipArray);
                }
            }
        }

        closedir($dp);
    }
    return $dir_list;
}

function GetListAllFiles($dir, $level, $last, &$files, $rootLength, $mainDir) {

    $dp = opendir($dir);

    if ($dp !== FALSE)  {
        while (false!=($file=readdir($dp)) && $level == $last) {
            if ($file!="." && $file!="..") {
                $path = (substr($dir, -1) == '/') ? $dir.$file : $dir . '/' . $file;

                if (is_dir($path)) {
                    if (ExcludeDirectory($path, $mainDir)) {
                        continue;
                    }

                    GetListAllFiles($path, $level+1, $last+1, $files, $rootLength, $mainDir); // uses recursion
                } else {
                    if (strpos($file, "sitemonitor_") !== FALSE ||  //exclude all sitemonitor files
                        strpos($file, ".xml") !== FALSE ||
                        strpos($file, "error_log") !== FALSE )
                      continue;

                    $locn = "$path";
                    $files[] = substr($locn, $rootLength + 1);
                }
            }
        }
        closedir($dp);
    }
}

function GetSize($path) {
    if(!is_dir($path)) return @filesize($path);

    $dir = opendir($path);
    $size = 0;

    if ($dir !== FALSE)  {
        while($file = readdir($dir)) {
            if(is_file($path."/".$file)) $size+=filesize($path."/".$file);
            if(is_dir($path."/".$file) && $file!="." && $file !="..") $size +=get_size($path."/".$file);
        }
        closedir($dir);
    }
    return $size;
}

function GetPart($part, $path)
{
  $parts = explode(",", $path);
  return trim($parts[$part]);
}

function GetReferenceFiles($path) //use curl if possible to read in site information
{
  global $username, $password, $admin_dir;
  $lines = array();

  if (! empty($admin_dir) && ! empty($username) && ! empty($password) && function_exists('curl_init'))
  {
    $path = $admin_dir . '/' . $path;
    $ch = curl_init();
    $timeout = 5; // set to zero for no timeout
    curl_setopt ($ch, CURLOPT_URL, $path);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    $lines = explode("\n", $file_contents);
  }
  else
  {
    $fd = @fopen($path, "r");

    while (!feof ($fd))
    {
      $buffer = fgets($fd, 4096);
      $lines[] = $buffer;
    }
    fclose ($fd);
  }

  if (empty($lines))
  {
     echo 'Failed to read Reference File';
     exit;
  }

  return $lines;
}

function WriteFile($filename, $files)
{
    $fpOut = @fopen($filename, "w");

    if (! $fpOut)  {
        echo 'Failed to open file '.$filename;
        exit;
    }

    $size = count($files) - 1;              //don't write last line
    for ($idx = 0; $idx < $size; ++$idx) {
        $str = $files[$idx]."\n";

        $str = str_replace('./','',$str);
        if (fwrite($fpOut, $str) === FALSE) {
            echo "Cannot write to file ($filename)";
            fclose($fpout);
            exit;
        }
    }

    $str = $files[$idx];                    //write the last line without a line feed
    $str = str_replace('./','',$str);
    if (fwrite($fpOut, $str) === FALSE) {
        echo "Cannot write to file ($filename)";
        fclose($fpout);
        exit;
    }

    fclose($fpOut);
}

function WriteConfigureFile($filename, $fp)
{
    $fileFound = file_exists($filename);

    if ($fileFound && !is_writable($filename)) {
        if (!chmod($filename, 0666)) {
            echo "Cannot change the mode of file ($filename)";
            return false;
        }
    }

    $mode = ($fileFound ? 'w' : 'x');
    $fpOut = fopen($filename, $mode);

    if (! $fpOut)  {
        echo 'Failed to open file '.$filename;
        fclose($fpOut);
        return false;
    }

    for ($idx = 0; $idx < count($fp); ++$idx)  {
        if (fwrite($fpOut, $fp[$idx]) === FALSE)  {
           echo "Cannot write to file ($filename)";
           fclose($fpOut);
           return false;
        }
    }

    fclose($fpOut);
    return true;
}

function WriteLogFile($logFile, $logEntry, $today) {
    global $logfile;

    if ($logfile) { //notice case - poor choice of names - to be changed in a future release
        $mode = (file_exists($logFile) ? 'a' : 'x');
        $fp = fopen($logFile, $mode);

        if ($fp)  {
            fputs($fp, "SiteMonitor Log Entry for " . $today. "\n");

            if (count($logEntry) == 0) {
                fputs($fp, "No mismatches found\n");
            } else  {
                foreach(array_keys($logEntry) as $groupKey) {
                    fputs($fp, $groupKey . "\n");

                    foreach($logEntry[$groupKey] as $item) {
                        fputs($fp, "\t" .$item . "\n");
                    }
                }
            }
            fputs($fp, "\n\n");
            fclose($fp);
        }
    }
}

function SiteMonitor_microtime_float() //just used for testing
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function rglob($sDir, $sPattern, $nFlags = NULL) //recursive function to get array of all files and directories
{
  $slash = (substr($sDir, -1) == '/' ? '' : '/');
  $aFiles = glob("$sDir$slash$sPattern", $nFlags); //get the initial directory
    $dir = (array)glob("$sDir$slash*", GLOB_ONLYDIR);       //moved from foreach to handle explict array creation

  foreach ($dir as $sSubDir) //recursive call
  {
        if (tep_not_null($sSubDir))
        {
    $aSubFiles = rglob($sSubDir, $sPattern, $nFlags);
    if (tep_not_null($aSubFiles))
      $aFiles = array_merge($aFiles, $aSubFiles);
  }
    }

  return $aFiles;
}

function rglob_files($pattern, $path = '', $flags = 0)
{
  if (!$path && ($dir = dirname($pattern)) != '.')
  {
    if ($dir == '\\' || $dir == '/') $dir = '';
    return rglob_files(basename($pattern), $flags, $dir . '/');
  }
  $paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
  $files = glob($path . $pattern, (int)$flags);
  foreach ($paths as $p)
    $files = array_merge($files, rglob_files($pattern, $flags, $p . '/'));
  return $files;
}

function runSitemonitor($referenceFile, $logFile, $verbose = 0) {
    global $start_dir, $always_email, $reference_reset, $excludeList, $quarantine, $to, $from, $admin_dir, $username, $password;

    $handleErrors = false;  //for use on some servers that don't display errors
    if ($handleErrors) {
        $current_error_reporting = error_reporting();
        $cuurent_display_errors = ini_get('display_errors');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    if (! defined('NAME')) define('NAME', 0);
    if (! defined('SIZE')) define('SIZE', 1);
    if (! defined('TIME')) define('TIME', 2);
    if (! defined('PERM')) define('PERM', 3);

    $msg = '';
    $files = array();
    $logEntry = array();

    CheckLogSize($logFile);
    clearstatcache();
    $storedRefFiles = '';

    /************** READ IN THE FILES ****************/
    //$time_start = SiteMonitor_microtime_float();
    CreateReferenceFile($start_dir, 1, 1, $files, $start_dir);
    //print SiteMonitor_microtime_float() - $time_start;

    /************** SAVE THE FILES OR, IF PRESENT, READ THEM IN ****************/
    if (! file_exists($referenceFile)) {
        if (empty($files)) {
            echo 'Reference file creation failed!';
            return -1;
        }

        WriteFile($referenceFile, $files);
        if ($verbose) echo 'First time ran. Reference file was created and saved.';
        return -2;
    } else {
        $refFiles = GetReferenceFiles($referenceFile);   //read in the saved file
        $size = count($refFiles);
        $storedRefFiles = $refFiles;                     //make a copy for later use

        for ($i = 0; $i < $size; ++$i) {
            $refFiles[$i] = rtrim($refFiles[$i]);
            $pos = strpos($refFiles[$i], ",");
            $refFiles[$i] = substr($refFiles[$i], 0, $pos);
        }
    }

    /************** COVERT NEW FILES TO NORMAL FILENAME ****************/
    $size = count($files);

    for ($i = 0; $i < $size; ++$i) {
        $files[$i] = str_replace("./", "", $files[$i]);
        $pos = strpos($files[$i], ",");
        $files[$i] = substr($files[$i], 0, $pos);
    }

    /************** SEE IF THERE ARE ANY NEW FILES ****************/
    $diff_added = array_diff($files, $refFiles);
    $msg = "NEW FILES:\n";
    $ttlErrors = 0;

    if (count($diff_added) > 0) {
        foreach($diff_added as $key => $value) { //can't use for loop due to keys staying constant - key 0 may not be present
            $msg .= DisplayMessage($verbose, ('Found a new file named ' . GetFileName($value)));
            $logEntry['New File Added'][] = $value;
            $ttlErrors++;

            if ($quarantine) {                   //new found file is to be moved to a safe directory
                $file = GetFileName($value);
                $backupFile = $file;
                $newfile = "quarantine/$file";     //get new file location and name

                if (file_exists($newfile)) {         //rename won't overwrite so create a new name
                    $path_parts = pathinfo($file);
                    if (($pos = strpos($file, $path_parts['extension'])) !== FALSE) { //get the extension
                        $newfile = sprintf("quarantine/%s_%s.%s  ",substr($file, 0, $pos - 1), @date("d_m_Y"),$path_parts['extension'] );
                        $backupFile = sprintf("%s_%s.%s  ",substr($file, 0, $pos - 1), @date("d_m_Y"),$path_parts['extension'] );
                    }
                }

                if (CreateDirectories(DIR_FS_ADMIN . 'quarantine/', $backupFile)) {
                    if (rename($value, $newfile)) {     //move the file
                        $msg .= DisplayMessage($verbose, ('Quarantined new file: ' . GetFileName($value)));
                        $logEntry['New File Quarantined'][] = $value;
                    }
                }
                else {
                    $msg .= DisplayMessage($verbose, ('Failed to create Quarantine directory for: ' . GetFileName($value)));
                    $logEntry['Failed to create Quarantine directory for'][] = $value;
                }
            }
        }
    }
    else
        $msg .= DisplayMessage($verbose, 'No new files found...');


    /************** SEE IF THERE ARE ANY DELETED FILES ****************/
    $diff_deleted = array_diff($refFiles, $files);
    $msg .= "\nDELETED FILES:\n";

    if (count($diff_deleted) > 0) {
        foreach($diff_deleted as $key => $value) { //can't use for loop due to keys staying constant - key 0 may not be present
            $msg .= DisplayMessage($verbose, ('Found a deleted file named ' . GetFileName($value)));
            $logEntry['File Deleted'][] = $value;
            $ttlErrors++;
            unset($storedRefFiles[$key]);
        }

        $storedRefFiles = array_values($storedRefFiles);    //reset the keys
    }
    else
        $msg .= DisplayMessage($verbose, 'No deleted files found...');


    /************** SEE IF THE FILE SIZES ARE DIFFERENT ****************/
    $error = 0;
    $msg .= "\nSIZE MISMATCH:\n";

    $refFiles = $storedRefFiles;  //reload for all checks below
    $size = count($files);
    $sizeRef = count($refFiles);

    if ($size == $sizeRef)  {
        for ($i = 0; $i < $size; ++$i) {
             $newSize = GetSize($files[$i]);
             $oldSize = GetPart(SIZE, $refFiles[$i]);

             if ($newSize != $oldSize) {
                 $msg .= DisplayMessage($verbose, ('Difference found: New-> '. GetFileName($files[$i]) . ' '. $newSize . ' Original-> ' . $oldSize));
                 $logEntry['Size Changed'][] = $files[$i];
                 $error++;
                 $ttlErrors++;
             }
        }
    } else if ($size > $sizeRef) { //files were added
         for ($i = 0; $i < $size; ++$i) {
             if (in_array($files[$i], $diff_added))        //ignore the new file
                 continue;

             for ($t = 0; $t < $sizeRef; ++$t) {
                 if ($files[$i] === GetPart(NAME, $refFiles[$t])) {
                     $newSize = GetSize($files[$i]);
                     $oldSize = GetPart(SIZE, $refFiles[$t]);
                     if ($newSize != $oldSize) {
                         $msg .= DisplayMessage($verbose, ('Difference found: New-> '. GetFileName($files[$i]) . ' '.$newSize. ' Original-> ' .$oldSize));
                         $logEntry['Size Changed'][] = $files[$i];
                         $error++;
                         $ttlErrors++;
                         break;
                    }
                }
           }
       }
    }

    if (! $error) {
        $msg .= DisplayMessage($verbose, 'No size differences found...');
    }

    /************** SEE IF THE TIMESTAMPS ARE DIFFERENT ****************/

    $msg .= "\nTIME MISMATCH:\n";
    $error = 0;

    if ($size == $sizeRef) { //increase by one to account for sitemonitor_reference.php
         for ($i = 0; $i < $size; ++$i) {
              $r = @stat($files[$i]);
              if ($r[9] != GetPart(TIME, $refFiles[$i])) {
                  $msg .= DisplayMessage($verbose, ('Time Mismatch on '. GetFileName($files[$i]). ' Last Changed on  ' . gmstrftime ("%A, %d %b %Y %T %Z", $r[9])));
                  $logEntry['Time Changed'][] = $files[$i];
                  $error++;
                  $ttlErrors++;
              }
          }
    } else if ($size > $sizeRef) {
        for ($i = 0; $i < $size; ++$i) {
            if (in_array($files[$i], $diff_added))        //ignore the new file
                 continue;

            for ($t = 0; $t < $sizeRef; ++$t) {
                if ($files[$i] === GetPart(NAME, $refFiles[$t])) {
                    $r = @stat($files[$i]);
                    if ($r[9] != GetPart(TIME, $refFiles[$t])) {
                        $msg .= DisplayMessage($verbose, ('Time Mismatch on '. GetFileName($files[$i]). ' Last Changed on  ' . gmstrftime ("%A, %d %b %Y %T %Z", $r[9])));
                        $logEntry['Time Changed'][] = $files[$i];
                        $error++;
                        $ttlErrors++;
                        break;
                    }
                }
            }
        }
    }

    if (! $error) {
        $msg .= DisplayMessage($verbose, 'No time mismatches found...');
    }


    /************** SEE IF THE PERMISSIONS ARE DIFFERENT ****************/
    $msg .= "\nPERMISSIONS MISMATCH:\n";
    $error = 0;

    if ($size == $sizeRef) {
        for ($i = 0; $i < $size; ++$i) {
            $pCurrent = substr(sprintf('%o', @fileperms($files[$i])), -3);
            $pLast =  GetPart(PERM, $refFiles[$i]);

            if ($pCurrent != $pLast) {
                $msg .= DisplayMessage($verbose, ('permissions Mismatch on '. GetFileName($files[$i]). ' Currently set to "' . $pCurrent . '" was set to "' . $pLast .'"'));
                $logEntry['Permissions Change'][] = $files[$i];
                $error++;
                $ttlErrors++;
            }
        }
    } else if ($size > $sizeRef) {
        for ($i = 0; $i < $size; ++$i) {
            if (in_array($files[$i], $diff_added))        //ignore the new file
                continue;

            for ($t = 0; $t < $sizeRef; ++$t) {
                if ($files[$i] === GetPart(NAME, $refFiles[$t])) {
                    $pCurrent = substr(sprintf('%o', @fileperms($files[$i])), -3);
                    $pLast =  GetPart(PERM, $refFiles[$t]);
                    if ($pCurrent != $pLast) {
                        $msg .= DisplayMessage($verbose, ('permissions Mismatch on '. GetFileName($files[$i]). ' Currently set to ' . $pCurrent . ' was set to ' . $pLast));
                        $logEntry['Permissions Change'][] = $files[$i];
                        $error++;
                        $ttlErrors++;
                        break;
                    }
                }
            }
        }

    }

    if (! $error) {
        $msg .= DisplayMessage($verbose, 'No permissions mismatches found...');
    }

    //print SiteMonitor_microtime_float() - $time_start;

    $today = @date("F j, Y, g:i a");
    $instance = substr($referenceFile, strlen('sitemonitor_reference_'));
    $instance = substr($instance, 0, -4);
    $msg .= DisplayMessage($verbose, '');
    $msg .= DisplayMessage($verbose, '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
    $msg .= DisplayMessage($verbose, "Sitemonitor (" . $instance . ") ran on " . $today);
    $msg .= DisplayMessage($verbose, "Total mismatches found were " . $ttlErrors);
    $msg .= DisplayMessage($verbose, "Total files being monitored is " . count($refFiles));

    if ($ttlErrors || $always_email) {
        mail($to, 'Site Monitor Results', $msg, $from);
        if ($verbose)
            echo 'Email sent to shop owner.' .'<br>';
    }

    if ($logFile)
        WriteLogFile($logFile, $logEntry, $today);

     CheckLogDelete($logFile);  //delete old log files

    if ($handleErrors) {
        error_reporting($current_error_reporting);
        ini_set('display_errors', $current_display_errors);
    }

    return $ttlErrors;
}
?>