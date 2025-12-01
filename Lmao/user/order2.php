<?php
include '../_base.php';

// Member role only
auth('Member');

// Get the logged-in User_ID
$User_id = $_user->User_ID ?? null;

if (!$User_id) {
    die('User not logged in.');
}

// Ensure the cart exists
if (!isset($_SESSION['cart'][$User_id]) || empty($_SESSION['cart'][$User_id])) {
    die('Your cart is empty. Please add some items before placing an order.');
}

// Fetch cart items
$cart = $_SESSION['cart'][$User_id];

// Initialize variables for totals
$grand_total = 0;
$grand_total_quantity = 0;
$book_details = [];

// Fetch details and calculate subtotals
foreach ($cart as $book_id => $quantity) {
    // Fetch book price
    $stmt = $_db->prepare('SELECT Book_Title, Price FROM books WHERE Book_ID = :book_id');
    $stmt->execute(['book_id' => $book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    $subtotal = $book ? $book['Price'] * $quantity : 0;
    $book_details[] = [
        'title' => $book['Book_Title'],
        'price' => $book['Price'],
        'quantity' => $quantity,
        'subtotal' => $subtotal
    ];

    // Add the subtotal to the grand total
    $grand_total += $subtotal;
    $grand_total_quantity += $quantity; // Add quantity to grand total quantity
}

// Order processing logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $_db->beginTransaction();

        // Insert into orderlist
        $stmt = $_db->prepare('INSERT INTO orderlist (User_ID, Total, QuantitySum, Ways) VALUES (:user_id, :total, :quantitySum, :Ways)');
        $stmt->execute([
            'user_id' => $User_id,
            'total' => $grand_total, // Use the grand total
            'quantitySum' => $grand_total_quantity, // Use the grand total quantity
            'Ways' => 'QR' // Example payment method
        ]);

        // Get new order ID
        $order_id = $_db->lastInsertId();

        // Insert each book in the cart into orderlist_book
        foreach ($cart as $book_id => $quantity) {
            // Fetch book price
            $stmt = $_db->prepare('SELECT Price FROM books WHERE Book_ID = :book_id');
            $stmt->execute(['book_id' => $book_id]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);

            $subtotal = $book ? $book['Price'] * $quantity : 0;

            $stmt = $_db->prepare('INSERT INTO orderlist_book (Order_ID, Book_ID, Quantity, Subtotal) 
                                   VALUES (:order_id, :book_id, :quantity, :subtotal)');
            $stmt->execute([
                'order_id' => $order_id,
                'book_id' => $book_id,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ]);
        }

        // Commit transaction
        $_db->commit();

        // Clear cart
        unset($_SESSION['cart'][$User_id]);

        // Redirect to confirmation page
        header('Location: order_confirmation.php?order_id=' . $order_id);
        exit;

    } catch (Exception $e) {
        $_db->rollBack();
        die("Failed to place order: " . htmlspecialchars($e->getMessage()));
    }
}

$pageTitle = "Payment | QR Code";
$_title = 'Order';
include '../_head.php';
?>

<!-- Order confirmation page -->
<div class="container">
    <div id="abc">
            <!-- Order details for review -->
    <p id="text" style="font-size: larger;"><b>Review your order details below before submitting:</b></p>
    <table class= "table "border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Book</th>
            <th>Price ($)</th>
            <th>Quantity</th>
            <th>Subtotal ($)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($book_details as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= number_format($book['price'], 2) ?></td>
                <td><?= htmlspecialchars($book['quantity']) ?></td>
                <td><?= number_format($book['subtotal'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><strong>Total:</strong></td>
            <td colspan="1"><strong><?= number_format($grand_total_quantity) ?></strong></td>
            <td><strong>$<?= number_format($grand_total, 2) ?></strong></td>
        </tr>
    </tfoot>
</table>

<h3>Enter Payment Details</h3>
<img src="/QR/Rickrolling_QR_code.png" alt="QR Code" width="200" height="200"> <br>


<!-- Fake payment confirmation -->
 <form method="post" action="">
    <button type="submit">Place Order</button>
    <button type="button" onclick="window.location.href='order.php';">Change payment method</button>
    </form>
</form>
<br><hr><br>
<button type="button" onclick="window.location.href='cart.php';">Back to Cart</button>
<button type="button" onclick="window.location.href='/page/realMenu.php';">Back to Menu</button>
    </div>
</div>

<?php include '../_foot.php';

