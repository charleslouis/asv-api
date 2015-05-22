<?php
/*
Template Name: Twitter Feed oAuth
*/
?>
<?php
	session_start();
	// require_once("libs/twitteroauth/autoload.php"); //Path to twitteroauth library
	require "libs/twitteroauth/autoload.php";
	use Abraham\TwitterOAuth\TwitterOAuth;

	$notweets = get_field('number_of_tweets');
	$consumerkey = get_field('consumer_key');
	$consumersecret = get_field('consumer_secret');
	$accesstoken = get_field('oauth_access_token');
	$accesstokensecret = get_field('oauth_access_token_secret');

	$twitteruser = get_field('twitter_handle');

	function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret); 
	  return $connection;
	}
	 
	$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

	$statuses = $connection->get("statuses/user_timeline", array("screen_name" => ["Minh_Q_Tran"], "count" => $notweets, "exclude_replies" => true));

	echo json_encode($statuses);
?>