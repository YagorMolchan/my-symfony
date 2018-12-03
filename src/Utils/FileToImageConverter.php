<?php
/**
 * Created by PhpStorm.
 * User: Egor
 * Date: 30.11.2018
 * Time: 16:34
 */

namespace App\Utils;


class FileToImageConverter
{
    public static function getImageFromFile()
    {
        $image = addslashes($_FILES['image']['tmp_name']);
        $name = addslashes($_FILES['image']['name']);
        $image = file_get_contents($image);
        $image = base64_encode($image);
        return $image;
    }

}