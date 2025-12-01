<?php
include '../_base.php';

auth('Admin');

if (is_get()) {
    $Book_ID = req('Book_ID'); // Retrieve Book_ID from the request
    if (!$Book_ID) {
        temp('error', 'Book ID is required');
        redirect('/'); // Redirect if Book_ID is missing
    }

    $stm = $_db->prepare('SELECT * FROM books WHERE Book_ID = ?');
    $stm->execute([$Book_ID]);
    $b = $stm->fetch(PDO::FETCH_OBJ); // Fetch as an object

    if (!$b) {
        temp('error', 'Book not found');
        redirect('/'); // Redirect if the book is not found
    }

    extract((array)$b); // Extract the book data into variables
    $_SESSION['photo'] = $b->Photo; // Store the photo in the session
}

if (is_post()) {
    $Book_Title = req('Book_Title');
    $Book_ID = req('Book_ID');
    $Genre  = req('Genre');
    $Price = req('Price');
    $Description = req('Description');
    $f = get_file('photo'); // Retrieve the uploaded photo file

    // Retain the current photo if no new photo is uploaded
    $Photo = $_SESSION['photo'];

    // Validate: Book Title
    if ($Book_Title == '') {
        $_err['Book_Title'] = 'Required';
    } else if (strlen($Book_Title) > 100) {
        $_err['Book_Title'] = 'Maximum 100 characters';
    } else {
        $stm = $_db->prepare('
            SELECT COUNT(*) FROM books
            WHERE Book_Title = ? AND Book_ID != ?
        ');
        $stm->execute([$Book_Title, $Book_ID]);

        if ($stm->fetchColumn() > 0) {
            $_err['Book_Title'] = 'Duplicated';
        }
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
        $_err['Price'] = 'Invalid Price';
    }

    // Validate: Description
    if ($Description == '') {
        $_err['Description'] = 'Required';
    } else if (strlen($Description) > 10000) {
        $_err['Description'] = 'Maximum 10000 characters';
    }

    // Validate: Photo (optional)
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be an image';
        } else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 1MB';
        }
    }

    // DB operation
    if (!$_err) {
        // (1) Save new photo if uploaded
        if ($f) {
            if ($Photo && file_exists("../bookphoto/$Photo")) {
                unlink("../bookphoto/$Photo"); // Delete the old photo
            }
            $Photo = save_photo($f, '../bookphoto');
        }

        // (2) Update book
        $stm = $_db->prepare('
            UPDATE books
            SET Book_Title = ?, Genre = ?, Price = ?, Description = ?, Photo = ?
            WHERE Book_ID = ?
        ');
        $stm->execute([$Book_Title, $Genre, $Price, $Description, $Photo, $Book_ID]);

        temp('info', 'Book has been updated');
        redirect('menu.php');
    }
}

$_title = 'Book | Edit Book';
include '../_head.php';
?>

<div id=abc class=container>
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
    <br>
    <label for="Description">Description</label>
    <textarea name="Description" rows="10" cols="50" maxlength="10000" style="width: 100%; height: 200px;"><?= htmlspecialchars($Description) ?></textarea>
    <?= err('Description') ?>
    <br>


            <label for="Price">Price</label>
            <input type="hidden" name="Book_ID" value="<?= htmlspecialchars($Book_ID) ?>">
            <input type="number" name="Price" value="<?= htmlspecialchars($Price) ?>" min="0" step="0.01" required>
            <input type="hidden" name="action" value="modify">
<?= err('Price') ?>
    <br>
    <label for="photo">Upload Photo:</label>
    <div style="margin-top: 10px; text-align: center;">
        <div style="margin-top: 10px;">
            <img id="photo-preview" 
                 src="/bookphoto/<?= htmlspecialchars($Photo) ?>" 
                 alt="Book Cover Preview" 
                 style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #ddd;">
        </div>
    </div>
    <div style="margin-top: 10px; text-align: center;">
        <input id="photo-input" type="file" name="photo" accept="image/*" style="display: block; margin-top: 10px;">
        <?= err('photo') ?>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Submit</button>
        <button type="button" id="reset-photo" class="btn btn-secondary" style="padding: 10px 20px; margin-left: 10px;">Reset</button>
    </div>

    <script>
        const defaultPhoto = "/bookphoto/<?= htmlspecialchars($Photo) ?>";
        const photoPreview = document.getElementById('photo-preview');
        const photoInput = document.getElementById('photo-input');
        const resetButton = document.getElementById('reset-photo');

        // Handle photo input change
        photoInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                photoPreview.src = URL.createObjectURL(file);
            } else {
                photoPreview.src = defaultPhoto;
            }
        });

        // Handle reset button click
        resetButton.addEventListener('click', function () {
            photoPreview.src = defaultPhoto;
            photoInput.value = "";
        });
    </script>
    
</form>

<?php
include '../_foot.php';
?>

