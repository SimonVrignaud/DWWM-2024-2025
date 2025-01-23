<?php 
require_once __DIR__."/../../ressources/services/_pdo.php";
/**
 * Retourne tous les messages d'un utilisateur.
 *
 * @param integer $idUser
 * @return array|false
 */
function getMessagesByUser(int $idUser): array|false
{
    $pdo = connexionPDO();
    $sql = $pdo->prepare("SELECT * FROM messages m  WHERE m.idUser = ? ORDER BY m.createdAt DESC");
    $sql->execute([$idUser]);
    return $sql->fetchAll();
}
/**
 * Retourne un message via son id.
 *
 * @param integer $id
 * @return array|false
 */
function getMessageById(int $id): array|false
{
    $pdo = connexionPDO();
    $sql = $pdo->prepare("SELECT * FROM messages WHERE idMessage = ?");
    $sql->execute([$id]);
    return $sql->fetch();
}
/**
 * Créer un nouveau message en BDD.
 *
 * @param array $values
 *  $values = ["m"=>(string) message, "id"=>(int) idUser]
 * @return void
 */
function addMessage(array $values): void
{
    $pdo = connexionPDO();
    $sql = $pdo->prepare("INSERT INTO messages(message, idUser) VALUES (:m, :id)");
    $sql->execute($values);
}
/**
 * Supprime un message via son ID
 *
 * @param integer $id
 * @return void
 */
function deleteMessageById(int $id): void
{
    $pdo = connexionPDO();
    $sql = $pdo->prepare("DELETE FROM messages WHERE idMessage=?");
    $sql->execute([$id]);
}
/**
 * Met à jour un message via son ID
 *
 * @param integer $idMessage
 * @param string $content
 * @return void
 */
function updateMessageById(int $idMessage, string $content): void
{
    $pdo = connexionPDO();
    $sql = $pdo->prepare("UPDATE messages SET message=:m, editedAt = current_timestamp() WHERE idMessage = :id");
    $sql->execute([
        "m" => $content,
        "id" => $idMessage
    ]);
    
}
?>