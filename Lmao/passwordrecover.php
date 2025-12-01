<?php
include '_base.php';

$Role = '';

// ----------------------------------------------------------------------------

if (is_post()) {

    $Email        = req('Email');
    $Movie        = req('Movie');
    $new_Password = req('new_Password');
    $confirm      = req('confirm');
    $Role         = req('Role');

    // Validate: Email
    if ($Email == '') {
        $_err['Email'] = 'Required';
    } else if (!is_Email($Email)) {
        $_err['Email'] = 'Invalid Email';
    } else {
    // Check if the email exists in the database
        $stm = $_db->prepare('SELECT COUNT(*) FROM user WHERE Email = ?');
        $stm->execute([$Email]);
        $emailExists = $stm->fetchColumn();

        if (!$emailExists) {
            $_err['Email'] = 'Email does not exist.';
        }
    }

    // Validate: Movie
    if ($Movie == '') {
        $_err['Movie'] = 'Required';
    }
    else {
        $stm = $_db->prepare('SELECT COUNT(*) FROM user WHERE Email = ? AND Movie = ?');
        $stm->execute([$Email, $Movie]);
        $movieMatches = $stm->fetchColumn();

        if (!$movieMatches) {
            $_err['Movie'] = 'The movie does not match our records.';
        }
    }

    // Validate: new_Password
    if ($new_Password == '') {
        $_err['new_Password'] = 'Required';
    }
    else if (strlen($new_Password) < 8 || strlen($new_Password) > 16) {
        $_err['new_Password'] = 'Between 8-16 characters.';
    }
    else if (!preg_match('/[A-Z]/', $new_Password)) {
        $_err['new_Password'] = 'Must have one capital letter.';
    }
    
    // Validate: confirm
    if (!$confirm) {
        $_err['confirm'] = 'Required';
    } 
    else if ($confirm != $new_Password) {
        $_err['confirm'] = 'Not matched.';
    }
    
    // Validate: Role
    if (empty($Role)) {
        $_err['Role'] = 'Required';
    } else if (!in_array($Role, ['Admin', 'Member'])) {
        $_err['Role'] = 'Invalid role selected';
    }

    // DB operation
    if (!$_err) {
        // Fetch User_ID based on Email and Role
        $stm = $_db->prepare('
            SELECT User_ID FROM user
            WHERE Email = ? AND Role = ?
        ');
    $stm->execute([$Email, $Role]);
    $user = $stm->fetch();

    if ($user) {
        // Update password if everything matches
        $stm = $_db->prepare('UPDATE user SET Password = SHA1(?) WHERE User_ID = ?');
        $stm->execute([$new_Password, $user->User_ID]);
        
        temp('info', 'Password changed');
        redirect('/login.php');
    } else {
        // Email exists but role is wrong
        $stm = $_db->prepare('SELECT Role FROM user WHERE Email = ?');
        $stm->execute([$Email]);
        $actualRole = $stm->fetchColumn();
        
            if ($actualRole) {
                $_err['Role'] = "You are not " . lcfirst($Role) . ". Please try again.";
            }
        }
    }
}

// ----------------------------------------------------------------------------

$_title = 'Recover Password';
include '_head.php';
?>

<div id=abc class=container>
<form method="post" class="form" enctype="multipart/form-data">
    <label for="Email">Email</label>
    <?= html_text('Email', 'maxlength="100"') ?>
    <?= err('Email') ?>
    <br>
    
    <label for="Movie">What's your favourite movie?</label>
    <?= html_text('Movie', 'maxlength="100"') ?>
    <?= err('Movie') ?>
    <br>

    <label for="new_Password">New Password</label>
    <?= html_Password('new_Password', 'maxlength="100"') ?>
    <?= err('new_Password') ?>
    <br>

    <label for="confirm">Confirm</label>
    <?= html_Password('confirm', 'maxlength="100"') ?>
    <?= err('confirm') ?>
<br>
    <label for="Role">Role</label>
    <select name="Role" id="Role">
    <option value="">Select Here</option>
    <option value="Admin" <?= $Role == 'Admin' ? 'selected' : '' ?>>Admin</option>
    <option value="Member" <?= $Role == 'Member' ? 'selected' : '' ?>>Member</option>
    </select>
    <?= err('Role') ?>
<p>New people? <a href="/user/register.php">Register here.</a></p>
    <section>
        <button>Change</button>
        <button type="reset">Reset</button>
    </section>
</form>
</div>

<?php
include '_foot.php';