<?php

require 'vendor/autoload.php';


function sanitize($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function jsonResponse($code = 1, $status = 'success', $response = null, $info = null)
{
    return json_encode(array(
            'code' => (int) $code,
            'status' => $status,
            'info' => $info,
            'response' => $response
        )
    );
}

function determineFileFormat($file)
{
    return substr($file, -4);
}


function extractFilename($file)
{
    $f = determineFileFormat($file);

    switch($f)
    {
        case 'jpeg':
            return substr($file, 0, -5);

            break;

        case '.jpg':
        case '.png':
            return substr($file, 0, -4);

            break;
    }

}

function defaultWidth($size)
{
    switch($size)
    {
        case 'sm':
            return 210;

            break;

        case 'md':
            return 310;

            break;

        case 'lg':
            return 400;

            break;
    }
}


function defaultHeight($size)
{
    switch($size)
    {
        case 'sm':
            return 210;

            break;

        case 'md':
            return 310;

            break;

        case 'lg':
            return 400;

            break;
    }
}

