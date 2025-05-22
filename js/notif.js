document.addEventListener("DOMContentLoaded", () => {
    const bell = document.getElementById("notifIcon");
    const dropdown = document.getElementById("notifDropdown");
    const count = document.getElementById("notifCount");
    const list = document.getElementById("notifList");
    const dateText = document.getElementById("dateText");

    // Tampilkan/Hide dropdown notifikasi saat ikon diklik
    bell.addEventListener("click", () => {
      dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    });

    // Klik di luar dropdown menutup dropdown
    document.addEventListener("click", (e) => {
      if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = "none";
      }
    });

    // Fungsi untuk mengambil notifikasi dari server
    function loadNotifications() {
      fetch("notif_api.php")
        .then(res => res.json())
        .then(data => {
          // Update jumlah notifikasi
          count.textContent = data.unread > 0 ? data.unread : 0;
          count.style.display = data.unread > 0 ? "inline-block" : "none";

          // Kosongkan list
          list.innerHTML = "";

          // Tampilkan notifikasi
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
        })
        .catch(error => {
          console.error("Gagal memuat notifikasi:", error);
          list.innerHTML = "<div class='notif-item'>Gagal memuat notifikasi.</div>";
        });
    }

    // Fungsi untuk menandai 1 notifikasi sebagai dibaca
    function markRead(id) {
      fetch("mark_read.php?id=" + id)
        .then(() => loadNotifications())
        .catch(error => console.error("Gagal menandai notifikasi:", error));
    }

    // Fungsi untuk menandai semua notifikasi telah dibaca
    window.markAllRead = () => {
      fetch("mark_all_read.php")
        .then(() => loadNotifications())
        .catch(error => console.error("Gagal menandai semua notifikasi:", error));
    };

    // Load awal notifikasi
    loadNotifications();

    // Auto refresh setiap 60 detik
    setInterval(loadNotifications, 60000);

    // Tampilkan tanggal hari ini
    dateText.textContent = new Date().toLocaleDateString("id-ID", {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric"
    });
  });