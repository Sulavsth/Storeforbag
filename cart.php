<?php
require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$cart_items = [];
$total = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $bag_id => $quantity) {
        $bag = get_bag_by_id($conn, $bag_id);
        if ($bag) {
            $cart_items[] = [
                'id' => $bag_id,
                'name' => $bag['name'],
                'price' => $bag['price'],
                'quantity' => $quantity,
                'subtotal' => $bag['price'] * $quantity
            ];
            $total += $bag['price'] * $quantity;
        }
    }
}


$shipping_cost = calculate_shipping_cost($cart_items);

?>


<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Your Cart</h1>
    <?php if (empty($cart_items)): ?>
        <p class="text-gray-600">Your cart is empty.</p>
    <?php else: ?>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $item['name']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">₹<?php echo number_format($item['price'], 2); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" class="quantity-input w-16 px-2 py-1 border rounded" data-id="<?php echo $item['id']; ?>" value="<?php echo $item['quantity']; ?>" min="1">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">₹<?php echo number_format($item['subtotal'], 2); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="remove-item text-red-600 hover:text-red-800" data-id="<?php echo $item['id']; ?>">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-bold">Shipping:</td>
                        <td class="px-6 py-4 text-right">₹<?php echo number_format($shipping_cost, 2); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-bold">Total:</td>
                        <td class="px-6 py-4 text-right font-bold">₹<?php echo number_format($total, 2); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="mt-8 text-right">
            <a href="checkout.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Proceed to Checkout
            </a>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.quantity-input').on('change', function() {
        updateCartItem($(this).data('id'), $(this).val());
    });

    $('.remove-item').on('click', function() {
        removeCartItem($(this).data('id'));
    });

    function updateCartItem(id, quantity) {
        $.post('update_cart.php', { id: id, quantity: quantity }, function() {
            location.reload();
        });
    }

    function removeCartItem(id) {
        $.post('remove_cart_item.php', { id: id }, function() {
            location.reload();
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>