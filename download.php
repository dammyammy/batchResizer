<?php

require 'vendor/autoload.php';

use Tech\Zipper;
use Tech\FileNotFoundException;

try
{
    $filename = isset($_GET['file']) ? $_GET['file'] : null;

    echo (new Zipper())->downloadFile($filename);
}
catch(FileNotFoundException $e)
{
    header("Location: index.php?cantdownload");
}