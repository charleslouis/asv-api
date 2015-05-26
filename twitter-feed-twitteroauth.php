<?php
/*
Template Name: Twitter Feed oAuth
*/
?>
<?php
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');

	session_start();
	// require_once("libs/twitteroauth/autoload.php"); //Path to twitteroauth library
	require "libs/twitteroauth/autoload.php";
	use Abraham\TwitterOAuth\TwitterOAuth;

	$notweets = get_field('number_of_tweets');

	$twitterhandle = get_field('twitter_handle');
	
	$consumerkey = get_field('consumer_key');
	$consumersecret = get_field('consumer_secret');
	$accesstoken = get_field('oauth_access_token');
	$accesstokensecret = get_field('oauth_access_token_secret');




	function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret); 
	  return $connection;
	}
	 
	$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);


	if (have_rows('twitter_accounts')) {		
	    while ( have_rows('twitter_accounts') ) : the_row();
	        $twitter_account = get_sub_field('twitter_username');
	        $params =  array("screen_name" => $twitter_account, "count" => $notweets, "exclude_replies" => true, );
			$twitter_account_timeline = $connection->get("statuses/user_timeline", $params);
			$tweet_feed = array_merge((array) $tweet_feed, $twitter_account_timeline);
	    endwhile;
	}


	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$reg_exHash = "/#([a-z_0-9]+)/i";
	$reg_exUser = "/@([a-z_0-9]+)/i";

	foreach ($tweet_feed as $tweet) {
	    
	    $tweet_text = $tweet->text; //get the tweet
	      
	    // make links link to URL
	    // http://css-tricks.com/snippets/php/find-urls-in-text-make-links/
	    // improved by @Charleloui as initial version replaced all urls with the same link
	    if(preg_match($reg_exUrl, $tweet_text, $url)) {
	    	preg_match_all($reg_exUrl, $tweet_text, $tweet_tewt_urls_out, PREG_PATTERN_ORDER);
	 
			// 	$urls = $tweet->entities->urls;
			$urls = $tweet_tewt_urls_out[0];
			
			foreach ($urls as $url) {
			   	$tweet_text = str_replace($url, "<a href='{$url}' title='{$url}' target='_blank'>{$url}</a> ", $tweet_text);
			}
	    }
	 
	    if(preg_match($reg_exHash, $tweet_text, $hash)) {
	 		
	 		preg_match_all($reg_exHash, $tweet_text, $tweet_tewt_hashes_out, PREG_PATTERN_ORDER);

			$hashes = $tweet_tewt_hashes_out[0];

			foreach ($hashes as $hash) {
	       		$tweet_text = str_replace($hash, "<a href='https://twitter.com/search?q={$hash}' target='_blank'>{$hash}</a> ", $tweet_text);
			}
	       	// make the hash tags hyper links    https://twitter.com/search?q=%23truth
	        
	       	// swap out the # in the URL to make %23
	       	$tweet_text = str_replace("/search?q=#", "/search?q=%23", $tweet_text );
	 
	    }


	    if(preg_match($reg_exUser, $tweet_text, $user)) {

	 		preg_match_all($reg_exUser, $tweet_text, $tweet_tewt_users_out, PREG_PATTERN_ORDER);

			$users = $tweet_tewt_users_out[0];

			foreach ($users as $user) {
	       		$tweet_text = str_replace($user, "<a href='http://twitter.com/{$user}' target='_blank'>{$user}</a> ", $tweet_text);
			}

	        // swap out the @ in the URL
	        $tweet_text = str_replace("/@", "/", $tweet_text );
 		}

 		$tweet->text_html = $tweet_text;
 	}


	echo json_encode($tweet_feed);
?>