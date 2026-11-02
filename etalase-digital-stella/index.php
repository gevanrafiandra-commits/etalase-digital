<?php
require_once 'config/kuis_db.php';
require_once 'middleware/auth_check.php';

check_login();

// Ambil semua karya beserta nama pembuat
$stmt = $pdo->query("SELECT projects.*, users.nama AS pembuat 
                     FROM projects 
                     JOIN users ON projects.user_id = users.id 
                     ORDER BY projects.created_at DESC");
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Etalase Digital Stella</title>
    <link rel="stylesheet" href="assets/css/style.php.css?v=red-theme">
    <link rel="stylesheet" href="assets/css/main.css">

</head>
<body>
<div class="header">
    <h2>Etalase Digital Stella</h2>
    <div>
        Halo, <b><?= htmlspecialchars($_SESSION['nama']) ?></b> (<?= $_SESSION['role'] ?>) | 
        <a href="project_tambah.php" class="btn btn-blue">+ Tambah Karya</a>
        <?php if ($_SESSION['role'] === 'super_admin'): ?>
            <a href="admin/users.php" class="btn btn-warning">Kelola Users</a>
        <?php endif; ?>
        <a href="auth/logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<div class="grid">
    <?php foreach($projects as $p): ?>
        <div class="card">
            <img src="uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="Karya">
            <div class="card-body">
                <h3><?= htmlspecialchars($p['nama_project']) ?></h3>
                <p><small>Oleh: <b><?= htmlspecialchars($p['pembuat']) ?></b></small></p>
                <p><?= htmlspecialchars($p['deskripsi']) ?></p>
                <p><a href="<?= htmlspecialchars($p['link_project']) ?>" target="_blank">Lihat Project Tautan &rarr;</a></p>
                
                <!-- Hak Akses Edit/Hapus: Owner / Admin / Super Admin -->
                <?php if($_SESSION['role'] === 'super_admin' || $_SESSION['role'] === 'admin' || $_SESSION['user_id'] == $p['user_id']): ?>
                    <a href="project_edit.php?id=<?= $p['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="project_hapus.php?id=<?= $p['id'] ?>" onclick="return confirm('Hapus karya ini?')" class="btn btn-danger">Hapus</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
