<div class="header">
  <div class="title"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Untitled'; ?></div>
  <div class="search-bar">
    <form method="GET" action="todo.php" class="d-flex">
      <input type="text" name="search" class="form-control" placeholder="Search task..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
      <button type="submit" class="btn"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <div class="notifications" id="notifWrapper" style="position: relative;">
    <div style="position: relative;">
      <i class="bi bi-bell" id="notifIcon"></i>
      <span id="notifCount" class="notif-badge">0</span>

      <div id="notifDropdown" class="notif-dropdown" style="display: none;">
        <div class="notif-header">Notifikasi</div>
        <div id="notifList">
          <div class="notif-item">Memuat notifikasi...</div>
        </div>
        <div class="notif-footer">
          <a href="#" onclick="markAllRead()">Tandai semua telah dibaca</a>
        </div>
      </div>
    </div>
    <div class="date" id="dateText"></div>
  </div>


</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const bell = document.getElementById("notifIcon");
    const dropdown = document.getElementById("notifDropdown");
    const count = document.getElementById("notifCount");
    const list = document.getElementById("notifList");

    bell.addEventListener("click", () => {
      dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", (e) => {
      if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = "none";
      }
    });

    // Load notifikasi dan jumlah
    function loadNotifications() {
      fetch("notif_api.php")
        .then(res => res.json())
        .then(data => {
          count.textContent = data.unread > 0 ? data.unread : 0;
          list.innerHTML = "";

          if (data.notifications.length > 0) {
            data.notifications.forEach(notif => {
              const item = document.createElement("div");
              item.className = "notif-item" + (notif.is_read ? " read" : " unread");
              item.innerText = notif.message;
              item.onclick = () => markRead(notif.id);
              list.appendChild(item);
            });

          } else {
            list.innerHTML = "<div class='notif-item'>Tidak ada notifikasi.</div>";
          }
        });
    }

    window.markAllRead = () => {
      fetch("mark_all_read.php")
        .then(() => loadNotifications());
    };

    // Load awal
    loadNotifications();

    // Auto refresh setiap 60 detik
    setInterval(loadNotifications, 60000);

    // Tampilkan tanggal
    document.getElementById("dateText").textContent = new Date().toLocaleDateString("id-ID", {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric"
    });
  });
</script>