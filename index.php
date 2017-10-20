<?php
require "php/config.php";

$router = new minirouter();
$router->a('/getVideo', function(){
    header ('Content-type: application/json; charset=utf-8');

    $cacheKey = "getVideo";
    getCache($cacheKey);

    $book = R::load("video", $_GET["id"]);

    echo json_encode($book);

    setCache($cacheKey, $book);
});


$router->a('/getSnippets', function(){
    header ('Content-type: application/json; charset=utf-8');

    $cacheKey = "getSnippets";
    getCache($cacheKey);

    $books = R::findAll( 'snippet' , ' ORDER BY id ASC' );

    echo json_encode($books);

    setCache($cacheKey, $books, true);
});

$router->a('/getPlaylists', function(){
    header ('Content-type: application/json; charset=utf-8');

    $cacheKey = "getPlaylists";
    getCache($cacheKey);

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

    setCache($cacheKey, $books, true);
});


$router->a('/getSnippet', function(){
    header ('Content-type: application/json; charset=utf-8');

    $cacheKey = "getSnippet".$_GET["id"];
    getCache($cacheKey);

    $book = R::load("snippet", $_GET["id"]);
    $book->start_time = (int)$book->start_time;
    $book->end_time = (int)$book->end_time;

    echo json_encode($book);

    setCache($cacheKey, $book);
});

$router->a('/getSnippetsLast', function(){
    header ('Content-type: application/json; charset=utf-8');

    $cacheKey = "getSnippetsLast";
    getCache($cacheKey, 10);

    $books = R::findAll( 'snippet' , ' ORDER BY id DESC limit 6' );

    echo json_encode($books);

    setCache($cacheKey, $books, true);
});

$router->a('/getPlaylist', function(){
    header ('Content-type: application/json; charset=utf-8');

    $cacheKey = "getPlaylist".$_GET["id"];
    getCache($cacheKey);

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

    setCache($cacheKey, $book);
});


$router->a('/getAll', function(){
    header ('Content-type: application/json; charset=utf-8');

    $cacheKey = "getAll";
    getCache($cacheKey);

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

    echo json_encode($videos);

    setCache($cacheKey, $videos, true);

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
    http://snippet.soocenje.24ur.com/getAll <br>
    
    http://snippet.soocenje.24ur.com/getVideo?id=1 <br>
    <br>
    http://snippet.soocenje.24ur.com/getSnippets <br>
    http://snippet.soocenje.24ur.com/getSnippetsLast , limit 6<br>
    http://snippet.soocenje.24ur.com/getSnippet?id=1 <br>
    <br>
    http://snippet.soocenje.24ur.com/getPlaylists <br>
    http://snippet.soocenje.24ur.com/getPlaylist?id=1 <br>
    
    <br><br>
    <h3>setter:</h3>
    POST na     http://snippet.soocenje.24ur.com/setSnippet
    <pre>
    var data = {
        video_id: 1,
        name: "snippet title",
        start_time: 1321654987654,
        end_time: 1321654987654,
        extras: "fdg sdfg dfg dfg df",
        published: 1,
        looping: 1,
    }
</pre>
    <br>
    POST na     http://snippet.soocenje.24ur.com/setPlaylist
    <pre>
    var data = {
        name: "my playstlie",
        snippets: "16,18,20",
        image_url: "http://myimage.gif",
        published: 1
    }
    </pre>
    
    ';
});

$router->e();

R::close();