<?php 
namespace Classes;

abstract class AbstractController
{
    protected function getFlash(): void
    {
        if(isset($_SESSION["flash"]))
        {
            echo "<div class='flash'>{$_SESSION['flash']}</div>";
            unset($_SESSION["flash"]);
        }
    }

    protected function setFlash(string $flash):void
    {
        $_SESSION["flash"] = $flash;
    }
    /**
     * Affiche la vue demandé en paramètre.
     *
     * @param string $view
     * @param array $options
     * @return void
     */
    protected function render(string $view, array $options = []): void
    {
        /* 
            ["title"=>"Ma super page"]
            Cela va créer une variable $title avec pour valeur "Ma super page".
        */
        foreach($options as $op=>$val)
        {
            $$op = $val;
        }
        require __DIR__."/../../ressources/template/_header.php";
        require __DIR__."/../view/$view";
        require __DIR__."/../../ressources/template/_footer.php";
    }
}