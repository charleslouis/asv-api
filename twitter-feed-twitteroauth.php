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
			$news_feed = array_merge((array) $news_feed, $twitter_account_timeline);
	    endwhile;
	}
	// $news_feed[] = $news_feed;
	echo json_encode($news_feed);
?>