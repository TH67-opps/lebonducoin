<!-- filepath: c:\xampp\htdocs\lebienducoin\mes_annonces.php -->
<?php
session_start();
include 'bd.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les annonces de l'utilisateur
$sql = "SELECT id, nom, description, prix, image FROM produits WHERE utilisateurs_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Annonces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .description {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Nombre de lignes visibles */
            -webkit-box-orient: vertical;
        }
        .btn-retour {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar nav-underline fixed-top navbar-expand-lg bg-body-tertiary shadow p-3 mb-1 bg-body-tertiary rounded">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-arrow-left me-2"></i> Retour à l'accueil
        </a>
        <div class="nav justify-content-center">
            <p class="fs-5 fst-italic fw-bold">LeBon<span class="text-success">DuCoin</span></p>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-uppercase" style="font-size: 3rem; color: #28a745; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); letter-spacing: 2px;">
            <i class="fas fa-bullhorn me-2"></i>Mes Annonces
        </h2>
        <p class="text-muted" style="font-size: 1.2rem; font-style: italic;">Gérez vos annonces en toute simplicité</p>
        <hr style="width: 50%; margin: 0 auto; border: 1px solid #28a745;">
    </div>
    <div class="row g-4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100 shadow-sm">
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($row['nom']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center fw-bold"><?php echo htmlspecialchars($row['nom']); ?></h5>
                            <p class="card-text text-muted text-center description"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="text-success fw-bold text-center"><?php echo htmlspecialchars($row['prix']); ?> €</p>
                            <div class="mt-auto d-flex justify-content-between">
                                <a href="modif.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <a href="deleteA.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">Supprimer</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="alert alert-warning">Vous n'avez publié aucune annonce pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>