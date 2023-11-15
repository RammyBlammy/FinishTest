<?php

namespace TestTask;

use TestTask\Helpers\RequestHelper;
use TestTask\Helpers\DB;

require_once("autoload.php");
$to_find = $_POST['query'];
$joins = [
    "JOIN" => [
        "testov.posts" => [
            "comments.post_id" => "posts.id"
        ]
    ]
];
$fields = ["posts.id", "posts.title", "comments.id as \"id_comment\"", "comments.name", "comments.email", "comments.body"];
$where = [["comments.body", "LIKE", "'%$to_find%'"]];
$result = DB::select("testov.comments", $fields, $joins, $where);
echo json_encode($result);

