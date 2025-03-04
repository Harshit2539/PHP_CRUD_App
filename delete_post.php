<?php
include 'db.php';

$post_id = $_GET['id'];

// Delete associated images
$stmt = $pdo->prepare("DELETE FROM post_images WHERE post_id = :post_id");
$stmt->execute(['post_id' => $post_id]);

// Delete the post
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
$stmt->execute(['id' => $post_id]);

header("Location: index.php");
?>