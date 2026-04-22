<?php
// index.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

// Fetch summary stats for the dashboard
try {
    $animal_count = $pdo->query("SELECT COUNT(*) FROM animals")->fetchColumn();
    $staff_count = $pdo->query("SELECT COUNT(*) FROM staff")->fetchColumn();
    $enclosure_count = $pdo->query("SELECT COUNT(*) FROM enclosures")->fetchColumn();
    $pending_feedings = $pdo->query("SELECT COUNT(*) FROM feedings WHERE status = 'Pending'")->fetchColumn();
    
    // Recent animals
    $recent_animals = $pdo->query("SELECT name, species, status FROM animals ORDER BY id DESC LIMIT 5")->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching data: " . $e->getMessage();
}
?>

<div class="dash-header">
    <div>
        <h1>Dashboard Overview</h1>
        <p style="color: var(--text-muted); margin-top: 0.25rem;">Welcome back, <b><?= htmlspecialchars($_SESSION['username']) ?></b>. Here's what's happening at the zoo today.</p>
    </div>
    <a href="/zoo_project/animals/add.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Animal</a>
</div>

<div class="overview-cards">
    <div class="card">
        <div class="card-icon pink">
            <i class="fa-solid fa-paw"></i>
        </div>
        <div class="card-info">
            <h3>Total Animals</h3>
            <h2><?= number_format($animal_count) ?></h2>
        </div>
    </div>
    <div class="card">
        <div class="card-icon orange">
            <i class="fa-solid fa-map-location-dot"></i>
        </div>
        <div class="card-info">
            <h3>Enclosures</h3>
            <h2><?= number_format($enclosure_count) ?></h2>
        </div>
    </div>
    <div class="card">
        <div class="card-icon green">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="card-info">
            <h3>Staff Members</h3>
            <h2><?= number_format($staff_count) ?></h2>
        </div>
    </div>
    <div class="card">
        <div class="card-icon blue">
            <i class="fa-solid fa-bone"></i>
        </div>
        <div class="card-info">
            <h3>Pending Feedings</h3>
            <h2><?= number_format($pending_feedings) ?></h2>
        </div>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <h2>Recently Added Animals</h2>
        <a href="/zoo_project/animals/index.php" class="btn btn-outline" style="padding: 0.5rem 1rem;">View All</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Species</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recent_animals)): ?>
            <tr>
                <td colspan="3" style="text-align: center; padding: 2rem;">No animals found. Start by adding some!</td>
            </tr>
            <?php else: ?>
                <?php foreach ($recent_animals as $animal): ?>
                <tr>
                    <td><b><?= htmlspecialchars($animal['name']) ?></b></td>
                    <td><?= htmlspecialchars($animal['species']) ?></td>
                    <td>
                        <span class="badge <?= $animal['status'] === 'Healthy' ? 'badge-healthy' : 'badge-sick' ?>">
                            <?= htmlspecialchars($animal['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
