<?php
include 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $phone_number = sanitize_input($_POST['phone_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, phone_number, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $phone_number, $hashed_password);

        if ($stmt->execute()) {
            $success = "Registration successful. You can now log in.";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
        <h1 class="text-4xl font-bold mb-8 text-center">Register</h1>

        <?php
        if ($error) echo display_error($error);
        if ($success) echo display_success($success);
        ?>

        <form action="" method="post" class="space-y-4">
            <div>
                <label for="username" class="block mb-2">Username</label>
                <input type="text" id="username" name="username" required class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label for="email" class="block mb-2">Email</label>
                <input type="email" id="email" name="email" required class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label for="phone_number" class="block mb-2">Phone Number</label>
                <input type="tel" id="phone_number" name="phone_number" required class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label for="password" class="block mb-2">Password</label>
                <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label for="confirm_password" class="block mb-2">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="text-center">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition duration-300">Register</button>
            </div>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/includes/footer.php';
?>