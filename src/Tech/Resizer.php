<?php

namespace Tech;
use Intervention\Image\ImageManager;

class Resizer {

    public function batchResize($images, $settings)
    {
        $files =  [];
        $width = [];
        $height = [];

        for($i = 0; $i < count($images['images']['name']); $i++)
        {
            if ($this->isFormatAccepted($images['images']['name'][$i]) == true)
            {
                $files[$i]['name'] = $images['images']['name'][$i];
                $files[$i]['tmp_name'] = $images['images']['tmp_name'][$i];
                $files[$i]['type'] = $images['images']['type'][$i];
                $files[$i]['size'] = $images['images']['size'][$i];
                $files[$i]['error'] = $images['images']['error'][$i];
            }
        }

        foreach($settings['imageSizes'] as $key => $size)
        {
            if (isset($size))
            {
                $height[$size] = (empty($settings['height'][$size]))
                    ? defaultHeight($size) : (int) $settings['height'][$size];

                $width[$size] = (empty($settings['width'][$size]))
                    ? defaultWidth($size) : (int) $settings['width'][$size];
            }
        }

        $watermark =  intval($settings['watermark']);

        $quality = (empty($settings['quality'])) ? 80 : $settings['quality'];

        $theImages = $this->resizeAllImages($files, $width, $height, $watermark, $quality);

        $response = (new Zipper())->zip($theImages);

        return $response;

    }

    private function resizeImage($folder, $file, $width, $height, $watermark, $quality)
    {
        $watermarkPath = "assets/img/watermark_";

        if (!file_exists($folder)) mkdir($folder, 0777, true);

        $filename = extractFilename($file['name']);

        $image = new ImageManager(array('driver' => 'gd'));

        $images = [];

        foreach($width as $key => $w)
        {
            $imagePath = $folder .  '/' . $filename . '_' . $key . '.jpg';

            $img =  $image->make($file['tmp_name'])->fit($width[$key],$height[$key]);

            $img =  ($watermark != 1) ? $img : $img->insert($watermarkPath . $key . '.png', 'bottom-middle', 3, 3);

            $img->save($imagePath, $quality);

            $images[] = $imagePath;
        }

        return $images;
    }

    private function resizeAllImages($files, $width, $height, $watermark, $quality)
    {
        set_time_limit(6000);

        $filename = time() . '-Batch-Resizer';

        $folder = 'resized-images/' . $filename;

        $images = [];

        foreach($files as $key => $image)
        {
            $response = $this->resizeImage($folder, $files[$key], $width, $height, $watermark, $quality);

            for($i = 0; $i < count($response); $i++)
            {
                $images[] = $response[$i];
            }
        }

        return ['images' => $images, 'filename' => $filename];
    }

    private function isFormatAccepted($file)
    {
        return ((substr($file,-3) == 'png') || (substr($file,-3) == 'jpg') || (substr($file,-4) == 'jpeg')) ? true: false;
    }
}