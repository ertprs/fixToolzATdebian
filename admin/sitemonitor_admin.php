<?php
/*
  $Id: sitemonitor_admin.php,v 1.2 2006/09/24
  sitemonitor Originally Created by: Jack mcs at oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/functions/sitemonitor_functions.php');

  $currentVersion = '';
  $instance = (isset($_POST['instance']) ? (int)$_POST['instance'] : '0');

  /********************** BEGIN VERSION CHECKER *********************/
  if (file_exists(DIR_WS_FUNCTIONS . 'version_checker.php')) {
      require(DIR_WS_LANGUAGES . $language . '/version_checker.php');
      require(DIR_WS_FUNCTIONS . 'version_checker.php');
      $contribPath = 'http://addons.oscommerce.com/info/4441';
      $currentVersion = 'SiteMonitor V 2.9';
      $contribName = 'SiteMonitor V';
      $versionStatus = '';
  }
  /********************** END VERSION CHECKER *********************/

  /********************** BEGIN CHECK THE USERNAME *********************/
  $filenameConfigure = DIR_FS_ADMIN . FILENAME_SITEMONITOR_CONFIGURE;
  $filenameConfigure = str_replace('.php', '_' . $instance . '.php', $filenameConfigure);
  $fp = @file($filenameConfigure);

  if (! $fp) {
      tep_redirect(tep_href_link(FILENAME_SITEMONITOR_CONFIG_SETUP, 'invalid_username=true&instance='.$instance));
  } else {
      for ($i = 0; $i < count($fp); ++$i) {
          if (strpos($fp[$i], '$start_dir') !== FALSE) {
              $root = substr(DIR_FS_DOCUMENT_ROOT, -1) === '/' ? DIR_FS_DOCUMENT_ROOT : DIR_FS_DOCUMENT_ROOT . '/'; // slash may not be used in settings so add
              if (strpos($fp[$i], $root) === FALSE) {
                  tep_redirect(tep_href_link(FILENAME_SITEMONITOR_CONFIG_SETUP, 'invalid_username=true&instance='.$instance));
              }
          }
      }
  }

  /********************** END CHECK THE USERNAME *********************/

  $logFile = 'sitemonitor_log' . '_' . $instance . '.txt';
  $referenceFile = 'sitemonitor_reference' . '_' . $instance . '.php';


  /********************** BEGIN CHECK COMMON SECURITY HOLES *********************/
  $adminSM = trim(DIR_WS_ADMIN, '/');
  if ($adminSM === 'admin') {
      $messageStack->add(ERROR_ADMIN_NAME, 'error');
  }
  if (file_exists(DIR_FS_ADMIN . 'file_manager.php')) {
      $messageStack->add(ERROR_FILE_MANAGER, 'error');
  }
  if (! file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . '.htaccess')) {
      $messageStack->add(ERROR_IMAGES_NOT_PROTECTED, 'error');
  }
  $invalidFiles = array_merge(glob(DIR_FS_CATALOG . DIR_WS_IMAGES . '*.php'),glob(DIR_FS_CATALOG . DIR_WS_IMAGES . '*.txt'));

  if (!empty($invalidFiles)) {
    $messageStack->add(ERROR_IMAGES_HAS_PHP, 'error');
    foreach ($invalidFiles as $filename) {
      echo $messageStack->add($filename);
    }
  }

  if (! is_writable(DIR_FS_ADMIN . $logFile)) {
      $messageStack->add(ERROR_LOG_NOT_WRITEABLE, 'error');
  }
  if (! is_writable(DIR_FS_ADMIN . $referenceFile)) {
      $messageStack->add(ERROR_REFERENCE_NOT_WRITEABLE, 'error');
  }

  /********************** END CHECK COMMON SECURITY HOLES *********************/

  $fileDeleted = false;
  $foundErrors = 0;
  $hackedFiles = array();
  $hackedFiles = array();
  $hackedResult = false;
  $showErrors  = 0;
  $useExcludeFile = '';   //if enabled, code will use the disk file with stored files in the hacker file search
  $overwriteExcludeFile = ''; //if enabled, a new hacker exclude list will be built

  $actionDelete  = (isset($_POST['action_delete']) ? $_POST['action_delete'] : false);
  $actionExecute = (isset($_POST['action_execute']) ? $_POST['action_execute'] : false);
  $actionManual  = (isset($_POST['action_manual']) ? $_POST['action_manual'] : false);
  $actionHackerCheck = (isset($_POST['action_hacker_check']) ? $_POST['action_hacker_check'] : false);
  $actionHackerExclude = (isset($_POST['action_hacker_exclude']) ? $_POST['action_hacker_exclude'] : false);
  $action = (isset($_POST['action']) ? $_POST['action'] : false);

  if (tep_not_null($action))  {
      /********************** CHECK THE VERSION ***********************/
      if ($action == 'getversion') {
          if (isset($_POST['version_check']) && $_POST['version_check'] == 'on') {
              $versionStatus = AnnounceVersion($contribPath, $currentVersion, $contribName);
          }
      }
  }

  else if (tep_not_null($actionDelete) || tep_not_null($actionExecute))
  {
      require($filenameConfigure);

      if (file_exists($referenceFile) && tep_not_null($actionDelete))
      {
          runSitemonitor($referenceFile, $logFile);    //run before deleting
          if (unlink($referenceFile))          //delete the reference file before running
              $fileDeleted = true;
      }


      $foundErrors = runSitemonitor($referenceFile, $logFile);        //create the reference files
      $showErrors = 1;
      switch ($foundErrors)                    //report result
      {
          case -1: $errmsg = 'Reference file creation failed.'; break;
          case -2: $errmsg = 'First time ran. Reference file was created and saved.'; break;
          case  0: $errmsg = 'No mismatches found'; break;
          default: $errmsg = sprintf("%d mismatches were found. Run the script manually or see the email for the actual mismatches.", $foundErrors); break;
      }
  }

  else if ($actionManual)
  {
      tep_redirect(tep_href_link('sitemonitor.php'));
  }

  else if ($actionHackerCheck)
  {
      $useExcludeFile = isset($_POST['use_exclude_file']) ? 'checked="yes"' : '';
      if (file_exists($referenceFile)) {
          $hackedResult = CheckForHackerCode($hackedFiles, $useExcludeFile, $instance);
      } else {
          $messageStack->add(ERROR_FAILED_REFERENCE_NOT_FOUND, 'error');
      }
  }

  else if ($actionHackerExclude)
  {
      $hackedFiles = unserialize(urldecode($_POST['hackerfiles']));
      $overwriteExcludeFile = isset($_POST['overwrite_exclude_file']) && $_POST['overwrite_exclude_file'] == 'on' ? 'checked' : ''; //save for restoring setting
      $hackerExcludeFile = 'sitemonitor_hacker_excludes_' . $instance . '.txt';
      $saveArray = array();

      if (empty($overwriteExcludeFile)) {  //then append changes
          if (file_exists($hackerExcludeFile)) {
              $saveArray = @file($hackerExcludeFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); //read in the file so it doesn't get overwriten
          }
      }

      $i = 0;
      $errorMsg = '';

      foreach ($hackedFiles as $key => $value) {
          if (isset($_POST['quaranteen_' . $i]) && $_POST['quaranteen_' . $i] == 'on') { //this file should be quaranteened
              if (file_exists($value['file'])) {               //make sure it is still preeent
                  $path_parts = pathinfo($value['file']);
                  $newfile = sprintf("%squarantine/%s_%s  ",DIR_FS_ADMIN, $path_parts['basename'], @date("Y-m-d") ); //get the path to the quaranteen file

                  $quaranteenDir = DIR_FS_ADMIN . 'quarantine/';

                  if (!( $quaranteenDirPresent = is_dir($quaranteenDir))) {  //check if the quaranteen directory exists
                      $quaranteenDirPresent = mkdir($quaranteenDir);         //try to create it if not
                  }

                  if ($quaranteenDirPresent) {                               //the quaranteen directory does exist
                      if (! rename($value['file'], $newfile)) {              //try moving the file that is marked
                          $errorMsg = ERROR_FAILED_FILE_WRITE;
                      }
                  } else {
                      $errorMsg = ERROR_FAILED_CREATE_QUARATINE_DIRECTORY;
                  }
              }
          }

          if (isset($_POST['exclude_'.$i]) && $_POST['exclude_'.$i] == 'on') { //build the array to exclude
              if (! in_array($value['file'], $saveArray)) {
                  $saveArray[] = $value['file'];
                  unset($hackedFiles[$key]);
              }
          }

          $i++;
      }

      $hackedFiles = array_values($hackedFiles);

      WriteFile($hackerExcludeFile, $saveArray);

      if (tep_not_null($errorMsg)) {
           $messageStack->add($errorMsg,'error');
      }
  }

  $enableExcludeBox = (file_exists('sitemonitor_hacker_excludes_' . $instance . '.txt')) ? '' : 'disabled';
  $instances = GetInstancesArray();
  $logFiles = GetLogFiles();
  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<style type="text/css">
td.HTC_subHead {color: sienna; font-size: 14px; }
table.BorderedBox {border: ridge #ddd 3px; background-color: #eee; }
table.BorderedBoxWhite {border: ridge #ddd 3px; background-color: #fff; }
table.BorderedBoxLight {border: ridge #ddd 3px; background-color: #E6E6E6; }
tr.Header { background-color: #eee; }
.ds_small { font-family: Verdana, Arial, sans-serif; font-size: 10px; font-weight:bold }
</style>
<script type="text/javascript"> <!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=800,height=800,screenX=150,screenY=150,top=15,left=15')
}
//--></script>
<script type="text/javascript"> <!--
function ChangeCheckedStatus(name, items)
{
  var status = document.getElementsByName(name)[0].checked;
  for (j = 0; j < items; j++)  {
    var id = name + '_' + j;
    document.getElementsByName(id)[0].checked = status;
  }
}
function ShowLogFile() {
  var list = document.getElementById("logreader");
  var file = list.options[list.selectedIndex].text;
  var isFile = file.indexOf(".txt");
  if (isFile > 0) {
    window.open(file);
  } else {
    alert("Invalid Selection");
  }
}
//--></script>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
     <tr>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="BorderedBox">
       <tr>
        <td><table border="0" width="40%" cellspacing="0" cellpadding="0">
            <tr>
               <td class="pageHeading" valign="top"><?php echo str_replace(" ", "&nbsp;", $currentVersion); ?></td>
            </tr>
            <tr>
               <td class="ds_small" valign="top"><?php echo HEADING_TITLE_SUPPORT_THREAD; ?></td>
            </tr>
        </table></td>

        <td><table border="0" width="100%">
         <tr>
          <td class="ds_small" align="right"><?php echo HEADING_TITLE_AUTHOR; ?></td>
         </tr>
         <?php
         if (function_exists('AnnounceVersion')) {
            if (false) { //database option not available so ignore
         ?>
               <tr>
                  <td class="ds_small" align="right" style="font-weight: bold; color: red;"><?php echo AnnounceVersion($contribPath, $currentVersion, $contribName); ?></td>
               </tr>
         <?php } else if (tep_not_null($versionStatus)) {
           echo '<tr><td class="ds_small" align="right" style="font-weight: bold; color: red;">' . $versionStatus . '</td></tr>';
         } else {
           echo tep_draw_form('version_check', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action', 'getversion');
         ?>
               <tr>
                  <td class="ds_small" align="right" style="font-weight: bold; color: red;"><INPUT TYPE="radio" NAME="version_check" onClick="this.form.submit();"><?php echo TEXT_VERSION_CHECK_UPDATES; ?></td>
               </tr>
           </form>
         <?php } } else { ?>
            <tr>
               <td class="ds_small" align="right" style="font-weight: bold; color: red;"><?php echo TEXT_MISSING_VERSION_CHECKER; ?></td>
            </tr>
         <?php } ?>
        </table></td>
       </tr>

       <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
       <tr>
        <td class="HTC_subHead" colspan="2"><?php echo TEXT_SITEMONITOR_ADMIN; ?></td>
       </tr>
      </table></td>
     </tr>

     <tr>
      <td><table border="0"width="100%" class="BorderedBoxLight">
       <tr>
        <td width="50%"><?php echo tep_draw_form('sitemonitor_instances', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action', 'process_instances'); ?><table border="0" width="100%" cellspacing="0" cellpadding="2">
         <tr>
          <td class="smallText" width="130"><?php echo TEXT_CHOOSE_INSTANCE; ?></td>
          <td width="50" align="left"><?php echo tep_draw_pull_down_menu('instance', $instances, $instance, 'class="smallText"  onChange="this.form.submit();"'); ?></td>
          <td class="smallText"><?php echo TEXT_CHOOSE_INSTANCE_EXPLAIN; ?></td>
         </tr>
        </table></form></td>
        <td width="50%"> <?php echo tep_draw_form('sitemonitor_logreader', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action', 'process_instances'); ?><table border="0" width="50%" cellspacing="0" cellpadding="2">
         <tr>
          <td class="smallText" width="100"><?php echo TEXT_LOG_READER; ?></td>
          <td align="left"><?php echo tep_draw_pull_down_menu('log_reader', $logFiles, '', 'class="smallText" id="logreader" onChange="ShowLogFile();"'); ?></td>
         </tr>
        </table></form></td>
       </tr>
      </table></td>
     </tr>
     <tr>
      <td><?php echo tep_black_line(); ?></td>
     </tr>

     <!-- BEGIN LOWER SECTION -->
     <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="BorderedBoxWhite">

       <!-- BEGIN DELETE AND GENERATE FILE -->
       <tr>
        <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
          <td align="right"><?php echo tep_draw_form('sitemonitor_auto', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action_delete', 'process'); ?></td>
           <tr>
            <td><table border="0" width="40%" border="0" cellspacing="0" cellpadding="2">
             <tr>
              <td class="smallText" width="70%" style="font-weight:bold;"><?php echo TEXT_SITEMONITOR_DELETE_REFERENCE; ?></td>
             </tr>
             <tr>
              <td class="smallText"><?php echo TEXT_SITEMONITOR_DELETE_EXPLAIN; ?></td>
              <td align="center"><?php echo tep_draw_hidden_field('instance', $instance) . tep_image_submit('button_update.gif', IMAGE_UPDATE)  . ' <a href="' . tep_href_link(FILENAME_SITEMONITOR_ADMIN, '') .'">' . '</a>'; ?></td>
             </tr>
             <?php if ($actionDelete && $fileDeleted) { ?>
              <tr><td class="smallText"><?php echo $referenceFile . ' has been deleted!'; ?></td></tr>
             <?php } ?>
             <?php if ($actionDelete && $showErrors) { ?>
              <tr><td class="smallText"><?php echo $errmsg; ?></td></tr>
             <?php } ?>
            </table></td>
           </tr>
          </form>
          </td>
         </tr>
        </table></td>
       </tr>
       <!-- END DELETE AND GENERATE FILE -->

       <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
       </tr>
       <tr>
        <td><?php echo tep_black_line(); ?></td>
       </tr>

       <!-- BEGIN EXECUTE FILE -->
       <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
          <td align="right"><?php echo tep_draw_form('sitemonitor_auto', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action_execute', 'process'); ?></td>
           <tr>
            <td><table width="40%" border="0" cellspacing="0" cellpadding="2">
             <tr>
              <td class="smallText" width="70%" style="font-weight:bold;"><?php echo TEXT_SITEMONITOR_EXECUTE; ?></td>
             </tr>
             <tr>
              <td class="smallText"><?php echo TEXT_SITEMONITOR_EXECUTE_EXPLAIN; ?></td>
              <td align="center"><?php echo tep_draw_hidden_field('instance', $instance) . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_SITEMONITOR_ADMIN, '') .'">' . '</a>'; ?></td>
             </tr>
             <?php if ($actionExecute && $showErrors) { ?>
              <tr><td class="smallText"><?php echo $errmsg; ?></td></tr>
             <?php } ?>
            </table></td>
           </tr>
          </form>
          </td>
         </tr>
        </table></td>
       </tr>
       <!-- END EXECUTE FILE -->

       <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
       </tr>
       <tr>
        <td><?php echo tep_black_line(); ?></td>
       </tr>

       <!-- BEGIN MANUALLY EXECUTE FILE -->
       <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
          <td align="right"><?php echo tep_draw_form('sitemonitor_auto', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action_manual', 'process'); ?></td>
           <tr>
            <td><table border="0" width="40%" cellspacing="0" cellpadding="2">
             <tr>
              <td class="smallText" width="70%" style="font-weight:bold;"><?php echo TEXT_SITEMONITOR_MANUAL; ?></td>
             </tr>
             <tr>
              <td class="smallText"><?php echo TEXT_SITEMONITOR_MANUAL_EXPLAIN; ?></td>
              <td align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE);?></td>
             </tr>
            </table></td>
           </tr>
          </form>
          </td>
         </tr>
        </table></td>
       </tr>
       <!-- END MANUALLY EXECUTE FILE -->

       <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
       </tr>
       <tr>
        <td><?php echo tep_black_line(); ?></td>
       </tr>

       <!-- BEGIN MANUALLY CHECK FOR HACKED FILES -->
       <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
          <td align="right"><?php echo tep_draw_form('sitemonitor_auto', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action_hacker_check', 'process'); ?></td>
           <tr>
            <td><table border="0" width="40%" cellspacing="0" cellpadding="2">
             <tr>
              <td class="smallText" width="70%" style="font-weight:bold;"><?php echo TEXT_SITEMONITOR_HACKER_CHECK; ?></td>
             </tr>
             <tr>
              <td class="smallText"><?php echo TEXT_SITEMONITOR_HACKER_CHECK_EXPLAIN; ?></td>
              <td align="center"><?php echo tep_draw_hidden_field('instance', $instance) . tep_image_submit('button_update.gif', IMAGE_UPDATE);?></td>
             </tr>
             <tr>
              <td><table border="0" width="100%" cellpadding="0">
               <tr>
                <td width="10"><input type="checkbox" name="use_exclude_file" <?php echo $useExcludeFile . $enableExcludeBox; ?> ></td>
                <td class="smallText"><?php echo TEXT_HACK_TITLE_USE_EXCLUDE_FILE; ?></td>
               </tr>
              </table></td>
             </tr>

            <table></td>
           </tr>
          </form>
          </td>
         </tr>
         <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
         </tr>
         <tr>
          <td class="smallText"><?php echo $hackedResult; ?></td>
         </tr>
         <?php if (count($hackedFiles) > 0) { ?>
         <tr>
          <td><table border="0" width="100%" cellpadding="0" style="background-color: #eee; border: ridge #CCFFCC 3px;">
           <tr bgcolor="yellow">
            <th class="smallText" align="left"><?php echo TEXT_HACK_TITLE_NOTES; ?></th>
           </tr>

           <tr>
            <th class="smallText" align="left"><?php echo TEXT_HACK_EXPLAIN_REF; ?></th>
           </tr>
           <tr>
            <th class="smallText" align="left"><?php echo TEXT_HACK_EXPLAIN_LINE; ?></th>
           </tr>
           <tr>
            <th class="smallText" align="left"><?php echo TEXT_HACK_EXPLAIN_LOCN; ?></th>
           </tr>
           <tr>
            <th class="smallText" align="left"><?php echo TEXT_HACK_EXPLAIN_FILE; ?></th>
           </tr>
           <tr>
            <th class="smallText" align="left"><?php echo TEXT_HACK_EXPLAIN_DATES_MATCH; ?></th>
           </tr>
           <tr>
            <th class="smallText" align="left"><?php echo TEXT_HACK_EXPLAIN_COLOR; ?></th>
           </tr>
          </table></td>
         </tr>

         <?php echo tep_draw_form('sitemonitor_exclude', FILENAME_SITEMONITOR_ADMIN, '', 'post') . tep_draw_hidden_field('action_hacker_exclude', 'process');
         $numFiles = count($hackedFiles);
         ?>
          <tr>
           <td><table border="1" width="100%" cellpadding="0">
            <tr>
             <th class="smallText"><?php echo TEXT_HACK_TITLE_REF; ?></th>
             <th class="smallText"><?php echo TEXT_HACK_TITLE_LINE; ?></th>
             <th class="smallText"><?php echo TEXT_HACK_TITLE_FILE; ?></th>
             <th class="smallText"><?php echo TEXT_HACK_TITLE_HACKER_CODE; ?></th>
             <th class="smallText"><?php echo TEXT_HACK_TITLE_DATE_CMP; ?></th>
             <th class="smallText"><?php echo TEXT_HACK_TITLE_EXCLUDE; ?><br><input type="checkbox" name="exclude" id="exclude" onClick="ChangeCheckedStatus('exclude', <?php echo $numFiles; ?>)"></th>
             <th class="smallText"><?php echo TEXT_HACK_TITLE_DELETE; ?><br><input type="checkbox" name="quaranteen" id="quaranteen" onClick="ChangeCheckedStatus('quaranteen', <?php echo $numFiles; ?>)"></th>
            </tr>

            <?php
             for ($i = 0; $i < count($hackedFiles); ++$i) {
            ?>
            <tr>
             <?php  $color = $hackedFiles[$i]['color']; ?>
             <td class="smallText" width="14" align="center"><?php echo ($hackedFiles[$i]['inref'] ? tep_image('images/mark_check.jpg') : '&nbsp;'); ?></td>
             <td class="smallText" width="24"><?php echo $hackedFiles[$i]['line'];  ?></td>
             <td class="smallText" ><a class="smallText" style="color: <?php echo $color; ?>" href="javascript:popupWindow('sitemonitor_popup.php?<?php echo $hackedFiles[$i]['file'];?>')"><?php echo substr($hackedFiles[$i]['file'], strlen(DIR_FS_CATALOG)); ?></a></td>
             <td class="smallText" width="14" align="center"><?php echo ($hackedFiles[$i]['hackercode'] ? $hackedFiles[$i]['hackercode'] : '&nbsp;'); ?></td>
             <td class="smallText" width="14" align="center"><?php echo ($hackedFiles[$i]['date_cmp'] ? tep_image('images/mark_check.jpg') : '&nbsp;'); ?></td>
             <td width="6" align="center"><input type="checkbox" name="exclude_<?php echo $i; ?>" value="on" id="exclude_<?php echo $i; ?>"></td>
             <td width="6" align="center"><input type="checkbox" name="quaranteen_<?php echo $i; ?>" value="on" id="quaranteen_<?php echo $i; ?>"></td>
            </tr>
            <?php } ?>
           </table></td>
          </tr>
          <tr>
           <td><table border="0" width="100%" cellpadding="0" style="background-color: #eee; border: ridge #CCFFCC 3px;">
            <tr>
             <td align="right" width="520" class="smallText"><?php echo TEXT_HACK_TITLE_OVERWRITE_EXCLUDE_FILE; ?></td>
             <td align="center" width="30"><input type="checkbox" name="overwrite_exclude_file" <?php echo $overwriteExcludeFile; ?> ></td>
             <td align="left" colspan="4" valign="middle">
              <?php echo tep_draw_hidden_field('hackerfiles', urlencode(serialize($hackedFiles))) .
                         tep_draw_hidden_field('use_exclude_file', $useExcludeFile) .
                         tep_image_submit('button_update.gif', IMAGE_UPDATE, 'name="hacker_exclude"'); ?>
             </td>
            </tr>
           </table></td>
          </tr>
         </form>

         <?php } else if (isset($_POST['action_hacker_check'])) { ?>
         <tr>
          <td class="smallText"><b><?php echo TEXT_NO_HACKED_FILES; ?></b></td>
         </tr>
         <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
         </tr>
         <?php } ?>

        </table></td>
       </tr>
       <!-- END MANUALLY CHECK FOR HACKED FILES -->

      </table></td>
     </tr>
     <!-- END LOWER SECTION -->

    </table></td>
  </tr>
</table>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>