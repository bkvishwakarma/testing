<?php
function getSmartyTemplate($templatedir = "layout/templates/",$basedir=false,$usesitename=true)
{
	global $website;
	global $meta;
	global $isSecure;

	if(!$basedir &&  defined('SITE_ABSPATH'))
	{
		$basedir =SITE_ABSPATH;
	}
	include_once "libs/smarty/Smarty.class.php";
	$smarty = new Smarty();
	$smarty->left_delimiter =  "<{";
	$smarty->right_delimiter=  "}>";

	if(defined('SITE_NAME') && $usesitename)
	{
		$smarty->template_dir = $basedir.SITE_NAME.$templatedir;
	}
	else
	{
		$smarty->template_dir = $basedir.$templatedir;
	}

	$smarty->compile_dir = $basedir.'templates_c/';
	$smarty->config_dir = $basedir.'configs/';
	$smarty->cache_dir = $basedir.'cache/';

	if(defined('SITE_URL'))
	{
		$smarty->assign('SITE_URL',SITE_URL);
		$smarty->assign('site_url',SITE_URL);
	}

	if(defined('SITE_SECURE_URL'))
	{
		$smarty->assign('SITE_SECURE_URL',SITE_SECURE_URL);
	}

	if(defined('SITE_SHORT_URL'))
	{
		$smarty->assign('SITE_SHORT_URL',SITE_SHORT_URL);
	}

	if(defined('SITE_LINK_URL'))
	{
		$smarty->assign('SITE_LINK_URL',SITE_LINK_URL);
	}

	if(defined('SITE_THEME_URL'))
	{
		$smarty->assign('SITE_THEME_URL',SITE_THEME_URL);
	}

	if(defined('THEME_NAME'))
	{
		$smarty->assign('THEME_NAME',THEME_NAME);
	}

	if(defined("DEVICE"))
	{
		$smarty->assign("DEVICE",DEVICE);
	}
	if(defined("THEME_SUFFIX"))
	{
		$smarty->assign("THEME_SUFFIX",THEME_SUFFIX);
	}

//	if(defined('SITE_THEME_DIR'))
//	{
//		$smarty->assign('SITE_THEME_DIR',SITE_THEME_DIR);
//	}

	if(defined('SITE_NAME'))
	{
		$smarty->assign('SITE_NAME',SITE_NAME);
	}
	$smarty->assign('isSecure',$isSecure);
	return $smarty;
}

function showpage($content,$heading="",$subheading="",$themepage="oblyengine.tpl")
{
	global $user;
	global $theme;
	global $data;
	global $errortext;

	global $org_config;
	if(isset($org_config['theme_options']))
	{
		$org_theme_options  = $org_config['theme_options'];
	}

	$copy['content']=$content;
	$copy['heading']=$heading;
	$copy['subheading']=$subheading;

	$theme->assign("error",$errortext);
	$theme->assign("data",$data);
	$theme->assign("copy",$copy);
// 	if($user['user_guid'])
// 	{
// 		cacheCookieSet($user['user_guid']."-last_activity_time",microtime_float());
// 	}
	echo $theme->fetch($themepage);
	exit;
}
function check_user()
{
	if(!isset($_SESSION['user']))
	{
		header("Location:". SITE_URL."/login.php");
	}
}
function redirect_uri($url,$data)
{
	$uri = get_param($data);
	ob_start();
    header('Location: '.$url.'?'.$uri);
    ob_end_flush();
    die();
}
function IsInjected($str)
{
	$injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str))
	{
		return true;
	}
	else
    {
		return false;
	}
}
function htmlMail($to, $from_mail, $from_name, $subject, $message)
{
	date_default_timezone_set('Etc/UTC');
	//Create a new PHPMailer instance
	$mail = new PHPMailer;

	//Set who the message is to be sent from
	$mail->setFrom($from_mail, $from_name);
	//Set an alternative reply-to address
	$mail->addReplyTo($from_mail, $from_name);
	//Set who the message is to be sent to
	$mail->addAddress($to);
	//Set the subject line
	$mail->Subject = $subject;
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->Body      = $message;
	$mail->IsHTML(true);
	$mail->AddAddress($to);
	/* Send mail to admin*/
	if($mail->Send())
	{
		return true;
	}
	else
	{
		return false;
	}
}
function readTemplateFile($FileName)
{
	$fp = fopen($FileName,"r") or exit("Unable to open File ".$FileName);
	$str = "";
	while(!feof($fp))
	{
		$str .= fread($fp,1024);
	}
	return $str;
}

function get_short($str,$num)
{
	$new_str=strtoupper(substr($str,0,2));
	$digits = $num;
	$new_str.=str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
	return $new_str;
}

function string_to_image($img_str,$folder)
{
	$img_str = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img_str));		
	$file_name = 'img_'.rand().'.png';
	file_put_contents($folder.'/'.$file_name, $img_str);	
	return $file_name;	
}

// send_obemail(to, from, sub, msg)

function send_obMail($to, $from, $sub, $data)
{
	$msg	= "";
	if(IsInjected($to))
	{
		$msg = "\n Bad email value.";
		return false;
	}
	if($msg=='')
	{
		$name    ="Gohasten";
		$ip      = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

		$emailBody = readTemplateFile(SITE_THEME_URL."mail_templates/ob-general.html");

		$logo      =SITE_THEME_URL."images/logo/logo.png";
		//Replace all the variables in template file
		$emailBody = str_replace("#logo#",$logo,$emailBody);
		$emailBody = str_replace("#sub#",$sub,$emailBody);
		$emailBody = str_replace("#msg#",$data,$emailBody);
		$emailBody = str_replace("#ip#",$ip,$emailBody);

		/* Send mail to admin*/
		if (htmlMail($to, $from, $name, $sub, $emailBody) )
		{
				return true;
		}
		else
		{
				return false;
		}
	}
}

function send_obMail_org($to, $from,$org_name,$org_logo, $sub, $data)
{
	$msg	= "";
	if(IsInjected($to))
	{
		$msg = "\n Bad email value.";
		return false;
	}
	if($msg=='')
	{
		$name    = $org_name;
		$ip      = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

		$emailBody = readTemplateFile(SITE_THEME_URL."mail_templates/ob-general.html");

		$logo      =	SITE_URL."uploads/".$org_logo;
		//Replace all the variables in template file
		$emailBody = str_replace("#logo#",$logo,$emailBody);
		$emailBody = str_replace("#sub#",$sub,$emailBody);
		$emailBody = str_replace("#msg#",$data,$emailBody);
		$emailBody = str_replace("#ip#",$ip,$emailBody);

		/* Send mail to admin*/
		if (htmlMail($to, $from, $name, $sub, $emailBody) )
		{
				return true;
		}
		else
		{
				return false;
		}
	}
}
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
{
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

function get_vcode($str,$num)
{
	$new_str=strtoupper(substr($str,0,5));
	$digits = $num;
	$new_str.=str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
	return $new_str;
}

function send_sms($sms_str,$mobile)
{
	return true;
}
function get_sql_date($date)
{
	$date_array = explode("/",trim ($date));
	return $date_array[2]."-".$date_array[1]."-".$date_array[0]." 00:00:00";
}

function get_sql_date_wt($date)
{
	$date_array = explode("/",trim ($date));
	return $date_array[2]."-".$date_array[1]."-".$date_array[0];
}
// write log 
function write_log($file,$txt)

{
	
	if (file_exists($file)) 
	{
		$current = file_get_contents($file);
		$current = $txt.$current;
		file_put_contents($file, $current);
	}
	else 
	{
		file_put_contents($file, $txt);
	}
}

?>
