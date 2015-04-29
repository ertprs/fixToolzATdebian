<?php
 return array (
  'list' => 
  array (
    'script' => 'list.php',
    'params' => 
    array (
    ),
    'functions' => 
    array (
      0 => 'manage_chatbox',
    ),
    'script_path' => 'modules/lhchatbox/list.php',
  ),
  'delete' => 
  array (
    'params' => 
    array (
      0 => 'id',
    ),
    'uparams' => 
    array (
      0 => 'csfr',
    ),
    'functions' => 
    array (
      0 => 'manage_chatbox',
    ),
    'script_path' => 'modules/lhchatbox/delete.php',
  ),
  'syncuser' => 
  array (
    'script' => 'syncuser.php',
    'params' => 
    array (
      0 => 'chat_id',
      1 => 'message_id',
      2 => 'hash',
    ),
    'uparams' => 
    array (
      0 => 'mode',
      1 => 'ot',
    ),
    'script_path' => 'modules/lhchatbox/syncuser.php',
  ),
  'addmsguser' => 
  array (
    'script' => 'addmsguser.php',
    'params' => 
    array (
      0 => 'chat_id',
      1 => 'hash',
    ),
    'uparams' => 
    array (
      0 => 'mode',
      1 => 'render',
    ),
    'script_path' => 'modules/lhchatbox/addmsguser.php',
  ),
  'view' => 
  array (
    'script' => 'view.php',
    'params' => 
    array (
      0 => 'id',
    ),
    'functions' => 
    array (
      0 => 'manage_chatbox',
    ),
    'script_path' => 'modules/lhchatbox/view.php',
  ),
  'new' => 
  array (
    'params' => 
    array (
      0 => 'id',
    ),
    'functions' => 
    array (
      0 => 'manage_chatbox',
    ),
    'script_path' => 'modules/lhchatbox/new.php',
  ),
  'chatwidget' => 
  array (
    'script' => 'chatwidget.php',
    'params' => 
    array (
    ),
    'uparams' => 
    array (
      0 => 'sound',
      1 => 'mode',
      2 => 'identifier',
      3 => 'chat_height',
      4 => 'hashchatbox',
    ),
    'script_path' => 'modules/lhchatbox/chatwidget.php',
  ),
  'getstatus' => 
  array (
    'script' => 'getstatus.php',
    'params' => 
    array (
    ),
    'functions' => 
    array (
    ),
    'uparams' => 
    array (
      0 => 'position',
      1 => 'top',
      2 => 'units',
      3 => 'width',
      4 => 'height',
      5 => 'chat_height',
      6 => 'sc',
      7 => 'scm',
      8 => 'dmn',
    ),
    'script_path' => 'modules/lhchatbox/getstatus.php',
  ),
  'embed' => 
  array (
    'script' => 'embed.php',
    'params' => 
    array (
    ),
    'uparams' => 
    array (
      0 => 'chat_height',
    ),
    'functions' => 
    array (
    ),
    'script_path' => 'modules/lhchatbox/embed.php',
  ),
  'embedcode' => 
  array (
    'script' => 'embedcode.php',
    'params' => 
    array (
    ),
    'functions' => 
    array (
      0 => 'manage_chatbox',
    ),
    'script_path' => 'modules/lhchatbox/embedcode.php',
  ),
  'edit' => 
  array (
    'script' => 'edit.php',
    'params' => 
    array (
      0 => 'id',
    ),
    'functions' => 
    array (
      0 => 'manage_chatbox',
    ),
    'script_path' => 'modules/lhchatbox/edit.php',
  ),
  'generalsettings' => 
  array (
    'params' => 
    array (
      0 => 'id',
    ),
    'functions' => 
    array (
      0 => 'manage_chatbox',
    ),
    'script_path' => 'modules/lhchatbox/generalsettings.php',
  ),
  'htmlcode' => 
  array (
    'script' => 'htmlcode.php',
    'params' => 
    array (
    ),
    'functions' => 
    array (
      0 => 'manage_chatbox',
    ),
    'script_path' => 'modules/lhchatbox/htmlcode.php',
  ),
  'chatwidgetclosed' => 
  array (
    'script' => 'chatwidgetclosed.php',
    'params' => 
    array (
    ),
    'script_path' => 'modules/lhchatbox/chatwidgetclosed.php',
  ),
  'configuration' => 
  array (
    'script' => 'configuration.php',
    'params' => 
    array (
    ),
    'script_path' => 'modules/lhchatbox/configuration.php',
  ),
);
?>