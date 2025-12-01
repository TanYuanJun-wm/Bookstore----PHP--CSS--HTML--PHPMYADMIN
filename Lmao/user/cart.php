<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// Member role
auth('Member');

// Get the logged-in User_ID
$User_id = $_user->User_ID ?? null;

if (!$User_id) {
    // If User_ID is not set, retrieve it using the username from the session
    if (!$username) {
        die('Username not found. Please log in.');
    }

    // Query the database to get the User_ID
    $stmt = $pdo->prepare('SELECT User_ID FROM user WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && isset($user['User_ID'])) {
        $User_id = $user['User_ID'];
        $_SESSION['User_ID'] = $User_id; // Store User_ID in the session for future use
    } else {
        die('User_ID not found in the database.');
    }
}

// ----------------------------------------------------------------------------

// Handle form submissions to modify, remove, or add a book to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['Book_ID'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $action = $_POST['action'] ?? 'add';  // Default action is "add"

    // Initialize cart if not present
    if (!isset($_SESSION['cart'][$User_id])) {
        $_SESSION['cart'][$User_id] = [];
    }

    // Retrieve the current cart
    $cart = $_SESSION['cart'][$User_id];

    if ($book_id) {
        if ($action == 'remove') {
            // Remove the book from the cart
            unset($cart[$book_id]);
            temp('info', 'Book removed');
        } elseif ($action == 'modify' && $quantity !== null) {
            // Update the book quantity
            $cart[$book_id] = $quantity;
            temp('info', 'Cart updated');
        } elseif ($action == 'add' && $quantity !== null) {
            // Add the new book or update the existing book quantity
            if (isset($cart[$book_id])) {
                // If the book exists, update the quantity
                $cart[$book_id] += $quantity;
            } else {
                // Otherwise, add the book to the cart
                $cart[$book_id] = $quantity;
            }
            temp('info', 'Book added');
        }

        // Save the updated cart back to the session
        $_SESSION['cart'][$User_id] = $cart;
    }

    // Redirect to cart page to show updated cart
    header('Location: cart.php');
    header("Location: cart.php");
    exit;
}

// Retrieve the cart for the current member
$cart = $_SESSION['cart'][$User_id] ?? [];

$pageTitle = "Cart";
$_title = 'Cart';
include '../_head.php';
?>

<?php if ($message = temp('info')): ?>
        <div id="info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="container">
    <div id="abc">
        <h1 id="text">Cart</h1>
        <!-- Display current cart items -->
        <?php
        $total = 0.0; // Initialize total cost
        $totalQuantity = 0;

        if (count($cart) > 0):
        ?>
        <table class="table" border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Book ID</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($cart as $book_id => $quantity):
                // Fetch book details from the database
                $stmt = $_db->prepare('SELECT Book_Title, Price, Photo FROM books WHERE Book_ID = :book_id');
                $stmt->execute(['book_id' => $book_id]);
                $book = $stmt->fetch(PDO::FETCH_ASSOC);

                // Skip if the book wasn't found
                if (!$book) continue;

                $title = htmlspecialchars($book['Book_Title']);
                $price = (float)$book['Price'];
                $subtotal = $price * $quantity;
                $total += $subtotal;
                $totalQuantity += $quantity;
            ?>
                <tr>
                    <td><?= $title ?></td>
                    <td><?= htmlspecialchars($book_id) ?></td>
                    <td>$<?= number_format($price, 2) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="Book_ID" value="<?= htmlspecialchars($book_id) ?>">
                            <input type="number" name="quantity" value="<?= htmlspecialchars($quantity) ?>" min="1" max="99" step="1" required>
                            <input type="hidden" name="action" value="modify">
                            <button type="submit">Modify</button>
                        </form>
                    </td>
                    <td><strong>$<?= number_format($subtotal, 2) ?></strong></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="Book_ID" value="<?= htmlspecialchars($book_id) ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit">Remove</button>
                        </form>
                        <img style="width: 150px;" src="/bookPhoto/<?= htmlspecialchars($book['Photo']) ?>" class="popup">
                    </td>
                </tr>
                <?php endforeach; ?>
<tr style="background-color: #f2f2f2; font-weight: bold;">
    <td colspan="3" style="text-align: right;"><strong>Totals: &nbsp</strong></td>
    <td><strong><?= $totalQuantity ?></strong></td>
    <td><strong>$<?= number_format($total, 2) ?></strong></td>
    <td></td>
</tr>
</tbody>
</table>

        <!-- Place order form -->
        <form method="GET" action="order.php">
            <button type="submit">Place Order</button>
        </form>
        <?php else: ?>
            <p id ="text" style="font-size : larger">Your cart is empty. Add some items to place an order.</p>
        <?php endif; ?>
    </div>
</div>


<?php
include '../_foot.php';