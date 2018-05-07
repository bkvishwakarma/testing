<?php

include_once 'oblyengine-header.php';

$data['page'] = 'bk-login.php';

// Add User

if(isset($data['action']) && $data['action']=='user_details')

{

	$user_details = json_decode($data['user_details']);

	if($user_details!="error")

	{

		$username 	= $user_details->name;

		$fb_id 		= $user_details->id;

		$first_name = $user_details->first_name;

		$last_name 	= $user_details->last_name;

		$gender 	= $user_details->gender;

		$picture_object 	= $user_details->picture;

		$picture	= $picture_object;

		$picture_url= $picture->data;

	 	$user_image = $picture_url->url;

	 	$fb_creatoin_date = date('Y-m-d');

	 	// check for user already add or not  

	 	$check = "SELECT * FROM post_users where fb_id='$fb_id' ";

		$result = mysqli_query($con,$check);

		$num = mysqli_num_rows($result);

		if($num>0)

		{

			$row =mysqli_fetch_assoc($result);

			$_SESSION['current_user']['id'] = $row['id'];

			$_SESSION['current_user']['user_name'] = $row['fb_user_name'];

		 	$_SESSION['current_user']['fb_id'] = $row['fb_id'];

		 	$_SESSION['current_user']['first_name'] = $row['fb_first_name'];

		 	$_SESSION['current_user']['gender'] = $row['fb_gender'];

		 	$_SESSION['current_user']['user_image'] = $user_image;

		 	// end hare make session	 	

		 	$current_user  = $_SESSION['current_user'];

			echo  json_encode($current_user);	

		}

		else

		{

			$insert_query  =  "INSERT into post_users (fb_id,fb_user_name,fb_first_name,fb_gender,fb_img_url,fb_user_mobile,fb_user_email,fb_creatoin_date,fb_user_status )values('$fb_id','$username','$first_name','$gender','$user_image','','','$fb_creatoin_date','1')";

			if(mysqli_query($con,$insert_query))

			{

				$id 	=	mysqli_insert_id($con);

				$_SESSION['current_user']['id'] = $id;

				$_SESSION['current_user']['user_name'] = $username;

			 	$_SESSION['current_user']['fb_id'] = $fb_id;

			 	$_SESSION['current_user']['first_name'] = $first_name;

			 	$_SESSION['current_user']['gender'] = $gender;

			 	$_SESSION['current_user']['user_image'] = $user_image;

			 	// end hare make session

			 	$current_user  = $_SESSION['current_user'];

				echo  json_encode($current_user);	

				

			}

			else

			{

				$data['msg']= "Something went wrong, Sorry Please  try Again";

			}

		 	// make here session 

		}

	 	// end code check for user already add or not 

		exit();

	}

}

















$theme->assign("data", $data);

$theme->assign("theme_opt", $theme_opt);

showpage($theme->fetch("login.tpl"));

?>