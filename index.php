<?php
require "php/config.php";

$router = new minirouter();
$router->a('/getVideo', function(){
    header ('Content-type: application/json; charset=utf-8');
    $book = R::load("video", $_GET["id"]);

    echo json_encode($book);
});

$router->a('/getSnippet', function(){
    header ('Content-type: application/json; charset=utf-8');
    $book = R::load("snippet", $_GET["id"]);
    $book->start_time = (int)$book->start_time;
    $book->end_time = (int)$book->end_time;

    echo json_encode($book);
});

$router->a('/getPlaylist', function(){
    header ('Content-type: application/json; charset=utf-8');
    $book = R::load("playlist", $_GET["id"]);

    echo json_encode($book);
});

$router->a('/setSnippet', function(){
    header ('Content-type: application/json; charset=utf-8');
    if(!isset($_POST)){
        return null;
    }
    if(!isset($_POST["start_time"])){
        return null;
    }
    if(!isset($_POST["end_time"])){
        return null;
    }
    if(!isset($_POST["video_id"])){
        return null;
    }
    if(!isset($_POST["published"])){
        return null;
    }

//    if($_SERVER['REMOTE_ADDR'] != LIMIT_TO_IP)
//    {
//        die('You are not allowed to shorten URLs with this service.');
//    }

    $book = saveSnippet($_POST);

    echo json_encode($book);
});

$router->a('/about', function(){
    header ('Content-type: text/html; charset=utf-8');

    echo 'About';
});

$router->a('', function(){
    header ('Content-type: text/html; charset=utf-8');

    echo '';
});

$router->e();

R::close();