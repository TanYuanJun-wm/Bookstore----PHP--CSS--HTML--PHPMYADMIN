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
    'Book_Title'       => 'Book Title',
    'Genre'          => 'Genre',
    'Description' => 'Description',
    'Price'         => 'Price',
    'Photo'         => 'Photo',
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
$p = new SimplePager($query, $params, 5, $page);
$arr = $p->result;

// -----------------------------------------------------------------------------
// (4) HTML
$_title = 'Page | Menu';
include '../_head.php';
?>
<div class="container">
    <div id="abc">
        <h1 id="text">Books</h1>
        <form style="display: flex; align-items: center; gap: 10px;">
        <?= html_search('Book_Title',"placeholder='Search'") ?>
            <select name="Genre">
                <option value="">All Genre</option>
                <option value="FANTASY" <?= $Genre == 'FANTASY' ? 'selected' : '' ?>>Fantasy</option>
                <option value="EDUCATION" <?= $Genre == 'EDUCATION' ? 'selected' : '' ?>>Education</option>
                <option value="HUMOR" <?= $Genre == 'HUMOR' ? 'selected' : '' ?>>Humor</option>
                <option value="ADVENTURE" <?= $Genre == 'ADVENTURE' ? 'selected' : '' ?>>Adventure</option>
                <option value="HISTORY" <?= $Genre == 'HISTORY' ? 'selected' : '' ?>>History</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
            <!-- Add Book Button -->
            <button type="button" onclick="window.location.href='addBook.php'" class="btn btn-success">Add Book</button>
        </form>
        <div style="margin-top: 20px;">
            <?= $p->count ?> of <?= $p->item_count ?> record(s) |
            Page <?= $p->page ?> of <?= $p->page_count ?>
        </div>
        <table class="table">
            <tr>
                <th>#</th>
                <?= table_headers($fields, $sort, $dir, "Book_Title=$Book_Title&Book_ID=$Book_ID&Genre=$Genre&page=$page") ?>
                <th>Actions</th>
            </tr>
            <?php 
            $counter = ($p->page - 1) * $p->limit + 1;
            foreach ($arr as $s): 
            ?>
            <tr>
                <td><?= $counter++ ?></td>
                <td><?= $s->Book_ID ?></td>
                <td><?= $s->Book_Title ?></td>
                <td><?= $s->Genre ?></td>
                <td class="multi-ellipsis"><?= $s->Description ?></td>
                <td><?= $s->Price ?></td>
                <td>
                    <img src="../bookPhoto/<?= htmlspecialchars($s->Photo) ?>" alt="Book Cover" width="100">
                </td>
                <td>
                    <button onclick="window.location.href='editBook.php?Book_ID=<?= $s->Book_ID ?>'" class="btn btn-primary">Edit</button>
                    <form action="deleteBook.php" method="post" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                        <input type="hidden" name="Book_ID" value="<?= $s->Book_ID ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach ?>
        </table>
        <?= $p->html("Book_Title=$Book_Title&Book_ID=$Book_ID&Genre=$Genre&sort=$sort&dir=$dir") ?>
    </div>
</div>

    <?php if ($message = temp('info')): ?>
    <div id="info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
<?php
include '../_foot.php'; 
?>