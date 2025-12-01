<?php

include '../_base.php';

auth('Admin'); // Ensure the user is an admin

// Get the user ID from the query parameter
$User_ID = req('user_id');
if (!$User_ID) {
    temp('error', 'User ID is required');
    redirect('userlist.php');
}

// Fetch user details
$stm = $_db->prepare('SELECT * FROM user WHERE User_ID = ?');
$stm->execute([$User_ID]);
$s = $stm->fetch(PDO::FETCH_OBJ);

if (!$s) {
    temp('error', 'User not found');
    redirect('userlist.php');
}

// Handle form submission
if (is_post()) {
    $f = get_file('photo'); // Get the uploaded file

    // Validate the uploaded photo
    if (!$f) {
        $_err['photo'] = 'Photo is required';
    } else if (!str_starts_with($f->type, 'image/')) {
        $_err['photo'] = 'Must be an image';
    } else if ($f->size > 1 * 1024 * 1024) {
        $_err['photo'] = 'Maximum size is 1MB';
    }

    // Save the photo if there are no errors
    if (!$_err) {
        // Delete the old photo if it exists
        if ($s->Photo && file_exists("../photos/{$s->Photo}")) {
            unlink("../photos/{$s->Photo}");
        }

        // Save the new photo
        $Photo = save_photo($f, '../photos');

        // Update the database
        $stm = $_db->prepare('UPDATE user SET Photo = ? WHERE User_ID = ?');
        $stm->execute([$Photo, $User_ID]);

        temp('info', 'Profile picture updated successfully');
        redirect("userdetail.php?user_id=$User_ID");
    }
}

// ----------------------------------------------------------------------------
$pageTitle = 'Edit User Photo';
$_title = 'Edit User Photo';
include '../_head.php';
?>

<div class="container" id="abc" style="max-width: 600px; margin: 50px auto;">
    <div class="card" style="padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; margin-bottom: 20px;">Edit Profile Picture</h2>
        <form method="post" enctype="multipart/form-data">
            <div style="text-align: center; margin-bottom: 20px;">
                <label for="photo" style="font-weight: bold;">Current Photo:</label>
                <div style="margin-top: 10px;">
                    <?php if ($s->Photo && file_exists("../photos/{$s->Photo}")): ?>
                        <img id="photo-preview" src="../photos/<?= htmlspecialchars($s->Photo) ?>" alt="User Photo" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                    <?php else: ?>
                        <p style="color: #888;">No photo available</p>
                    <?php endif; ?>
                </div>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="photo" style="font-weight: bold;">Upload New Photo:</label>
                <input id="photo-input" type="file" name="photo" accept="image/*" style="display: block; margin-top: 10px;">
                <?= err('photo') ?>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Update Photo</button>
                <button type="button" id="reset-photo" class="btn btn-secondary" style="padding: 10px 20px; margin-left: 10px;">Reset</button>
                <button type="button" onclick="window.location.href='userdetail.php?user_id=<?= $User_ID ?>'" class="btn btn-secondary" style="padding: 10px 20px; margin-left: 10px;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    const originalPhoto = "<?= $s->Photo && file_exists("../photos/{$s->Photo}") ? "../photos/" . htmlspecialchars($s->Photo) : '' ?>";
    const photoPreview = document.getElementById('photo-preview');
    const photoInput = document.getElementById('photo-input');
    const resetButton = document.getElementById('reset-photo');

    // Handle photo input change
    photoInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            photoPreview.src = URL.createObjectURL(file);
        }
    });

    // Handle reset button click
    resetButton.addEventListener('click', function () {
        if (originalPhoto) {
            photoPreview.src = originalPhoto; // Reset to the original photo
        }
        photoInput.value = ''; // Clear the file input
    });
</script>

<?php
include '../_foot.php';
?>