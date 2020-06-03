<?php

require_once('SomeClass.php');

//header('content-type:text/plain;charset:utf-8');

$obj = new SomeClass();

$sl_token = $obj->getAuthToken('ju16a6m81mhid5ue1z3v2g0uh', 'dummy@dummy.com', 'Ranjeet Kumar');

echo $sl_token . '<br>';

//echo __LINE__.'<br><textarea style="width:100%;height:200px">'.print_r($obj->getSocialPosts($sl_token, 1),1).'</textarea><br>';
//$obj->getSocialPosts($sl_token, 1);
for ($i = 1; $i <= 10; $i++) {
    $obj->getSocialPosts($sl_token, $i);
}


echo print_r($obj->avgCharLengthOfPostPerMonth(), 1);
echo print_r($obj->longestPostByCharLenPerMonth(), 1);
echo print_r($obj->totalPostsSplitByWeek(), 1);
echo print_r($obj->avgNumOfPostsPerUserPerMonth(), 1);

