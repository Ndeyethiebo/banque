<?php
$serveur = '127.0.0.1'; // Adresse IP du serveur MySQL
$user = 'root'; // Nom d'utilisateur MySQL
$password = ''; // Mot de passe MySQL
$name = "connexion"; // Nom de la base de données

try {
    // Création d'une nouvelle instance PDO pour la connexion à la base de données
    $connexion = new PDO("mysql:host=$serveur;dbname=$name", $user, $password);

    // Configuration des options de PDO pour afficher les erreurs SQL
    //$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
     // Création de la table "clients"
     $create_clients_table = "CREATE TABLE IF NOT EXISTS clients(
        id  INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        prenom VARCHAR(255) NOT NULL,
        telephone VARCHAR(15) NOT NULL,
        adresse VARCHAR(255) NOT NULL
    )";
    $connexion->exec($create_clients_table);

    // Création de la table "comptes_bancaires"
    $create_comptes_table = "CREATE TABLE IF NOT EXISTS comptes_bancaires (
        id INT AUTO_INCREMENT PRIMARY KEY,
        numero VARCHAR(20) NOT NULL,
        solde DECIMAL(10, 2) NOT NULL,
        proprietaire_id INT,
        mot_de_passe VARCHAR(255),
        FOREIGN KEY (proprietaire_id) REFERENCES clients(id)
    )";
    $connexion->exec($create_comptes_table);

    $sql_employes = "CREATE TABLE IF NOT EXISTS employes (
        id_employe INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nom varchar(50),
        prenom varchar(50),
        username VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL
    )";
    
    if ($connexion->query($sql_employes) === TRUE) {
        echo "Table employes créée avec succès<br>";
    } 

    // Création de la table "operations_bancaires"
    $create_operations_table = "CREATE TABLE IF NOT EXISTS operations_bancaires (
        id INT AUTO_INCREMENT PRIMARY KEY,
        numero_compte INT NOT NULL,
        type_operation ENUM('depot', 'retrait', 'virement') NOT NULL,
        montant DECIMAL(10, 2) NOT NULL,
        date_operation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (numero_compte) REFERENCES comptes_bancaires(id)
    )";
    $connexion->exec($create_operations_table);

} catch(PDOException $e) {
    // En cas d'erreur, affichage du message d'erreur
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>
