<?php
$conn = mysqli_connect("localhost", "root", "", "db_simak_smk");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "=== PRESENSI_SISWA COLUMNS ===\n";
$result = mysqli_query($conn, "DESCRIBE presensi_siswa");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " | " . $row['Type'] . "\n";
}

echo "\n=== SISWA COLUMNS ===\n";
$result = mysqli_query($conn, "DESCRIBE siswa");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " | " . $row['Type'] . "\n";
}

echo "\n=== USERS COLUMNS ===\n";
$result = mysqli_query($conn, "DESCRIBE users");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " | " . $row['Type'] . "\n";
}

mysqli_close($conn);
?>
