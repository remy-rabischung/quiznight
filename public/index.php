<?php
session_start();
$pageTitle = "Accueil";
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<main class="container mt-5">
    <div class="text-center">
        <h1>Bienvenue au Quiz Night!</h1>
        <p>Participer à des quiz amusants et défiez vos amis!</p>
        <a href="../public/quiz_list.php" class="btn btn-primary btn-lg">Voir les Quiz</a>
    </div>
    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card">
                <img src="../assets/images/quiz1.jpg" class="card-img-top" alt="Quiz 1">
                <div class="card-body">
                    <h5 class="card-title">Quiz Populaire 1</h5>
                    <p class="card-text">Essayez ce quiz populaire et testez vos connaissances!</p>
                    <a href="quiz_detail.php?id=1" class="btn btn-primary">Jouer</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img src="../assets/images/quiz2.png" class="card-img-top" alt="Quiz 2">
                <div class="card-body">
                    <h5 class="card-title">Quiz Populaire 2</h5>
                    <p class="card-text">Un autre quiz passionnant pour défier vos amis.</p>
                    <a href="quiz_detail.php?id=2" class="btn btn-primary">Jouer</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img src="../assets/images/quiz3.jpg" class="card-img-top" alt="Quiz 3">
                <div class="card-body">
                    <h5 class="card-title">Quiz Populaire 3</h5>
                    <p class="card-text">Un autre quiz passionnant pour défier vos amis.</p>
                    <a href="quiz_detail.php?id=2" class="btn btn-primary">Jouer</a>
                </div>
            </div>
        </div>
    </div>
</main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
