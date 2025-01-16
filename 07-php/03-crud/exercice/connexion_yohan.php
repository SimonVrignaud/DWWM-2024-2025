<?php
// Démarrage de la session pour gérer les variables de session
session_start();

// Inclusion du fichier de configuration de la base de données
require_once '../includes/db.php';  

// Activation de l'affichage de toutes les erreurs PHP pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tentative de connexion à la base de données MySQL
try {
    $pdo = new PDO(
        "mysql:host=db;dbname=ma_base", // Connexion au service 'db' Docker
        "user",                         // Nom d'utilisateur défini dans docker-compose
        "password",                     // Mot de passe défini dans docker-compose
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) // Active le mode d'erreur PDO
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Variable pour stocker les messages d'erreur
$error = '';

// Traitement du formulaire lors d'une soumission POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    $email = trim($_POST['email']);     // Supprime les espaces inutiles
    $password = $_POST['password'];     // Récupère le mot de passe
    
    try {
        // Préparation de la requête SQL pour éviter les injections SQL
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        // Vérification des identifiants
        if ($user && password_verify($password, $user['password'])) {
            // Si authentification réussie, création des variables de session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ../index.php'); // Redirection vers la page d'accueil
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Erreur de connexion à la base de données.";
    }
}

// Si l'utilisateur est déjà connecté, redirection vers l'accueil
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <?php if(isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore inscrit ? <a href="../inscription/inscription.php">S'inscrire</a></p>
    </div>
</body>
</html> 