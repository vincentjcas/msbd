<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=db_simak_smk', 'root', '');
    
    echo "=== Checking Kepala Sekolah Account ===\n";
    $stmt = $pdo->query('SELECT * FROM users WHERE role="kepala_sekolah" LIMIT 1');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($result) {
        echo "✓ Akun ditemukan:\n";
        echo "Username: " . $result['username'] . "\n";
        echo "Email: " . $result['email'] . "\n";
        echo "Nama: " . $result['nama_lengkap'] . "\n";
        echo "Status: " . ($result['status_aktif'] ? 'Aktif' : 'Nonaktif') . "\n";
    } else {
        echo "✗ Akun tidak ditemukan, membuat akun baru...\n\n";
        
        $hash = password_hash('password123', PASSWORD_BCRYPT);
        $insert = $pdo->prepare('INSERT INTO users (username, email, password, role, nama_lengkap, no_hp, status_aktif, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
        $insert->execute(['kepsek', 'kepsek@yapim.com', $hash, 'kepala_sekolah', 'Kepala Sekolah YAPIM', '081234567890', 1]);
        
        echo "✓ Akun berhasil dibuat!\n\n";
        echo "Login Credentials:\n";
        echo "─────────────────────────────\n";
        echo "Email:    kepsek@yapim.com\n";
        echo "Password: password123\n";
        echo "─────────────────────────────\n";
    }
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
