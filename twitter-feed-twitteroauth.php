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
	    if(preg_match($reg_exUrl, $tweet_text, $url)) {
	 
	       // make the urls hyper links
	       $tweet_text = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a> ", $tweet_text);
	 
	    }
	 
	    if(preg_match($reg_exHash, $tweet_text, $hash)) {
	 
	       // make the hash tags hyper links    https://twitter.com/search?q=%23truth
	       $tweet_text = preg_replace($reg_exHash, "<a href='https://twitter.com/search?q={$hash[0]}'>{$hash[0]}</a> ", $tweet_text);
	        
	       // swap out the # in the URL to make %23
	       $tweet_text = str_replace("/search?q=#", "/search?q=%23", $tweet_text );
	 
	    }
	 
	    if(preg_match($reg_exUser, $tweet_text, $user)) {
	 
	        $tweet_text = preg_replace("/@([a-z_0-9]+)/i", "<a href='http://twitter.com/$1'>$0</a>", $tweet_text);
 		
 		}

 		$tweet->text_html = $tweet_text;
 	}

	// $tweet_feed['tewt_html'] = $tweet_feed;
	echo json_encode($tweet_feed);
?>