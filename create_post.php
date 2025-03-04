<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Insert the post
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (:user_id, :title, :content)");
    $stmt->execute(['user_id' => $user_id, 'title' => $title, 'content' => $content]);

    // Get the last inserted post ID
    $post_id = $pdo->lastInsertId();


 
    // Handle file uploads
    if (isset($_FILES['images'])) {
        
        $images = $_FILES['images'];
        $totalFiles = count($images['name']);

        for ($i = 0; $i < $totalFiles; $i++) {
            if ($images['error'][$i] == 0) {
                $targetDir = "uploads/";
                $fileName = basename($images['name'][$i]);
                $targetFilePath = $targetDir . $fileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($images['tmp_name'][$i], $targetFilePath)) {
                    // Insert image path into the database
                    $stmt = $pdo->prepare("INSERT INTO post_images (post_id, image_path) VALUES (:post_id, :image_path)");
                    $stmt->execute(['post_id' => $post_id, 'image_path' => $targetFilePath]);
                } else {
                    echo "Error uploading file: " . $images['name'][$i];
                }
            } else {
                echo "Error with file: " . $images['name'][$i] . " - Error Code: " . $images['error'][$i];
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
            <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="title" placeholder="Post Title" required>
    <textarea name="content" placeholder="Post Content" required></textarea>
    <input type="file" name="images[]" multiple required>
    < <button type="submit">Create Post</button>
</form>