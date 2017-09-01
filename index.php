<?php
require "php/config.php";

$router = new minirouter();
$router->a('/getVideo', function(){
    header ('Content-type: application/json; charset=utf-8');
    $book = R::load("video", $_GET["id"]);

    echo json_encode($book);
});


$router->a('/getSnippets', function(){
    header ('Content-type: application/json; charset=utf-8');

    $books = R::findAll( 'snippet' , ' ORDER BY id ASC' );

    echo json_encode($books);
});

$router->a('/getPlaylists', function(){
    header ('Content-type: application/json; charset=utf-8');

    $books = R::findAll( 'playlist' , ' ORDER BY id ASC' );

    foreach ($books as $book) {

        $snippets = explode(",", $book->snippets);
        $snippetsList = array();
        foreach ($snippets as $key => $snippet) {
            $s = R::load("snippet", $snippet);
            $s->order = $key;
            $snippetsList[] = $s;
        }

        $book->snippets = $snippetsList;
    }

    echo json_encode($books);
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

    $snippets = explode(",", $book->snippets);
    $snippetsList = array();
    foreach ($snippets as $key => $snippet) {
        $s = R::load("snippet", $snippet);
        $s->order = $key;
        $snippetsList[] = $s;
    }

    $book->snippets = $snippetsList;

    echo json_encode($book);
});


$router->a('/getAll', function(){
    header ('Content-type: application/json; charset=utf-8');

    $videos = R::findAll( 'video' , ' ORDER BY id ASC' );

    foreach ($videos as $video) {


        $playlists = R::findAll('playlist', ' video_id = ? ORDER BY id ASC', array($video->id));

        $playlistList = array();
        foreach ($playlists as $playlist) {

            $snippets = explode(",", $playlist->snippets);
            $snippetsList = array();
            foreach ($snippets as $key => $snippet) {
                $s = R::load("snippet", $snippet);
                $s->order = $key;
                $snippetsList[] = $s;
            }

            $playlist->snippets = $snippetsList;
            $playlistList[] = $playlist;

        }
        $video->playlists = $playlistList;
    }

    echo json_encode($video);
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

$router->a('/setPlaylist', function(){
    header ('Content-type: application/json; charset=utf-8');
    if(!isset($_POST)){
        return null;
    }
    if(!isset($_POST["name"])){
        return null;
    }
    if(!isset($_POST["snippets"])){
        return null;
    }

//    if($_SERVER['REMOTE_ADDR'] != LIMIT_TO_IP)
//    {
//        die('You are not allowed to shorten URLs with this service.');
//    }

    $book = savePlaylist($_POST);

    echo json_encode($book);
});

$router->a('/about', function(){
    header ('Content-type: text/html; charset=utf-8');

    echo 'About';
});

$router->a('', function(){
    header ('Content-type: text/html; charset=utf-8');

    echo '
    <h3>getter:</h3>
    http://snippet.knedl.si/getAll <br>
    
    http://snippet.knedl.si/getVideo?id=1 <br>
    <br>
    http://snippet.knedl.si/getSnippets <br>
    http://snippet.knedl.si/getSnippet?id=1 <br>
    <br>
    http://snippet.knedl.si/getPlaylists <br>
    http://snippet.knedl.si/getPlaylist?id=1 <br>
    
    <br><br>
    <h3>setter:</h3>
    POST na     http://snippet.knedl.si/setSnippet
    <pre>
    var data = {
        video_id: 1,
        start_time: 1321654987654,
        end_time: 1321654987654,
        extras: "fdg sdfg dfg dfg df",
        published: 1,
        looping: 1,
    }
</pre>
    <br>
    POST na     http://snippet.knedl.si/setPlaylist
    <pre>
    var data = {
        name: "my playstlie",
        snippets: "16,18,20",
        published: 1
    }
    </pre>
    
    ';
});

$router->e();

R::close();