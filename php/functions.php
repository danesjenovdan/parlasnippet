<?php

function debug($d){
    $k = (time() + microtime() - STARTTIME);
    $txt = sprintf("time: %.4f\n", $k);
    var_dump($d . ' - '.sprintf("%.4f", STARTTIME).' - ' .$txt);
}

function getShortenedURLFromID ($integer, $base = ALLOWED_CHARS)
{
    $out = '';
    $length = strlen($base);
    while($integer > $length - 1)
    {
        $out = $base[fmod($integer, $length)] . $out;
        $integer = floor( $integer / $length );
    }
    return $base[$integer] . $out;
}

function getIDFromShortenedURL ($string, $base = ALLOWED_CHARS)
{
    $length = strlen($base);
    $size = strlen($string) - 1;
    $string = str_split($string);
    $out = strpos($base, array_pop($string));
    foreach($string as $i => $char)
    {
        $out += strpos($base, $char) * pow($length, $size - $i);
    }
    return $out;
}

function util_array_trim(array &$array, $filter = false)
{
    array_walk_recursive($array, function (&$value) use ($filter) {
        $value = trim($value);
        if ($filter) {
            $value = filter_var($value, FILTER_SANITIZE_STRING);
        }
    });

    return $array;
}

function handleUrlPath(){
    $ruri = $_SERVER["REQUEST_URI"];
    if(strpos($ruri, "?")!==false) {
        $ruri = substr($ruri, 0, strpos($ruri, "?"));
    }else{
        if(strpos($ruri, "&")!==false) {
            $ruri = substr($ruri, 0, strpos($ruri, "&"));
        }
    }
    return $ruri;
}

class minirouter
{
    private $router = [];
    function a($router, callable $c){
        $this->router[$router] = $c;
    }

    function e() {
        $p = handleUrlPath();
        $k = isset($this->router[$p]) ? $this->router[$p] : $this->router[''];
        $k();
    }
}


function saveSnippet($data){
    $data = util_array_trim($data, true);


    $section = "snippet";
    $book = R::dispense($section);
    $book->video_id = $data["video_id"];
    $book->start_time = (int)$data["start_time"];
    $book->end_time = (int)$data["end_time"];
    $book->extras = $data["extras"];
    $book->short_url = "";
    $book->published = $data["published"];
    $book->looping = $data["looping"];
    R::store($book);
    //$snippet_id = R::getInsertID();
    $snippet_id = $book->id;

    $section = "shortenedurls";
    $book = R::dispense($section);
    $book->long_url = "";
    $book->snippet_id = $snippet_id;
    $book->created = date("Y-m-d H:i:s");
    R::store($book);

    //$inserted_id = R::getInsertID();
    $inserted_id = $book->id;
    $shortened_url = getShortenedURLFromID($inserted_id);

    $book = R::load("snippet", $snippet_id);
    $book->short_url = $shortened_url;
    R::store($book);

    $book->start_time = (int)$book->start_time;
    $book->end_time = (int)$book->end_time;

    return $book;
}


function setHeaders(){
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        $if_modified_since = preg_replace('/;.*$/', '',   $_SERVER['HTTP_IF_MODIFIED_SINCE']);
    } else {
        $if_modified_since = '';
    }

    $mtime = filemtime($_SERVER['SCRIPT_FILENAME']);
    $gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';


    if ($if_modified_since == $gmdate_mod) {
        header("HTTP/1.0 304 Not Modified");
        exit;
    }
    session_start();
    header("Last-Modified: $gmdate_mod");

    header('Access-Control-Max-Age: 604800');
    header('Cache-Control: public');
    header("Cache-Control: max-age=604800");
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (604800)) . ' GMT');
}

/******************/
/*
$_POST = array(
    'product_id'    => 'libgd<script>',
    'component'     => '10',
    'versions'      => '2.0.33',
    'testscalar'    => array('2', '23', '10', '12'),
    'testarray'     => '2',
);
*/
/*
$args = array(
    'product_id'   => FILTER_SANITIZE_ENCODED,
    'component'    => array('filter'    => FILTER_VALIDATE_INT,
        'flags'     => FILTER_REQUIRE_ARRAY,
        'options'   => array('min_range' => 1, 'max_range' => 10)
    ),
    'versions'     => FILTER_SANITIZE_ENCODED,
    'doesnotexist' => FILTER_VALIDATE_INT,
    'testscalar'   => array(
        'filter' => FILTER_VALIDATE_INT,
        'flags'  => FILTER_REQUIRE_SCALAR,
    ),
    'testarray'    => array(
        'filter' => FILTER_VALIDATE_INT,
        'flags'  => FILTER_REQUIRE_ARRAY,
    )

);

$myinputs = filter_input_array(INPUT_POST, $args);
*/