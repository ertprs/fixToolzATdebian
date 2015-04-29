<?php
/*

	$Id: export_data.php,v 1.1 07/08/2008 Geoffrey Walton
	Author: Geoffrey Walton

	for use with:
	osCommerce v2.2, Open Source E-Commerce Solutions
	http://www.oscommerce.com

	Released under the GNU General Public License

*/

require('includes/application_top.php');
  /********************** BEGIN VERSION CHECKER *********************/
  if (file_exists(DIR_WS_FUNCTIONS . 'version_checker.php'))
  {
  require(DIR_WS_LANGUAGES . $language . '/version_checker.php');
  require(DIR_WS_FUNCTIONS . 'version_checker.php');
  $contribPath = 'http://addons.oscommerce.com/info/6134';
  $currentVersion = 'Check Permissions 1.4';
  $contribName = 'Check Permissions'; 
  $versionStatus = '';
  }
  /********************** END VERSION CHECKER *********************/

function recursive_remove_directory($directory, $empty=FALSE)
 {
     // Use to remove the install directory if it is still on the site.
	 // if the path has a slash at the end we remove it here
     if(substr($directory,-1) == '/')
     {
         $directory = substr($directory,0,-1);
     }
  
     // if the path is not valid or is not a directory ...
     if(!file_exists($directory) || !is_dir($directory))
     {
         // ... we return false and exit the function
         return FALSE;
  
     // ... if the path is not readable
     }elseif(!is_readable($directory))
     {
         // ... we return false and exit the function
         return FALSE;
  
     // ... else if the path is readable
     }else{
  
         // we open the directory
         $handle = opendir($directory);
  
         // and scan through the items inside
         while (FALSE !== ($item = readdir($handle)))
         {
             // if the filepointer is not the current directory
             // or the parent directory
             if($item != '.' && $item != '..')
             {
                 // we build the new path to delete
                 $path = $directory.'/'.$item;
  
                 // if the new path is a directory
                 if(is_dir($path)) 
                 {
                     // we call this function with the new path
                     recursive_remove_directory($path);
  
                 // if the new path is a file
                 }else{
                     // we remove the file
                     unlink($path);
                 }
             }
         }
         // close the directory
         closedir($handle);
  
         // if the option to empty is not set to true
         if($empty == FALSE)
         {
             // try to delete the now empty directory
             if(!rmdir($directory))
             {
                 // return false if not possible
                 return FALSE;
             }
         }
         // return success
         return TRUE;
     }
 }

 function file_perms($file, $octal = false)
{
    if(!file_exists($file)) return false;

    $perms = fileperms($file);
    $cut = $octal ? 2 : 3;
    return substr(decoct($perms), $cut);
}

//-------------------------------------------------------
// Get file mode
// Get file permissions supported by chmod
function getmod($filename) {
   $val = 0;
   $perms = fileperms($filename);
   // Owner; User
   $val += (($perms & 0x0100) ? 0x0100 : 0x0000); //Read
   $val += (($perms & 0x0080) ? 0x0080 : 0x0000); //Write
   $val += (($perms & 0x0040) ? 0x0040 : 0x0000); //Execute
 
   // Group
   $val += (($perms & 0x0020) ? 0x0020 : 0x0000); //Read
   $val += (($perms & 0x0010) ? 0x0010 : 0x0000); //Write
   $val += (($perms & 0x0008) ? 0x0008 : 0x0000); //Execute
 
   // Global; World
   $val += (($perms & 0x0004) ? 0x0004 : 0x0000); //Read
   $val += (($perms & 0x0002) ? 0x0002 : 0x0000); //Write
   $val += (($perms & 0x0001) ? 0x0001 : 0x0000); //Execute

   // Misc
   $val += (($perms & 0x40000) ? 0x40000 : 0x0000); //temporary file (01000000)
   $val += (($perms & 0x80000) ? 0x80000 : 0x0000); //compressed file (02000000)
   $val += (($perms & 0x100000) ? 0x100000 : 0x0000); //sparse file (04000000)
   $val += (($perms & 0x0800) ? 0x0800 : 0x0000); //Hidden file (setuid bit) (04000)
   $val += (($perms & 0x0400) ? 0x0400 : 0x0000); //System file (setgid bit) (02000)
   $val += (($perms & 0x0200) ? 0x0200 : 0x0000); //Archive bit (sticky bit) (01000)

   return $val;
}

//-------------------------------------------------------
// Find out if file has mode
function hasmod($perms, $permission) {

# User Read = 0400 (256), Write = 0200 (128), Execute = 0100 (64)
# Group Read = 0040 (32), Write = 0020 (16), Execute = 0010 (8)
# Public Read = 0004 (4), Write = 0002 (2), Execute = 0001 (1)

    return (($perms & $permission) == $permission);
} 

function scanDirectories($rootDir, $allData=array()) {
    // set filenames invisible if you want
    $invisibleFileNames = array(".", "..", ".htaccess", ".htpasswd", ".svn");
    // run through content of root directory
    $dirContent = scandir($rootDir);

	foreach($dirContent as $key => $content) {
        // filter all files not accessible
        $path = $rootDir.'/'.$content;
        if(!in_array($content, $invisibleFileNames)) {
            // if content is file & readable, add to array
            if(is_file($path) && is_readable($path)) {
                // save file name with path
                $allData[] = $path;
            // if content is a directory and readable, add path and name
            }elseif(is_dir($path) && is_readable($path)) {
                // recursive callback to open new directory
				echo $path . '<br>';
                $allData = scanDirectories($path, $allData);
            }
        }
    }
    return $allData;
}

function pathlock($dir, $listall = false, $testrun = true) {

	global $configure, $a_php, $other_a_files, $na_php, $other_na_files, $images, $graph, $backup, $temp, $tmp, $otherdirectories, $pub, $download, $banned, $sitemap, $ip_trap;
	echo ($testrun ? '**Permission check in progress (no changes will be made).**<br><br>' : '**Permissions being changed.**<br><br>');

	$filename = 'backups';

	if (file_exists($filename)) {
		echo '<tr><td colspan=2 class="Main">The directory ' .$filename . ' exists, so this is OK.</td><tr>';
	} else {
		echo '<tr><td colspan=2 class="Main">The directory ' .$filename . ' does not exist, so it is being created.</td><tr>';
		mkdir($filename, 0644);

		if (file_exists($filename)) {
			echo '<tr><td colspan=2 class="Main">That worked.</td><tr>';
		} else {
			echo '<tr><td colspan=2 class="Main">Sorry you need to create ' .$filename . ' it did not work.</td><tr>';
		}
	}
	//find admin directory name
    $public_directory = dirname($_SERVER['PHP_SELF']);
    //place each directory into array
    $directory_array = explode('/', $public_directory);
    //get highest or top level in array of directory strings
    $admin_dir = max($directory_array);
	//echo '<br />The admin directory is called ' . DIR_WS_ADMIN . '<br /><br />';   

     if(substr(DIR_WS_ADMIN,-1) == '/')
     {
         $directory = substr(DIR_WS_ADMIN,0,-1);
     }

	$admin_dir1 = './'.end(explode('/', $directory));
    //get highest or top level in array of directory strings
	echo '<br />The admin directory is called ' . $admin_dir1 . '<br /><br />';   
	$admin_dir1_len = strlen($admin_dir1);
	//echo $admin_dir1_len .'<br>';

	chdir ('../');
   $file_list = '';
   $stack[] = $dir;
	$i = 0;

   while ($stack) {
      $current_dir = array_pop($stack);

	  if ($dh = opendir($current_dir)) {
          while (($file = readdir($dh)) !== false) {

			  if (substr($file,0,1) !== '.') {
				  $i = $i + 1;
					$current_file = "{$current_dir}/{$file}";
					$mode = getmod($current_file);    //Get the mode
						if (substr($current_file,0,$admin_dir1_len) == $admin_dir1){
							$admin_dir = "Y";
						} else {
							$admin_dir = "N";
						}
//						echo "<tr><td>[".$current_file ."] [" . $admin_dir. "]</td><td>x</td><td>y</td></tr>";
					if (is_dir($current_file)) {
						if (substr($current_file,-8,8) == '/install'){
							echo "<b>Why is the install directory still here?: $current_file</b><BR>";
							$in_dir_exists = 1;
						}

						$ch = true;

						if (substr($current_file,-7,7) == '/images'){
							$correct_mod = $images;
						} elseif (substr($current_file,-13,13) == 'images/graphs'){
							$correct_mod = $graph;
						} elseif (substr($current_file,-7,7) == 'backups' && $admin_dir == "Y"){
							$correct_mod = $backup;
						} elseif (substr($current_file,-3,3) == 'pub'){
							$correct_mod = $pub;
						} elseif (substr($current_file,-8,8) == 'download'){
							$correct_mod = $download;
						} elseif (substr($current_file,-6,6) == 'banned'){
							$correct_mod = $banned;
						} elseif (substr($current_file,-7,7) == 'sitemap'){
							$correct_mod = $sitemap;
						} elseif (substr($current_file,-3,3) == 'tmp'){
							$correct_mod = $tmp;
						} elseif (substr($current_file,-4,4) == 'temp'){
							$correct_mod = $temp;
						} else {
							$correct_mod = $otherdirectories;
						}

						if (!$testrun) {

							if ($filename == '../install') {

								if (file_exists($filename)) {
									echo '<tr><td colspan=2 class="Main">The directory ' .$filename . ' exists, now off to delete it.</td><tr>';
									recursive_remove_directory($filename);

									if (file_exists($filename)) {
										echo '<tr><td colspan=2 class="Main">Could not delete ' .$filename . ', you will have to do it manually.</td><tr>';
									}

								} else {
									echo '<tr><td colspan=2 class="Main">The directory ' .$filename . ' does not exist. Looks like you followed the installation instructions.</td><tr>';
								}
								echo '<tr><td colspan=2 class="Main">&nbsp</td><tr>';
							} else {
								switch ($correct_mod) {
									case 777:					
										$ch = chmod($current_file, 0777);
										break;
									case 755:
										$ch = chmod($current_file, 0755);
										break;
									case 744:					
										$ch = chmod($current_file, 0744);
										break;
									case 666:					
										$ch = chmod($current_file, 0644);
										break;
									case 644:					
										$ch = chmod($current_file, 0644);
										break;
									case 444:
										$ch = chmod($current_file, 0444);
										break;
									case 440:
										$ch = chmod($current_file, 0440);
										break;
									case 400:
										$ch = chmod($current_file, 0400);
										break;							
									default:
										$ch = chmod($current_file, 0644);
								}
//								if ($ch ){echo '<br>ok '.$current_file; }else{echo '<br>ohoh '.$current_file;}
							}
                        }

						echo "<tr>
							<td>Directory</td>
							<td>" . $current_file . "</td>
							<td>" . decoct($mode) . "</td>
							<td>" . ((decoct($mode)==$correct_mod) ? "OK" : (($testrun) ? "Action Required, should be [" . $correct_mod . "]" : ($ch ? "Updated to " . $correct_mod : "Update failed"))) . "</td>
						</tr>";

                      $stack[] = $current_file;
                  } else {

						$ch = true;
						if (substr($current_file,-14,14) == '/configure.php'){
							$correct_mod = $configure;
						} elseif (substr($current_file,-14,14) == 'IP_Trapped.txt'){
							$correct_mod = $ip_trap;
						} else {
							if (substr($current_file,-3,3) == 'php'){

								if ($admin_dir == "Y"){
									$correct_mod = $a_php;
								} else {
									$correct_mod = $na_php;
								}

							} else {

								if ($admin_dir == "Y"){
									$correct_mod = $other_a_files;
								} else {
									$correct_mod = $other_na_files;
								}
							}
						}

						if (!$testrun) {

							if(substr($current_file,-3,3) == 'bak'){
								unlink($path);
							} else {
								switch ($correct_mod) {
									case 777:					
										$ch = chmod($current_file, 0777);
										break;
									case 755:
										$ch = chmod($current_file, 0755);
										break;
									case 744:					
										$ch = chmod($current_file, 0744);
										break;
									case 644:					
										$ch = chmod($current_file, 0644);
										break;
									case 444:
										$ch = chmod($current_file, 0444);
										break;
									case 440:
										$ch = chmod($current_file, 0440);
										break;
									case 400:
										$ch = chmod($current_file, 0400);
										break;							
									default:
										$ch = chmod($current_file, 0644);
								}
//								if ($ch ){echo '<br>ok '.$current_file; }else{echo '<br>ohoh '.$current_file;}
							}
						}

						echo "<tr>
							<td>File</td>
							<td>" . $current_file . "</td>
							<td>" . decoct($mode) . "</td>
							<td>" . ((decoct($mode)==$correct_mod) ? "OK" : (($testrun) ? "Action Required, should be [" . $correct_mod ."]" : ($ch ? "Updated to " . $correct_mod : "Update failed"))) . "</td>
						</tr>";

					  } // if if_dir
if ($i > 5000) {
	break;
}

              } //if ($file !== '.' AND $file !== '..')
          } //while (($file = readdir($dh)) !== false)
      } //if ($dh = opendir($current_dir))
} // while ($stack)
chdir(DIR_FS_ADMIN);
return;
   //return $path_list;
} // end function

if (isset($_POST['action']) && $_POST['action'] == 'getversion')
  {
    if (isset($_POST['version_check']) && $_POST['version_check'] == 'on')
      $versionStatus = AnnounceVersion($contribPath, $currentVersion, $contribName);
  }
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
       <tr>
           <td class="smallText" valign="top"><?php echo HEADING_TITLE_SUPPORT_THREAD; ?></td>
       </tr>
       <tr>       
          <td class="smallText" align="right"><?php echo HEADING_TITLE_AUTHOR; ?></td>
         </tr>
         <?php  
         if (function_exists('AnnounceVersion')) {
          if (CHECK_PERMISSIONS_ENABLE_VERSION_CHECKER == 'true') { 
         ?>
         <tr>
          <td class="smallText" align="right" style="font-weight: bold; color: red;"><?php echo AnnounceVersion($contribPath, $currentVersion, $contribName); ?></td>
         </tr>
         <?php } else if (tep_not_null($versionStatus)) { 
           echo '<tr><td class="smallText" align="right" style="font-weight: bold; color: red;">' . $versionStatus . '</td></tr>';
         } else {
           echo tep_draw_form('version_check', FILENAME_CHECK_PERMISSIONS, '', 'post') . tep_draw_hidden_field('action', 'getversion'); 
         ?>
         <tr>
          <td class="smallText" align="right" style="font-weight: bold; color: red;"><INPUT TYPE="radio" NAME="version_check" onClick="this.form.submit();"><?php echo TEXT_VERSION_CHECK_UPDATES; ?></td>
         </tr>
         </form>
         <?php } } else { ?>
            <tr>
               <td class="smallText" align="right" style="font-weight: bold; color: red;"><?php echo TEXT_MISSING_VERSION_CHECKER; ?></td>
            </tr>
         <?php } ?>
        </td>
       </tr>  
      </td>  
     </tr>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
   if ($_GET['action']) {
		$configure = $_POST['configure'];
		$a_php = $_POST['a_php'];
		$na_php = $_POST['na_php'];
		$other_na_files = $_POST['other_na_files'];
		$other_a_files = $_POST['other_a_files'];
		$images = $_POST['images'];
		$graph = $_POST['graph'];
		$download = $_POST['download'];
		$banned = $_POST['banned'];
		$sitemap = $_POST['sitemap'];
		$ip_trap = $_POST['ip_trap'];
		$backup = $_POST['backup'];
		$temp = $_POST['temp'];
		$tmp = $_POST['tmp'];
		$pub = $_POST['pub'];
		$otherdirectories = $_POST['otherdirectories'];
		echo "<tr>
			<td>Type</td>
			<td>Name</td>
			<td>Original permissions</td>
			<td>Actions / Results</td>
		</tr>";

      switch ($_GET['action']) {
        case 'check':					
			pathlock('.',false,true); // listall?=false, testrun?=true
			//pathlock($_SERVER['DOCUMENT_ROOT'],false,true); // listall?=false, testrun?=true
			break;
		case "update":
			pathlock('.',false,false); 
			break;
		}

 }else{ 
?>
		<tr>
			<td colspan=4><br /><?php echo TEXT_CHECK_PERMISSIONS; ?><br /></td>
		</tr>
<form name=f1 method="post" action=<?php FILENAME_CHECK_PERMISSIONS ?>>
		<tr>
            <td class="Main" width=25%><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_1; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('configure', '644', "size=3, maxlength=3"); ?></td>
			<td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_1; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_2; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('na_php', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_2; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_3; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('other_na_files', '644', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_3; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_4; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('a_php', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_4; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_5; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('other_a_files', '644', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_5; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_6; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('images', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_6; ?></td>
		</tr>		
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_7; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('graph', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_7; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_8; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('backup', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_8; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_9; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('temp', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_9; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_10; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('tmp', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_10; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_12; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('pub', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_12; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_13; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('download', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_13; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_14; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('banned', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_14; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_15; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('ip_trap', '666', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_14; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_16; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('sitemap', '666', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_16; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_11; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
			<td align="left"><?php echo tep_draw_input_field('otherdirectories', '755', "size=3, maxlength=3"); ?></td>
		    <td align="left"><?php echo TEXT_CHECK_PERMISSIONS_TEXT_QUESTION_11; ?></td>
		</tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_20; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
           <td><input type='submit' value='check' onclick="f1.action='check_permissions.php?action=check';return true;"></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
        </tr>
		<tr>
            <td class="Main"><?php echo TEXT_CHECK_PERMISSIONS_QUESTION_21; ?></td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
           <td><input type='submit' value='update' onclick="f1.action='check_permissions.php?action=update'; return true;"> </td>
		    <td align="left"><?php echo tep_draw_separator('pixel_trans.gif', '1', '50'); ?></td>
        </tr>
</form> 

<?php } ?>

          </table>
        </td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>