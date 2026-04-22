<?php
// animals/edit.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$error = '';
$success = '';
$id = $_GET['id'] ?? 0;

// Fetch animal data
$stmt = $pdo->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch();

if (!$animal) {
    echo "<div class='alert alert-error'>Animal not found.</div>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$enclosures = $pdo->query("SELECT id, name FROM enclosures ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $species = trim($_POST['species']);
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'] ?: null;
    $join_date = $_POST['join_date'] ?: date('Y-m-d');
    $description = trim($_POST['description']);
    $enclosure_id = $_POST['enclosure_id'] ?: null;
    $status = $_POST['status'];
    
    $image_url = $animal['image_url'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = uploadImage($_FILES['image']);
    }

    if (empty($name) || empty($species)) {
        $error = "Name and Species are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE animals SET name=?, species=?, gender=?, date_of_birth=?, join_date=?, description=?, image_url=?, enclosure_id=?, status=? WHERE id=?");
            if ($stmt->execute([$name, $species, $gender, $date_of_birth, $join_date, $description, $image_url, $enclosure_id, $status, $id])) {
                $success = "Animal updated successfully!";
                // Refresh data
                $stmt = $pdo->prepare("SELECT * FROM animals WHERE id = ?");
                $stmt->execute([$id]);
                $animal = $stmt->fetch();
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="dash-header">
    <div>
        <h1>Edit Animal: <?= htmlspecialchars($animal['name']) ?></h1>
    </div>
    <a href="index.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to List</a>
</div>

<div class="form-container">
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="form-grid">
        <div class="form-group" style="grid-column: 1 / -1; text-align: center;">
            <img src="/zoo_project/uploads/animals/<?= htmlspecialchars($animal['image_url']) ?>" alt="Current Image" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid var(--primary-light);">
        </div>
        
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($animal['name']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Species *</label>
            <input type="text" name="species" value="<?= htmlspecialchars($animal['species']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Gender</label>
            <select name="gender">
                <option value="Unknown" <?= $animal['gender']==='Unknown' ? 'selected' : '' ?>>Unknown</option>
                <option value="Male" <?= $animal['gender']==='Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $animal['gender']==='Female' ? 'selected' : '' ?>>Female</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Healthy" <?= $animal['status']==='Healthy' ? 'selected' : '' ?>>Healthy</option>
                <option value="Sick" <?= $animal['status']==='Sick' ? 'selected' : '' ?>>Sick</option>
                <option value="In Treatment" <?= $animal['status']==='In Treatment' ? 'selected' : '' ?>>In Treatment</option>
                <option value="Quarantine" <?= $animal['status']==='Quarantine' ? 'selected' : '' ?>>Quarantine</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" value="<?= htmlspecialchars($animal['date_of_birth'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label>Join Date</label>
            <input type="date" name="join_date" value="<?= htmlspecialchars($animal['join_date']) ?>">
        </div>
        
        <div class="form-group">
            <label>Update Enclosure</label>
            <select name="enclosure_id">
                <option value="">-- No Enclosure --</option>
                <?php foreach ($enclosures as $enc): ?>
                    <option value="<?= $enc['id'] ?>" <?= $animal['enclosure_id'] == $enc['id'] ? 'selected' : '' ?>><?= htmlspecialchars($enc['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Update New Image</label>
            <input type="file" name="image" accept="image/*">
            <small style="color:var(--text-muted);">Leave empty to keep current image</small>
        </div>
        
        <div class="form-group full-width">
            <label>Description / Notes</label>
            <textarea name="description" rows="4"><?= htmlspecialchars($animal['description'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group full-width" style="margin-top: 1rem;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
