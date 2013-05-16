<?php
	session_start();
	require_once("facebook-sdk/facebook.php");
	require_once("config.php");
	
	if(!isset($_SESSION["appId"]) || !isset($_SESSION["appSecret"])) {
		header("Location: index.php?error=1");
		die("1");
	}
	
	$appId = $_SESSION["appId"];
	$appSecret= $_SESSION["appSecret"];
	$appScope = $_SESSION["appScope"];
	$pageId = $_SESSION["pageId"];
	$pageUrl = $_SESSION["pageUrl"];
	
	if(!isset($_GET["code"])&& $_GET["code"] != "") {
		header("Location: index.php?error=1");
		die("2");
	}
		
	$code = $_GET["code"];
	
	if($pageId == "") {
		$pageId = getIdByUrl($pageUrl);
		if(!$pageId) {
			header("Location: index.php?error=4");
			die("3");
		}
	}
	
	$myUrl = $_SERVER['SERVER_NAME'] . $_SERVER["PHP_SELF"];
	$myUrl = "http://".str_replace(basename($_SERVER['PHP_SELF']), "", $myUrl);
	$myUrl .= "getToken.php";

	
	$tokenUrl = "https://graph.facebook.com/oauth/access_token?client_id="
		. $appId . "&redirect_uri=" . urlencode($myUrl)
		. "&client_secret=" . $appSecret
		. "&code=" . $code;
	$accessToken = @file_get_contents($tokenUrl);
	if(!$accessToken) {
		header("Location: index.php?error=1");
		exit;
	}

	$pageTokenUrl = "https://graph.facebook.com/" .
		$pageId . "?fields=access_token&" . $accessToken;
	$response = @file_get_contents($pageTokenUrl);
	if(!$response) {
		header("Location: index.php?error=1");
		exit;
	}

	// Parse the return value and get the Page access token
	$resp_obj = json_decode($response, true);

	$pageAccessToken = $resp_obj['access_token'];
	
	if($pageAccessToken == "") {
		header("Location: index.php?error=1");
		exit;
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Facebook Page Access Token Generator</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		
		<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="res/main.css">
  </head>
  <body>
		<div id="main">
			
			<h1>Facebook Page Access Token Generator - Result</h1>
			<p>
				Your Access Token has been successfully generated!
			</p>
			<h2>Your data</h2>
			<ul>
				<li><strong>App ID:</strong> <?php echo $appId; ?></li>
				<li><strong>App Secret:</strong> <?php echo $appSecret; ?></li>
				<li><strong>Permissions:</strong> <?php echo $appScope; ?></li>
			</ul>
			<ul>
				<li><strong>Page ID:</strong> <?php echo $pageId; ?></li>
				<li><strong>Page Access Token:</strong>
					<div class='token'><?php echo $pageAccessToken; ?></div></li>
			</ul>
			
			<p><a href='index.php'>Create another Token</a></p>
		</div>
	</body>
</html>