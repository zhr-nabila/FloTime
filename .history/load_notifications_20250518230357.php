<?php
include 'koneksi.php';
session_start();
$user_id = $_SESSION['user_id'];

$notif = mysqli_query($conn, "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 10");

if (mysqli_num_rows($notif) == 0) {
    echo "<div class='notif-item'>Tidak ada notifikasi</div>";
} else {
    while ($n = mysqli_fetch_assoc($notif)) {
        $readClass = $n['is_read'] ? 'read' : 'unread';
        echo "<div class='notif-item $readClass' onclick=\"markRead({$n['id']})\">".htmlspecialchars($n['message'])."</div>";
    }
}
?>

<script>
function markRead(id) {
  fetch('mark_read.php?id=' + id).then(() => {
    document.getElementById('notifBell').click(); // reload notif
  });
}
</script>
