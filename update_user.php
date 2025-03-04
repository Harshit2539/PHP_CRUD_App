<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];

    $stmt = $pdo->prepare("UPDATE users SET name = :name WHERE id = :id");
    $stmt->execute(['name' => $name, 'id' => $id]);

    header("Location: index.php");
}

$id = $_GET['id'];
$user = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$user->execute(['id' => $id]);
$user = $user->fetch();
?>

<form method="POST">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">
    <input type="text" name="name" value="<?= $user['name'] ?>" required>
    <button type="submit">Update User</button>
</form>