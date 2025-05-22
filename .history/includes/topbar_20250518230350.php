<div class="header">
    <div class="title"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Untitled'; ?></div>
    <div class="search-bar">
        <form method="GET" action="todo.php" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Search task..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit" class="btn"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <?php
include 'koneksi.php';
session_start();
$user_id = $_SESSION['user_id'];

// Hitung notif yang belum dibaca
$notif_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM notifications WHERE user_id = $user_id AND is_read = 0"));
?>


    <div class="notifications" id="notifWrapper" style="position: relative;">
    <div style="position: relative;">
        <i class="bi bi-bell" id="notifIcon"></i>
        <span id="notifCount" class="notif-badge">0</span>

        <div id="notifDropdown" class="notif-dropdown">
            <div class="notif-header">Notifikasi</div>
            <div id="notifList">
                <div class="notif-item">Memuat notifikasi...</div>
            </div>
            <div class="notif-footer">
                <a href="#" onclick="markAllRead()">Tandai semua telah dibaca</a>
            </div>
        </div>
    </div>
    <div class="date"><?= date('l, d F Y') ?></div>
</div>



</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const bell = document.getElementById("notifBell");
  const box = document.getElementById("notifBox");

  bell.addEventListener("click", () => {
    fetch("load_notifications.php")
      .then(res => res.text())
      .then(data => {
        box.innerHTML = data;
        box.style.display = box.style.display === "none" ? "block" : "none";
      });
  });

  document.addEventListener("click", (e) => {
    if (!bell.contains(e.target) && !box.contains(e.target)) {
      box.style.display = "none";
    }
  });
});
</script>
