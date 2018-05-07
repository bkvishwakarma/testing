<?php
include_once '../oblyengine-header.php';
$data['page']="index";
$meta['title'] = "Match Your Face";
$theme->assign("meta", $meta);
// Body 
$data['link_url'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// Body =============================== ========================

// if Ready Image Then
if(isset($data['theme_id']) && isset($data['img']))
{
	$sql = "SELECT user_id FROM post WHERE post_img = '".$data['img']."'";
	$post_user_id =  mysqli_fetch_assoc(mysqli_query($con,$sql))['user_id'];
	
	$data['readyImg'] = $data['img'];
	$sql = "SELECT theme_tags, theme_title, theme_desc FROM bk_match_theme WHERE id = '".$data['theme_id']."' ";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_assoc($result))
	{
		$meta['title'] = $row['theme_title'];
		$meta['keywords'] = $row['theme_tags'];
		$meta['description'] = $row['theme_title'];
	}
}


if(isset($user) && isset($user['id']))
{	

	// Get User Image 
	$path = $user['user_image'];
    $type = pathinfo($path, PATHINFO_EXTENSION);
	$file_data = file_get_contents($path);
	$data['user_img'] = 'data:image/' . $type . ';base64,' . base64_encode($file_data);
}	



// Defalutl  ==========================================================
$sql = "SELECT id, theme_title, theme_tpl, theme_img FROM bk_match_theme ORDER BY id DESC LIMIT 0,60";
$result = mysqli_query($con,$sql);
while($row = mysqli_fetch_assoc($result))
{
	$data['theme'][$row['id']] = $row;
}

// Theme Assign
$theme->assign("data", $data);
$theme->assign("meta", $meta);
showpage($theme->fetch("match/index.tpl"),"","","oblyengine-match.tpl");	
?>