<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="shortcut icon" href="/images/favicon.png">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Brother Bookstore'; ?></title>
</head>

<body>   
     <nav class="nav">
        <ul>
            <li><a href="/">Home</a></li>
            <?php if ($_user?->Role != 'Admin'): ?>
                <li><a href="/page/realMenu.php">Menu</a></li>
            <?php endif ?>

        <?php if ($_user?->Role == 'Admin'): ?>
            <li><a href="/page/menu.php">Menu</a></li>
            <li><a href="/admin/userlist.php">Member List</a></li>
            <li><a href="/admin/order_list.php">Order List</a></li>
        <?php endif ?>

        <?php if ($_user?->Role == 'Member'): ?>
            <li><a href="/user/cart.php">Cart
                <?php
                $cart = get_cart();
                $count = count(get_cart() ?? []);   
                if ($count) echo "($count)";
                ?>
                
            </a></li>
        <?php endif ?>
        
        <?php if ($_user?->Role == 'Member'): ?>
            <li><a href="/user/order_history.php">Order History</a></li>
        <?php endif ?>

        <?php if ($_user): ?>
            <div class="profile-dropdown">
        <button class="profile-button">
            <?= $_user->Name ?> (<?= $_user->Role ?>)
        </button>
        <ul class="dropdown-menu">
            <li><a href="/user/profile.php">Profile</a></li>
            <li><a href="/user/password.php">Password</a></li>
            <li><a href="/logout.php">Logout</a></li>
            <li><img src="/photos/<?= $_user->Photo ?>"></li>
            </div>
        </ul>
    </div>
        <?php else: ?>
            <li><a href="/user/register.php">Register</a></li>
            <li><a href="/login.php">Login</a></li>
        <?php endif ?>
        </ul>
    </nav>
    <div class="hero">

    <h1 class="h1"><?= $_title ?? 'Untitled' ?></h1>
    
    </div>

