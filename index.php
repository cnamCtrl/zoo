<?php
// staff/add.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$error = '';
$success = '';

// Fetch enclosures for the dropdown
$enclosures = $pdo->query("SELECT id, name FROM enclosures ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $role_title = trim($_POST['role_title']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $assigned_enclosure_id = $_POST['assigned_enclosure_id'] ?: null;
    $hire_date = $_POST['hire_date'] ?: date('Y-m-d');
    
    if (empty($first_name) || empty($last_name) || empty($role_title) || empty($email)) {
        $error = "First Name, Last Name, Role, and Email are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO staff (first_name, last_name, role_title, email, phone, assigned_enclosure_id, hire_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$first_name, $last_name, $role_title, $email, $phone, $assigned_enclosure_id, $hire_date])) {
                $success = "Staff member added successfully!";
            } else {
                $error = "Failed to add staff member.";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation (duplicate email)
                $error = "Error: A staff member with that email already exists.";
            } else {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="dash-header">
    <div>
        <h1>Add New Staff Member</h1>
        <p style="color: var(--text-muted); margin-top: 0.25rem;">Register a new employee, zookeeper, or veterinarian.</p>
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

    <form method="POST" action="" class="form-grid">
        <div class="form-group">
            <label>First Name *</label>
            <input type="text" name="first_name" required>
        </div>
        
        <div class="form-group">
            <label>Last Name *</label>
            <input type="text" name="last_name" required>
        </div>
        
        <div class="form-group">
            <label>Role / Title *</label>
            <input type="text" name="role_title" placeholder="e.g. Veterinarian, Guide" required>
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone">
        </div>
        
        <div class="form-group">
            <label>Hire Date</label>
            <input type="date" name="hire_date" value="<?= date('Y-m-d') ?>">
        </div>
        
        <div class="form-group full-width">
            <label>Assign Enclosure Sub-zone (Optional)</label>
            <select name="assigned_enclosure_id">
                <option value="">-- No Enclosure Assigned --</option>
                <?php foreach ($enclosures as $enc): ?>
                    <option value="<?= $enc['id'] ?>"><?= htmlspecialchars($enc['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group full-width" style="margin-top: 1rem;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Staff Member</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
