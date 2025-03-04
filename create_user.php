<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    $stmt = $pdo->prepare("INSERT INTO users (name) VALUES (:name)");
    $stmt->execute(['name' => $name]);

    header("Location: index.php");
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="User  Name" required>
    <button type="submit">Create User</button>
</form>

 