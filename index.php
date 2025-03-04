<?php
include 'db.php';

$posts = $pdo->query("SELECT posts.*, users.name AS user_name FROM posts JOIN users ON posts.user_id = users.id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your custom CSS file -->
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Social Posts</h1>
    <div class="mb-3">
        <a href="create_user.php" class="btn btn-primary">Create User</a>
        <a href="create_post.php" class="btn btn-success">Create Post</a>
    </div>

    <?php foreach ($posts as $post): ?>
        <div class="post card mb-3 p-3">
            <h2><?= htmlspecialchars($post['title']) ?> by <?= htmlspecialchars($post['user_name']) ?></h2>
            <p><?= htmlspecialchars($post['content']) ?></p>
            <h3>Images:</h3>
            <?php
            $images = $pdo->prepare("SELECT * FROM post_images WHERE post_id = :post_id");
            $images->execute(['post_id' => $post['id']]);
            $post_images = $images->fetchAll();
            ?>
            <?php foreach ($post_images as $image): ?>
                <img src="<?= htmlspecialchars($image['image_path']) ?>" alt="Post Image" style="width: 100px; height: auto;" class="mr-2">
            <?php endforeach; ?>
            <div class="mt-2">
                <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="delete_post.php?id=<?= $post['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?');">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>