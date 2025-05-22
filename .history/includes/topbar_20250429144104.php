<!-- topbar.php -->
<div class="header">
    <div class="title"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Untitled'; ?></div>

    <div class="search-bar">
    <form method="GET" action="todo.php" class="d-flex">
        <input type="text" name="search" class="form-control" placeholder="Search task..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit" class="btn"><i class="bi bi-search"></i></button>
    </form>
</div>

    <div class="notifications">
        <i class="bi bi-bell"></i>
        <div class="date"><?= date('l, d F Y') ?></div>
    </div>
</div>

