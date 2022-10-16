<?php
require_once ('../classes/User.php');
require_once ('../classes/Post.php');

if (!isset($_SESSION['user']) || unserialize($_SESSION['user'])->getId() == null) {
    redirect('/auth');
}

view('index', ['posts' => Post::getAll()]);
