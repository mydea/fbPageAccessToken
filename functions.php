<?php

/*
 * Returns the dialog url for the passed parameters
 */
	function getDialogUrl($appId, $myUrl, $scopeArray) {
		// FOR TESTING
		$scopeArray = array("manage_pages", "create_event", "publish_stream");
		
		// Build the url
		$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=".$appId
						."&redirect_uri=".urlencode($myUrl)
						."&scope=".buildScopeString($scopeArray);

		return $dialog_url;
	}
	
	/*
	 * Build the scope string from an array of scopes
	 * i.e. manage_pages+create_event+publish_stream
	 */
	function buildScopeString($scopeArray) {
		$scopeString = "";
		for($i=0; $i<count($scopeArray); $i++) {
			if($i>0) 
				$scopeString .= "+".$scopeArray[$i];
			else
				$scopeString .= $scopeArray[$i];
		}
		
		return $scopeString;
	}
	
	function getIdByUrl($pageUrl) {
		$pagePart = substr($pageUrl, strrpos($pageUrl, "/"));
		
		$url = "https://graph.facebook.com/$pagePart";

		$response = @file_get_contents($url);
		if(!$response) {
			header("Location: index.php?error=4");
			exit;
		}
		$resp_obj = json_decode($response, true);
	
		return $resp_obj["id"];
	}
	
	/*
	 * 
	 */
	function getAccessToken() {
		if (empty($code)) {
			// Get permission from the user to manage their Page.
			$dialog_url = "http://www.facebook.com/dialog/oauth?client_id="
				. $app_id . "&redirect_uri=" . urlencode($my_url)
				. "&scope=manage_pages+create_event+publish_stream";
			//die($dialog_url);
			echo('<script>top.location.href="' . $dialog_url . '";</script>');
			return;
		} 
	
		// Get access token for the app, so we can GET Page access token
		$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
			. $app_id . "&redirect_uri=" . urlencode($my_url)
			. "&client_secret=" . $app_secret
			. "&code=" . $code;
		//die($token_url);
		$access_token = file_get_contents($token_url);

		$page_token_url = "https://graph.facebook.com/" .
			$page_id . "?fields=access_token&" . $access_token;
		$response = file_get_contents($page_token_url);

		// Parse the return value and get the Page access token
		$resp_obj = json_decode($response, true);

		$page_access_token = $resp_obj['access_token'];
		die($page_access_token);
		// Using the Page access token from above,
		// we can GET the settings for the page
		$page_settings_url = "https://graph.facebook.com/" .
			$page_id . "/settings?access_token=" . $page_access_token;
		//die($page_settings_url);
		$response = file_get_contents($page_settings_url);
		$resp_obj = json_decode($response, true);

		echo '<pre>';
		print_r($response);
		echo '</pre>';

	}
	
	/*
	 * Formats a Facebook Error Result
	 */
	function formatError($e) {
		$e = $e["error"];
		$error = "<div class='errorBox'>";
			$error .= "<h3>Error Code: ".$e["code"]."</h3>";
			$error .= "<p>Error Type: ".$e["type"]."</p>";
			$error .= "<p>".$e["message"]."</p>";
		$error .= "</div>";
		return $error;
	}

?>
