<?php
include '../_base.php';

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    die('Order ID is missing.');
}

// Fetch order details
$stmt = $_db->prepare("SELECT o.*, u.Name, u.Address, u.Phone 
                       FROM orderlist o 
                       JOIN user u ON o.User_ID = u.User_ID 
                       WHERE o.Order_ID = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die('Order not found.');
}

// Fetch books in the order
$stmt = $_db->prepare("SELECT ob.*, b.Book_Title, b.Price 
                       FROM orderlist_book ob 
                       JOIN books b ON ob.Book_ID = b.Book_ID 
                       WHERE ob.Order_ID = ?");
$stmt->execute([$order_id]);
$book_details = $stmt->fetchAll();

// Calculate totals
$grand_total = $order->Total;
$grand_total_quantity = $order->QuantitySum;
?>
<?php 
$pageTitle = "Successful Order";
$_title = 'Order Confirmation';
include '../_head.php';
?>
<div class="container">
    <div id="abc">
        <h1 id="text">Order Receipt</h1>
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order_id) ?></p>
        <p><strong>Order Date:</strong> <?= htmlspecialchars($order->OrderDate) ?></p>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($order->Ways) ?></p>

        <h3>Customer Info</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($order->Name) ?></p>
        <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order->Address)) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($order->Phone) ?></p>

        <h3>Order Summary</h3>
        <table class="table" border="1" cellpadding="8" cellspacing="0">
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
                        <td><?= htmlspecialchars($book->Book_Title) ?></td>
                        <td><?= number_format($book->Price, 2) ?></td>
                        <td><?= htmlspecialchars($book->Quantity) ?></td>
                        <td><?= number_format($book->Subtotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><strong>Total Quantity</strong></td>
                    <td><strong><?= number_format($grand_total_quantity) ?></strong></td>
                    <td><strong>$<?= number_format($grand_total, 2) ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <p><a href="/page/realMenu.php">← Continue Shopping</a></p>
    </div>
</div>

<?php include '../_foot.php'; ?>