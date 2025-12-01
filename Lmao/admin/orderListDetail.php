<?php
require_once '../_base.php'; // Include base functions and database setup
auth('Admin'); // Ensure only admins can access this page

// Get the Order_ID from the query string
$orderId = get('order_id');
if (!$orderId) {
    die('Invalid Order ID.');
}

// Fetch order details
$orderDetailsQuery = "
    SELECT ol.Order_ID, ol.Total, ol.OrderDate AS Date, u.User_ID, u.Name AS User_Name, u.Photo AS User_Image, 
           u.Address, u.Phone, ol.Ways AS Payment_Method,
           b.Book_Title, b.Photo AS Book_Image, ob.Quantity
    FROM orderlist ol
    LEFT JOIN orderlist_book ob ON ol.Order_ID = ob.Order_ID
    LEFT JOIN books b ON ob.Book_ID = b.Book_ID
    LEFT JOIN user u ON ol.User_ID = u.User_ID
    WHERE ol.Order_ID = :order_id
";
$stmt = $_db->prepare($orderDetailsQuery);
$stmt->execute(['order_id' => $orderId]);
$orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($orderDetails)) {
    die('Order not found.');
}

// Include the header
$pageTitle = 'Order Details';
$_title = 'Order Details';
require_once '../_head.php';
?>

<div id="text">
    <b>User Information</b>
    <table class="table">
        <thead>
            <tr id="tr">
                <th>User Image</th>
                <th>User ID</th>
                <th>User Name</th>
                <th>Address</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <img src="/photos/<?= encode($orderDetails[0]['User_Image'] ?: 'default.jpg') ?>" 
                         alt="<?= encode($orderDetails[0]['User_Name']) ?>" 
                         style="width: 100px; height: auto; border-radius: 50%;">
                </td>
                <td><?= encode($orderDetails[0]['User_ID']) ?></td>
                <td><?= encode($orderDetails[0]['User_Name']) ?></td>
                <td><?= encode($orderDetails[0]['Address']) ?></td>
                <td><?= encode($orderDetails[0]['Phone']) ?></td>
            </tr>
        </tbody>
    </table>
    <br><hr>

    <b>Order Details</b>
    <!-- <h2 style="text-align: center; margin-top: 20px;">Order Details</h2> -->
    <table class="table">
    <thead>
        <tr id="tr">
            <th>Order ID</th>
            <th>Book Name</th>
            <th>Book Image</th>
            <th>Quantity</th>
            <th>Total Quantity</th>
            <th>Payment Method</th>
            <th>Date</th>
            <th>Total</th>
        </tr>
    </thead>
        <tbody>
            <?php 
            $firstRow = true; // Track the first row for each order
            $totalQuantity = 0; // Initialize total quantity for the order

            // Calculate total quantity
            foreach ($orderDetails as $detail) {
                $totalQuantity += $detail['Quantity'];
            }

            foreach ($orderDetails as $index => $detail): ?>
                <tr>
                    <?php if ($firstRow): ?>
                        <!-- Display Order ID only in the first row -->
                        <td rowspan="<?= count($orderDetails) ?>"><?= encode($detail['Order_ID']) ?></td>
                    <?php endif; ?>
                    <td><?= encode($detail['Book_Title']) ?></td>
                    <td>
                        <img src="/bookPhoto/<?= encode($detail['Book_Image'] ?: 'default.jpg') ?>" 
                            alt="<?= encode($detail['Book_Title']) ?>" 
                            style="width: 100px; height: auto;">
                    </td>
                    <td><?= encode($detail['Quantity']) ?></td>
                    <?php if ($firstRow): ?>
                        <!-- Display Payment Method, Date, Total, and Total Quantity only in the first row -->
                        <td rowspan="<?= count($orderDetails) ?>"><?= $totalQuantity ?></td>
                        <td rowspan="<?= count($orderDetails) ?>"><?= encode($detail['Payment_Method']) ?></td>
                        <td rowspan="<?= count($orderDetails) ?>"><?= encode($detail['Date']) ?></td>
                        <td rowspan="<?= count($orderDetails) ?>">RM <?= number_format($detail['Total'], 2) ?></td>
                        <?php $firstRow = false; // Set to false after the first row ?>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../_foot.php'; // Include footer ?>