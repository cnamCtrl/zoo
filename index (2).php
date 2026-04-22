<?php
// enclosures/index.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

try {
    $enclosures = $pdo->query("SELECT * FROM enclosures ORDER BY id DESC")->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="dash-header">
    <div>
        <h1>Enclosures Management</h1>
        <p style="color: var(--text-muted); margin-top: 0.25rem;">Manage the different habitats in the zoo.</p>
    </div>
    <a href="add.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Enclosure</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($enclosures)): ?>
            <tr><td colspan="5" style="text-align: center;">No closures found.</td></tr>
            <?php else: ?>
                <?php foreach ($enclosures as $enc): ?>
                <tr>
                    <td><?= $enc['id'] ?></td>
                    <td><b><?= htmlspecialchars($enc['name']) ?></b></td>
                    <td><?= htmlspecialchars($enc['type']) ?></td>
                    <td><?= $enc['capacity'] ?> animals</td>
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
