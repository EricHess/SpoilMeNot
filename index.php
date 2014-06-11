<?php
ini_set('display_errors', 1);
require_once('TwitterAPIExchange.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "109027257-arf0nXCG8Vfhi19M59AOuzADw8X6wbVc0V8lY1Ft",
    'oauth_access_token_secret' => "vnnZciZTKlj0vzMKguckqpfC1DXgsdDUbRbMqCoMqcCs6",
    'consumer_key' => "FNa5Yv1rJESkspxpHZzHwz0Cy",
    'consumer_secret' => "5VmlWmGNOHotovHfh6kxkNiwAwP970f97KsnSBTv8uK7laeTkV"
);

/** Perform a GET request and echo the response **/
/** Note: Set the GET field BEFORE calling buildOauth(); **/
$url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
$getfield = '?screen_name=EricHessDesign&count=10';
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);
$feed = json_decode($twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest());

//Grab the no spoilers keywords from the URL
$noSpoilers = explode(',',$_GET['spoilMeNot']);

//Echo out the terms that are being hidden (add checkbox functionality)
foreach($noSpoilers as $hideMe){
    echo '<div>'.$hideMe.'</div>';
}


//Grab the latest tweets and also parse out their hashtags (if they have any)
foreach($feed as $tweets){

    //Clear out the hashtag array each run (maybe put at bottom)
    $allActiveHashtags = array();

    $showThisTweet = false;

    //Push each hashtag from each tweet in to the allActiveHashtags array
    foreach($tweets->entities->hashtags as $hashtags){
        array_push($allActiveHashtags, $hashtags->text);
    }

    //Check each item in allactivehashtags array against noSpoilers and do not render tweet if there is a match
    $spoiler = array_intersect($allActiveHashtags, $noSpoilers);

    if(empty($spoiler)){
        $showThisTweet = true;
    }

    if($showThisTweet){
    echo '<div style="margin:2em 0">';
    echo $tweets->text;
    echo '</div>';
    };
}

