<?php
include 'db.php';

$post_id = $_GET['id'];
$post = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$post->execute(['id' => $post_id]);
$post = $post->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("UPDATE posts SET user_id = :user_id, title = :title, content = :content WHERE id = :id");
    $stmt->execute(['user_id' => $user_id, 'title' => $title, 'content' => $content, 'id' => $post_id]);

    // Handle file uploads
    if (isset($_FILES['images'])) {
        $images = $_FILES['images'];
        $totalFiles = count($images['name']);

        for ($i = 0; $i < $totalFiles; $i++) {
            if ($images['error'][$i] == 0) {
                $targetDir = "uploads/";
                $fileName = basename($images['name'][$i]);
                $targetFilePath = $targetDir . $fileName;

                if (move_uploaded_file($images['tmp_name'][$i], $targetFilePath)) {
                    $stmt = $pdo->prepare("INSERT INTO post_images (post_id, image_path) VALUES (:post_id, :image_path)");
                    $stmt->execute(['post_id' => $post_id, 'image_path' => $targetFilePath]);
                }
            }
        }
    }

    header("Location: index.php");
}

$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<form method="POST" enctype="multipart/form-data">
    <select name="user_id" required>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['id'] ?>" <?= $user['id'] == $post['user_id'] ? 'selected' : '' ?>><?= $user['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
    <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
    <input type="file" name="images[]" multiple>
    <button type="submit">Update Post</button>
</form>