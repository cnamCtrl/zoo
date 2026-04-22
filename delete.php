<?php
// animals/add.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$error = '';
$success = '';

// Fetch enclosures for the dropdown
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
    
    // Handle Image Upload
    $image_url = 'default.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = uploadImage($_FILES['image']);
    }
    
    if (empty($name) || empty($species)) {
        $error = "Name and Species are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO animals (name, species, gender, date_of_birth, join_date, description, image_url, enclosure_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $species, $gender, $date_of_birth, $join_date, $description, $image_url, $enclosure_id, $status])) {
                $success = "Animal added successfully!";
            } else {
                $error = "Failed to add animal.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="dash-header">
    <div>
        <h1>Add New Animal</h1>
        <p style="color: var(--text-muted); margin-top: 0.25rem;">Register a new animal into the zoo system.</p>
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
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" required>
        </div>
        
        <div class="form-group">
            <label>Species *</label>
            <input type="text" name="species" required>
        </div>
        
        <div class="form-group">
            <label>Gender</label>
            <select name="gender">
                <option value="Unknown">Unknown</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Healthy">Healthy</option>
                <option value="Sick">Sick</option>
                <option value="In Treatment">In Treatment</option>
                <option value="Quarantine">Quarantine</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth">
        </div>
        
        <div class="form-group">
            <label>Join Date</label>
            <input type="date" name="join_date" value="<?= date('Y-m-d') ?>">
        </div>
        
        <div class="form-group">
            <label>Assign Enclosure</label>
            <select name="enclosure_id">
                <option value="">-- No Enclosure --</option>
                <?php foreach ($enclosures as $enc): ?>
                    <option value="<?= $enc['id'] ?>"><?= htmlspecialchars($enc['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Upload Image</label>
            <input type="file" name="image" accept="image/*">
        </div>
        
        <div class="form-group full-width">
            <label>Description / Notes</label>
            <textarea name="description" rows="4"></textarea>
        </div>
        
        <div class="form-group full-width" style="margin-top: 1rem;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Animal</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
