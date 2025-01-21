<?php 
require "./routes.php";

$url = filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL);

$url = explode("?",$url)[0];

$url = trim($url, "/");
// var_dump($url);

if(array_key_exists($url, ROUTES))
{
    require "./controller/".ROUTES[$url]["controller"];
    ROUTES[$url]["fonction"]();
    // readUsers()
}
else
{
    require "view/404.php";
}