<?php
	session_start();
	require_once("facebook-sdk/facebook.php");
	require_once("config.php");
	
	if(!isset($_POST["submitDetails"])) {
		header("Location: index.php?error=1");
		exit;
	}
	
	if(isset($_POST["appId"])) $appId = trim($_POST["appId"]); else $appId = "";
	if(isset($_POST["appSecret"])) $appSecret = trim($_POST["appSecret"]); else $appSecret = "";
	if(isset($_POST["appScope"])) $appScope = trim($_POST["appScope"]); else $appScope = "";
	if(isset($_POST["pageId"])) $pageId = trim($_POST["pageId"]); else $pageId = "";
	if(isset($_POST["pageUrl"])) $pageUrl = trim($_POST["pageUrl"]); else $pageUrl = "";
	
	if($appId == "" || $appSecret == "") {
		header("Location: index.php?error=2");
		exit;
	}
	
	if($pageId == "" && ($pageUrl == "" || !filter_var($pageUrl, FILTER_VALIDATE_URL))) {
		header("Location: index.php?error=3");
		exit;
	}
	
	$scopeArray = explode(",", $appScope);
	for($i=0;$i<count($scopeArray); $i++) {
		$scopeArray[$i] = trim($scopeArray[$i]);
	}
	
	// If no pageId is set, try to get it via the page URL
	if($pageId == "") {
		//$pageId = getIdByUrl($pageUrl);
	}
	
	$_SESSION["appId"] = $appId;
	$_SESSION["appSecret"] = $appSecret;
	$_SESSION["appScope"] = $appScope;
	$_SESSION["pageId"] = $pageId;
	$_SESSION["pageUrl"] = $pageUrl;
	
	$myUrl = $_SERVER['SERVER_NAME'] . $_SERVER["PHP_SELF"];
	$myUrl = "http://".str_replace(basename($_SERVER['PHP_SELF']), "", $myUrl);
	$myUrl .= "getToken.php";
	
	$dialogUrl = getDialogUrl($appId, $myUrl, $scopeArray);
	header("Location: $dialogUrl");
	
	/*
	$appId = "157862504357265";
	$appSecret = "6cf49d2d9dcaec480424fa7feb0fd667";
	$pageId = "";
	$myUrl = "https://www.gbw.at/typo3conf/ext/on_fbconnect/facebook_generator.php";
	$fbUrl = $my_url."?page_id=$page_id";
	$code = "";
	$accessToken = "";
	
	if($code == "") $code = $_GET["code"];
	 */
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Facebook Access Token Generator</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		
		<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="res/main.css">
  </head>
  <body>
		<div id="main">
			
		</div>
	</body>
</html>