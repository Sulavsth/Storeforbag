<?php
session_start();
require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['order_id'];
$order = get_order_details($order_id);
$order_items = get_order_items($order_id);

if (!$order || $order['user_id'] != $_SESSION['user_id']) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - EbagStores</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Order Confirmation</h1>
        <h2 class="text-2xl font-semibold mb-6">Order #<?php echo $order_id; ?></h2>
        <p class="text-green-600 font-semibold mb-8">Thank you for your order!</p>
        
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4">Order Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <p><span class="font-semibold">Total:</span> ₹<?php echo number_format($order['total'], 2); ?></p>
                <p><span class="font-semibold">Shipping:</span> ₹<?php echo number_format($order['shipping'], 2); ?></p>
                <p><span class="font-semibold">Payment Method:</span> <?php echo ucfirst($order['payment_method']); ?></p>
                
                <?php if ($order['payment_method'] == 'online' && $order['payment_evidence']): ?>
                    <p><span class="font-semibold">Payment Evidence:</span> <a href="<?php echo $order['payment_evidence']; ?>" target="_blank" class="text-blue-600 hover:text-blue-800">View</a></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <h3 class="text-xl font-semibold p-6 bg-gray-50 border-b">Order Items</h3>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $item['quantity']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">₹<?php echo number_format($item['price'], 2); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">₹<?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Add this button just before the closing </div> tag of the main container -->
<div class="mt-8 text-center">
    <a href="bags.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-block">
        Continue Shopping
    </a>
</div>

</div>
</body>
</html>

    </div>
</body>
</html>
