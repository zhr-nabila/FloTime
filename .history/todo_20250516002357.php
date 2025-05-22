<?php if ($result->num_rows > 0): ?>
    <div class="table-responsive shadow-sm rounded-4 overflow-hidden">
        <table class="table table-hover align-middle mb-0 premium-table">
            <thead class="table-light text-center">
                <tr>
                    <th>Judul</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="<?= $row['status'] == 'completed' ? 'completed-task' : '' ?>">
                        <td class="<?= $row['status'] == 'completed' ? 'text-decoration-line-through' : '' ?>">
                            <?= htmlspecialchars($row['title']) ?>
                        </td>
                        <td class="<?= $row['status'] == 'completed' ? 'text-decoration-line-through' : '' ?>">
                            <?= htmlspecialchars($row['deadline']) ?>
                        </td>
                        <td class="text-center">
                            <?php
                            if ($row['status'] == 'completed') {
                                echo "<span class='badge rounded-pill text-bg-success px-3 py-2'>Selesai</span>";
                            } elseif (date('Y-m-d') > $row['deadline']) {
                                echo "<span class='badge rounded-pill text-bg-danger px-3 py-2'>Terlambat</span>";
                            } else {
                                echo "<span class='badge rounded-pill text-bg-warning text-dark px-3 py-2'>Belum Selesai</span>";
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['status'] == 'completed'): ?>
                                <a href="?undo=<?= $row['id'] ?>" class="btn btn-icon btn-outline-secondary btn-sm me-1" title="Batalkan status">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            <?php else: ?>
                                <a href="?done=<?= $row['id'] ?>" class="btn btn-icon btn-outline-success btn-sm me-1" title="Tandai selesai">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                            <?php endif; ?>
                            <a href="edit_task.php?id=<?= $row['id'] ?>" class="btn btn-icon btn-outline-warning btn-sm me-1" title="Edit tugas">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus tugas ini?')" class="btn btn-icon btn-outline-danger btn-sm" title="Hapus tugas">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-muted mt-4 text-center">✨ Tidak ada tugas ditemukan. Buat yang baru, yuk!</p>
<?php endif; ?>