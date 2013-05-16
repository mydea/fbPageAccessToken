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
	
	/*
	 * Trys to get an ID for a page URL.
	 */
	function getIdByUrl($pageUrl) {
		// Old!
		//$pagePart = substr($pageUrl, strrpos($pageUrl, "/"));
		//$pagePart = str_replace("/", "", $pagePart);
	
		$urlSelect = "select url, id, type from object_url where url = \"$pageUrl\"";
		$url = "http://graph.facebook.com/fql?q=".urlencode($urlSelect);

		$response = @file_get_contents($url);
		if(!$response) {
			header("Location: index.php?error=4");
			exit;
		}
		$resp_obj = json_decode($response, true);
	
		
		return $resp_obj["data"][0]["id"];
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
