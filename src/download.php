<?php

namespace TestTask;

use TestTask\Helpers\RequestHelper;
use TestTask\Helpers\DB;

require_once("autoload.php");
$RequestHelper = new RequestHelper();

$resp = $RequestHelper->request("https://jsonplaceholder.typicode.com/posts");
$resp = json_decode($resp, 1);
foreach ($resp as $key => $value) {
    $resp[$key]['user_id'] = $value['userId'];
    unset($resp[$key]['userId']);
}
$group_posts = array_chunk($resp, 20);
foreach ($group_posts as $group)
    DB::insert("testov.posts", $group);
print("Загружено " . count($resp) . " записей. ");

$resp = $RequestHelper->request("https://jsonplaceholder.typicode.com/comments");
$resp = json_decode($resp, 1);
foreach ($resp as $key => $value) {
    $resp[$key]['post_id'] = $value['postId'];
    unset($resp[$key]['postId']);
}
$group_posts = array_chunk($resp, 20);
foreach ($group_posts as $group)
    DB::insert("testov.comments", $group);
print("Загружено " . count($resp) . " комментариев");
