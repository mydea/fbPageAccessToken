<?php
	require_once 'config.php';

	// Error handling
	$error = "";
	if(isset($_GET["error"])) {
		$errorCode = $_GET["error"];
		
		switch($errorCode) {
			case 1:
				$error = "An error occured. Please enter your App Details again!";
				break;
			case 2: 
				$error = "You have to enter an App ID and an App Secret!";
				break;
			case 3:
				$error = "You have to enter either a Page ID or a valid Page URL!";
				break;
			case 4:
				$error = "Sorry, but the URL you provided could not be resolved. Please enter the ID of your page manually.";
				break;
		}
	}

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
			
			<h1>Facebook Access Token Generator</h1>
			<p>
				This tool generates an Access Token for a Facebook Page, 
				in order to be able to programatically use the Facebook API.
			</p>
			<h2>Prerequisites</h2>
			<ul>
				<li>Make sure you are logged in with the correct Facebook-Account.</li>
				<li>Make sure the account is administrator of the desired page.</li>
				<li>Make sure this tool is running on a server which has been added to the Facebook Developer Tool. <a href='res/info_url.png' target='_blank'>[Show further instructions]</a></li>
			</ul>
			<h2>Instructions</h2>
			<ol>
				<li>Enter the details of your Facebook App and click on "Generate".</li>
				<li>Accept all popups by Facebook.</li>
				<li>That's it!</li>
			</ol>
			
			<h2>Your App Details</h2>
			<form action='getCode.php' method='post'>
				<?php
					if($error != "") {
						echo "<div class='errorBox'>$error</div>";
					}
				?>
				<ul class='formList'>
					<li>
						<label for='appId'>App ID*:</label>
						<input type='text' name='appId' id='appId' required value='<?php if($defaultAppId != "") echo $defaultAppId; ?>' <?php if($defaultAppId != "" && $defaultReadonly) echo "readonly"; ?> />
					</li>
					<li>
						<label for='appSecret'>App Secret*:</label>
						<input type='text' name='appSecret' id='appSecret' required value='<?php if($defaultAppSecret != "") echo $defaultAppSecret; ?>' <?php if($defaultAppSecret != "" && $defaultReadonly) echo "readonly"; ?> />
					</li>
					<li>
						<label for='appScope'>Permissions:</label>
						<input type='text' name='appScope' id='appScope' placeholder='Comma separatad, e.g. publish_stream,create_event' value='<?php if($defaultAppScope != "") echo $defaultAppScope; ?>' <?php if($defaultAppScope != "" && $defaultReadonly) echo "readonly"; ?> />
					</li>
					<li>&nbsp;</li>
					<li><label for='pageId'>Page ID:</label><input type='text' name='pageId' id='pageId' /></li>
					<li class='center'>OR</li>
					<li><label for='pageUrl'>Page URL:</label><input type='url' name='pageUrl' id='pageUrl'  placeholder='e.g. https://www.facebook.com/YourSite'/></li>
					<li class='center'><input type='submit' name='submitDetails' value='Generate' /></li>
				</ul>
				
			</form>
		</div>
	</body>
</html>

