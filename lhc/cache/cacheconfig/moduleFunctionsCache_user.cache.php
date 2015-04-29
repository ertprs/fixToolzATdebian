<?php
 return array (
  'login' => 
  array (
    'script' => 'login.php',
    'params' => 
    array (
    ),
    'uparams' => 
    array (
      0 => 'r',
    ),
    'script_path' => 'modules/lhuser/login.php',
  ),
  'logout' => 
  array (
    'script' => 'logout.php',
    'params' => 
    array (
    ),
    'script_path' => 'modules/lhuser/logout.php',
  ),
  'account' => 
  array (
    'script' => 'logout.php',
    'params' => 
    array (
    ),
    'functions' => 
    array (
      0 => 'selfedit',
    ),
    'script_path' => 'modules/lhuser/account.php',
  ),
  'userlist' => 
  array (
    'script' => 'userlist.php',
    'params' => 
    array (
    ),
    'functions' => 
    array (
      0 => 'userlist',
    ),
    'script_path' => 'modules/lhuser/userlist.php',
  ),
  'grouplist' => 
  array (
    'script' => 'grouplist.php',
    'params' => 
    array (
    ),
    'functions' => 
    array (
      0 => 'grouplist',
    ),
    'script_path' => 'modules/lhuser/grouplist.php',
  ),
  'edit' => 
  array (
    'script' => 'edit.php',
    'params' => 
    array (
      0 => 'user_id',
    ),
    'functions' => 
    array (
      0 => 'edituser',
    ),
    'script_path' => 'modules/lhuser/edit.php',
  ),
  'delete' => 
  array (
    'script' => 'delete.php',
    'params' => 
    array (
      0 => 'user_id',
    ),
    'uparams' => 
    array (
      0 => 'csfr',
    ),
    'functions' => 
    array (
      0 => 'deleteuser',
    ),
    'script_path' => 'modules/lhuser/delete.php',
  ),
  'new' => 
  array (
    'script' => 'new.php',
    'params' => 
    array (
    ),
    'functions' => 
    array (
      0 => 'createuser',
    ),
    'script_path' => 'modules/lhuser/new.php',
  ),
  'newgroup' => 
  array (
    'script' => 'newgroup.php',
    'params' => 
    array (
    ),
    'functions' => 
    array (
      0 => 'creategroup',
      1 => 'editgroup',
    ),
    'script_path' => 'modules/lhuser/newgroup.php',
  ),
  'editgroup' => 
  array (
    'script' => 'editgroup.php',
    'params' => 
    array (
      0 => 'group_id',
    ),
    'functions' => 
    array (
      0 => 'editgroup',
    ),
    'script_path' => 'modules/lhuser/editgroup.php',
  ),
  'groupassignuser' => 
  array (
    'script' => 'groupassignuser.php',
    'params' => 
    array (
      0 => 'group_id',
    ),
    'functions' => 
    array (
      0 => 'groupassignuser',
    ),
    'script_path' => 'modules/lhuser/groupassignuser.php',
  ),
  'deletegroup' => 
  array (
    'script' => 'deletegroup.php',
    'params' => 
    array (
      0 => 'group_id',
    ),
    'uparams' => 
    array (
      0 => 'csfr',
    ),
    'functions' => 
    array (
      0 => 'deletegroup',
    ),
    'script_path' => 'modules/lhuser/deletegroup.php',
  ),
  'forgotpassword' => 
  array (
    'script' => 'forgotpassword.php',
    'params' => 
    array (
    ),
    'script_path' => 'modules/lhuser/forgotpassword.php',
  ),
  'remindpassword' => 
  array (
    'script' => 'remindpassword.php',
    'params' => 
    array (
      0 => 'hash',
    ),
    'script_path' => 'modules/lhuser/remindpassword.php',
  ),
  'setsetting' => 
  array (
    'script' => 'setsetting.php',
    'params' => 
    array (
      0 => 'identifier',
      1 => 'value',
    ),
    'script_path' => 'modules/lhuser/setsetting.php',
  ),
  'setsettingajax' => 
  array (
    'script' => 'setsettingajax.php',
    'params' => 
    array (
      0 => 'identifier',
      1 => 'value',
    ),
    'script_path' => 'modules/lhuser/setsettingajax.php',
  ),
  'setoffline' => 
  array (
    'script' => 'setoffline.php',
    'functions' => 
    array (
      0 => 'changeonlinestatus',
    ),
    'params' => 
    array (
      0 => 'status',
    ),
    'script_path' => 'modules/lhuser/setoffline.php',
  ),
);
?>