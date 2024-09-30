<?php
include 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    // Check for master admin login
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['user_id'] = 0; // Special ID for master admin
        $_SESSION['is_admin'] = 1;
        $_SESSION['username'] = 'Admin';
        redirect('/admin/dashboard.php');
    } else {
        // Regular user login process
        $stmt = $conn->prepare("SELECT id, username, password, is_admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['is_admin'] = $user['is_admin'];
                $_SESSION['username'] = $user['username'];
                redirect('/index.php');
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }

        $stmt->close();
    }
}
?>
  <div class="container mx-auto px-4 py-8">
      <div class="max-w-md mx-auto">
          <h1 class="text-4xl font-bold mb-8 text-center">Login</h1>

          <?php
          if ($error) echo display_error($error);
          ?>

          <form action="" method="post" class="space-y-4">
              <div>
                  <label for="username" class="block mb-2">Username</label>
                  <input type="text" id="username" name="username" required class="w-full px-3 py-2 border rounded">
              </div>
              <div>
                  <label for="password" class="block mb-2">Password</label>
                  <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded">
              </div>
              <div class="text-center">
                  <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition duration-300">Login</button>
              </div>
          </form>
      </div>
  </div>


  <?php
require_once __DIR__ . '/includes/footer.php';
?>
