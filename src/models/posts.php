<?php

function getPosts() {
    $db = bdd_connection();
    $request = $db->prepare("
        SELECT * 
        FROM posts
        ORDER BY posts.user_id ASC;");

    $request->execute();

    return $request->fetchAll();
}
