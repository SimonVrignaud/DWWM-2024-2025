<?php 

use Model\UserModel;
use Classes\AbstractController;
use Classes\Interface\CrudInterface;

require __DIR__."/../../ressources/services/_shouldBeLogged.php";
require __DIR__."/../../ressources/services/_csrf.php";

class UserController extends AbstractController implements CrudInterface
{
    use Classes\Trait\Debug;

    private UserModel $db;

    function __construct()
    {
        $this->db = new UserModel();
        // $this->dump($this->db);
    }
    function create()
    {
        echo "create";
    }
    /**
     * Gère la page d'affichage des utilisateurs
     *
     * @return void
     */
    function read()
    {
        $users = $this->db->getAllUsers();

        $this->render("user/list.php", [
            "users" => $users,
            "title"=>"POO - Liste Utilisateur"
        ]);
    }
    function update(){}
    function delete(){}
}