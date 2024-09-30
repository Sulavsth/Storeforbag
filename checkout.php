<?php
session_start();
require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

function create_order($user_id, $total, $shipping) {
    global $conn;
    $sql = "INSERT INTO orders (user_id, total, shipping, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idd", $user_id, $total, $shipping);
    return $stmt->execute() ? $conn->insert_id : false;
}
function add_order_items($order_id, $cart_items) {
    global $conn;
    $sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($cart_items as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $stmt->bind_param("iisidi", $order_id, $item['id'], $item['name'], $item['quantity'], $item['price'], $subtotal);
        $stmt->execute();
    }
}

// Add this function to handle file uploads
function save_payment_evidence($file) {
    $target_dir = "uploads/payment_evidence/";
    $target_file = $target_dir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file was actually uploaded
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        error_log("File upload failed: No file was uploaded.");
        return false;
    }

    // Check if image file is a actual image or fake image
    $check = @getimagesize($file["tmp_name"]);
    if($check === false) {
        error_log("File upload failed: Uploaded file is not a valid image.");
        return false;
    }

    // Check file size
    if ($file["size"] > 500000) {
        error_log("File upload failed: File is too large.");
        return false;
    }

    // Allow certain file formats
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    if(!in_array($imageFileType, $allowed_types)) {
        error_log("File upload failed: Invalid file type - " . $imageFileType);
        return false;
    }

    // Additional MIME type check
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed_mimes = array('image/jpeg', 'image/png', 'image/gif');
    if (!in_array($mime, $allowed_mimes)) {
        error_log("File upload failed: Invalid MIME type - " . $mime);
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;  // Return the file path
    } else {
        error_log("File upload failed: Unable to move uploaded file.");
        return false;
    }
}// Add this function to handle payment method selection
function process_payment($order_id, $payment_method, $payment_evidence) {
    global $conn;
    $sql = "UPDATE orders SET payment_method = ?, payment_evidence = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $payment_method, $payment_evidence, $order_id);
    return $stmt->execute();
}

// Modify the process_order function to include payment method
function process_order($conn, $cart_items, $payment_method, $payment_evidence) {
    $user_id = $_SESSION['user_id'];
    $subtotal = calculate_total($cart_items);
    $shipping = calculate_shipping_cost($cart_items);
    $total = $subtotal + $shipping;
    $conn->begin_transaction();

    try {
        // Create a single order row with all information
        $order_sql = "INSERT INTO orders (user_id, total, shipping, status, payment_method, payment_evidence) 
                      VALUES (?, ?, ?, 'pending', ?, ?)";
        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("iddss", $user_id, $total, $shipping, $payment_method, $payment_evidence);
        $order_stmt->execute();
        $order_id = $conn->insert_id;

        // Add order items
        $item_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) 
                     VALUES (?, ?, ?, ?, ?, ?)";
        $item_stmt = $conn->prepare($item_sql);

        foreach ($cart_items as $item) {
            $item_subtotal = $item['price'] * $item['quantity'];
            $item_stmt->bind_param("iisidi", $order_id, $item['id'], $item['name'], $item['quantity'], $item['price'], $item_subtotal);
            $item_stmt->execute();
        }

        $conn->commit();
        return $order_id;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Order processing failed: " . $e->getMessage());
        return false;
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

// Use session cart data instead of database
$cart_items = [];
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if (is_array($item)) {
            $cart_items[] = [
                'id' => $item['bag_id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity']
            ];
        } else {
            // Fetch bag details from database
            $bag = get_bag_by_id($conn, $key);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $payment_evidence = null;

    if ($payment_method === 'online' && isset($_FILES['payment_evidence'])) {
        $payment_evidence = save_payment_evidence($_FILES['payment_evidence']);
        if (!$payment_evidence) {
            $error_message = "Failed to upload payment evidence. Please try again.";
        }
    }

    if (!$error_message) {
        $order_id = process_order($conn, $cart_items, $payment_method, $payment_evidence);
        if ($order_id) {
            $success_message = "Order placed successfully! Order ID: $order_id";
            unset($_SESSION['cart']);
        } else {
            $error_message = "Failed to process order. Please try again.";
        }
    }
}
            if ($bag) {
                $cart_items[] = [
                    'id' => $key,
                    'name' => $bag['name'],
                    'price' => $bag['price'],
                    'quantity' => $item,
                    'subtotal' => $bag['price'] * $item
                ];
            }
        
    


$total = calculate_total($cart_items);
$shipping_cost = calculate_shipping_cost($cart_items);
$grand_total = $total + $shipping_cost;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $payment_evidence = null;
    if ($payment_method === 'online' && isset($_FILES['payment_evidence'])) {
        $payment_evidence = save_payment_evidence($_FILES['payment_evidence']);
        
    }

    if (!$error_message) {
        $order_id = process_order($conn, $cart_items, $payment_method, $payment_evidence);
        
        if ($order_id) {
            if ($payment_method === 'online') {
                if (process_payment($order_id, $payment_method, $payment_evidence)) {
                    header("Location: order_confirmation.php?order_id=" . $order_id);
                    exit();
                } else {
                    $error_message = "Order placed, but payment evidence upload failed. Please contact support.";
                }
            } else {
                header("Location: order_confirmation.php?order_id=" . $order_id);
                exit();
            }
            unset($_SESSION['cart']);
        } else {
            $error_message = "Failed to process order. Please try again.";
        }
    }
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - EbagStores</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1, h2 { color: #333; }
        form { background: #f4f4f4; padding: 20px; border-radius: 5px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .error { color: red; }
        .success { color: green; }
        button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .payment-options { margin-bottom: 20px; }
        .qr-code { max-width: 200px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Checkout</h1>
    
    <?php if ($error_message): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php else: ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <h2>Order Summary</h2>
        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty. Please add items to your cart before checking out.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>Rs <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div>
                <p>Subtotal: Rs <?php echo number_format($total, 2); ?></p>
                <p>Shipping: Rs <?php echo number_format($shipping_cost, 2); ?></p>
                <p><strong>Grand Total: Rs <?php echo number_format($grand_total, 2); ?></strong></p>
            </div>

            <div class="payment-options">
                <h2>Payment Method</h2>
                <label>
                    <input type="radio" name="payment_method" value="cash" required> Cash on Delivery
                </label>
                <label>
                    <input type="radio" name="payment_method" value="online" required> Pay Online
                </label>
            </div>

            <div id="online-payment" style="display: none;">
                <h3>Online Payment</h3>
                <p>Please scan the QR code below to make the payment:</p>
                <img src="path/to/qr-code.png" alt="Payment QR Code" class="qr-code">
                <label for="payment_evidence">Upload Payment Evidence:</label>
                <input type="file" name="payment_evidence" id="payment_evidence" accept="image/*">
            </div>

            <button type="submit">Place Order</button>
        <?php endif; ?>
    </form>

    <script>
        document.querySelectorAll('input[name="payment_method"]').forEach((elem) => {
            elem.addEventListener("change", function(event) {
                var onlinePayment = document.getElementById("online-payment");
                onlinePayment.style.display = event.target.value === "online" ? "block" : "none";
            });
        });
    </script>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
