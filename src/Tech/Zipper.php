<?php

namespace Tech;
use ZipArchive;

class Zipper
{
    public function zip($files, $fileTypeKey = "images")
    {
        $zip = new ZipArchive();

        $filename = $files['filename'] . '.zip';

        $path = 'zip/';

        if(file_exists($path . $filename))
        {
            if($zip->open($path . $filename, ZIPARCHIVE::CHECKCONS) !== TRUE)
            {
                $errMsg = "Unable to Open $filename";

                return jsonResponse(403, 'error', $errMsg);
            }
            elseif(($zip->open($path . $filename, ZIPARCHIVE::CM_PKWARE_IMPLODE) !== TRUE) ||
                ($zip->open($path .$filename, ZipArchive::CREATE)!==TRUE))
            {
                $errMsg = "Could not Create $filename";

                return jsonResponse(403, 'error', $errMsg);
            }
        }
        else
        {
            if($zip->open($path .$filename, ZipArchive::CREATE)===TRUE)
            {
                foreach($files[$fileTypeKey] as $key => $file)
                {
                    if(!$zip->addFile($file))
                    {
                        $errMsg = "error archiving $file in $filename";

                        return jsonResponse(403, 'error', $errMsg);
                    }

                    $zip->addFile($file);
                }

                $result['numOfFiles'] = $zip->numFiles;
                $result['status'] = $zip->status;
                $result['zipFileName'] = $filename;

                $zip->close();

                return jsonResponse(200,'success', 'File Successfully Resized!!', $result);
            }
        }
    }

    public function downloadFile($filename)
    {
        $path = dirname(__DIR__) . '/../zip/' . $filename;

        if(! file_exists($path))
        {
            throw new FileNotFoundException;
            return false;
        }

        header("Content-Type: application/zip");
        header("Content-Length: ". filesize($path));
        header("Content-Disposition: attachment; filename=".$filename);

        readfile ($path);
    }
}