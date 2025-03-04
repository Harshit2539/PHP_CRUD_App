<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("UPDATE posts SET user_id = :user_id, title = :title, content = :content WHERE id = :id");
    $stmt->execute(['user_id' => $user_id, 'title' => $title, 'content' => $content, 'id' => $id]);

    header("Location: index.php");
}

$id = $_GET['id'];
$post = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$post->execute(['id' => $id]);
$post = $post->fetch();

$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<form method="POST">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    <select name="user_id" required>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['id'] ?>" <?= $user['id'] == $post['user_id'] ? 'selected' : '' ?>><?= $user['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="title" value="<?= $post['title'] ?>" required>
    <textarea name="content" required><?= $post['content'] ?></textarea>
    <button type="submit">Update Post</button>
</form>