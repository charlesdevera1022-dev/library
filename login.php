<?php
include 'connection.php';
if (isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $result = run_query($conn, "SELECT * FROM users WHERE email = ?", "s", array($email));
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($pass, $user['password'])) {
        session_regenerate_id(true);               
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Wrong email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/style.css" rel="stylesheet">
</head>
<body class="login-page">
  <main class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">
        <section class="card login-card">
          <div class="card-body p-4 text-center">

            <img src="img/pcnl.png" alt="Logo" style="height: 110px;">
            <h1 class="h4 mt-3 mb-1">Yearning Library Management System</h1>
            <p class="text-muted mb-4">Sign in to continue</p>

            <div class="text-start">
              <?php if (isset($_SESSION['message'])) { ?>
                <div class="alert alert-<?php echo e($_SESSION['message_type']); ?> py-2"><?php echo e($_SESSION['message']); ?></div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
              <?php } ?>
              <?php if ($error != "") { ?>
                <div class="alert alert-danger py-2"><?php echo e($error); ?></div>
              <?php } ?>

              <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                  <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" required>
                  <div class="invalid-feedback">Please enter your email.</div>
                </div>
                <div class="mb-3">
                  <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                  <div class="invalid-feedback">Please enter your password.</div>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-lg w-100">Login</button>
              </form>
            </div>

            <p class="mt-3 mb-0">New member? <a href="register.php">Create an account</a></p>
             <p class="mt-3 mb-0">In case of emergency, <br>Contact: <a href="register.php">charlesdevera1022@gmail.com</a></p>
             <p>09621322353</p>

          </div>
        </section>
      </div>
    </div>
  </main>
  <script src="assets/app.js"></script>
</body>
</html>
