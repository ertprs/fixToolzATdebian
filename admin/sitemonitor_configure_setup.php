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

  $fcn = (version_compare(PHP_VERSION, '5.0.0', '<') ? 'html_entity_decode' : 'htmlspecialchars_decode');

  $instance = (isset($_GET['invalid_username']) && $_GET['invalid_username'] == 'true' && isset($_GET['instance']) ? $_GET['instance'] :
              (isset($_POST['instance']) ? (int)$_POST['instance'] : '0'));

  $filenameConfigure = DIR_FS_ADMIN . FILENAME_SITEMONITOR_CONFIGURE;
  $filenameConfigure = str_replace('.php', '_' . $instance . '.php', $filenameConfigure);
  $fp = GetDefaultConfigureFile($filenameConfigure);

  /*********** Verify the start directory is not empty, or /, since that would casue a full account read ***********/
  if (isset($_GET['invalid_username']) && $_GET['invalid_username'] = 'true') {
       $startDir = '';
       for ($i = 0; $i < count($fp); ++$i) {
           if (strpos($fp[$i], '$start_dir') !== FALSE) {
               $startDir = str_replace("'", "", GetConfigureSetting($fp[$i], "'", "'"));
               break;
           }
       }

       if (strlen($startDir) < 2) { ?>
           <script language="javascript">
           <!--
           alert("Setup Error!!! The start direcory is set to read the whole server. Replace the admin/sitemonitor_configure.php file with the one from the contribution and try again.");
           //-->
           </script>
           <?php
           echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', 'continue') . '</a>';
           exit;
       }
  }

  /*********** LOAD THE CONFIGURE SETTINGS **********/
  $switch = array();
  for ($i = 0; $i < count($fp); ++$i)
  {
    if (strpos($fp[$i], "\$always_email") !== FALSE)
    {
      if (($pos = strpos($fp[$i], ";")) !== FALSE)
       $switch['always_email'] = ((int)substr($fp[$i], $pos -1) == 1) ? 'Checked' : '';
    }
    else if (strpos($fp[$i], "\$verbose") !== FALSE)
    {
      if (($pos = strpos($fp[$i], ";")) !== FALSE)
       $switch['verbose'] = ((int)substr($fp[$i], $pos -1) == 1) ? 'Checked' : '';
    }
    else if (strpos($fp[$i], "\$logfile") !== FALSE && strpos($fp[$i], "\$logfile_") === FALSE)
    {
      if (($pos = strpos($fp[$i], ";")) !== FALSE)
       $switch['logfile'] = ((int)substr($fp[$i], $pos -1) == 1) ? 'Checked' : '';
    }
    else if (strpos($fp[$i], "\$logfile_size") !== FALSE)
    {
      if (($pos = strpos($fp[$i], ";")) !== FALSE)
       $switch['logfile_size'] = (((int)substr($fp[$i], $pos - 1) > 0) ? (int)substr($fp[$i], $pos - 1) : '100000');
    }
    else if (strpos($fp[$i], "\$logfile_delete") !== FALSE)
    {
      if (($pos = strpos($fp[$i], ";")) !== FALSE)
       $switch['logfile_delete'] = (((int)substr($fp[$i], $pos - 1) > 0) ? (int)substr($fp[$i], $pos - 1) : '30');
    }
    else if (strpos($fp[$i], "\$reference_reset") !== FALSE)
    {
      if (($pos = strpos($fp[$i], ";")) !== FALSE)
       $switch['reference_reset'] = (((int)substr($fp[$i], $pos - 1) > 0) ? (int)substr($fp[$i], $pos - 1) : '');
    }
    else if (strpos($fp[$i], "\$quarantine") !== FALSE)
    {
      if (($pos = strpos($fp[$i], ";")) !== FALSE)
       $switch['quarantine'] = ((int)substr($fp[$i], $pos -1) == 1) ? 'Checked' : '';
    }
    else if (strpos($fp[$i], "\$to") !== FALSE)
    {
      $switch['to_address'] = GetConfigureSetting($fp[$i], "'", "'");
      $switch['to_address'] = ($switch['to_address'] === 'some_address@your_dosmallText.com') ? STORE_OWNER_EMAIL_ADDRESS : $switch['to_address'];
    }
    else if (strpos($fp[$i], "\$from") !== FALSE)
    {
      $switch['from_address'] = GetConfigureSetting($fp[$i], "'", "'");
      $switch['from_address'] = ($switch['from_address'] === 'From: some_address@your_dosmallText.com') ? "From: " . STORE_OWNER_EMAIL_ADDRESS : $switch['from_address'];
    }
    else if (strpos($fp[$i], "\$start_dir") !== FALSE)
    {
      $switch['start_dir'] = GetConfigureSetting($fp[$i], "'", "'");
      $start_dir = ($switch['start_dir'] === '/home/username/public_html') ? DIR_FS_DOCUMENT_ROOT : $switch['start_dir'];
      $start_dir = (substr($start_dir, -1) == '/' ? $start_dir : $start_dir .'/');
    }
    else if (strpos($fp[$i], "\$admin_dir") !== FALSE)
    {
      $switch['admin_dir'] = GetConfigureSetting($fp[$i], "'", "'");
      $admin_dir = ($switch['admin_dir'] === 'https://www.yourdosmallText.com/admin') ? HTTP_SERVER.DIR_WS_ADMIN : $switch['admin_dir'];
    }
    else if (strpos($fp[$i], "\$admin_username") !== FALSE)
    {
      $switch['admin_username'] = GetConfigureSetting($fp[$i], "'", "'");
      $admin_username = $switch['admin_username'];
    }
    else if (strpos($fp[$i], "\$admin_password") !== FALSE)
    {
      $switch['admin_password'] = GetConfigureSetting($fp[$i], "'", "'");
      $admin_password = $switch['admin_password'];
    }
    else if (strpos($fp[$i], "\$excludeList") !== FALSE)
    {
      $quarantine = substr(DIR_WS_ADMIN, 1) . "quarantine";
      $list = stripslashes(GetConfigureSetting($fp[$i], "(", ")"));
      $adminSM = trim(DIR_WS_ADMIN, "/");
      $switch['exclude_list'] = ((! strstr($list, $adminSM)) ? "'" .  $adminSM . "', " : '');
      $switch['exclude_list'] = (strpos($list, $quarantine) === FALSE) ? "'" .  $quarantine . "', " . $list : $list;
    }
    else if (strpos($fp[$i], "\$hackIgnoreList") !== FALSE)
    {
      $switch['hackIgnoreList'] = stripslashes(GetConfigureSetting($fp[$i], "(", ")"));
    }
    else if (strpos($fp[$i], "\$hackCodeSegments") !== FALSE)
    {
      $switch['hackCodeSegments'] = stripslashes(GetConfigureSetting(htmlspecialchars($fp[$i]), "(", ")"));
    }
  }

  /****************** BUILD THE DIRECTORY LIST ***********************/
  $exclude_array = array();
  $exclude_selector =  (isset($_GET['override']) ? array() : BuildExcludeSelection($start_dir, $switch['exclude_list'], $exclude_array)); //if override is in url string, don't build a files list

  /************************ CHECK THE INPUT ***************************/
  if (isset($_GET['action_reset']) && tep_not_null($_GET['action_reset'])) {
  }

  else if (isset($_GET['invalid_username']) && $_GET['invalid_username'] = 'true')
  {
     $userRoot = '';
     for ($i = 0; $i < count($fp); ++$i) {
         if (strpos($fp[$i], '$start_dir') !== FALSE) {
             $userRoot = str_replace("'", "", GetConfigureSetting($fp[$i], "'", "'"));
             break;
         }
     }

     if (empty($userRoot)) {
         $userRoot = $fp[$i];
     }

     $msg = sprintf("%s: <b>System -> </b> %s - <b>SiteMonitor -> </b>%s",ERROR_INVALID_USERNAME, DIR_FS_DOCUMENT_ROOT, $userRoot);
     $messageStack->add($msg, 'error');
  }

  else if (isset($_POST['action']) && $_POST['action'] == 'process')
  {
    if (isset($_POST['update_x']))
    {
      $switch['always_email'] = (isset($_POST['always_email'])) ? 'Checked' : '';
      $switch['verbose'] = (isset($_POST['verbose'])) ? 'Checked' : '';
      $switch['logfile'] = (isset($_POST['logfile'])) ? 'Checked' : '';
      $switch['logfile_size'] = $_POST['logfile_size'];
      $switch['logfile_delete'] = $_POST['logfile_delete'];
      $switch['reference_reset'] = $_POST['reference_reset'];
      $switch['quarantine'] = (isset($_POST['quarantine'])) ? 'Checked' : '';
      $switch['to_address'] = $_POST['to_address'];
      $switch['from_address'] = $_POST['from_address'];
      $switch['start_dir'] =  (substr($_POST['start_dir'], -1) == '/' ? $_POST['start_dir'] : $_POST['start_dir'] . '/');
      $switch['admin_dir'] = $_POST['admin_dir'];
      $switch['admin_username'] = $_POST['admin_username'];
      $switch['admin_password'] = $_POST['admin_password'];
      $switch['exclude_list'] = stripslashes($_POST['exclude_list']);
      $switch['hackIgnoreList'] = stripslashes($_POST['hackIgnoreList']);
      $switch['hackCodeSegments'] = stripslashes($_POST['hackCodeSegments']);

      $error = false;
      $errmsg = '';

      if (! tep_not_null($switch['to_address']))
      {
        $errmsg = "To address is required.";
        $error = true;
      }
      else if (! tep_not_null($switch['from_address']))
      {
        $errmsg = "From address is required.";
        $error = true;
      }
      else if (! tep_not_null($switch['start_dir']))
      {
        $errmsg = "Start directory is required.";
        $error = true;
      }

      if (! $error)
      {
        $options = array();

        $opt = ($switch['always_email']) == 'Checked' ? 1 : 0;
        $options['always_email'] = sprintf("\$always_email = %d; //set to 1 to always email the results", $opt);

        $opt = ($switch['verbose']) == 'Checked' ? 1 : 0;
        $options['verbose'] = sprintf("\$verbose = %d; //set to 1 to see the results displayed on the page (for when running manually)", $opt);

        $opt = ($switch['logfile']) == 'Checked' ? 1 : 0;
        $options['logfile'] = sprintf("\$logfile = %d; //set to 1 to see to track results in a log file", $opt);

        $opt = (! tep_not_null($switch['logfile_size'])) ? '100000' : $switch['logfile_size'];
        $options['logfile_size'] = sprintf("\$logfile_size = %d; //set the maximum size of the logfile", $opt);

        $opt = (! tep_not_null($switch['logfile_delete'])) ? '30' : $switch['logfile_delete'];
        $options['logfile_delete'] = sprintf("\$logfile_delete = %d; //set of days to wait before a previous log file is deleted - leave blank to never delete", $opt);

        $opt = (! tep_not_null($switch['reference_reset'])) ? '' : $switch['reference_reset'];
        $options['reference_reset'] = sprintf("\$reference_reset = %d; //delete the reference file this many days apart", $opt);

        $opt = ($switch['quarantine']) == 'Checked' ? 1 : 0;
        $options['quarantine'] = sprintf("\$quarantine = %d; //set to 1 to move new files found to the quarantine directory", $opt);

        $opt = $switch['to_address'];
        $options['to_address'] = sprintf("\$to = '%s'; //where email is sent to", $opt);

        $opt = $switch['from_address'];
        $options['from_address'] = sprintf("\$from = '%s'; //where email is sent from", $opt);

        $opt = $switch['start_dir'] ;
        $options['start_dir'] = sprintf("\$start_dir = '%s'; //your shops root", $opt);

        $opt = $switch['admin_dir'] ;
        $options['admin_dir'] = sprintf("\$admin_dir = '%s'; //your shops admin", $opt);

        $opt = $switch['admin_username'] ;
        $options['admin_username'] = sprintf("\$admin_username = '%s'; //your admin username", $opt);

        $opt = $switch['admin_password'] ;
        $options['admin_password'] = sprintf("\$admin_password = '%s'; //your admin password", $opt);

        $opt = CheckExcludeList($switch['exclude_list']);
        $optH = CheckExcludeList($switch['hackIgnoreList']);
        $optS = CheckExcludeList($switch['hackCodeSegments']);

        if (($o1 = strpos($opt, "FAILED")) === FALSE && ($o2 = strpos($optH, "FAILED")) === FALSE && ($o3 = strpos($optS, "FAILED")) === FALSE)
        {
          $options['exclude_list'] = stripslashes(sprintf("\$excludeList = array(%s); //don't check these directories - change to your liking - must be set prior to first run", $opt));
          $options['hackIgnoreList'] = stripslashes(sprintf("\$hackIgnoreList = array(%s); //don't check these types of files - change to your liking", $optH));
          $options['hackCodeSegments'] = stripslashes(sprintf("\$hackCodeSegments = array(%s); //enter any hacker code that you would like to check for", $optS));

          $fp = GetDefaultConfigureFile($filenameConfigure);
          $fp_out = array();
          for ($i = 0; $i < count($fp); ++$i)
          {
            if (strpos($fp[$i], "\$always_email") !== FALSE)
             $fp_out[] = $options['always_email'] . "\n";
            else if (strpos($fp[$i], "\$verbose") !== FALSE)
             $fp_out[] = $options['verbose'] . "\n";
            else if (strpos($fp[$i], "\$logfile") !== FALSE && (strpos($fp[$i], "\$logfile_") === FALSE) )
             $fp_out[] = $options['logfile'] . "\n";
            else if (strpos($fp[$i], "\$logfile_size") !== FALSE)
             $fp_out[] = $options['logfile_size'] . "\n";
            else if (strpos($fp[$i], "\$logfile_delete") !== FALSE)
             $fp_out[] = $options['logfile_delete'] . "\n";
            else if (strpos($fp[$i], "\$reference_reset") !== FALSE)
             $fp_out[] = $options['reference_reset'] . "\n";
            else if (strpos($fp[$i], "\$quarantine") !== FALSE)
             $fp_out[] = $options['quarantine'] . "\n";
            else if (strpos($fp[$i], "\$to") !== FALSE)
             $fp_out[] = $options['to_address'] . "\n";
            else if (strpos($fp[$i], "\$from") !== FALSE)
             $fp_out[] = $options['from_address'] . "\n";
            else if (strpos($fp[$i], "\$start_dir") !== FALSE)
             $fp_out[] = $options['start_dir'] . "\n";
            else if (strpos($fp[$i], "\$admin_dir") !== FALSE)
             $fp_out[] = $options['admin_dir'] . "\n";
            else if (strpos($fp[$i], "\$admin_username") !== FALSE)
             $fp_out[] = $options['admin_username'] . "\n";
            else if (strpos($fp[$i], "\$admin_password") !== FALSE)
             $fp_out[] = $options['admin_password'] . "\n";
            else if (strpos($fp[$i], "\$excludeList") !== FALSE)
             $fp_out[] = $options['exclude_list'] . "\n";
            else if (strpos($fp[$i], "\$hackIgnoreList") !== FALSE)
             $fp_out[] = $options['hackIgnoreList'] . "\n";
            else if (strpos($fp[$i], "\$hackCodeSegments") !== FALSE)
             $fp_out[] = $fcn($options['hackCodeSegments']) . "\n";
            else
             $fp_out[] = $fp[$i];
          }
          if (WriteConfigureFile($filenameConfigure, $fp_out))
          {
             $exclude_array = array();
             $exclude_selector =  BuildExcludeSelection($start_dir, $switch['exclude_list'], $exclude_array);
          }
        }
        else  {
          $msg  = (tep_not_null($o1) ? $o1 . '<br>' : '');
          $msg .= (tep_not_null($o2) ? (tep_not_null($msg) ? $msg . $o2 . '<br>' : $o2 . '<br>') : '');
          $msg .= (tep_not_null($o3) ? (tep_not_null($msg) ? $msg . $o3 . '<br>' : $o3 . '<br>') : '');
          $messageStack->add_session('sitemonitor', $msg, 'error');
        }
      }
      else if ($error)
       $messageStack->add_session('sitemonitor', $errmsg, 'error');
    }

    /************************** EXCLUDE SELECTOR WAS USED ******************************/
    else if ($_POST['exclude_selector_array'] > 0)
    {
      $removeThis1 = sprintf("'%s',", $exclude_selector[$_POST['exclude_selector_array']]['text']);
      $removeThis2 = sprintf("'%s'", $exclude_selector[$_POST['exclude_selector_array']]['text']);
      if (strpos($_POST['exclude_list'], $removeThis1) !== FALSE)  //already exists in list with comma so remove it
      {
         $switch['exclude_list'] = str_replace($removeThis1, "", $_POST['exclude_list']);
      }
      else if (strpos($_POST['exclude_list'], $removeThis2) !== FALSE)  //already exists in list so remove it
      {
         $switch['exclude_list'] = str_replace($removeThis2, "", $_POST['exclude_list']);
         if (substr($switch['exclude_list'], -1) == ",")
           $switch['exclude_list'] = substr($switch['exclude_list'], 0, -1); //remove extra comma at end if needed
         else if (strpos($switch['exclude_list'], ",,") !== FALSE)
           $switch['exclude_list'] = str_replace(",,", ",", $switch['exclude_list']);  //remove extra comma in string if needed
         else
           $switch['exclude_list'] = ltrim($switch['exclude_list'], ", ");  //remove extra comma at the beginning if needed
      }
      else
      {
        $curList = stripslashes($_POST['exclude_list']);

        if (tep_not_null($errmsg = AddToExcludeList($curList, GetDirectoryName($start_dir, $exclude_array[$_POST['exclude_selector_array']-1]), DIR_WS_ADMIN)))
          $messageStack->add($errmsg, 'error');

        $switch['exclude_list'] = $curList;
      }

      $exclude_array = array();
      $exclude_selector =  BuildExcludeSelection($start_dir, $switch['exclude_list'], $exclude_array);
    }
  }

  $instances = GetInstancesArray();

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<style type="text/css">
td.HTC_Head {color: sienna; font-size: 24px; font-weight: bold; }
td.HTC_subHead {color: sienna; font-size: 14px; }
</style>
<script language="javascript"><!--
function VerifyUsername(sys) {
   var startdir = document.getElementById("startdir").value;

   if (startdir != sys)  {
      alert("Setup Error!!! The start directory: \n\n" + startdir + "\n\ndoes not match the shops directory: \n\n" + sys + "\n\nPlease correct it and try again.");
      return false;
   }

   return true;
}
//-->
</script>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
     <tr>
      <td class="HTC_Head"><?php echo HEADING_SITEMONITOR_CONFIGURE_SETUP; ?></td>
     </tr>
     <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
     <tr>
      <td class="HTC_subHead"><?php echo TEXT_SITEMONITOR_CONFGIURE_SETUP; ?></td>
     </tr>
     <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
     </tr>
     <tr>
      <td><?php echo tep_black_line(); ?></td>
     </tr>

     <tr>
      <td><table width="100%">
       <tr>
        <td align="right"><?php echo tep_draw_form('sitemonitor_instances', FILENAME_SITEMONITOR_CONFIG_SETUP, '', 'post') . tep_draw_hidden_field('action', 'process_instances'); ?></td>
         <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
           <tr>
            <td class="smallText" width="140"><?php echo TEXT_CHOOSE_INSTANCE; ?></td>
            <td width="50" align="left"><?php echo tep_draw_pull_down_menu('instance', $instances, $instance, 'class="smallText"  onChange="this.form.submit();"'); ?></td>
            <td class="smallText"><?php echo TEXT_CHOOSE_INSTANCE_EXPLAIN; ?></td>
           </tr>
          </table></td>
         </tr>
        </form></td>
       </tr>
      </table></td>
     </tr>
     <tr>
      <td><?php echo tep_black_line(); ?></td>
     </tr>

     <!-- BEGIN SITEMONITOR CONFIGURE SETTINGS -->
     <tr>
      <td><table width="100%">
       <tr>
        <td align="right"><?php echo tep_draw_form('sitemonitor', FILENAME_SITEMONITOR_CONFIG_SETUP, '', 'post',  'onsubmit="return VerifyUsername(\'' . DIR_FS_DOCUMENT_ROOT . '\')"') . tep_draw_hidden_field('action', 'process'); ?></td>
         <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_ALWAYS_EMAIL; ?></td>
              <td width="8%"><?php echo tep_draw_checkbox_field('always_email', '', $switch['always_email'], ''); ?> </td>
              <td class="smallText" valign="top"><?php echo TEXT_OPTION_ALWAYS_EMAIL_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_QUARANTINE; ?></td>
              <td width="8%"><?php echo tep_draw_checkbox_field('quarantine', '', $switch['quarantine'], ''); ?> </td>
              <td class="smallText" valign="top" align="left"><?php echo TEXT_OPTION_QUARANTINE_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_VERBOSE; ?></td>
              <td width="8%"><?php echo tep_draw_checkbox_field('verbose', '', $switch['verbose'], ''); ?> </td>
              <td class="smallText" valign="top" align="left"><?php echo TEXT_OPTION_VERBOSE_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_LOGFILE; ?></td>
              <td width="8%"><?php echo tep_draw_checkbox_field('logfile', '', $switch['logfile'], ''); ?> </td>
              <td class="smallText" valign="top" align="left"><?php echo TEXT_OPTION_LOGFILE_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_LOGFILE_SIZE; ?></td>
              <td width="8%"><?php echo tep_draw_input_field('logfile_size',$switch['logfile_size'], 'maxlength="10" size="6" class="smallText"', false, 300); ?> </td>
              <td class="smallText" valign="top" align="left"><?php echo TEXT_OPTION_LOGFILE_SIZE_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_LOGFILE_DELETE; ?></td>
              <td width="8%"><?php echo tep_draw_input_field('logfile_delete',$switch['logfile_delete'], 'maxlength="10" size="6" class="smallText"', false, 300); ?> </td>
              <td class="smallText" valign="top" align="left"><?php echo TEXT_OPTION_LOGFILE_DELETE_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_REFERENCE_RESET; ?></td>
              <td width="8%"><?php echo tep_draw_input_field('reference_reset',$switch['reference_reset'], 'maxlength="10" size="6" class="smallText"', false, 300); ?> </td>
              <td class="smallText" valign="top"><?php echo TEXT_OPTION_REFERENCE_RESET_EXPLAN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_TO_EMAIL; ?></td>
              <td width="32%"><?php echo tep_draw_input_field('to_address', $switch['to_address'], 'maxlength="255" size="40" class="smallText"', false, 300); ?> </td>
              <td class="smallText" valign="top"><?php echo TEXT_OPTION_TO_ADDRESS_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_FROM_EMAIL; ?></td>
              <td width="32%"><?php echo tep_draw_input_field('from_address', $switch['from_address'], 'maxlength="255" size="40" class="smallText"', false, 300); ?> </td>
              <td class="smallText" valign="top"><?php echo TEXT_OPTION_FROM_ADDRESS_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_START_DIR; ?></td>
              <td width="32%"><?php echo tep_draw_input_field('start_dir', (tep_not_null($switch['start_dir']) ? $switch['start_dir'] : DIR_FS_DOCUMENT_ROOT), 'maxlength="255" size="40" class="smallText" id="startdir"', false, 300); ?> </td>
              <td valign="top"><?php echo TEXT_OPTION_START_DIR_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_ADMIN_DIR; ?></td>
              <td width="32%"><?php echo tep_draw_input_field('admin_dir', (tep_not_null($switch['admin_dir']) ? $switch['admin_dir'] : HTTP_SERVER . DIR_WS_ADMIN ), 'maxlength="255" size="40" class="smallText"', false, 300, false); ?> </td>
              <td valign="top"><?php echo TEXT_OPTION_ADMIN_DIR_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_ADMIN_USERNAME; ?></td>
              <td width="32%"><?php echo tep_draw_input_field('admin_username', (($switch['admin_username'] == 'username') ? DB_SERVER_USERNAME : $switch['admin_username']), 'maxlength="255" size="40" class="smallText"', false, 300, false); ?> </td>
              <td valign="top"><?php echo TEXT_OPTION_ADMIN_USERNAME_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_ADMIN_PASSWORD; ?></td>
              <td width="32%"><?php echo tep_draw_input_field('admin_password', (($switch['admin_username'] == 'username') ? DB_SERVER_PASSWORD : $switch['admin_password']), 'maxlength="255" size="40" class="smallText"', false, 300, false); ?> </td>
              <td valign="top"><?php echo TEXT_OPTION_ADMIN_PASSWORD_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%"><?php echo TEXT_OPTION_EXCLUDE_SELECTOR; ?></td>
              <td><?php echo tep_draw_pull_down_menu('exclude_selector_array', $exclude_selector, '', 'class="smallText" onChange="this.form.submit();"');?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%" valign="top"><?php echo TEXT_OPTION_EXCLUDE_LIST; ?></td>
              <td><?php echo tep_draw_textarea_field('exclude_list', 'soft', 60, 7, $switch['exclude_list'], 'class="smallText"', false); ?></td>
              <td valign="top"><?php echo TEXT_OPTION_EXCLUDE_LIST_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%" valign="top"><?php echo TEXT_OPTION_EXCLUDE_HACKED_FILES_LIST; ?></td>
              <td><?php echo tep_draw_textarea_field('hackIgnoreList', 'soft', 60, 4, $switch['hackIgnoreList'], 'class="smallText"', false); ?></td>
              <td valign="top"><?php echo TEXT_OPTION_EXCLUDE_HACKED_FILES_LIST_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="smallText">
              <td width="18%" valign="top"><?php echo TEXT_OPTION_EXCLUDE_HACKED_CODE_SEGMENTS; ?></td>
              <td><?php echo tep_draw_textarea_field('hackCodeSegments', 'soft', 60, 4, $fcn($switch['hackCodeSegments']), 'class="smallText"', false); ?></td>
              <td valign="top"><?php echo TEXT_OPTION_EXCLUDE_HACKED_CODE_SEGMENTS_EXPLAIN; ?></td>
             </tr>
            </table></td>
           </tr>
           <tr>
            <td><table border="0" width="80%" cellspacing="0" cellpadding="2">
             <tr>
              <td align="center">
               <input type="image" src="<?php echo DIR_WS_LANGUAGES . $language . '/images/buttons/button_update.gif'; ?>" NAME="update">
               <?php echo tep_draw_hidden_field('instance', $instance); ?>
               <?php echo '<a href="' . tep_href_link(FILENAME_SITEMONITOR_CONFIG_SETUP, 'action_reset=reset') . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?>
              </td>
             </tr>
            </table></td>
           </tr>
          <table></td>
         </tr>
        </form>
        </td>
       </tr>
      </table></td>
     </tr>
     <!-- END SITEMONITOR CONFIGRE SETTINGS -->

     <tr>
      <td><?php echo tep_black_line(); ?></td>
     </tr>

    </table></td>
  </tr>
</table>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>