<?php

use App\Models\Language;
use Illuminate\Support\Facades\Config;

function get_languages(){

    return Language::active()->Selection()->get();
}

function get_default_lang(){

     return   Config::get('app.locale');
}

function uploadImage($folder, $image)
{
    $image->store($folder);
    $filename = $image->hashName();
    $path = 'assets/images/' . $folder . '/' . $filename;
    return $path;
}

function SaveImage($folder, $photo)
{
    $file_extension = $photo->getClientOriginalExtension();

    $file_name = $photo->hashName() . '.' . $file_extension;
    $path = 'assets/images/' . $folder;
    $photo->move($path, $file_name);
    return $path . "/" . $file_name;
}

