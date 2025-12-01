<?php
include '../_base.php';

auth('Admin'); // Ensure the user is an admin

if (is_post()) {
    $Book_Title = req('Book_Title');
    $Price = req('Price');
    $Genre = req('Genre');
    $Description = req('Description');
    $f = get_file('photo'); // Retrieve the uploaded photo file

    // Validate: Book Title
    if (!$Book_Title) {
        $_err['Book_Title'] = 'Required';
    } else if (strlen($Book_Title) > 100) {
        $_err['Book_Title'] = 'Maximum 100 characters';
    } else if (!is_unique($Book_Title, 'books', 'Book_Title')) {
        $_err['Book_Title'] = 'Duplicated';
    }

    // Validate: Genre
    if ($Genre == '') {
        $_err['Genre'] = 'Required';
    } else if (!in_array($Genre, ['HUMOR', 'ADVENTURE', 'EDUCATION', 'FANTASY'])) {
        $_err['Genre'] = 'Invalid Genre';
    }

    // Validate: Price
    if ($Price == '') {
        $_err['Price'] = 'Required';
    } else if (!is_numeric($Price)) {
        $_err['Price'] = 'Invalid Price';
    } else if ($Price < 0) {
        $_err['Price'] = 'Price must be positive';
    }

    // Validate: Description
    if ($Description == '') {
        $_err['Description'] = 'Required';
    } else if (strlen($Description) > 10000) {
        $_err['Description'] = 'Maximum 10000 characters';
    }

    // Validate: Photo (optional)
    if ($f) { // Only validate if a file is uploaded
        if (!str_starts_with($f->type, 'image/')) {
            $_err['Photo'] = 'Must be an image';
        } else if ($f->size > 1 * 1024 * 1024) {
            $_err['Photo'] = 'Maximum 1MB';
        }
    }

    // DB operation
    if (!$_err) {
        // (1) Save photo if uploaded
        if ($f && $f->tmp_name) {
            $Photo = save_photo($f, '../bookphoto');
            if (!$Photo) {
                $_err['Photo'] = 'Failed to save photo';
            }
        } else {
            $Photo = null;
        }

        // (2) Insert book
        $stm = $_db->prepare('
            INSERT INTO books (Book_Title, Genre, Price, Description, Photo)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stm->execute([$Book_Title, $Genre, $Price, $Description, $Photo]);

        temp('info', 'New book created');
        redirect('menu.php');
    }
}

// Initialize variables to avoid undefined variable warnings
$Genre = $Genre ?? '';
$Description = $Description ?? '';

// ----------------------------------------------------------------------------

$_title = 'Book | Add Book';
include '../_head.php';
?>

<div id="abc" class="container">
    <form method="post" class="form" enctype="multipart/form-data">
        <label for="Book_Title">Book Title</label>
        <?= html_text('Book_Title', 'maxlength="100"') ?>
        <?= err('Book_Title') ?>
        <br>

        <label for="Genre">Genre</label>
        <select name="Genre">
            <option value="">Select Here</option>
            <option value="HUMOR" <?= $Genre == 'HUMOR' ? 'selected' : '' ?>>HUMOR</option>
            <option value="ADVENTURE" <?= $Genre == 'ADVENTURE' ? 'selected' : '' ?>>ADVENTURE</option>
            <option value="EDUCATION" <?= $Genre == 'EDUCATION' ? 'selected' : '' ?>>EDUCATION</option>
            <option value="FANTASY" <?= $Genre == 'FANTASY' ? 'selected' : '' ?>>FANTASY</option>
        </select>
        <?= err('Genre') ?>
        <br><br>

        <label for="Price">Price</label>
        <input type="number" name="Price" value="<?= htmlspecialchars($Price) ?>" min="0" step="0.01" required>
        <?= err('Price') ?>
        <br>

        <label for="Description">Description</label>
        <textarea name="Description" rows="10" cols="50" maxlength="10000" style="width: 100%;"><?= htmlspecialchars($Description) ?></textarea>
        <?= err('Description') ?>
        <br>

        <label for="photo">Upload Photo:</label>
        <div style="margin-top: 10px; text-align: center;">
            <div style="margin-top: 10px;">
                <img id="photo-preview" 
                     src="/images/upload.jpg" 
                     alt="Book Cover Preview" 
                     style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #ddd;">
            </div>
        </div>
        <div style="margin-top: 10px; text-align: center;">
            <input id="photo-input" type="file" name="photo" accept="image/*" style="display: block; margin-top: 10px;">
            <?= err('Photo') ?>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Submit</button>
            <button type="button" id="reset-photo" class="btn btn-secondary" style="padding: 10px 20px; margin-left: 10px;">Reset</button>
        </div>

        <script>
            const defaultPhoto = "/images/upload.jpg";
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
                photoPreview.src = defaultPhoto; // Reset to the default photo
                photoInput.value = ''; // Clear the file input
            });
        </script>
    </form>
</div>

<?php
include '../_foot.php';