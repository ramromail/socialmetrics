<?php

require_once('configs.php');

class SomeClass {

    private $Posts = array();

    public function avgCharLengthOfPostPerMonth() {
        $postsPerMonth = array();
        $avgCharLenPerMonth = array();

        foreach ($this->Posts as $post) {

            $message = (string) $post['message'];
            $created = (string) $post['created_time'];

            $month = substr($created, 0, 7);

            $postsPerMonth[$month][] = $message;
        }

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

    public function longestPostByCharLenPerMonth() {
        $postsPerMonth = array();
        $postsLenPerMonth = array();
        $longestPostByMonth = array();

        foreach ($this->Posts as $post) {

            $message = (string) $post['message'];
            $created = (string) $post['created_time'];

            $month = substr($created, 0, 7);

            $postsPerMonth[$month][] = $message;
        }

        ksort($postsPerMonth);

        foreach ($postsPerMonth as $month => $posts) {
            $characterCount = 0;

            foreach ($posts as $post) {
                $length = strlen($post);
                $characterCount += $length;
                $postsLenPerMonth[$month][$length][] = $post;
            }

            krsort($postsLenPerMonth[$month]);
        }

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

    public function totalPostsSplitByWeek() {
        $postsByWeek = array();
        $totalPostsByWeek = array();

        foreach ($this->Posts as $post) {

            $message = (string) $post['message'];
            $created = (string) $post['created_time'];

            $week = date('W', strtotime($created));

            $postsByWeek[$week][] = $message;
        }


        foreach ($postsByWeek as $week => $posts) {
            $totalPostsByWeek[$week] = count($posts);
        }

        ksort($totalPostsByWeek);

        return $totalPostsByWeek;
    }

    public function avgNumOfPostsPerUserPerMonth() {
        $postsByUserByMonth = array();
        $avgPostsByUserByMonth = array();

        foreach ($this->Posts as $post) {
            $userId = (string) $post['from_id'];
            $name = (string) $post['from_name'];
            $message = (string) $post['message'];
            $created = (string) $post['created_time'];

            $month = substr($created, 0, 7);

            $postsByUserByMonth[$userId . '__' . $name][$month][] = $message;
        }

        ksort($postsByUserByMonth);
        foreach ($postsByUserByMonth as $uid => $months) {
            foreach ($months as $month => $posts) {
                $avgPostsByUserByMonth[$uid][$month] = count($posts);
            }
        }

        return $avgPostsByUserByMonth;
    }

    public function getPostsArray() {
        return $this->Posts;
    }

    public function getSocialPosts($sl_token, $page) {
        $postUrl = "https://api.supermetrics.com/assignment/posts?sl_token=" . $sl_token . "&page=" . $page;

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

    public function getAuthToken($client_id, $email, $name) {

        $authUrl = 'https://api.supermetrics.com/assignment/register';

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
                'client_id' => $client_id,
                'email' => $email,
                'name' => $name
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if (false !== $response) {
            $response = json_decode($response, 1);

            if (!empty($response['data']['sl_token'])) {
                return $response['data']['sl_token'];
            }
        }

        return false;
    }

}
