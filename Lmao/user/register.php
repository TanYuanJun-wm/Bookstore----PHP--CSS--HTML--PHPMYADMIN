<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $Email    = req('Email');
    $Password = req('Password');
    $confirm  = req('confirm');
    $Name     = req('Name');
    $Address   = req('Address');
    $Phone    = req('Phone');
    $Movie    = req('Movie');
    $f = get_file('photo');

    // Validate: Email
    if (!$Email) {
        $_err['Email'] = 'Required';
    }
    else if (strlen($Email) > 100) {
        $_err['Email'] = 'Maximum 100 characters';
    }
    else if (!is_Email($Email)) {
        $_err['Email'] = 'Invalid Email';
    }
    else if (!is_unique($Email, 'user', 'Email')) {
        $_err['Email'] = 'Duplicated';
    }

    // Validate: Password
    if (!$Password) {
        $_err['Password'] = 'Required';
    }
    else if (strlen($Password) < 8 || strlen($Password) > 16) {
        $_err['Password'] = 'Between 8-16 characters';
    }   
    else if (!preg_match('/[A-Z]/', $Password)) {
        $_err['Password'] = 'Must have one capital letters';
    }

    // Validate: confirm
    if (!$confirm) {
        $_err['confirm'] = 'Required';
    }
    else if ($confirm != $Password) {
        $_err['confirm'] = 'Not matched';
    }

    // Validate: Address
    if (!$Address) {
        $_err['Address'] = 'Required';
    }
    else if (strlen($Address) < 40) {
        $_err['Address'] = 'Too short (minimum 40 characters)';
    }
    else if (strlen($Address) > 1000) {
        $_err['Address'] = 'Maximum 1000 characters';
    }

    // Validate: Phone
    if (!$Phone) {
        $_err['Phone'] = 'Required';
    }
    else if (!is_numeric($Phone)) {
        $_err['Phone'] = 'Only numbers are allowed';
    }
    else if (strlen($Phone) > 12) {
        $_err['Phone'] = 'Must be between 11-12 characters';
    }
    else if (strlen($Phone) < 11) {
        $_err['Phone'] = 'Must be between 11-12 characters';
    }

    // Validate: Name
    if (!$Name) {
        $_err['Name'] = 'Required';
    }
    else if (strlen($Name) > 100) {
        $_err['Name'] = 'Maximum 100 characters';
    }

    // Validate: photo (file)
    if (!$f) {
        $_err['photo'] = 'Required';
    }
    else if (!str_starts_with($f->type, 'image/')) {
        $_err['photo'] = 'Must be image';
    }
    else if ($f->size > 1 * 1024 * 1024) {
        $_err['photo'] = 'Maximum 1MB';
    }

    // Validate: Movie
    if (!$Movie) {
        $_err['Movie'] = 'Required';
    }

    // DB operation
    if (!$_err) {
        // (1) Save photo
        $Photo = save_photo($f, '../photos');
        
        // (2) Insert user (member)
        $stm = $_db->prepare('
            INSERT INTO user (Email, Password, Name, photo, role, Address, Phone, Movie)
            VALUES (?, SHA1(?), ?, ?, "Member", ?, ?, ?)
        ');
        $stm->execute([$Email, $Password, $Name, $Photo, $Address, $Phone, $Movie]);

        temp('info', 'Account created');
        redirect('../login.php');
    }
}

// ----------------------------------------------------------------------------

$pageTitle = 'Register';
$_title = 'User | Register Member';
include '../_head.php';
?>

<div id=abc class=container>
<form method="post" class="form" enctype="multipart/form-data">
    <label for="Email">Email</label>
    <?= html_text('Email', 'maxlength="100"') ?>
    <?= err('Email') ?>
    <br>

    <label for="Password">Password</label>
    <?= html_Password('Password', 'maxlength="100"') ?>
    <?= err('Password') ?>
    <br>

    <label for="confirm">Confirm</label>
    <?= html_Password('confirm', 'maxlength="100"') ?>
    <?= err('confirm') ?>
    <br>

    <label for="Name">Name</label>
    <?= html_text('Name', 'maxlength="100"') ?>
    <?= err('Name') ?>
    <br>

    <label for="Address">Address</label>
    <?= html_text('Address', 'size="100" maxlength="1000"') ?>
    <?= err('Address') ?>
    <br>
    
    <label for="Phone">Phone</label>
    <?= html_text('Phone', 'maxlength="100"') ?>
    <?= err('Phone') ?>
    <br>

    <label for="Movie">What's your favourite movie?</label>
    <?= html_text('Movie', 'maxlength="100"') ?>
    <?= err('Movie') ?>
    <br>

    <label for="photo">Photo</label>
<div class="photo-upload-container">
    <label class="photo-upload" tabindex="0">
        <input id="photo-input" type="file" name="photo" accept="image/*" class="photo-input">
        <img id="photo-preview" 
             src="/images/upload.jpg"
             alt="User Photo" 
             class="photo-preview">
    </label>
    <?= err('photo') ?>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Create Account</button>
    <button type="button" id="reset-photo" class="btn btn-secondary">Reset</button>
</div>

<script>
    // Set default photo path in JavaScript
    const defaultPhoto = "/images/upload.jpg"; // Must match PHP path
    
    document.addEventListener('DOMContentLoaded', function() {
        const photoPreview = document.getElementById('photo-preview');
        const photoInput = document.getElementById('photo-input');
        const resetButton = document.getElementById('reset-photo');

        // Initialize with default image
        photoPreview.src = defaultPhoto;

        // Handle photo input change
        photoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                photoPreview.src = URL.createObjectURL(file);
            } else {
                photoPreview.src = defaultPhoto; // Revert to default on invalid file
                photoInput.value = '';
            }
        });

        // Handle reset button click
        resetButton.addEventListener('click', function() {
            photoPreview.src = defaultPhoto;
            photoInput.value = '';
        });
    });
</script>
</form>
</div>

<?php
include '../_foot.php';