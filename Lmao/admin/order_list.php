<?php
require_once '../_base.php'; // Include base functions and database setup

// Restrict access to Admins only
auth('Admin');

// Parameters
$page = req('page', 1);
$itemsPerPage = 5;

// Fetch total record count
$totalQuery = "
    SELECT COUNT(DISTINCT ol.Order_ID) AS total
    FROM orderlist ol
    LEFT JOIN user u ON ol.User_ID = u.User_ID
";
$stmt = $_db->prepare($totalQuery);
$stmt->execute();
$totalRecords = $stmt->fetchColumn();

// Fetch orders with filtering, sorting, and pagination
require_once '../lib/SimplePager.php';
$query = "
    SELECT ROW_NUMBER() OVER (ORDER BY ol.Order_ID ASC) AS Row_ID,
           u.Name AS User_Name, ol.Order_ID, ol.Total, 
           GROUP_CONCAT(CONCAT(b.Book_Title, ' (x', ob.Quantity, ')') SEPARATOR ', ') AS Books,
           SUM(ob.Quantity) AS Total_Books, -- Calculate total quantity of books
           ol.OrderDate AS Date
    FROM orderlist ol
    LEFT JOIN orderlist_book ob ON ol.Order_ID = ob.Order_ID
    LEFT JOIN books b ON ob.Book_ID = b.Book_ID
    LEFT JOIN user u ON ol.User_ID = u.User_ID
    GROUP BY ol.Order_ID
    ORDER BY ol.Order_ID ASC
";
$p = new SimplePager($query, [], $itemsPerPage, $page, $totalRecords);
$orders = $p->result;

// Include the header
$pageTitle = 'Order List';
$_title = 'Order List';
require_once '../_head.php';
?>

<div>
    <?php if (empty($orders)): ?>
        <p class="oht">No orders found.</p>
    <?php else: ?>
        <p><?= $p->count ?> of <?= $totalRecords ?> record(s) | Page <?= $p->page ?> of <?= ceil($totalRecords / $itemsPerPage) ?></p>
        <table class="table" ><!--class="oth"-->
            <thead>
                <tr id="tr">
                <thead>
                    <tr id="tr">
                        <th style="text-align: center;">No.</th>
                        <th style="text-align: center;">User Name</th>
                        <th style="text-align: center;">Order ID</th>
                        <th style="text-align: center;">Date</th>
                        <th style="text-align: center;">Total Books</th>
                        <th style="text-align: center;">Books</th>
                        <th style="text-align: center;">Total Price</th>
                    </tr>
                </thead>
            <tbody style="text-align: center">
                <?php 
                $totalOrderAmount = 0; // Initialize total order amount
                $totalBooksOrdered = 0; // Initialize total books ordered
                $counter = 1 + (($page - 1) * $itemsPerPage); // Adjust counter for pagination
                foreach ($orders as $order): 
                    $totalOrderAmount += $order->Total; // Add to total order amount
                    $totalBooksOrdered += $order->Total_Books; // Add to total books ordered

                    // Split the books into an array for display
                    $books = explode(', ', $order->Books);
                    $rowCount = count($books); // Number of rows for this order
                ?>
                    <?php foreach ($books as $index => $book): ?>
                        <tr onclick="window.location.href='orderListDetail.php?order_id=<?= $order->Order_ID ?>'" style="cursor: pointer;">
                            <?php if ($index === 0): ?>
                                <!-- Display order details only for the first row -->
                                <td rowspan="<?= $rowCount ?>"><?= $counter++ ?></td> <!-- Numbering -->
                                <td rowspan="<?= $rowCount ?>"><?= encode($order->User_Name) ?></td>
                                <td rowspan="<?= $rowCount ?>"><?= encode($order->Row_ID + 1000) ?></td>
                                <td rowspan="<?= $rowCount ?>"><?= encode($order->Date) ?></td>
                                <td rowspan="<?= $rowCount ?>"><?= encode($order->Total_Books) ?></td>
                            <?php endif; ?>
                            <!-- Display book details -->
                            <td><?= encode($book) ?></td>
                            <?php if ($index === 0): ?>
                                <td rowspan="<?= $rowCount ?>">RM <?= number_format($order->Total, 2) ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td style="color: black; background: #fff2d7; text-align: right; font-weight: bold;" colspan="2"><br>Total Records: </td>
                    <td style="color: black; background: #fff2d7; text-align: center; font-weight: bold;" colspan="1"><?= count($orders) ?></td>
                    <td style="color: black; background: #fff2d7; text-align: right; font-weight: bold;" colspan="1"><br>Total Books Ordered: </td>
                    <td style="color: black; background: #fff2d7; text-align: center; font-weight: bold;" colspan="1"><?= $totalBooksOrdered ?></td>
                    <td style="color: black; background: #fff2d7; text-align: right; font-weight: bold" colspan="1"><br>Total Order Amount (RM): </td>
                    <td style="color: black; background: #fff2d7; text-align: center; font-weight: bold;" colspan="2"><?= number_format($totalOrderAmount, 2) ?></td>
                </tr>
            </tfoot>
        </table>
        <!-- Pagination -->
        <?= $p->html() ?>
    <?php endif; ?>
</div>

<?php require_once '../_foot.php'; // Include footer ?>