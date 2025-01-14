<?php 
/* 
    Ce fichier est un des plus importants de votre projet.
    Il est celui qui ne doit surtout pas tomber entre 
    des mains malveillantes.
    Car ce fichier contiendra toute les informations necessaire 
    à se connecter à votre Base de données.

    * Petite astuce :
        On est hors de toute fonction, et pourtant j'utilise le mot clef return.
        Cela va me permettre quand je vais importer le fichier, de 
        récupérer directement le tableau que je crée ici.
*/
return 
[
    // l'host est l'hebergeur de ma BDD (ici localhost)
    "host"=>$_ENV["DB_HOST"]??"127.0.0.1",
    // Le port utilisé pour se connecter (souvent 3306)
    "port"=>$_ENV["DB_PORT"]??"3306",
    // database est le nom de la bdd à laquelle on veut se connecter.
    "database"=> $_ENV["DB_NAME"]??"",
    // le nom d'utilisateur qui doit s'y connecter
    "user"=> $_ENV["DB_USER"]??"root",
    // le mot de passe de cet utilisateur.
    "password"=> $_ENV["DB_PASSWORD"]??"root",
    // le set de caractère utilisé.
    "charset" => "utf8mb4",
    /* 
        Un tableau d'options qui seront utilisé pour indiquer à PDO
        (PHP Data Object) comment réagire dans certains cas précis.
    */
    "options" => 
    [
        // Mode d'affichage des erreurs.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        /* On indique comment les données de notre BDD doivent être
        retournées. Ici on demande des tableaux associatif */ 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        /* 
            PDO peut émuler lui même les requêtes préparés plutôt 
            que laisser le pilote de BDD faire.
            Notre version de MySQL gère très bien cela, on 
            désactive donc cette option.
        */
        PDO::ATTR_EMULATE_PREPARES => false
    ]
    /*
        Une liste des différents attributs disponible pour ces options peut se retrouver ici : 
            https://www.php.net/manual/fr/pdo.setattribute.php
        Ainsi que les différents FETCH_MODE ici :
            https://www.php.net/manual/fr/pdostatement.fetch.php
    */
];
?>
?>