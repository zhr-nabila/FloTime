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
  
</script>
