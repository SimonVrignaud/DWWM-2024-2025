<?php
require("../../../ressources/services/_shouldBeLogged.php");

unset($_SESSION);
session_destroy();
setcookie("PHPSESSID","", time()-3600);
// puis on redirige notre utilisateur vers la page de connexion
header("location: ./connexion.php");
exit;
?>