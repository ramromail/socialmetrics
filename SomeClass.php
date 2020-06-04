<?php
ini_set('html_errors', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_logging', 1);
ini_set('error_reporting', 'E_ALL');
// ini_set('session.cookie_httponly', 1);

class SomeClass {

    private $client_id;
    private $client_email;
    private $client_name;
    private $auth_url;
    private $fetch_url;
    private $auth_token;
    private $Posts = array();

    public function __construct($client_id, $client_email, $client_name, $auth_url, $fetch_url) {
        $this->client_id = $client_id;
        $this->client_email = $client_email;
        $this->client_name = $client_name;
        $this->auth_url = $auth_url;
        $this->fetch_url = $fetch_url;
    }

    /*
     * this function calculates the average character length of posts per month
     */

    public function avgCharLengthOfPostPerMonth() {
        $postsPerMonth = array();
        $avgCharLenPerMonth = array();

        /*
         * go through all the posts and divide them in months
         * based on created_time value
         */
        foreach ($this->Posts as $post) {

            $message = (string) $post['message'];
            $created = (string) $post['created_time'];

            // get the first 7 character, this gives the month
            $month = substr($created, 0, 7);

            $postsPerMonth[$month][] = $message;
        }

        /*
         * now calculate the average length
         */
        foreach ($postsPerMonth as $month => $posts) {
            $characterCount = 0;

            foreach ($posts as $post) {
                $length = strlen($post);
                $characterCount += $length;
            }

            $avgCharLenPerMonth[$month] = (int) ($characterCount / count($posts));
        }

        return $avgCharLenPerMonth;
    }

    /*
     * this function goes through each post to find the longest post per month
     */

    public function longestPostByCharLenPerMonth() {
        $postsPerMonth = array();
        $postsLenPerMonth = array();
        $longestPostByMonth = array();

        /*
         * split posts by month
         */
        foreach ($this->Posts as $post) {

            $message = (string) $post['message'];
            $created = (string) $post['created_time'];

            // get the first 7 character, this gives the month
            $month = substr($created, 0, 7);

            $postsPerMonth[$month][] = $message;
        }

        // sorting the array, to have months in ascending order
        ksort($postsPerMonth);

        /*
         * count the length of posts and put them in array
         * where the array key is length of post
         */
        foreach ($postsPerMonth as $month => $posts) {
            $characterCount = 0;

            foreach ($posts as $post) {
                $length = strlen($post);
                $characterCount += $length;
                $postsLenPerMonth[$month][$length][] = $post;
            }

            // reverse sort the array to have the longest post first in array
            krsort($postsLenPerMonth[$month]);
        }

        /*
         * get the first post and break out of the loop,
         * the first array element is the longes post
         */
        foreach ($postsLenPerMonth as $month => $arrayPosts) {
            foreach ($arrayPosts as $length => $value) {
                $longestPostByMonth[$month] = array(
                    'length' => $length,
                    'post' => $value[0]
                );
                break;
            }
        }

        return $longestPostByMonth;
    }

    /*
     * function to calculate number of posts by week
     */

    public function totalPostsSplitByWeek() {
        $postsByWeek = array();
        $totalPostsByWeek = array();

        /*
         * split all the post based on the week number they were posted
         */
        foreach ($this->Posts as $post) {

            $message = (string) $post['message'];
            $created = (string) $post['created_time'];

            // get the week number from the created_time
            $week = date('W', strtotime($created));

            $postsByWeek[$week][] = $message;
        }

        /*
         * count the number of posts
         */
        foreach ($postsByWeek as $week => $posts) {
            $totalPostsByWeek[$week] = count($posts);
        }

        // sort by the week number
        ksort($totalPostsByWeek);

        return $totalPostsByWeek;
    }

    /*
     * this function calculates average number of post per user per month
     * by counting total number of posts by each user throug out the period
     * and then dividing it by number of months in the time period
     */

    public function avgNumOfPostsPerUserPerMonth() {
        $avgPostsPerUserPerMonth = array();

        $monthsInTimePeriod = array();
        $totalPostsPerUser = array();

        foreach ($this->Posts as $post) {
            $userId = (string) $post['from_id'];
            $name = (string) $post['from_name'];
            $created = (string) $post['created_time'];

            // get the first 7 character, this gives the month
            $month = substr($created, 0, 7);

            // this will give us an array with unique months
            $monthsInTimePeriod[$month] = '';

            if (!isset($totalPostsPerUser[$name . '__' . $userId])) {
                $totalPostsPerUser[$name . '__' . $userId] = 1;
            } else {
                $totalPostsPerUser[$name . '__' . $userId]++;
            }
        }

        // sort the array by ascending order of year-month
        ksort($monthsInTimePeriod);
        ksort($totalPostsPerUser);

        // calculate the average, number of post per user per month
        // to calculate this i have used this method,
        // total number of posts throughout the period of time divided
        // by number of months in the time period, notice also including
        // the month were user was inactive i.e. made 0 post
        $countOfMonths = count($monthsInTimePeriod);

        foreach ($totalPostsPerUser as $uid => $totalPostsThroughOut) {
            list($user_name, $user_id) = explode('__', $uid);
            $avgPostsPerUserPerMonth[] = array(
                'user_id' => (string) $user_id,
                'user_name' => (string) $user_name,
                'avg_num_of_posts_per_month' => (float) round($totalPostsThroughOut / $countOfMonths, 2)
            );
        }

        return $avgPostsPerUserPerMonth;
    }

    /*
     * get the posts by page
     * the api returns 100 posts per page
     * this is a GET request
     */

    public function getSocialPosts($page) {

        $postUrl = $this->fetch_url . "?sl_token=" . $this->auth_token . "&page=" . $page;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $postUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if (false !== $response) {

            $response = json_decode($response, 1);

            if (!empty($response['data']['posts'])) {
                $this->Posts = array_merge($this->Posts, $response['data']['posts']);
                return $response['data']['posts'];
            }
        }

        return false;
    }

    /*
     * get the token to make requests for posts later
     * save the token to private variable
     * this is a POST request
     */

    public function getAuthToken() {

        $authUrl = $this->auth_url;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $authUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'client_id' => $this->client_id,
                'email' => $this->client_email,
                'name' => $this->client_name
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if (false !== $response) {
            $response = json_decode($response, 1);

            if (!empty($response['data']['sl_token'])) {
                $this->auth_token = (string) $response['data']['sl_token'];
                return $this->auth_token;
            }
        }

        return false;
    }

}
