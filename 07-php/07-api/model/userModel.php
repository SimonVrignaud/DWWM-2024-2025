<?php 
require __DIR__."/../../ressources/services/_pdo.php";

/**
 * Récupère tous les utilisateurs.
 *
 * @return array
 */
function getAllUsers(): array
{
    $pdo = connexionPDO();
    $sql = $pdo->query("SELECT idUser, username FROM users");
    return $sql->fetchAll();
}
/**
 * récupère un utilisateur via son email
 *
 * @param string $email email de l'utilisateur enregistré en BDD.
 * @return array|false
 */
function getOneUserByEmail(string $email): array|false
{
    $pdo = connexionPDO();
    $sql = $pdo->prepare("SELECT * FROM users WHERE email = :em");
    $sql->execute(["em"=>$email]);
    return $sql->fetch();
}
/**
 * Récupère un utilisateur via son id.
 *
 * @param string|integer $id
 * @return array|false
 */
function getOneUserById(string|int $id): array|false
{
    $pdo = connexionPDO();
    $sql = $pdo->prepare("SELECT * FROM users WHERE idUser = :id");
    $sql->execute(["id"=>$id]);
    return $sql->fetch();
}
/**
 * Ajoute un nouvel utilisateur
 *
 * @param string $us
 * @param string $em
 * @param string $pass
 * @return void
 */
function addUser(string $us, string $em, string $pass): void
{
    $pdo = connexionPDO();
    $sql = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:us, :em, :pass)");
    $sql->execute(["us"=>$us, "em"=>$em, "pass"=>$pass]);
}
/**
 * Supprime un utilisateur via son id.
 *
 * @param string|integer $id
 * @return void
 */
function deleteUserById(string|int $id): void
{
    $pdo = connexionPDO();
    $sql = $pdo->prepare("DELETE FROM users WHERE idUser = :id");
    $sql->execute(["id"=>$id]);
}
/**
 * Met à jour un utilisateur via son id.
 *
 * @param string $username
 * @param string $email
 * @param string $password
 * @param string|integer $id
 * @return void
 */
function updateUserById(string $username, string $email, string $password, string|int $id):void
{
    $pdo = connexionPDO();
    $sql = $pdo->prepare("UPDATE users SET username = :us, email = :em, password = :mdp WHERE idUser = :id");
    $sql->execute(["us"=>$username, "em"=>$email, "mdp"=>$password, "id"=>$id]);
}