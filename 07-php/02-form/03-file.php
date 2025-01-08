<?php
/* 
    Ici nous allons voir comment uploader un fichier sur votre serveur.
    Même si nous ne le ferons pas ici, il est important de retenir que lorsque 
    l'on téléverse un fichier, nous ne le mettons pas en BDD.
    On va simplement ranger notre fichier dans un dossier.  
    Puis sauvegarder le nom du fichier et/ou le chemin en BDD.
*/
$error = $target_file = $target_name = $mime_type = $oldName = "";
/*  
    target_dir va contenir le chemin vers le dossier d'upload.
    Pour des raisons de sécurité, si vous comptez rendre les fichiers téléverser 
    accessible à vos utilisateurs, il serait bon que votre dossier d'upload ne soit 
    pas au milieu de dossier sensible de votre projet.
    les utilisateurs accedant à ces fichier pourront voir le chemin vers le dossier d'upload.
*/
$target_dir = "./upload/";
// On liste dans un tableau les types mimes des fichiers que l'on accepte.
$typesPermis = ['image/png', 'image/jpeg', 'image/gif', 'application/pdf'];
// Arrive t-on en méthode POST par le formulaire que l'on a défini 
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload"])){
    /*
        Lorsque l'on upload un fichier, le serveur va le sauvegarder dans un dossier
        temporaire et supprimera le contenu de ce dossier une fois l'execution du script terminé.
        On va donc vérifier que ce fichier corresponde bien à nos attentes avant de le déplacer ailleurs. 

        La première étape est de voir si le fichier a bien été uploadé. 
        Pour cela on va se servir de la superglobal $_FILES de la même façon qu'on utilise $_GET et $_POST.
        Ici on a nommé notre input "fichier" donc on va chercher $_FILES["fichier"] qui est lui même un 
        tableau associatif comportant plusieurs informations :

            "tmp_name" est l'adresse du fichier temporaire.
            On va donc utiliser is_uploaded_file() pour vérifier son existence.
    */
    if(!is_uploaded_file($_FILES['fichier']['tmp_name'])){
        $error = "Veuillez sélectionner un fichier";
    }else{
        /* 
            On trouvera le nom d'origine du fichier à la clef "name".
            basename() va retourner le dernier composant d'un chemin.
            par exemple si on lui donne "categorie/nourriture/pizza" il nous rendra "pizza"
            Ici on veut que si l'utilisateur nous fourni un fichier ayant un chemin intégré dans son
            nom, on ne récupère que la dernière partie.
        */
        $oldName = basename($_FILES["fichier"]["name"]);
        /* 
            La seconde étape est de préparer un nouveau nom pour notre fichier. 
            Pourquoi cela ? Car qu'arrivera t-il si deux de vos utilisateurs téléverse deux fichiers
            deux même noms comme "cv.pdf" ? 
            Et bien le second à le faire écrasera le premier.
            Donc pour éviter cela on va renommer nos fichiers grâce à la fonction
            "uniqid()", elle peut être utilisé sans argument et produira alors 13 caractères aléatoire.
                (Attention, ne pas utiliser pour de la sécurité)
            "uniqid('', true)" avec son second argument à true, on passera à 23 caractères aléatoire.
            "uniqid('chaussette') le premier paramètre permet de placer un prefix, ici le code sera 
                précéde par "chaussette". 

            Dans mon exemple ci dessous j'utilise uniqid à true avec le timestamp en prefixe le tout 
            concaténé au nom de base du fichier. 
            Donc pour avoir un doublons il faudrait que deux utilisateurs postent un fichier de même nom
            à la même seconde et tombe par hasard sur les 23 même caractères.
        */
        $target_name =  uniqid(time()."-", true) ."-". $oldName;
        /* 
            On concatène le nom de notre fichier au chemin du dossier où on veut le ranger.
            
            On ne le fera pas ici, mais si on voudrait créer de nouveaux dossiers, par exemple 
            un dossier par utilisateur ou un dossier par mois.
            On pourrait faire une condition avec is_dir() pour vérifier si un dossier existe déjà
            puis si il n'existe pas en créer un avec mkdir()
            Et on aurait plus qu'à l'ajouter à notre chemin.
        */
        $target_file = $target_dir . $target_name;
        /* 
            On récupère le type mime du fichier dans sa zone temporaire. dont on va se servir 
            pour vérifier si le type du fichier correspond à nos attentes.

            Vous trouverez d'autres façons de vérifier le type comme en regardant l'extention du fichier
            mais cela n'est pas très sécurisé, car il est très facile de changer l'extension d'un fichier.
        */
        $mime_type = mime_content_type($_FILES['fichier']['tmp_name']);
        /* 
            Cette partie sert à vérifier si le fichier existe déjà, vu le nom qu'on lui a donné 
            il n'y a que très peu de chance que cela arrive, mais cela ne coûte rien de vérifier.
            Et cela peut vous être utile si vous faites un changement de nom plus basique.
        */
        if(file_exists($target_file)){
            $error = "Ce fichier existe déjà.";
        }
        /* 
            Ensuite il est bon de donner une taille maximum à votre fichier.
            On voudrais éviter que les utilisateurs remplissent notre serveur de fichiers de plusieurs 
            giga. Le poid qui se trouve à la clef "size" est en "octet", donc n'hésitez quand même pas 
            à donner un grand nombre. 

            Si jamais vous faites de l'upload de gros fichiers et que votre upload ne fonctionne pas.
            n'hésitez pas à vérifier la configuration de PHP dans le fichier php.ini de votre serveur.
            Il y a une taille maximum des upload entre autre paramètre qui peuvent bloquer.
        */
        if($_FILES["fichier"]["size"] > 500000){
            $error = "Ce fichier est trop gros";
        }
        /* 
            Puis on vérifie tout simplement si le type mime que l'on a récupéré est bien dans notre 
            tableau défini en début de script.
        */
        if(!in_array($mime_type, $typesPermis)){
            $error = "Ce type de fichier n'est pas accepté";
        }
        // Enfin la dernière étape si il n'y a pas d'erreur :
        if(empty($error)){
            /* 
                On utilise la fonction "move_uploaded_file" pour déplacer notre fichier depuis 
                son dossier temporaire (premier argument) jusqu'à 
                son dossier final (second argument) avec son nouveau nom.

                Cette fonction retourne un boolean indiquant si le déplacement s'est bien passé. 
                Je l'utilise donc ici directement dans une condition mais j'aurais pu ranger ce boolean 
                dans une variable telle que :
                $uploaded = move_uploaded_file($from, $to);
                puis faire un if($uploaded)
            */
            if(move_uploaded_file($_FILES["fichier"]["tmp_name"],$target_file)){
                /* 
                    Si tout s'est bien passé on arrivera ici et il ne nous restera plus
                    qu'à sauvegarder en BDD le nom et/ou le chemin de notre fichier.
                */ 
            }else{
                $error = "Erreur lors de l'upload";
            }
        }
    }
}
require("../ressources/template/_header.php");
?>
<!-- Notre formulaire est assez classique, on oublie juste pas l'attribut :
    "enctype" lorsque l'on veut uploader un fichier. -->
<form action="" method="post" enctype="multipart/form-data">
    <label for="fichier">Choisir un fichier :</label>
    <input type="file" name="fichier" id="fichier">
    <input type="submit" value="Envoyer" name="upload">
    <span class="error"><?php echo $error??""; ?></span>   
</form>
<!-- On affiche cette partie que is on a envoyé notre formulaire et qu'il n'y a aucune erreur. -->
<?php if(isset($_POST["upload"]) && empty($error)): ?>
    <p>
        Votre fichier a bien été téléversé sous le nom  "<?php echo $target_name ?>". <br>
        Vous pouvez le télécharger <br> <a href="<?php echo $target_file ?>" download="<?php echo $oldName ?>"> ICI</a>
    </p>
<?php
endif;
require("../ressources/template/_footer.php");
?>