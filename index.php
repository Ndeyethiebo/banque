<?php
// Démarrer la session
session_start();

// Inclure le fichier de connexion à la base de données
include_once('connexionBD.php');

/*if(isset($_SESSION['user_type'])) {
    echo "User type is set.";
    if($_SESSION['user_type'] == 'employer') {
        echo "Redirecting to employer.php";
        header('Location: employer.php');
        exit();
    } else if($_SESSION['user_type'] == 'client') {
        echo "Redirecting to client.php";
        header('Location: client.php');
        exit();
    }
}*/

// Vérifier si le formulaire de connexion a été soumis
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête SQL pour vérifier les identifiants de l'utilisateur dans la table employes
    $sql = "SELECT * FROM employes WHERE username = ? LIMIT 1";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Utilisateur trouvé dans la table employes, connexion réussie
        $_SESSION['user_type'] = 'employer'; // Utilisateur connecté en tant qu'employé
        header('Location: employer.php');
        exit();
    } else {
        // L'utilisateur n'a pas été trouvé dans la table employes, vérifier la table comptes_bancaires
        $sql = "SELECT * FROM comptes_bancaires WHERE numero = ? LIMIT 1";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Utilisateur trouvé dans la table comptes_bancaires, connexion réussie en tant que client
            $_SESSION['user_type'] = 'client'; // Utilisateur connecté en tant que client
            $_SESSION['numero_compte'] = $username; // Stocker le numéro de compte dans la session
            header('Location: client.php');
            exit();
        } else {
            // Identifiants incorrects, afficher un message d'erreur
            $error = "Identifiant ou mot de passe incorrect.";
        }
    }
} elseif(isset($_POST['Inscription'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

    // Requête SQL pour insérer les données dans la table employes
    $sql = "INSERT INTO employes (nom, prenom, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([$nom, $prenom, $username, $hashedPassword]);

    // Rediriger vers la page de connexion après l'inscription
    header('Location: index.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion Bancaire</title>
    <link rel="stylesheet" href="CSS/indexe.css">
</head>
<body>
   <header>
        <h1>Gestion Bancaire</h1>
        <button onclick="showRegistrationForm()">S'inscrire</button>
    </header>
    <div class="login-container">
        <h2>Connexion</h2>
        <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="" method="post">
            <label for="username">Identifiant :</label><br>
            <input type="text" id="username" name="username"><br>
            <label for="password">Mot de passe :</label><br>
            <input type="password" id="password" name="password"><br>
            <input type="submit" name="login" value="Se connecter">
        </form>
    </div>
   
    <div id="registration-form" style="display: none;" class="login-container">
            <h2>Inscription</h2>
            <form action="" method="post">
                <label for="nom">Nom :</label><br>
                <input type="text" id="nom" name="nom" required><br>
                <label for="prenom">Prénom :</label><br>
                <input type="text" id="prenom" name="prenom" required><br>
                <label for="username">Identifiant :</label><br>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Mot de passe :</label><br>
                <input type="password" id="password" name="password" required><br>
                <input type="submit" name="Inscription" value="S'inscrire">
            </form>
    </div>
    <script>
        function showRegistrationForm() {
            var registrationForm = document.getElementById("registration-form");
            registrationForm.style.display = "block";
        }
    </script>

</body>
</html>
