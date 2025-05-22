<div class="header">
    <div class="title"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Untitled'; ?></div>
    <div class="search-bar">
        <form method="GET" action="todo.php" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Search task..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit" class="btn"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="notifications dropdown" id="notifWrapper" style="position: relative;">
    <a href="#" id="notifIcon" class="nav-link position-relative">
        <i class="bi bi-bell" style="font-size: 1.4rem;"></i>
        <span id="notifCount" class="badge bg-danger position-absolute top-0 start-100 translate-middle">0</span>
    </a>

    <div id="notifDropdown" class="dropdown-menu dropdown-menu-end p-2" style="width: 300px;">
        <h6 class="dropdown-header">Notifikasi</h6>
        <div id="notifList">
            <div class="notif-item">Memuat notifikasi...</div>
        </div>
        <div class="dropdown-footer text-center mt-2">
            <a href="#" onclick="markAllRead()">Tandai semua telah dibaca</a>
        </div>
    </div>
</div>
<div class="date"><?= date('l, d F Y') ?></div>

</div>

<script>
  const notifIcon = document.getElementById("notifIcon");
  const notifDropdown = document.getElementById("notifDropdown");
  const notifCount = document.getElementById("notifCount");
  const notifList = document.getElementById("notifList");

  notifIcon.addEventListener("click", (e) => {
    e.preventDefault();
    notifDropdown.style.display = notifDropdown.style.display === "block" ? "none" : "block";
    loadNotifications();
  });

  function loadNotifications() {
    fetch('load_notifications.php')
      .then(res => res.json())
      .then(data => {
        notifList.innerHTML = "";
        if (data.length === 0) {
          notifList.innerHTML = `<div class="notif-item text-muted">Tidak ada notifikasi.</div>`;
          notifCount.style.display = "none";
        } else {
          notifCount.textContent = data.length;
          notifCount.style.display = "inline-block";
          data.forEach(notif => {
            const item = document.createElement("div");
            item.className = "notif-item";
            item.textContent = notif.text;
            notifList.appendChild(item);
          });
        }
      });
  }

  function markAllRead() {
    fetch('mark_read.php')
      .then(() => {
        notifCount.textContent = "0";
        notifCount.style.display = "none";
        notifList.innerHTML = `<div class="notif-item text-muted">Tidak ada notifikasi.</div>`;
        notifDropdown.style.display = "none";
      });
  }

  // Tutup dropdown kalau klik di luar area
  window.addEventListener("click", function (e) {
    if (!document.getElementById("notifWrapper").contains(e.target)) {
      notifDropdown.style.display = "none";
    }
  });
</script>
