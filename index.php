<?php

require_once('SomeClass.php');

//header('content-type:text/plain;charset:utf-8');
// define the necessary urls
$auth_url = 'https://api.supermetrics.com/assignment/register';
$fetch_url = 'https://api.supermetrics.com/assignment/posts';

// define the other necessary information
$client_id = 'ju16a6m81mhid5ue1z3v2g0uh';
$client_email = 'your@email.address';
$client_name = 'Your Name';

// create a new Object of class SomeClass, pass all the params.
$obj = new SomeClass($client_id, $client_email, $client_name, $auth_url, $fetch_url);

// see if, based on given info auth. token can be received
// if not do not try to get posts
if (false !== ($token = $obj->getAuthToken())) {
    // load all 10 pages of data
    for ($i = 1; $i <= 10; $i++) {
        if (false === $obj->getSocialPosts($i)) {
            echo 'Failed to load data on page ' . $i . '<br>';
        }
    }
} else {
    echo 'Failed to get auth. token.<br/>';
    exit();
}

/*
 * make a JSON object containing all metrics
 */
$allTogether = array(
    'Average character length of posts per month' => $obj->avgCharLengthOfPostPerMonth(),
    'Longest post by length per month' => $obj->longestPostByCharLenPerMonth(),
    'Total ammount of posts by week' => $obj->totalPostsSplitByWeek(),
    'Average number of posts per user per month' => $obj->avgNumOfPostsPerUserPerMonth(),
);

// print it
header('content-type:application/json;charset:utf-8');
echo json_encode($allTogether, JSON_PRETTY_PRINT);

/***
 * to calculate "the average number of post per user per month"
// to calculate this i have used this method,
// total number of posts throughout the period of time divided
// by number of months in the time period, notice also including
// the month were user was inactive i.e. made 0 post
 *
 */


/*
// Average character length of posts per month
$avgCharLengthOfPostPerMonth = $obj->avgCharLengthOfPostPerMonth();

// encode it to JSON, and also enable PRETTY PRINT
$avgCharLengthOfPostPerMonth = json_encode($avgCharLengthOfPostPerMonth, JSON_PRETTY_PRINT);

// Now show it
echo '<div>'
 . '<b>Average character length of posts per month:</b>'
 . '<br/><sub>The key indicates month, values indicates number of posts.</sub>'
 . '</div>'
 . '<textarea style="width:70%;height:10em;" readonly>' . $avgCharLengthOfPostPerMonth . '</textarea><br/><br/>';

// similarlly for others
// Longest post by length per month
$longestPostByCharLenPerMonth = $obj->longestPostByCharLenPerMonth();
$longestPostByCharLenPerMonth = json_encode($longestPostByCharLenPerMonth, JSON_PRETTY_PRINT);
echo '<div>'
 . '<b>Longest post by length per month</b>'
 . '<br><sub>The key indicates month, value contains length of post and the longest post.</sub>'
 . '</div>'
 . '<textarea style="width:70%;height:10em;" readonly>' . $longestPostByCharLenPerMonth . '</textarea><br/><br/>';

// Total amount of posts by week
$totalPostsSplitByWeek = $obj->totalPostsSplitByWeek();
$totalPostsSplitByWeek = json_encode($totalPostsSplitByWeek, JSON_PRETTY_PRINT);
echo '<div>'
 . '<b>Total ammount of posts by week</b>'
 . '<br/><sub>The key indicates week number and value indicates number of posts.</sub>'
 . '</div>'
 . '<textarea style="width:70%;height:10em;" readonly>' . $totalPostsSplitByWeek . '</textarea><br/><br/>';

// Average number of posts per user per month
$avgNumOfPostsPerUserPerMonth = $obj->avgNumOfPostsPerUserPerMonth();
$avgNumOfPostsPerUserPerMonth = json_encode($avgNumOfPostsPerUserPerMonth, JSON_PRETTY_PRINT);
echo '<div>'
 . '<b>Average number of posts per user per month</b>'
 . '<br/><sub>The key indicates user id and user name in the format userid__username and they values are array of months and number of posts.</sub>'
 . '</div>'
 . '<textarea style="width:70%;height:10em;" readonly>' . $avgNumOfPostsPerUserPerMonth . '</textarea><br/><br/>';

// or put them all to form one JSON object
$allTogether = array(
    'Average character length of posts per month' => $obj->avgCharLengthOfPostPerMonth(),
    'Longest post by length per month' => $obj->longestPostByCharLenPerMonth(),
    'Total ammount of posts by week' => $obj->totalPostsSplitByWeek(),
    'Average number of posts per user per month' => $obj->avgNumOfPostsPerUserPerMonth(),
);

// print it
echo '<h1>All data as one JSON object</h1>'
 . '<textarea style="width:70%;height:10em;" readonly>' . json_encode($allTogether, JSON_PRETTY_PRINT) . '</textarea>';
*/