<?php
require 'db.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM responses WHERE id = ?");
    if ($stmt->execute([$id])) {
        header('Location: index.php?msg=deleted');
        exit;
    }
}
header('Location: index.php?msg=error');
