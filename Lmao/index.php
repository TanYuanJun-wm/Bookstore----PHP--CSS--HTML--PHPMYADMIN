<?php
require '_base.php';

$_title = 'Index';

include '_head.php';

?>
    <div class="container">
        <div id="abc">
            <h1 id="text">Book.</h1>
            <h3 id="text">Welcome to our bookstore!</h3>
            <p id="text">Founded in 2019, <b><i>Bro Bookstore</b></i> is a passion project born out of a love for literature and a mission to make books accessible to everyone. 
            What started as a small online store has grown into a thriving community of book lovers, offering a curated selection of 
            bestsellers, rare finds, and digital gems. </p>
            <br>
        </div>
    </div>
    <br><br><br><br> 

    <?php if ($message = temp('info')): ?>
    <div id="info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
<?php
include '_foot.php';

        
