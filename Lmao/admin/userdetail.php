<?php
include '../_base.php';

auth('Admin');

//-----------------------------------------------------------------------------

$User_ID = req('user_id');
$stm = $_db->prepare('SELECT * FROM user WHERE User_ID = ?');
$stm->execute([$User_ID]);
$s = $stm->fetch(PDO::FETCH_OBJ);

// ----------------------------------------------------------------------------
$pageTitle = 'User Detail';
$_title = 'User Detail';
include '../_head.php';
?>
<div class="container" id="abc">
    <div style="float: left; margin-right: 20px;">
        <img src="../photos/<?= htmlspecialchars($s->Photo) ?>" alt="User Photo" width="200">
    </div>
    <div style="overflow: hidden;"> <!-- Ensures content flows properly next to the image -->
        <p><strong>User ID:</strong> <?= htmlspecialchars($s->User_ID) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($s->Name) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($s->Role) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($s->Email) ?></p>
        <p><strong>Phone Number:</strong> <?= htmlspecialchars($s->Phone) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($s->Address) ?></p>
    </div>
    <?php if ($_user?->Role == 'Admin' && $s->Role != 'Admin'): ?>
        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button onclick="window.location.href='userlist.php'" class="btn btn-secondary">Return to User List</button>
            <button onclick="window.location.href='edituserphoto.php?user_id=<?= $s->User_ID ?>'" class="btn btn-warning">
                Edit Member's Profile Picture
            </button>
            <button onclick="window.location.href='order_list.php?user_id=<?= $s->User_ID ?>'" class="btn btn-primary">
                View Member's Order List
            </button>
        </div>
    <?php else: ?>
        <div style="margin-top: 20px;">
            <button onclick="window.location.href='userlist.php'" class="btn btn-secondary">Return to User List</button>
        </div>
    <?php endif ?>
</div>

<?php
include '../_foot.php';
?>
