<?php 

header("HTTP/1.1 404 Not Found");
// echo http_response_code();

if(rand(0,100) < 50)
{
    // exit("before redirection");
    header("Location: 09-b-header.php");
    exit;
    // die;
}

$title = " Headers page 1";
require("../ressources/template/_header.php");
?>
<h2>Vous avez de la chance</h2>
<?php 

require("../ressources/template/_footer.php");
?>