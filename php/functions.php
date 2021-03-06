<?php

function debug($d)
{
    $k = (time() + microtime() - STARTTIME);
    $txt = sprintf("time: %.4f\n", $k);
    var_dump($d . ' - ' . sprintf("%.4f", STARTTIME) . ' - ' . $txt);
}

function getShortenedURLFromID($integer, $base = ALLOWED_CHARS)
{
    $out = '';
    $length = strlen($base);
    while ($integer > $length - 1) {
        $out = $base[(int)fmod($integer, $length)] . $out;
        $integer = floor($integer / $length);
    }
    return $base[(int)$integer] . $out;
}

function getIDFromShortenedURL($string, $base = ALLOWED_CHARS)
{
    $length = strlen($base);
    $size = strlen($string) - 1;
    $string = str_split($string);
    $out = strpos($base, array_pop($string));
    foreach ($string as $i => $char) {
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

function handleUrlPath()
{
    $ruri = $_SERVER["REQUEST_URI"];
    if (strpos($ruri, "?") !== false) {
        $ruri = substr($ruri, 0, strpos($ruri, "?"));
    } else {
        if (strpos($ruri, "&") !== false) {
            $ruri = substr($ruri, 0, strpos($ruri, "&"));
        }
    }
    return $ruri;
}

class minirouter
{
    private $router = [];

    function a($router, callable $c)
    {
        $this->router[$router] = $c;
    }

    function e()
    {
        $p = handleUrlPath();
        $k = isset($this->router[$p]) ? $this->router[$p] : $this->router[''];
        $k();
    }
}


function saveSnippet($data)
{
    $data = util_array_trim($data, true);


    $section = "snippet";
    $book = R::dispense($section);
    $book->video_id = $data["video_id"];
    $book->name = $data["name"];
    $book->start_time = (int)$data["start_time"];
    $book->end_time = (int)$data["end_time"];
    $book->extras = $data["extras"];
    $book->short_url = "";
    $book->published = $data["published"];
    $book->looping = $data["looping"];

    if(!empty($data["muted"])){
        $book->muted = $data["muted"];
    }else{
        $book->muted = 0;
    }

    $book->ts = date("Y-m-d H:i:s");
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


function savePlaylist($data)
{
    $data = util_array_trim($data, true);

    $section = "playlist";
    $book = R::dispense($section);
    $book->name = $data["name"];
    $book->snippets = $data["snippets"];
    $book->published = true;
    $book->video_id = ($data["video_id"] > 0) ? $data["video_id"] : 1;
    $book->image_url = $data["image_url"];
    R::store($book);

    return $book;
}


function setHeaders()
{
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        $if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
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


function getCache($cacheKey, $lifeTime = 0)
{
    if (!CACHE) {
        return false;
    }

    $result = cache_get($cacheKey, $lifeTime);
    if (!empty($result)) {
        echo($result);
        die();
    }
}

function getCache_($cacheKey)
{
    if (!CACHE) {
        return false;
    }

    $c = new SimpleCache();
    $result = $c->get_cache($cacheKey);

    if (!empty($result)) {
        echo json_encode($result);
        die();
    }
}

function setCache_($cacheKey, $data)
{
    if (!CACHE) {
        return false;
    }

    $c = new SimpleCache();
    $c->set_cache($cacheKey, $data);
}

function setCache($cacheKey, $data, $toArray = false)
{
    if (!CACHE) {
        return false;
    }

    if ($toArray) {
        $e = null;
        foreach ($data as $item) {
            $e[] = $item->export();
        }
        cache_set($cacheKey, json_encode($e));
        die();
    }

    cache_set($cacheKey, json_encode($data->export()));

}

function cache_set($key, $val)
{
    $val = var_export($val, true);

    // HHVM fails at __set_state, so just use object cast for now
    $val = str_replace('stdClass::__set_state', '(object)', $val);
    $val = str_replace('RedBeanPHP\OODBBean::__set_state', '(object)', $val);
    $val = str_replace('RedBeanPHP\BeanHelper\SimpleFacadeBeanHelper::__set_state', '(object)', $val);

    // Write to temp file first to ensure atomicity
    $tmp = CACHE_DIR . "$key." . uniqid('', true) . '.tmp';
    file_put_contents($tmp, '<?php $val = ' . $val . ';', LOCK_EX);
    rename($tmp, CACHE_DIR . "$key");
}

function cache_get($key = "getAll", $lifeTime = 0)
{
    $cacheLifeTime = CACHELIFETIME;
    if($lifeTime>0){
        $cacheLifeTime = $lifeTime;
    }

    if (file_exists(CACHE_DIR . $key) && (filemtime(CACHE_DIR . $key) + $cacheLifeTime >= time())) {
        @include CACHE_DIR . $key;
        return isset($val) ? $val : false;
    } else {
        return false;
    }
}