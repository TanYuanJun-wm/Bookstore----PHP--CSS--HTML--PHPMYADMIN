<?php
include '_base.php';

$Role = '';

// ----------------------------------------------------------------------------

if (is_post()) {
    $Email    = req('Email');
    $Password = req('Password');
    $Role = req('Role');

    // Validate: Email
    if ($Email == '') {
        $_err['Email'] = 'Required';
    }
    else if (!is_Email($Email)) {
        $_err['Email'] = 'Invalid Email';
    }

    // Validate: Password
    if ($Password == '') {
        $_err['Password'] = 'Required';
    }
    else if (strlen($Password) < 8 || strlen($Password) > 16) {
        $_err['Password'] = 'Between 8-16 characters';
    }
    else if (!preg_match('/[A-Z]/', $Password)) {
        $_err['Password'] = 'Must have one capital letter';
    }

    // Validate: Role
    if ($Role == '') {
        $_err['Role'] = 'Required';
    }
    else if (!in_array($Role, ['Admin', 'Member'])) {
        $_err['Role'] = 'Invalid Role, try again';
    }

    // Login user
    if (!$_err) {
        $stm = $_db->prepare('
        SELECT * FROM user
        WHERE Email = ? AND Password = SHA1(?) AND Role = ?
        ');
        $stm->execute([$Email, $Password, $Role]);
        $u = $stm->fetch();

        if ($u) {
            temp('info', 'Login successful');
            login($u);
        } else {
            $stm = $_db->prepare('
            SELECT * FROM user
            WHERE Email = ? AND Password = SHA1(?)
            ');
            $stm->execute([$Email, $Password]);
            $u = $stm->fetch();

            if ($u) {
                $_err['Role'] = "You are not " . lcfirst($Role) . ". Please try again.";
            } else {
                $_err['Password'] = 'Not matched';
            }
        }
    }
}

// ----------------------------------------------------------------------------

$pageTitle = 'Login';
$_title = 'Login';
include '_head.php';
?>

<div id=abc class=container>

<?php if ($message = temp('info')): ?>
<div id="info"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post" class="form" enctype="multipart/form-data">
    <label for="Email">Email</label>
    <?= html_text('Email', 'maxlength="100"') ?>
    <?= err('Email') ?>
<br>
    <label for="Password">Password</label>
    <?= html_Password('Password', 'maxlength="100"') ?>
    <?= err('Password') ?>
<br>
    <label for="Role">Role</label>
    <select name="Role">
    <option value="">Select Here</option>
    <option value="Admin" <?= $Role == 'Admin' ? 'selected' : '' ?>>Admin</option>
    <option value="Member" <?= $Role == 'Member' ? 'selected' : '' ?>>Member</option>
</select>
<?= err('Role') ?>

<p>Forgot password? <a href="/passwordrecover.php">Click here.</a></p>
<p>New people? <a href="/user/register.php">Register here.</a></p>
    <section>
        <button>Login</button>
        <button type="reset">Reset</button>
    </section>
</form>
</div>

<?php
include '_foot.php';