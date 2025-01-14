<?php
/*
    Dans PHP il existe plusieurs outils de connexion à la BDD
    les deux plus utilisé sont "MySQLi" et "PDO". 
    "MySQLi" est adapté uniquement aux BDD avec un pilote "MySQL"
    alors que "PDO" peut accepter n'importe quel pilote.

    Donc malgré que notre BDD soit en MySQL, dans un soucis 
    d'adaptabilité nous verrons ensemble "PDO".
*/
/**
 * retourne une instance de connexion PDO à la BDD
 *
 * @return PDO
 */
function connexionPDO(): \PDO{
    /* 
        on require notre fichier de configuration.
        Comme il retourne un tableau, on peut directement
        le ranger dans une variable.
    */
    $config = require(__DIR__."/../config/_blogConfig.php");
    /*
        "DSN" signifie "Data Source Name" c'est un string 
        contenant toute les informations pour localiser 
        la BDD. Elle prendra la forme suivante :
            "pilote":host="hôte de la BDD";port="port de la BDD";dbname="nom de la BDD";charset="charset utilisé par la BDD"
            Le tout sans espace. en remplaçant les parties entre
            guillemet par les valeurs appropriées.
        exemple :
            mysql:host=localhost;port=3306;dbname=blog;charset=utf8mb4
    */
    // phpinfo();
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
    /* $dsn = 
    "mysql:host=".$config["host"]
    .";port=".$config["port"]
    .";dbname=".$config["database"]
    .";charset=".$config["charset"]; */
    /* 
        Le "try" permet d'essayer plusieurs actions puis en cas
        d'erreur de rediriger tous les messages d'erreur au même endroit.
        le "catch" va attraper les erreurs et s'occuper de leur affichage.
    */
    try{
        /* 
            On crée une nouvelle instance de "PDO" en lui donnant 
                en premier argument le DSN
                en second le nom d'utilisateur
                en troisième le mot de passe
                en quatrième les options de PDO
            Le "\" est ici complètement optionnel, il sera 
            utile quand on fera de la POO.
        */
        $pdo = new \PDO(
            $dsn, 
            $config["user"], 
            $config["password"],
            $config["options"] 
        );
		// Puis on retourne notre instance de PDO.
		return $pdo;
        /* 
            On capture les erreurs en tant que "PDOException" dans 
            le paramètre $e.
        */
    }catch(\PDOException $e){
        /*
            On lance une nouvelle instance de PDOException
            avec en premier argument le message d'erreur 
            et en second argument le code d'erreur.
        */
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

?>