<?php
$host = 'localhost'; // Database host
$dbname = 'quiz_night'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Establish a PDO database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO to throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optionally, set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Optionally, set character set
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    // Print PDO exception message
    die("Erreur de connexion : " . $e->getMessage());
}
?>
