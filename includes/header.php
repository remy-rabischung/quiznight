<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : "Quiz"; ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Lier le fichier CSS principal -->
    <link rel="stylesheet" href="../assets/css/custom_styles.css"> <!-- Lier le fichier CSS personnalisé -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
  <header class="p-3 bg-dark text-white">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
          <img src="../assets/images/Quiz-logo.png" class="bi me-2" width="220" height="80" role="img" aria-label="Bootstrap" href="index.php"></img>
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="../public/index.php" class="nav-link px-2 text-secondary">Accueil</a></li>
            <li><a href="../public/admin.php" class="nav-link px-2 text-white">Créer un Quiz</a></li>
            <li><a href="../public/quiz_list.php" class="nav-link px-2 text-white">Voir les Quiz</a></li>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
                <li><a href="../public/admin_users.php" class="nav-link px-2 text-white">Gérer les Utilisateurs</a></li>
            <?php } ?>
        </ul>


        <div class="text-end">
            <?php if (isset($_SESSION['user_id'])) { ?>
                <form action="../public/logout.php" method="post">
                    <button type="submit" class="btn btn-outline-danger me-2">Se Déconnecter</button>
                </form>
            <?php } else { ?>
                <button type="button" class="btn btn-outline-light me-2"><a href="../public/login.php" class="text-white">Se Connecter</a></button>
                <button type="button" class="btn btn-primary"><a href="../public/register.php">S'inscrire</a></button>
            <?php } ?>
        </div>
      </div>
    </div>
  </header>
