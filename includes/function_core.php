<?php

function redirect($url)
{
    header('location:http://localhost/shop' . $url);
    exit;


}

function url($path){
    return HOME_URL . $path;
}

function image_url($path){
    return url($path);
}


?>