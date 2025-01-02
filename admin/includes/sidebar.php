<div class="sidebar">
    <div class="sidebar-header">
        <img src="../images/Appolice.png" alt="Logo" class="sidebar-logo">
        <h3>Admin Panel</h3>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="news.php"><i class="fas fa-newspaper"></i> News</a></li>
            <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
            <li><a href="initiatives.php"><i class="fas fa-project-diagram"></i> Initiatives</a></li>
            <li><a href="stations.php"><i class="fas fa-building"></i> Police Stations</a></li>
            <li><a href="contacts.php"><i class="fas fa-phone"></i> Emergency Contacts</a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
