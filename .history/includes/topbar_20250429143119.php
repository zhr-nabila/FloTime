<!-- topbar.php -->
<div class="header">
    <div class="title">Dashboard</div>
    <div class="search-bar">
    <form method="GET" action="todo.php" class="d-flex" style="gap:10px;">
        <input type="text" name="search" class="form-control" placeholder="Search title..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        
        <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
            <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
        </select>

        <input type="date" name="deadline" class="form-control" value="<?= isset($_GET['deadline']) ? htmlspecialchars($_GET['deadline']) : '' ?>">

        <button type="submit" class="btn"><i class="bi bi-search"></i></button>
    </form>
</div>

    <div class="notifications">
        <i class="bi bi-bell"></i>
        <div class="date"><?= date('l, d F Y') ?></div>
    </div>
</div>

