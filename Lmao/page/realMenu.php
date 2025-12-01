<?php
require '../_base.php';

// -----------------------------------------------------------------------------
// (1) Parameters
$Book_Title = req('Book_Title');
$Book_ID = req('Book_ID');
$Genre = req('Genre');
$sort = req('sort');
$dir = req('dir');
$page = req('page', 1);

$fields = [
    'Book_ID'         => 'Id',
    'Book_Title'      => 'Book Title',
    'Genre'           => 'Genre',
    'Description'     => 'Description',
    'Price'           => 'Price',
    'Photo'           => 'Photo',
];

// Validate sorting
key_exists($sort, $fields) || $sort = 'Book_ID';
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// -----------------------------------------------------------------------------
// (2) Where Clause
$where = "WHERE 1";
$params = [];

if ($Book_Title) {
    $where .= " AND Book_Title LIKE ?";
    $params[] = "%$Book_Title%";
}

if ($Book_ID) {
    $where .= " AND Book_ID = ?";
    $params[] = $Book_ID;
}

if ($Genre) {
    $where .= " AND Genre = ?";
    $params[] = $Genre;
}

// -----------------------------------------------------------------------------
// (3) Paging with Filtering + Sorting
require_once '../lib/SimplePager.php';
$query = "SELECT * FROM books $where ORDER BY $sort $dir";
$p = new SimplePager($query, $params, 8, $page);
$arr = $p->result;

// -----------------------------------------------------------------------------
// (4) HTML
$pageTitle = "Menu";    
$_title = 'Menu';
include '../_head.php';
?>

<div class="container" id = "abc">
    <form>
        <?= html_search('Book_Title',"placeholder='Search'") ?>
        <select name="Genre">
            <option value="">All Genre</option>
            <option value="FANTASY" <?= $Genre == 'FANTASY' ? 'selected' : '' ?>>Fantasy</option>
            <option value="EDUCATION" <?= $Genre == 'EDUCATION' ? 'selected' : '' ?>>Education</option>
            <option value="HUMOR" <?= $Genre == 'HUMOR' ? 'selected' : '' ?>>Humor</option>
            <option value="ADVENTURE" <?= $Genre == 'ADVENTURE' ? 'selected' : '' ?>>Adventure</option>
            <option value="HISTORY" <?= $Genre == 'HISTORY' ? 'selected' : '' ?>>History</option>
        </select>
        <button>Search</button>
    </form>

    <div class="catalogue">
        <?php foreach ($arr as $s): ?>
            <div class="catalogue-item">
                <a href="viewBook.php?id=<?= $s->Book_ID ?>">
                    <img src="../bookPhoto/<?= htmlspecialchars($s->Photo) ?>" alt="Book Cover" class="book-image">
                    <h3><?= htmlspecialchars($s->Book_Title) ?></h3>
                    <p><?= htmlspecialchars($s->Genre) ?></p>
                    <p>$<?= number_format($s->Price, 2) ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?= $p->html("Book_Title=$Book_Title&Book_ID=$Book_ID&Genre=$Genre&sort=$sort&dir=$dir") ?>
    </div>
</div>

<?php include '../_foot.php'; ?>
