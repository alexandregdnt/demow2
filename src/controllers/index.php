<?php
require_once ('../classes/User.php');
require_once ('../classes/Post.php');

if (!isset($_SESSION['user']) || unserialize($_SESSION['user'])->getId() == null) {
    redirect('/auth');
}
$user = unserialize($_SESSION['user']);

echo '<pre>';
var_dump($user);
echo '</pre>';

view('index');
