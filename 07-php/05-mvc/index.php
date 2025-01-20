<?php 
require "./routes.php";

$url = filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL);

$url = explode("?",$url)[0];

$url = trim($url, "/");
// var_dump($url);

if(array_key_exists($url, ROUTES))
{
    require "pages/".ROUTES[$url];
}
else
{
    require "pages/404.php";
}