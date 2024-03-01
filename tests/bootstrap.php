<?php
// This array has a single file but could whole the contents of an entire directory.
$files = [
    dirname(__DIR__).'/GETCoursesAPI_Test.php',
    dirname(__DIR__).'/POSTPrereqsAPI_Test.php',
    dirname(__DIR__).'/GETPrereqsAPI_Test.php',
    dirname(__DIR__).'/PUTCoursesAPI_Test.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        require_once $file;
    }
}