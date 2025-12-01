<?php
require '../_base.php';

auth('Admin'); // Ensure the user is an admin

// ----------------------------------------------------------------------------
// (1) Parameters
$User_ID = req('User_ID');
$Name = req('Name');
$Email = req('Email');  
$Role = req('Role');
$sort = req('sort');
$dir = req('dir');
$page = req('page', 1);

$fields = [
    'User_ID' => 'User_ID',
    'Name'    => 'Name',
    'Email'   => 'Email',
    'Role'    => 'Role',
];

// Validate sorting
key_exists($sort, $fields) || $sort = 'User_ID';
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// -----------------------------------------------------------------------------
// (2) Where Clause
$where = "WHERE 1";
$params = [];

if ($Name) {
    $where .= " AND Name LIKE ?";
    $params[] = "%$Name%";
}

if ($User_ID) {
    $where .= " AND User_ID = ?";
    $params[] = $User_ID;
}

if ($Role) {
    $where .= " AND Role = ?";
    $params[] = $Role;
}

// -----------------------------------------------------------------------------
// (3) Paging with Filtering + Sorting
require_once '../lib/SimplePager.php';
$query = "SELECT * FROM user $where ORDER BY $sort $dir";
$p = new SimplePager($query, $params, 2, $page);
$arr = $p->result;



// ----------------------------------------------------------------------------
$pageTitle = 'Member List';
$_title = 'Member List';
include '../_head.php';
?>

<div class="container">
    <div id="abc">
        <form>
            <input type="text" name="Name" value="<?= htmlspecialchars($Name) ?>" placeholder="Search Name">
            <select name="Role">
                <option value="">Select Roles</option>
                <option value="Admin" <?= $Role == 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Member" <?= $Role == 'Member' ? 'selected' : '' ?>>Member</option>
            </select>
            <button>Search</button>
        </form>
        <p><?= $p->count ?> of <?= $p->item_count ?> record(s) | Page <?= $p->page ?> of <?= $p->page_count ?></p>
        <table class="table">
            <tr>
                <th>#</th>
                <?= table_headers($fields, $sort, $dir, "Name=$Name&User_ID=$User_ID&Role=$Role&page=$page") ?>
            </tr>
            <?php 
            $counter = ($p->page - 1) * $p->limit + 1; // Calculate starting number for the current page
            foreach ($arr as $s): 
            ?>
            <tr onclick="window.location.href='userdetail.php?user_id=<?= $s->User_ID ?>'">
                <td><?= $counter++ ?></td> <!-- Display the row number -->
                <td><?= $s->User_ID ?></td>
                <td><?= $s->Name ?></td>
                <td><?= $s->Email ?></td>
                <td><?= $s->Role ?>
                <img src="/photos/<?= $s->Photo ?>" class="popup" alt="User photo">
                </td>
            </tr>
            <?php endforeach ?>
        </table>
        <?= $p->html("Name=$Name&User_ID=$User_ID&Role=$Role&sort=$sort&dir=$dir") ?>
    </div>
</div>

<?php
include '../_foot.php';
?>
