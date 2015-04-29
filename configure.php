<?php
date_default_timezone_set('Europe/Bucharest'); 
  define('HTTP_SERVER', 'http://www.toolszone.ro');
  define('HTTPS_SERVER', 'http://www.toolszone.ro');
  define('ENABLE_SSL', false);
  define('HTTP_COOKIE_DOMAIN', 'www.toolszone.ro');
  define('HTTPS_COOKIE_DOMAIN', 'www.toolszone.ro');
  define('HTTP_COOKIE_PATH', '/');
  define('HTTPS_COOKIE_PATH', '/');
  define('DIR_WS_HTTP_CATALOG', '/');
  define('DIR_WS_HTTPS_CATALOG', '/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/'); 
  define('DIR_WS_IMAGES_MANUFACTURERES', DIR_WS_IMAGES . 'manufacturers/');
  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_toolszone', '/home/toolsz/public_html/');
  define('DIR_FS_DOWNLOAD', DIR_FS_toolszone . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_toolszone . 'pub/');

  define('DB_SERVER', 'localhost');
  define('DB_SERVER_USERNAME', 'toolsz_tz');
  define('DB_SERVER_PASSWORD', 'p455w0rd');
  define('DB_DATABASE', 'toolsz_catalog');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');
?>