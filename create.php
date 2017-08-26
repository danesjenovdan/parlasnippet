<?php
require "php/config.php";

/********/
$section = "video";
$book = R::dispense($section);
$book->videoid = "R3uQ5SwS3yU";
$book->published = true;
$book->subtitles_url = "https://www.youtube.com/watch?v=R3uQ5SwS3yU";
R::store($book);
var_dump($book);

/********/
$section = "snippet";
$book = R::dispense($section);
$book->video_id = 1;
$book->start_time = (int)substr(date("YmdHisu"), 0, -3);;
$book->end_time = (int)substr(date("YmdHisu"), 0, -3);;
$book->extras = "lorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsumlorem ipsum lorem ipsum";
$book->short_url = "shortan";
$book->published = true;
$book->looping = true;
R::store($book);
var_dump($book);

/********/
$section = "shortenedurls";
$book = R::dispense($section);
$book->long_url = "https://www.youtube.com/watch?v=R3uQ5SwS3yU";
$book->created = date("Y-m-d H:i:s");;
R::store($book);
var_dump($book);

$inserted_id = R::getInsertID();
$shortened_url = getShortenedURLFromID($inserted_id);
var_dump($inserted_id);
var_dump($shortened_url);

/********/
$section = "playlist";
$book = R::dispense($section);
$book->name = "playlist";
$book->snippet_order = "snippet_id_1|snippet_id_2|snippet_id_3";
$book->published = true;
R::store($book);
var_dump($book);


R::close();