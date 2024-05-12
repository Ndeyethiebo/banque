<?php
session_start();

// Vérifier si le numéro de compte est défini dans la session et s'il s'agit d'un client

// Récupérer le numéro de compte depuis la session
$numero_compte = $_SESSION['numero_compte'];

// Inclure le fichier de connexion à la base de données et les classes nécessaires
include_once('connexionBD.php');
include_once('classes.php');

// Récupérer les informations du compte du client
$sql_compte = "SELECT * FROM comptes_bancaires WHERE numero = :numero_compte";
$stmt_compte = $connexion->prepare($sql_compte);
$stmt_compte->bindParam(':numero_compte', $numero_compte);
$stmt_compte->execute();
$compte_info = $stmt_compte->fetch(PDO::FETCH_ASSOC);

// Si le compte n'existe pas, afficher un message d'erreur

// Traitement des opérations bancaires
if(isset($_POST['operation'])) {
    $type_operation = $_POST['operation'];
    $montant = $_POST['montant'];

    // Vérifier si le compte existe dans la base de données
    $stmt_check_compte = $connexion->prepare("SELECT id, solde FROM comptes_bancaires WHERE numero = ?");
    $stmt_check_compte->execute([$numero_compte]);
    $compte = $stmt_check_compte->fetch(PDO::FETCH_ASSOC);

    if ($compte) {
        $compte_obj = new CompteBancaire($numero_compte, $compte["solde"], $compte["id"]);

        if ($type_operation == 'depot') {
            OperationBancaire::effectuerDepot($compte_obj, $montant);

            // Insérer l'opération de dépôt dans la table operations_bancaires
            $stmt_insert_operation = $connexion->prepare("INSERT INTO operations_bancaires (numero_compte, type_operation, montant) VALUES (?, ?, ?)");
            $stmt_insert_operation->execute([$compte["id"], $type_operation, $montant]);

            echo "<p><br> <br><br> <br>Opération de dépôt effectuée avec succès. Nouveau solde: $compte_obj->solde</p>";
        } elseif ($type_operation == 'retrait' && $compte_obj->solde >= $montant) {
            OperationBancaire::effectuerRetrait($compte_obj, $montant);

            // Insérer l'opération de retrait dans la table operations_bancaires
            $stmt_insert_operation = $connexion->prepare("INSERT INTO operations_bancaires (numero_compte, type_operation, montant) VALUES (?, ?, ?)");
            $stmt_insert_operation->execute([$compte["id"], $type_operation, $montant]);

            echo "<p><br> <br><br> <br>Opération de retrait effectuée avec succès. Nouveau solde: $compte_obj->solde</p>";
        } else {
            echo "<p><br> <br><br> <br> Erreur lors de l'opération : montant invalide ou solde insuffisant.</p>";
        }

        // Mettre à jour le solde du compte
        $stmt_update_compte = $connexion->prepare("UPDATE comptes_bancaires SET solde = ? WHERE numero = ?");
        $stmt_update_compte->execute([$compte_obj->solde, $numero_compte]);
    } else {
        echo "<p>    <br> <br><br> <br>Aucun compte bancaire trouvé avec ce numéro.</p>";
    }
    // Stocker le numéro de compte dans la session
    $_SESSION['releve'] = $numero_compte;
}

/*if(!isset($_SESSION['numero_compte']) || $_SESSION['user_type'] != 'client') {
    // Si le numéro de compte n'est pas défini dans la session ou si l'utilisateur n'est pas un client, rediriger vers la page de connexion
    header("Location: index.php");
    exit();
}*/
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client - Gestion Bancaire</title>
    <link rel="stylesheet" href="CSS/client.css">
</head>
<body>
    <header>
        <h1>Gestion Bancaire</h1>
    </header>
    <br> <br><br> <br>
    <div class="container">
        <h1>Client - Gestion Bancaire</h1>
        <div class="account-info">
            <h2>Informations du Compte :</h2>
            <p><strong>Numéro de Compte :</strong> <?php echo $compte_info['numero']; ?></p>
            <p><strong>Solde :</strong> <?php echo $compte_info['solde']; ?></p>
        </div>

        <div class="operations">
            <h2>Effectuer une Opération :</h2>
            <form action="" method="post">
                <label for="operation">Type d'Opération :</label><br>
                <select name="operation" id="operation">
                    <option value="depot">Dépôt</option>
                    <option value="retrait">Retrait</option>
                </select><br>
                <label for="montant">Montant :</label><br>
                <input type="number" id="montant" name="montant" required><br>
                <input type="submit" value="Effectuer">
            </form>
            <form action="ReleveCompte.php" method="post">
              <input type="submit" value="Voir le relevé de compte">
            </form>
        </div>
    </div>
</body>
</html>
