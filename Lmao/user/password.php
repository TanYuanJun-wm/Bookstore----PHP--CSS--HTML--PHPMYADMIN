<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// Authenticated users
auth();

if (is_post()) {
    $Password     = req('Password');
    $new_Password = req('new_Password');
    $confirm      = req('confirm');

    // Validate: Password
    if ($Password == '') {
        $_err['Password'] = 'Required';
    }
    else if (strlen($Password) < 8 || strlen($Password) > 16) {
        $_err['Password'] = 'Between 8-16 characters';
    }
    else if (!preg_match('/[A-Z]/', $Password)) {
        $_err['Password'] = 'Must have one capital letters';
    }
    else {
        $stm = $_db->prepare('
            SELECT COUNT(*) FROM user
            WHERE Password = SHA1(?) AND User_ID = ?
        ');
        $stm->execute([$Password,$_user->User_ID]);
        
        if ($stm->fetchColumn() == 0) {
            $_err['Password'] = 'Invalid Password';
        }
    }

    // Validate: new_Password
    if ($new_Password == '') {
        $_err['new_Password'] = 'Required';
    }
    else if (strlen($Password) < 8 || strlen($Password) > 16) {
        $_err['Password'] = 'Between 8-16 characters.';
    }
    else if (!preg_match('/[A-Z]/', $Password)) {
        $_err['Password'] = 'Must have one capital letter.';
    }
    else if ($new_Password == $Password) {
        $_err['new_Password'] = 'Must be different from the old Password.';
    }

    // Validate: confirm
    if (!$confirm) {
        $_err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 8 || strlen($confirm) > 16) {
        $_err['confirm'] = 'Between 8-16 characters.';
    }
    else if ($confirm != $new_Password) {
        $_err['confirm'] = 'Not matched.';
    }

    // DB operation
    if (!$_err) {
        // Update user (Password)
        $stm = $_db->prepare('
            UPDATE user
            SET Password = SHA1(?)
            WHERE User_ID = ?
        ');
        $stm->execute([$new_Password,$_user->User_ID]);

        temp('info', 'Password updated');
        redirect('/');
    }
}

// ----------------------------------------------------------------------------

$pageTitle = 'Password Reset';
$_title = 'User | Password';
include '../_head.php';
?>

<div id=abc class=container>
<form method="post" class="form">
    <label for="Password">Password</label>
    <?= html_Password('Password', 'maxlength="100"') ?>
    <?= err('Password') ?>
    <br>

    <label for="new_Password">New Password</label>
    <?= html_Password('new_Password', 'maxlength="100"') ?>
    <?= err('new_Password') ?>
    <br>

    <label for="confirm">Confirm</label>
    <?= html_Password('confirm', 'maxlength="100"') ?>
    <?= err('confirm') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '../_foot.php';