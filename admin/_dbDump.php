<?php
/*
  $Id: stats_customers.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require_once('includes/application_top.php');?>


<?

function  get_db_pdfs(){
    $pdfs = array();
    $query = tep_db_query("SELECT products_id, cod_unic, products_pdf  FROM products");
    while ($p = tep_db_fetch_array($query)){

        if (file_exists(PDFS_PATH.$p['products_pdf'])){
            $pdfs[$p['products_image']]['id'] = $p['products_id'];
            $pdfs[$p['products_image']]['cu'] = $p['cod_unic'];
        }

    }
    return $pdfs;
}
function  get_db_images(){
    $images = array();
    $query = tep_db_query("SELECT products_id, cod_unic, products_image, products_image_2, products_image_3, products_image_4  FROM products");
    while ($p = tep_db_fetch_array($query)){

        if (file_exists(MARI_PATH.$p['products_image'])){
            /*$images[$p['products_image']]['path'] = 'http://www.toolszone.ro/images/mari/'. $p['products_image'];*/
            $images[$p['products_image']]['id'] = $p['products_id'];
            $images[$p['products_image']]['cu'] = $p['cod_unic'];
        }
        if (file_exists(MARI_PATH.$p['products_image_2'])){
            /*$images[$p['products_image']]['path'] = 'http://www.toolszone.ro/images/mari/'. $p['products_image'];*/
            $images[$p['products_image_2']]['id'] = $p['products_id'];
            $images[$p['products_image_2']]['cu'] = $p['cod_unic'];
        }
        if (file_exists(MARI_PATH.$p['products_image_3'])){
            /*$images[$p['products_image']]['path'] = 'http://www.toolszone.ro/images/mari/'. $p['products_image'];*/
            $images[$p['products_image_3']]['id'] = $p['products_id'];
            $images[$p['products_image_3']]['cu'] = $p['cod_unic'];
        }
        if (file_exists(MARI_PATH.$p['products_image_4'])){
            /*$images[$p['products_image']]['path'] = 'http://www.toolszone.ro/images/mari/'. $p['products_image'];*/
            $images[$p['products_image_4']]['id'] = $p['products_id'];
            $images[$p['products_image_4']]['cu'] = $p['cod_unic'];
        }

    }
    return $images;
}

function  get_images($dir){
    $images = array();
    if ($handle = opendir($dir)) {

        /* This is the correct way to loop over the directory. */
        $i = 0;
        while (false !== ($entry = readdir($handle))) {
            if($entry != '.' && $entry != '..' && $entry != '.htaccess' && $entry != '.ftpquota' && !is_dir(POZE_PATH.$entry) )
                $images[$i++] = $entry;
        }
        closedir($handle);
    }

    return $images;
}


class scanDir {
    static private $directories, $files, $ext_filter, $recursive;

// ----------------------------------------------------------------------------------------------
    // scan(dirpath::string|array, extensions::string|array, recursive::true|false)
    static public function scan(){
        // Initialize defaults
        self::$recursive = false;
        self::$directories = array();
        self::$files = array();
        self::$ext_filter = false;

        // Check we have minimum parameters
        if(!$args = func_get_args()){
            die("Must provide a path string or array of path strings");
        }
        if(gettype($args[0]) != "string" && gettype($args[0]) != "array"){
            die("Must provide a path string or array of path strings");
        }

        // Check if recursive scan | default action: no sub-directories
        if(isset($args[2]) && $args[2] == true){self::$recursive = true;}

        // Was a filter on file extensions included? | default action: return all file types
        if(isset($args[1])){
            if(gettype($args[1]) == "array"){self::$ext_filter = array_map('strtolower', $args[1]);}
            else
                if(gettype($args[1]) == "string"){self::$ext_filter[] = strtolower($args[1]);}
        }

        // Grab path(s)
        self::verifyPaths($args[0]);
        return self::$files;
    }

    static private function verifyPaths($paths){
        $path_errors = array();
        if(gettype($paths) == "string"){$paths = array($paths);}

        foreach($paths as $path){
            if(is_dir($path)){
                self::$directories[] = $path;
                $dirContents = self::find_contents($path);
            } else {
                $path_errors[] = $path;
            }
        }

        if($path_errors){echo "The following directories do not exists<br />";die(var_dump($path_errors));}
    }

    // This is how we scan directories
    static private function find_contents($dir){
        $result = array();
        $root = scandir($dir);
        foreach($root as $value){
            if($value === '.' || $value === '..') {continue;}
            if(is_file($dir.DIRECTORY_SEPARATOR.$value)){
                if(!self::$ext_filter || in_array(strtolower(pathinfo($dir.DIRECTORY_SEPARATOR.$value, PATHINFO_EXTENSION)), self::$ext_filter)){
                    self::$files[] = array($result[] = $dir.DIRECTORY_SEPARATOR.$value,filesize($dir.DIRECTORY_SEPARATOR.$value));
                }
                continue;
            }
            if(self::$recursive){
                foreach(self::find_contents($dir.DIRECTORY_SEPARATOR.$value) as $value) {
                    self::$files[] = array($result[] = $value,filesize($value));
                }
            }
        }
        // Return required for recursive search
        return $result;
    }
}



class FS {
    public $base = '/home1/toolz/www/';
    public $files;
    public $folders = array('poze','images/mari','images/mici','pdf');

    public function listFiles($askedForFolder=''){
        $fullPath = array();
        if (!isset($askedForFolder) || !is_array($askedForFolder)){
            $askedForFolder = $this->folders;
        }

        foreach($askedForFolder as $folder){
            $fullPath[] = $this->base . $folder;
        }



        $this->files = scanDir::scan($fullPath,null,true);
    }

    public function getChecksum(){
        return md5(json_encode($this->files));
    }

    public function getList(){
        return json_encode($this->files);
    }

}


class DB {
    public $tables;
    public $data;

    public function getTables(){
        $i = 0;
        $query = tep_db_query("SHOW TABLES FROM ".DB_DATABASE);
        while ($t = mysql_fetch_array($query, MYSQL_NUM)){
            $this->tables[$i++] = $t[0];
        }
    }

    public function extractTable($askedForTables = ''){
        $queryTables = array();
        if (!isset($askedForTables) || !is_array($askedForTables)){
            $queryTables = $this->tables;
        }
        foreach ($askedForTables as $table) {
            if (in_array($table,$this->tables)){
                $queryTables[] = $table;
            }
        }
        foreach ($queryTables as $table){
            $result = mysql_query("SELECT * from $table") or die('{"error":"Could not query table "'.$table.'}');

            if(mysql_num_rows($result)){
                mysql_fetch_assoc($result);
                while($row=mysql_fetch_row($result)){
                    //  cast results to specific data types
                    $this->data[$table][] = $row;
                }
            } else {
                echo 'error!';
            }

            //mysql_close($db);
        }
    }

    public function getChecksum(){
        return md5(json_encode($this->data));
    }
    public function __construct(){
        $this->getTables();
    }
}




//require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>

</head>
<body>
<?
/*$db = new DB();
$db->extractTable(array('products_attributes'));
echo $db->getChecksum();*/

$fs = new FS();
$fs->listFiles('images/mici');
echo $fs->getChecksum();
echo $fs->getList();

echo 'all done';
?>
</body>
</html>