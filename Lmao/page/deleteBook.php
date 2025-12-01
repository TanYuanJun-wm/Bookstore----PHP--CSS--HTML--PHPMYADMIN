<?php
require '../_base.php';
// ----------------------------------------------------------------------------

auth('Admin');

if (is_post()) {
    $Book_ID = req('Book_ID');

    if (!$Book_ID) {
        temp('error', 'Invalid Book ID!');
        redirect('/page/menu.php');
        exit;
    }

    // Delete related records in the orderlist_book table
    $stm = $_db->prepare('DELETE FROM orderlist_book WHERE Book_ID = ?');
    $stm->execute([$Book_ID]);

    // Delete the book from the books table
    $stm = $_db->prepare('DELETE FROM books WHERE Book_ID = ?');
    $stm->execute([$Book_ID]);

    temp('info', 'Book deleted successfully!');
    redirect('menu.php');
}
?>
