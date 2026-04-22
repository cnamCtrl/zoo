<?php
// enclosures/add.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $capacity = (int)$_POST['capacity'];
    $description = trim($_POST['description']);

    if (empty($name) || empty($type) || empty($capacity)) {
        $error = "All required fields must be filled.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO enclosures (name, type, capacity, description) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $type, $capacity, $description])) {
                $success = "Enclosure added successfully!";
            }
        } catch (PDOException $e) {
            $error = "DB Error: " . $e->getMessage();
        }
    }
}
?>

<div class="dash-header">
    <div>
        <h1>Add New Enclosure</h1>
    </div>
    <a href="index.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to List</a>
</div>

<div class="form-container">
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" action="" class="form-grid">
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" required placeholder="e.g. Safari Plains">
        </div>
        <div class="form-group">
            <label>Type *</label>
            <input type="text" name="type" required placeholder="e.g. Open Field, Aquarium">
        </div>
        <div class="form-group full-width">
            <label>Capacity *</label>
            <input type="number" name="capacity" required placeholder="Number of animals">
        </div>
        <div class="form-group full-width">
            <label>Description</label>
            <textarea name="description" rows="3"></textarea>
        </div>
        <div class="form-group full-width">
            <button type="submit" class="btn btn-primary">Save Enclosure</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
