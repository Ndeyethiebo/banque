<?php
session_start();

include_once 'ConnexionBD.php';
include_once 'classes.php';

if (isset($_SESSION['releve'])) {
    $numero_compte = $_SESSION['releve'];

    // Récupérer les informations sur le compte
    $stmt_get_compte = $connexion->prepare("SELECT * FROM comptes_bancaires WHERE numero = ?");
    $stmt_get_compte->execute([$numero_compte]);
    $compte_info = $stmt_get_compte->fetch(PDO::FETCH_ASSOC);

    if ($compte_info) {
        // Récupérer les opérations effectuées sur ce compte
        $stmt_get_operations = $connexion->prepare("SELECT * FROM operations_bancaires WHERE numero_compte = ?");
        $stmt_get_operations->execute([$compte_info['id']]); // Utilisation de l'ID du compte
        $operations = $stmt_get_operations->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Aucun compte trouvé avec ce numéro.";
    }
} else {
    echo "Le numéro de compte n'a pas été spécifié.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relevé de Compte</title>
    <link rel="stylesheet" href="CSS/relevecompte.css">
</head>
<body>
    <div class="container">
    <h1>Relevé de Compte</h1>
    <h2>Informations sur le compte :</h2>
    <p>Numéro de Compte: <?php echo $compte_info['numero']; ?></p>
    <p>Solde Total: <?php echo $compte_info['solde']; ?> CFA</p>
    <h2>Opérations effectuées :</h2>
    <table border="1">
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Montant</th>
        </tr>
        <?php foreach ($operations as $operation) { ?>
            <tr>
                <td><?php echo $operation['date_operation']; ?></td>
                <td><?php echo $operation['type_operation']; ?></td>
                <td><?php echo $operation['montant']; ?> CFA</td>
            </tr>
        <?php } ?>
    </table>
    </div>
    
</body>
</html>

  
