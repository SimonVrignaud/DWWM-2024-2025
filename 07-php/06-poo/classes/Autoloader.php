<?php 

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function($class){
            // var_dump($class);
            // echo "<br>";
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).".php";
            // var_dump($file);
            // echo "<br>";
            if(file_exists($file)){
                require_once $file;
                return true;
            }
            return false;
        });
    }
}