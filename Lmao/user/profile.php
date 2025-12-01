<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// Authenticated users
auth();

if (is_get()) {
    $stm = $_db->prepare('SELECT * FROM user WHERE User_ID = ?');
    $stm->execute([$_user->User_ID]);
    $u = $stm->fetch();

    if (!$u) {
        redirect('/');
    }

    extract((array)$u);
    $_SESSION['photo'] = $u->Photo;
}

if (is_post()) {
    $Email = req('Email');
    $Name  = req('Name');
    $Address = req('Address');
    $Phone = req('Phone');
    $Photo = $_SESSION['photo']; // Retain the current photo
    $f = get_file('photo'); // Retrieve the uploaded photo file

    // Validate: Email
    if ($Email == '') {
        $_err['Email'] = 'Required';
    } else if (strlen($Email) > 100) {
        $_err['Email'] = 'Maximum 100 characters';
    } else if (!is_Email($Email)) {
        $_err['Email'] = 'Invalid Email';
    } else {
        $stm = $_db->prepare('
            SELECT COUNT(*) FROM user
            WHERE Email = ? AND User_ID != ?
        ');
        $stm->execute([$Email, $_user->User_ID]);

        if ($stm->fetchColumn() > 0) {
            $_err['Email'] = 'Duplicated';
        }
    }

    // Validate: Name
    if ($Name == '') {
        $_err['Name'] = 'Required';
    } else if (strlen($Name) > 100) {
        $_err['Name'] = 'Maximum 100 characters';
    }

    // Validate: Address
    if (!$Address) {
        $_err['Address'] = 'Required';
    } else if (strlen($Address) < 40) {
        $_err['Address'] = 'Too short (minimum 40 characters)';
    } else if (strlen($Address) > 1000) {
        $_err['Address'] = 'Maximum 1000 characters';
    }

    // Validate: Phone
    if (!$Phone) {
        $_err['Phone'] = 'Required';
    } else if (!is_numeric($Phone)) {
        $_err['Phone'] = 'Only numbers are allowed';
    } else if (strlen($Phone) > 12) {
        $_err['Phone'] = 'Must be exactly 12 characters';
    }

    // Validate: Photo (optional)
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be image';
        } else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 1MB';
        }
    }

    // DB operation
    if (!$_err) {
        // (1) Delete and save Photo --> optional
        if ($f) {
            if ($Photo && file_exists("../photos/$Photo")) {
                unlink("../photos/$Photo"); // Delete the old photo
            }
            $Photo = save_photo($f, '../photos');
        }

        // (2) Update user (Email, Name, Photo)
        $stm = $_db->prepare('
            UPDATE user
            SET Email = ?, Name = ?, Address = ?, Phone = ?, Photo = ?
            WHERE User_ID = ?
        ');
        $stm->execute([$Email, $Name, $Address, $Phone, $Photo, $_user->User_ID]);

        // (3) Update global user object
        $_user->Email = $Email;
        $_user->Name = $Name;
        $_user->Address = $Address;
        $_user->Phone = $Phone;
        $_user->Photo = $Photo;

        temp('info', 'Record updated');
        redirect('/');
    }
}

// ----------------------------------------------------------------------------

$pageTitle = 'User | Profile';
$_title = 'User | Profile';
include '../_head.php';
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div id=abc class=container>
<form method="post" class="form" enctype="multipart/form-data">
    <label for="Email">Email</label>
    <?= html_text('Email', 'maxlength="100"') ?>
    <?= err('Email') ?>
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

    <label for="photo">Photo</label>
<div class="photo-upload-container">
    <label class="photo-upload" tabindex="0">
        <input id="photo-input" type="file" name="photo" accept="image/*" class="photo-input">
        <img id="photo-preview" 
        src="<?= ($Photo && file_exists("../photos/{$Photo}")) ? "../photos/" . htmlspecialchars($Photo) : '/images/upload.jpg' ?>" 
        alt="User Photo"
        class="photo-preview">
    </label>
    <?= err('photo') ?>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Update Profile</button>
    <button type="button" id="reset-photo" class="btn btn-secondary">Reset</button>
</div>

    <script>
        const originalPhoto = "<?= $Photo && file_exists("../photos/{$Photo}") ? "../photos/" . htmlspecialchars($Photo) : '/images/default-profile.jpg' ?>";
        const photoPreview = document.getElementById('photo-preview');
        const photoInput = document.getElementById('photo-input');
        const resetButton = document.getElementById('reset-photo');

        // Handle photo input change
        photoInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                photoPreview.src = URL.createObjectURL(file);
            } else {
                alert('Please select a valid image file.');
                photoInput.value = ''; // Clear invalid input
            }
        });

        // Handle reset button click
        resetButton.addEventListener('click', function () {
            photoPreview.src = originalPhoto; // Reset to the original photo
            photoInput.value = ''; // Clear the file input
        });
    </script>
</form>

<?php
include '../_foot.php';
?>