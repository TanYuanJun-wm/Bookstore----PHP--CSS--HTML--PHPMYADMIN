<?php
require_once '../_base.php'; // Include base functions and database setup
auth('Member'); // Ensure only logged-in members can access this page

// Get the Order_ID from the query string
$orderId = get('order_id');
if (!$orderId) {
    die('Invalid Order ID.');
}

// Fetch order details
$orderDetailsQuery = "
    SELECT ol.Order_ID, ol.Total, ol.OrderDate AS Date, u.Address, u.Phone, ol.Ways AS Payment_Method,
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

<div>
    <!-- <h1 class="oh">Order Details</h1> -->

    <table class="table">
    <thead>
        <tr id="tr">
            <th>Book Name</th>
            <th>Book Image</th>
            <th>Quantity</th>
            <th>Payment Method</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Group the orders first
        $groupedOrders = [];
        foreach ($orderDetails as $detail) {
            $key = $detail['Payment_Method'] . '|' . $detail['Address'] . '|' . $detail['Phone'] . '|' . $detail['Date'] . '|' . $detail['Total'];
            $groupedOrders[$key][] = $detail;
        }
        ?>

        <?php foreach ($groupedOrders as $orders): ?>
            <?php $rowspan = count($orders); ?>
            <?php foreach ($orders as $index => $detail): ?>
                <tr>
                    <td><?= encode($detail['Book_Title']) ?></td>
                    <td>
                        <img src="/bookPhoto/<?= encode($detail['Book_Image'] ?: 'default.jpg') ?>" 
                            alt="<?= encode($detail['Book_Title']) ?>" 
                            style="width: 100px; height: auto;">
                    </td>
                    <td><?= encode($detail['Quantity']) ?></td>

                    <?php if ($index === 0): ?>
                        <td rowspan="<?= $rowspan ?>"><?= encode($detail['Payment_Method']) ?></td>
                        <td rowspan="<?= $rowspan ?>"><?= encode($detail['Address']) ?></td>
                        <td rowspan="<?= $rowspan ?>"><?= encode($detail['Phone']) ?></td>
                        <td rowspan="<?= $rowspan ?>"><?= encode($detail['Date']) ?></td>
                        <td rowspan="<?= $rowspan ?>">RM <?= number_format($detail['Total'], 2) ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>

</div>

<?php require_once '../_foot.php'; // Include footer ?>