<?php
include '../config/database.php';
session_start();

$pageTitle = "Connexion";
include '../includes/header.php';
?>

<h1>Connexion</h1>
<form method="POST" action="login.php">
    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required><br>
    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required><br>
    <button type="submit">Se connecter</button>
</form>

<?php
include '../includes/footer.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Vérifier les informations de connexion
    $sql = "SELECT id, password FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            echo "Connexion réussie!";
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
