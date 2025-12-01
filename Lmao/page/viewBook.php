<?php
require '../_base.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = (int) $_GET['id'];

    // Prepare and execute the PDO query
    $stmt = $_db->prepare("SELECT * FROM books WHERE Book_ID = :book_id");
    $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the result
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        echo "Book not found.";
        exit;
    }
} else {
    echo "Invalid book ID.";
    exit;
}

$User_id = $_user->User_ID ?? null;
$cart = $_SESSION['cart'][$User_id] ?? [];
$book_in_cart = isset($cart[$book['Book_ID']]);

$pageTitle = htmlspecialchars($book['Book_Title']) . ' | Preview';
$_title = 'Preview | Menu';
include '../_head.php';
?>

<div class="container">
<div id="abc">
<h1 id="text"><?= htmlspecialchars($book['Book_Title']) ?></h1>
<img style = "float : left"src="../bookPhoto/<?= htmlspecialchars($book['Photo']) ?>" alt="Book Cover" width="200">
    <p><strong>Genre:</strong><br> <?= htmlspecialchars($book['Genre']) ?></p>
    <br><br>
    <p><strong>Price:</strong><br> $<?= htmlspecialchars($book['Price']) ?></p>
    <p style="clear : both"><strong>Description:</strong><br> <?= nl2br(htmlspecialchars($book['Description'])) ?></p>

    <button onclick="window.location.href='realMenu.php';">Back to menu</button>
    
    
    <?php if ($_user): ?>


        <?php if ($_user?->Role == 'Member'): ?>
    <?php if ($book_in_cart): ?>
        <p><em>This book is already in your cart.</em></p>
        <?php else: ?>
        <form method="POST" action="/user/cart.php">
            <label for="quantity">Quantity:</label>
            <input type="hidden" name="Book_ID" value="<?= htmlspecialchars($book['Book_ID']) ?>">
            <input type="number" id="quantity" name="quantity" step="1" min="1" max="99" required>
            <button type="submit">Add to cart</button>
        </form>
        <?php endif; ?>
    <?php endif; ?>
    <?php else: ?>
            <button onclick="window.location.href='/login.php'">Please login</button>
    <?php endif ?>
    </div>
</div>
<?php
include '../_foot.php';