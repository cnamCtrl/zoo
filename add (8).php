<?php
// staff/index.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

try {
    $staffQuery = "SELECT s.*, e.name as enclosure_name FROM staff s LEFT JOIN enclosures e ON s.assigned_enclosure_id = e.id ORDER BY s.id DESC";
    $staff = $pdo->query($staffQuery)->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="dash-header">
    <div>
        <h1>Staff Management</h1>
        <p style="color: var(--text-muted); margin-top: 0.25rem;">Manage zookeepers, vets, and personnel.</p>
    </div>
    <a href="add.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Staff</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Assigned Enclosure</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($staff)): ?>
            <tr><td colspan="5" style="text-align: center;">No staff found.</td></tr>
            <?php else: ?>
                <?php foreach ($staff as $person): ?>
                <tr>
                    <td><b><?= htmlspecialchars($person['first_name'] . ' ' . $person['last_name']) ?></b></td>
                    <td><?= htmlspecialchars($person['role_title']) ?></td>
                    <td><?= htmlspecialchars($person['email']) ?></td>
                    <td><?= htmlspecialchars($person['enclosure_name'] ?? 'None') ?></td>
                    <td class="action-btns">
                        <a href="javascript:void(0)" class="btn-icon btn-edit"><i class="fa-solid fa-pen"></i></a>
                        <?php if(isAdmin()): ?>
                            <a href="javascript:void(0)" class="btn-icon btn-delete"><i class="fa-solid fa-trash"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
