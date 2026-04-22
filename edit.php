<?php
// animals/delete.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireAuth();

if (!isAdmin()) {
    die("Unauthorized access.");
}

$id = $_GET['id'] ?? 0;

if ($id) {
    try {
        // Optional: remove image from server before deleting DB record
        $stmt = $pdo->prepare("SELECT image_url FROM animals WHERE id = ?");
        $stmt->execute([$id]);
        $animal = $stmt->fetch();
        if ($animal && $animal['image_url'] !== 'default.jpg') {
            $file_path = __DIR__ . '/../uploads/animals/' . $animal['image_url'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM animals WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // could map errors here if necessary, though ON DELETE is configured
    }
}

redirect('/zoo_project/animals/index.php');
exit;
?>
