<?php
// includes/sidebar.php
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fa-solid fa-leaf"></i>
            <span>ZOO</span>Admin
        </div>
    </div>
    <ul class="nav-links">
        <li>
            <a href="/zoo_project/index.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'animals') === false) ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i>
                <span class="link-name">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/zoo_project/animals/index.php" class="<?= (strpos($_SERVER['REQUEST_URI'], '/animals/') !== false) ? 'active' : ''; ?>">
                <i class="fa-solid fa-paw"></i>
                <span class="link-name">Animals</span>
            </a>
        </li>
        <li>
            <a href="/zoo_project/enclosures/index.php" class="<?= (strpos($_SERVER['REQUEST_URI'], '/enclosures/') !== false) ? 'active' : ''; ?>">
                <i class="fa-solid fa-map-location-dot"></i>
                <span class="link-name">Enclosures</span>
            </a>
        </li>
        <li>
            <a href="/zoo_project/staff/index.php" class="<?= (strpos($_SERVER['REQUEST_URI'], '/staff/') !== false) ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i>
                <span class="link-name">Staff</span>
            </a>
        </li>
        <li>
            <a href="/zoo_project/feedings/index.php" class="<?= (strpos($_SERVER['REQUEST_URI'], '/feedings/') !== false) ? 'active' : ''; ?>">
                <i class="fa-solid fa-bone"></i>
                <span class="link-name">Feedings</span>
            </a>
        </li>
        <li>
            <a href="/zoo_project/medical/index.php" class="<?= (strpos($_SERVER['REQUEST_URI'], '/medical/') !== false) ? 'active' : ''; ?>">
                <i class="fa-solid fa-notes-medical"></i>
                <span class="link-name">Medical</span>
            </a>
        </li>
        <li class="logout-link">
            <a href="/zoo_project/auth/logout.php">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span class="link-name">Logout</span>
            </a>
        </li>
    </ul>
</aside>
<div class="main-content">
    <nav class="top-nav">
        <div class="search-box">
            <i class="fa-solid fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>
        <div class="profile-details">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username'] ?? 'User'); ?>&background=f472b6&color=fff" alt="profile">
            <span class="admin-name"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
        </div>
    </nav>
    <div class="content-wrapper">
