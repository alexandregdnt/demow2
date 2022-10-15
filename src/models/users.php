<?php

function getUsers() {
    $db = bdd_connection();
    $request = $db->prepare("
        SELECT * 
        FROM users
        ORDER BY users.user_id ASC;");

    $request->execute();

    return $request->fetchAll();
}
