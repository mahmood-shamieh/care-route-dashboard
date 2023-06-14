<?php
function upload_images($filename,$path,$time){
	$image_name = $_FILES[$filename]['name'];
	$type = explode("." ,$image_name);
	$final_type = $type[count($type)-1];
	$image_name = $path.$time.".".$final_type;
	$ext = array('jpg','jpeg','png','gif','GIF','JPG','PNG','JPEG','svg','SVG','webp','WEBP');
	if(!in_array($final_type, $ext)){
		print '<meta http-equiv="refresh" content="0;URL=index.php?cmd='.$_GET["cmd"].'&error=1" />';
		die;
	}else{
		copy($_FILES[$filename]['tmp_name'],$image_name) OR move_uploaded_file($_FILES[$filename]['tmp_name'],$image_name);
		$save = $time.'.'.$final_type;
		return $save;
	}
}
?>
