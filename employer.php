<?php
// Inclure le fichier de connexion à la base de données et la classe CompteBancaire
include_once ('ConnexionBD.php');
include_once ('classes.php');

if(isset($_POST['creer_compte_client'])) {
    $nom_client = $_POST['nom_client'];
    $prenom_client = $_POST['prenom_client'];
    $adresse_client = $_POST['adresse_client'];
    $telephone_client = $_POST['telephone_client'];

    // Création d'une nouvelle instance de la classe Client
    $nouveau_client = new Client($nom_client, $prenom_client,  $telephone_client,$adresse_client);

    // Insérer les informations du client dans la base de données
    $sql = "INSERT INTO clients (nom, prenom,telephone,adresse) VALUES (?, ?, ?, ?)";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$nom_client, $prenom_client,$telephone_client, $adresse_client]);
        echo"<p><br> <br><br> <br> Nouveau compte client a ete cree</p>";
}

// Code PHP pour récupérer la liste de tous les clients et afficher les informations

// Exemple de requête SQL pour récupérer la liste des clients
$sql_clients = "SELECT * FROM clients";
$stmt_clients = $connexion->query($sql_clients);
$clients = $stmt_clients->fetchAll(PDO::FETCH_ASSOC);

// Afficher les informations de chaque client
/*echo "<h1>Liste des clients :</h1>";
echo "<ul>";
foreach ($clients as $client) {
    echo "<li>Nom : " . $client['nom'] . " | Prénom : " . $client['prenom'] . " | Adresse : " . $client['adresse'] . " | Téléphone : " . $client['telephone'] . "</li>";
}
echo "</ul>";*/

// Vérifier si le formulaire de création de compte a été soumis
if(isset($_POST['creer_compte'])) {
    $numero_compte = $_POST['numero_compte'];
    $solde = $_POST['solde'];
    $proprietaire_id = $_POST['proprietaire_id'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password ,PASSWORD_DEFAULT);

    // Création d'une nouvelle instance de la classe CompteBancaire
    $nouveau_compte = new CompteBancaire($numero_compte, $solde, $proprietaire_id);

    // Insérer les informations du compte dans la base de données
    $stmt_insert_compte = $connexion->prepare("INSERT INTO comptes_bancaires (numero, solde, proprietaire_id,mot_de_passe) VALUES (?, ?, ?,?)");
    $stmt_insert_compte->execute([$numero_compte, $solde, $proprietaire_id,$hashedPassword]);

    if ($stmt_insert_compte->execute()) 
        echo "<p>    <br> <br><br> <br>Compte bancaire créé avec succès.</p>";
    
}
// Code PHP pour récupérer la liste de tous les comptes bancaires et afficher les informations

// Exemple de requête SQL pour récupérer la liste des comptes bancaires
/*$sql_comptes = "SELECT * FROM comptes_bancaires";
$stmt_comptes = $connexion->query($sql_comptes);
$comptes = $stmt_comptes->fetchAll(PDO::FETCH_ASSOC);

// Afficher les informations de chaque compte
echo "<h1>Liste des comptes bancaires :</h1>";
echo "<ul>";
foreach ($comptes as $compte) {
    echo "<li>Numéro de compte : " . $compte['numero'] . " | Solde : " . $compte['solde'] . "</li>";
}
echo "</ul>";*/

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Compte Bancaire</title>
    <link rel="stylesheet" href="CSS/employer.css">
</head>
<body>
    <header>
        <h1>Gestion Bancaire</h1>
    </header>
    <br> <br><br> <br>  <br>

    <!-- Formulaire de création de compte pour le client -->
    <div class="login-container">

    <h2>Créer un compte client :</h2>
    <form action="" method="post">
        <label for="nom_client">Nom :</label><br>
        <input type="text" id="nom_client" name="nom_client" required><br>
        <label for="prenom_client">Prénom :</label><br>
        <input type="text" id="prenom_client" name="prenom_client" required><br>
        <label for="adresse_client">Adresse :</label><br>
        <input type="text" id="adresse_client" name="adresse_client" required><br>
        <label for="telephone_client">Téléphone :</label><br>
        <input type="text" id="telephone_client" name="telephone_client" required><br>
        <input type="submit" name="creer_compte_client" value="Créer Compte Client">
    </form>
    </div>
     <br>      <br> 

    <div class="login-container">
    <h2>Créer un Compte Bancaire</h1>
    <form action="" method="post">
        <label for="numero_compte">Numéro de Compte :</label><br>
        <input type="text" id="numero_compte" name="numero_compte" required><br>
        <label for="solde">Solde :</label><br>
        <input type="number" id="solde" name="solde" required><br>
        <label for="proprietaire_id">ID du Propriétaire :</label><br>
        <input type="text" id="proprietaire_id" name="proprietaire_id" required><br>
        <label for="password">Mot de pass :</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" name="creer_compte" value="Créer Compte">
    </form>
    </div>
    
</body>
</html>


