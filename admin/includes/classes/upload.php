<?php
/*
  $Id: upload.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  class upload {
    var $file, $filename, $destination, $permissions, $extensions, $tmp_filename, $message_location;

    function upload($file = '', $destination = '', $permissions = '777', $extensions = '', $w=150, $h=50) {
      $this->set_file($file);
      $this->set_destination($destination);
      $this->set_permissions($permissions);
      $this->set_extensions($extensions);

      $this->set_output_messages('direct');
      if (tep_not_null($this->file) && tep_not_null($this->destination)) {
        $this->set_output_messages('session');
        if ( ($this->resize($w, $h) == true) && ($this->parse() == true) && ($this->save() == true)) {
          return true;
        } else {
          return false;
        }
      } 
    }

    function parse() {
      global $messageStack;

      $file = array();


      if (isset($_FILES[$this->file])) {
        $file = array('name' => $_FILES[$this->file]['name'],
                      'type' => $_FILES[$this->file]['type'],
                      'size' => $_FILES[$this->file]['size'],
                      'tmp_name' => $_FILES[$this->file]['tmp_name']);
      } elseif (isset($_FILES[$this->file])) {
        $file = array('name' => $_FILES[$this->file]['name'],
                      'type' => $_FILES[$this->file]['type'],
                      'size' => $_FILES[$this->file]['size'],
                      'tmp_name' => $_FILES[$this->file]['tmp_name']);
      }

      if ( tep_not_null($file['tmp_name']) && ($file['tmp_name'] != 'none') && is_uploaded_file($file['tmp_name']) ) {
        if (sizeof($this->extensions) > 0) {
          if (!in_array(strtolower(substr($file['name'], strrpos($file['name'], '.')+1)), $this->extensions)) {
            if ($this->message_location == 'direct') {
              $messageStack->add(ERROR_FILETYPE_NOT_ALLOWED, 'error');
            } else {
              $messageStack->add_session(ERROR_FILETYPE_NOT_ALLOWED, 'error');
            }

            return false;
          }
        }

        $this->set_file($file);
        $this->set_filename($file['name']);
        $this->set_tmp_filename($file['tmp_name']);

        return $this->check_destination();
      } else {
        if ($this->message_location == 'direct') {
          $messageStack->add(WARNING_NO_FILE_UPLOADED, 'warning');
        } else {
          $messageStack->add_session(WARNING_NO_FILE_UPLOADED, 'warning');
        }

        return false;
      }
    }

    function save() {
      global $messageStack;

      if (substr($this->destination, -1) != '/') $this->destination .= '/';

      if (move_uploaded_file($this->file['tmp_name'], $this->destination . $this->filename)) {
        chmod($this->destination . $this->filename, $this->permissions);

        if ($this->message_location == 'direct') {
          $messageStack->add(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
        } else {
          $messageStack->add_session(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
        }

        return true;
      } else {
        if ($this->message_location == 'direct') {
          $messageStack->add(ERROR_FILE_NOT_SAVED, 'error');
        } else {
          $messageStack->add_session(ERROR_FILE_NOT_SAVED, 'error');
        }

        return false;
      }
    }
	function imagecreatefromfile($path, $user_functions = false)
{
    $info = @getimagesize($path);
   
    if(!$info)
    {
        return false;
    }
   
    $functions = array(
        IMAGETYPE_GIF => 'imagecreatefromgif',
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG => 'imagecreatefrompng',
        IMAGETYPE_WBMP => 'imagecreatefromwbmp',
        IMAGETYPE_XBM => 'imagecreatefromwxbm',
        );
   
    if($user_functions)
    {
        $functions[IMAGETYPE_BMP] = 'imagecreatefrombmp';
    }
   
    if(!$functions[$info[2]])
    {
        return false;
    }
   
    if(!function_exists($functions[$info[2]]))
    {
        return false;
    }
   
    return $functions[$info[2]]($path);
}
function set_thumb($file, $photos_dir, $thumbs_dir , $quality=100, $w=150, $h=50) {
                //get image info
                list($width, $height, $type, $attr) = getimagesize($photos_dir.$file);
               
                //set dimensions
                if($width > $height) {
                  $width_t=$w;
                  //respect the ratio
                  $height_temp=round($height/$width*$w);  
                  
                  if($height_temp>$h){
                    $height_t=$h;
                    $width_t=round($width/$height*$h);                         
                  }else{
                    $height_t = $height_temp;
                  }
                  //set the offset
                  $off_y=ceil(($h - $height_t)/2);
                  $off_x=ceil(($w - $width_t)/2);
                        
                }else{
                  $height_t=$h;
                  $width_temp=round($width/$height*$h);
                   
                  if($width_temp>$w){
                    $width_t=$w;
                    $height_temp=round($height/$width*$w);                         
                  }else{
                    $width_t = $width_temp;
                  }     
                  
                  $off_y=ceil(($h - $height_t)/2);
                  $off_x=ceil(($w - $width_t)/2);
                }
                               
                $thumb=$this->imagecreatefromfile($photos_dir.$file);
                $thumb_p = imagecreatetruecolor($w, $h);
                //default background is black
                $bg = imagecolorallocate ( $thumb_p, 255, 255, 255 );
                imagefill ( $thumb_p, 0, 0, $bg );
                imagecopyresampled($thumb_p, $thumb, $off_x, $off_y, 0, 0, $width_t, $height_t, $width, $height);
                imagejpeg($thumb_p,$thumbs_dir.$file,$quality);
}
	function resize($w=150, $h=50){
		

	
	/*
		// This is the temporary file created by PHP
		$uploadedfile = $this->destination . $this->filename;
		
		// Create an Image from it so we can do the resize
		
		
		$src = $this->imagecreatefromfile($uploadedfile);
		
		// Capture the original size of the uploaded image
		list($width,$height)=getimagesize($uploadedfile);
		
		// For our purposes, I have resized the image to be
		// 600 pixels wide, and maintain the original aspect
		// ratio. This prevents the image from being "stretched"
		// or "squashed". If you prefer some max width other than
		// 600, simply change the $newwidth variable
		
		if($width > $height){
			$newwidth=600;
			$newheight=($height/$width)*$newwidth;
			$tmp = imagecreatetruecolor($newwidth,$newheight);	
			
			
		}else{
			$newheight=400;
			$newwidth=($width/$height)*$newheight;
			$tmp = imagecreatetruecolor($newwidth,$newheight);	
			
		}
		// this line actually does the image resizing, copying from the original
		// image into the $tmp image
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		
		
		// now write the resized image to disk. I have assumed that you want the
		// resized, uploaded image file to reside in the ./images subdirectory.
		$filename = $this->destination .'mari/'. $this->filename;
		
		imagejpeg($tmp,$filename,80);
		
		imagedestroy($src);
		imagedestroy($tmp);*/
		
		//echo $this->destination;

		if(file_exists($this->destination.$this->filename)){
					
			$this->set_thumb($this->filename,$this->destination,$this->destination,90,$w,$h);
		
		}
		 // NOTE: PHP will clean up the temp file it created when the request
		// has completed.
				
		return true;
	}

	function unlink(){
		if(file_exists($this->source.$this->filename)){
			unlink($this->source.$this->filename);
		}
	}
	
    function set_file($file) {
      $this->file = $file;
    }

    function set_destination($destination) {
      $this->destination = $destination;
    }
    
    function set_source($source) {
      $this->source = $source;
    }

    function set_permissions($permissions) {
      $this->permissions = octdec($permissions);
    }

    function set_filename($filename) {
      $this->filename = $filename;
    }

    function set_tmp_filename($filename) {
      $this->tmp_filename = $filename;
    }

    function set_extensions($extensions) {
      if (tep_not_null($extensions)) {
        if (is_array($extensions)) {
          $this->extensions = $extensions;
        } else {
          $this->extensions = array($extensions);
        }
      } else {
        $this->extensions = array();
      }
    }

    function check_destination() {
      global $messageStack;

      if (!is_writeable($this->destination)) {
        if (is_dir($this->destination)) {
          if ($this->message_location == 'direct') {
            $messageStack->add(sprintf(ERROR_DESTINATION_NOT_WRITEABLE, $this->destination), 'error');
          } else {
            $messageStack->add_session(sprintf(ERROR_DESTINATION_NOT_WRITEABLE, $this->destination), 'error');
          }
        } else {
          if ($this->message_location == 'direct') {
            $messageStack->add(sprintf(ERROR_DESTINATION_DOES_NOT_EXIST, $this->destination), 'error');
          } else {
            $messageStack->add_session(sprintf(ERROR_DESTINATION_DOES_NOT_EXIST, $this->destination), 'error');
          }
        }

        return false;
      } else {
        return true;
      }
    }

    function set_output_messages($location) {
      switch ($location) {
        case 'session':
          $this->message_location = 'session';
          break;
        case 'direct':
        default:
          $this->message_location = 'direct';
          break;
      }
    }
  }
?>
