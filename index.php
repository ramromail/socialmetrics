<?php

require_once('SomeClass.php');

header('content-type:text/plain;charset:utf-8');

$client_id = 'ju16a6m81mhid5ue1z3v2g0uh';
$client_email = 'your@email.address';
$client_name = 'Your Name';

$auth_url = 'https://api.supermetrics.com/assignment/register';
$fetch_url = 'https://api.supermetrics.com/assignment/posts';

$obj = new SomeClass($client_id, $client_email, $client_name, $auth_url, $fetch_url);

if (false !== ($token = $obj->getAuthToken())) {
    echo 'Success ' . $token . PHP_EOL;
    for ($i = 1; $i <= 10; $i++) {
        $obj->getSocialPosts($i);
    }
} else {
    echo 'Failed to get token';
}

//echo __LINE__.'<br><textarea style="width:100%;height:200px">'.print_r($obj->getSocialPosts($sl_token, 1),1).'</textarea><br>';
//$obj->getSocialPosts($sl_token, 1);


echo print_r($obj->avgCharLengthOfPostPerMonth(), 1);
echo print_r($obj->longestPostByCharLenPerMonth(), 1);
echo print_r($obj->totalPostsSplitByWeek(), 1);
echo print_r($obj->avgNumOfPostsPerUserPerMonth(), 1);

