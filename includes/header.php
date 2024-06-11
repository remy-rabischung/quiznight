<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($quiz['title']); ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Lier le fichier CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <header class="p-3 bg-dark text-white">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
          <img src="../assets/images/Quiz-logo.png" class="bi me-2" width="150" height="130" role="img" aria-label="Bootstrap" href="index.php"></img>
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="../public/index.php" class="nav-link px-2 text-secondary">Accueil</a></li>
          <li><a href="../public/admin.php" class="nav-link px-2 text-white">Créer un Quiz</a></li>
          <li><a href="../public/quiz.php" class="nav-link px-2 text-white">Voir les Quiz</a></li>
          <li><a href="../public/index.php" class="nav-link px-2 text-white">A Propos</a></li>
        </ul>

        <div class="text-end">
          <button type="button" class="btn btn-outline-light me-2">Se Connecter</button>
          <button type="button" class="btn btn-light">S'inscrire</button>
        </div>
      </div>
    </div>
  </header>
</head>
<body>
