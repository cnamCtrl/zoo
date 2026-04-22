<?php
// feedings/index.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

try {
    $feedingsQuery = "
        SELECT f.*, a.name as animal_name, CONCAT(s.first_name, ' ', s.last_name) as staff_name 
        FROM feedings f 
        JOIN animals a ON f.animal_id = a.id 
        JOIN staff s ON f.staff_id = s.id 
        ORDER BY f.feed_time DESC";
    $feedings = $pdo->query($feedingsQuery)->fetchAll();
} catch (PDOException $e) {
    if ($e->getCode() == '42S02') { // table not found / empty handling wrapper just in case
        $feedings = [];
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>

<div class="dash-header">
    <div>
        <h1>Feeding Schedules</h1>
        <p style="color: var(--text-muted); margin-top: 0.25rem;">Monitor and assign feeding schedules.</p>
    </div>
    <a href="add.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Schedule Feeding</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Animal</th>
                <th>Food</th>
                <th>Assigned To</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($feedings)): ?>
            <tr><td colspan="5" style="text-align: center;">No feeding schedules found.</td></tr>
            <?php else: ?>
                <?php foreach ($feedings as $f): ?>
                <tr>
                    <td><?= formatDate($f['feed_time']) . ' ' . date('H:i', strtotime($f['feed_time'])) ?></td>
                    <td><b><?= htmlspecialchars($f['animal_name']) ?></b></td>
                    <td><?= htmlspecialchars($f['quantity']) ?> <?= htmlspecialchars($f['food_type']) ?></td>
                    <td><?= htmlspecialchars($f['staff_name']) ?></td>
                    <td>
                        <span class="badge <?= $f['status'] === 'Completed' ? 'badge-healthy' : 'badge-sick' ?>">
                            <?= htmlspecialchars($f['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
