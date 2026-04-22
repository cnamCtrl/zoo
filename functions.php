<?php
// feedings/add.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$error = '';
$success = '';

// Fetch animals and staff for dropdowns
$animals = $pdo->query("SELECT id, name, species FROM animals ORDER BY name")->fetchAll();
$staff = $pdo->query("SELECT id, first_name, last_name, role_title FROM staff ORDER BY first_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id = $_POST['animal_id'];
    $staff_id = $_POST['staff_id'];
    $feed_time = $_POST['feed_time'];
    $food_type = trim($_POST['food_type']);
    $quantity = trim($_POST['quantity']);
    $status = $_POST['status'];

    if (empty($animal_id) || empty($staff_id) || empty($feed_time) || empty($food_type) || empty($quantity)) {
        $error = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO feedings (animal_id, staff_id, feed_time, food_type, quantity, status) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$animal_id, $staff_id, $feed_time, $food_type, $quantity, $status])) {
                $success = "Feeding schedule added successfully!";
            } else {
                $error = "Failed to schedule feeding.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="dash-header">
    <div>
        <h1>Schedule Feeding</h1>
        <p style="color: var(--text-muted); margin-top: 0.25rem;">Assign a feeding task to a staff member.</p>
    </div>
    <a href="index.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to List</a>
</div>

<div class="form-container">
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" action="" class="form-grid">
        <div class="form-group">
            <label>Select Animal *</label>
            <select name="animal_id" required>
                <option value="">-- Choose Animal --</option>
                <?php foreach ($animals as $a): ?>
                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name'] . ' (' . $a['species'] . ')') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Assign to Staff *</label>
            <select name="staff_id" required>
                <option value="">-- Choose Staff --</option>
                <?php foreach ($staff as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name'] . ' - ' . $s['role_title']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Feed Time *</label>
            <input type="datetime-local" name="feed_time" required>
        </div>
        
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Food Type *</label>
            <input type="text" name="food_type" placeholder="e.g. Raw Meat, Bamboo, Fish" required>
        </div>
        
        <div class="form-group">
            <label>Quantity *</label>
            <input type="text" name="quantity" placeholder="e.g. 5 lbs, 2 buckets" required>
        </div>
        
        <div class="form-group full-width" style="margin-top: 1rem;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Schedule</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
