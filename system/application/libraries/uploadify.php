<?php
/*
*	Functions taken from CI_Upload Class
*
*/
	
	function set_filename($path, $filename, $file_ext, $encrypt_name = FALSE)
	{
		if ($encrypt_name == TRUE)
		{		
			mt_srand();
			$filename = md5(uniqid(mt_rand())).$file_ext;	
		}
	
		if ( ! file_exists($path.$filename))
		{
			return $filename;
		}
	
		$filename = str_replace($file_ext, '', $filename);
		
		$new_filename = '';
		for ($i = 1; $i < 100; $i++)
		{			
			if ( ! file_exists($path.$filename.$i.$file_ext))
			{
				$new_filename = $filename.$i.$file_ext;
				break;
			}
		}

		if ($new_filename == '')
		{
			return FALSE;
		}
		else
		{
			return $new_filename;
		}
	}
	
	function prep_filename($filename) {
	   if (strpos($filename, '.') === FALSE) {
		  return $filename;
	   }
	   $parts = explode('.', $filename);
	   $ext = array_pop($parts);
	   $filename    = array_shift($parts);
	   foreach ($parts as $part) {
		  $filename .= '.'.$part;
	   }
	   $filename .= '.'.$ext;
	   return $filename;
	}
	
	function get_extension($filename) {
	   $x = explode('.', $filename);
	   return '.'.end($x);
	} 


// Uploadify v1.6.2
// Copyright (C) 2009 by Ronnie Garcia
// Co-developed by Travis Nickels
if (!empty($_FILES)) {
	$path = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	
 //$path = "D:project/thekeralavacation.com/uploads/";
 
   $rand=rand(1,10000);
   $file_temp = $_FILES['Filedata']['tmp_name'];
   $file_name = prep_filename($_FILES['Filedata']['name']);
   $file_ext = get_extension($_FILES['Filedata']['name']);
   $real_name = $file_name;
   $newf_name = set_filename($path, $file_name, $file_ext);
   $newf_name = $rand.str_replace (' ','_',$newf_name);
   $file_size = round($_FILES['Filedata']['size']/1024, 2);
   $file_type = preg_replace("/^(.+?);.*$/", "\\1", $_FILES['Filedata']['type']);
   $file_type = strtolower($file_type);
   $targetFile =  str_replace('//','/',$path) .$newf_name;
   move_uploaded_file($file_temp,$targetFile);
   //echo "check".$newf_name;
   //$filearray = array();
   //$filearray['file_name'] = $newf_name;
   //$filearray['real_name'] = $real_name;
  // $filearray['file_ext'] = $file_ext;
   //$filearray['file_size'] = $file_size;
   //$filearray['file_path'] = $targetFile;
   //$filearray['file_temp'] = $file_temp;
   $file_name = $newf_name;
   ///image resizing
   if(!empty($targetFile))
		{
			require_once('imageResize.class.php');
            $nwidth='500';
	        $nheight='500';
			$fileSavePath=$targetFile;
			$image=str_replace (' ','_',basename($_FILES['Filedata']['name']));
			$width=126;
			$height=126;
			//$newFileName="D:project/thekeralavacation.com/uploads/thumb/tn_".$rand.$image;
			$newFileName=$path."/thumb/tn_".$rand.$image;
			imagejpeg(imageResize::Resize($fileSavePath,$nwidth,$nheight),$fileSavePath);
			imagejpeg(imageResize::Resize($fileSavePath,$width,$height),$newFileName);
			//imageResize::create_watermark($watermarklogo,$fileSavePath);
			
			
		}

   
   //$filearray['client_id'] = $client_id;
   

   $json_array = json_encode( $file_name);
   echo $json_array;
}else{
	echo "1";	
}