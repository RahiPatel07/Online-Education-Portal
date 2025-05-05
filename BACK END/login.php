<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$page_title = "Login";

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);
   
   if($select_user->rowCount() > 0){
     setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:home.php');
   }else{
      $message[] = 'Incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login | E-Learning Platform</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS files -->
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <link rel="stylesheet" href="../FRONT END/css/features/auth.css">

   <style>
      body {
         min-height: 100vh;
         background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
         display: flex;
         flex-direction: column;
         overflow-x: hidden;
      }
      .bg-animated { display: none; }
      .centered-card {
         flex: 1;
         display: flex;
         align-items: center;
         justify-content: center;
         z-index: 1;
         min-height: 80vh;
      }
      .glass-card {
         background: var(--white);
         box-shadow: var(--shadow-lg);
         border-radius: var(--radius-xl,2.2rem);
         border: 1.5px solid var(--border-color,rgba(255,255,255,0.25));
         padding: 3.5rem 2.8rem 2.8rem 2.8rem;
         width: 100%;
         max-width: 480px;
         min-height: 440px;
         animation: cardIn 0.8s cubic-bezier(.68,-0.55,.27,1.55);
         position: relative;
         display: flex;
         flex-direction: column;
         justify-content: center;
      }
      @keyframes cardIn {
         0% { opacity: 0; transform: translateY(40px) scale(0.95); }
         100% { opacity: 1; transform: translateY(0) scale(1); }
      }
      .glass-card .logo {
         display: flex;
         align-items: center;
         gap: 0.7rem;
         font-size: 1.5rem;
         font-weight: 700;
         color: var(--primary);
         margin-bottom: 1.5rem;
         letter-spacing: 1px;
         justify-content: center;
      }
      .glass-card h1 {
         font-size: 2.3rem;
         font-weight: 800;
         margin-bottom: 1.7rem;
         text-align: center;
         color: var(--primary-dark);
         letter-spacing: 1px;
      }
      .glass-card form {
         display: flex;
         flex-direction: column;
         gap: 1.5rem;
      }
      .form-anim {
         position: relative;
         margin-bottom: 0.5rem;
      }
      .form-anim input {
         width: 100%;
         padding: 1.3rem 1.2rem 1.3rem 2.8rem;
         border: 1.5px solid var(--border-color, #e0e0e0);
         border-radius: var(--radius-lg,0.95rem);
         font-size: 1.18rem;
         background: var(--gray-50, #f8f9fa);
         transition: border 0.2s, box-shadow 0.2s;
         outline: none;
         color: var(--text-color, #222);
      }
      .form-anim input:focus {
         border-color: var(--primary);
         box-shadow: 0 0 0 2px var(--primary-light, #4361ee33);
         background: var(--white);
      }
      .form-anim label {
         position: absolute;
         left: 2.8rem;
         top: 50%;
         transform: translateY(-50%);
         color: var(--gray-600,#888);
         font-size: 1.13rem;
         pointer-events: none;
         transition: 0.2s cubic-bezier(.68,-0.55,.27,1.55);
         background: transparent;
      }
      .form-anim input:focus + label,
      .form-anim input:not(:placeholder-shown) + label {
         top: 0.3rem;
         left: 2.8rem;
         font-size: 1.01rem;
         color: var(--primary);
         background: var(--white);
         padding: 0 0.2rem;
         border-radius: 0.2rem;
         animation: labelFloat 0.3s;
      }
      @keyframes labelFloat {
         0% { top: 50%; font-size: 1.13rem; }
         100% { top: 0.3rem; font-size: 1.01rem; }
      }
      .form-anim .input-icon {
         position: absolute;
         left: 1.1rem;
         top: 50%;
         transform: translateY(-50%);
         color: var(--primary);
         font-size: 1.18rem;
         opacity: 0.8;
         transition: transform 0.2s;
      }
      .form-anim input:focus ~ .input-icon {
         transform: translateY(-50%) scale(1.2);
         color: var(--primary-dark);
      }
      .show-hide {
         position: absolute;
         right: 1.2rem;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
         color: var(--gray-600,#888);
         font-size: 1.18rem;
         opacity: 0.8;
         transition: color 0.2s;
      }
      .show-hide:hover { color: var(--primary); }
      .glass-card .alert {
         padding: 0.8rem 1rem;
         border-radius: 0.75rem;
         background: #fff5f5;
         color: #c53030;
         border: 1px solid #feb2b2;
         font-size: 1.08rem;
         margin-bottom: 0.5rem;
         display: flex;
         align-items: center;
         gap: 0.7rem;
         animation: shake 0.3s;
      }
      @keyframes shake {
         0% { transform: translateX(0); }
         20% { transform: translateX(-6px); }
         40% { transform: translateX(6px); }
         60% { transform: translateX(-4px); }
         80% { transform: translateX(4px); }
         100% { transform: translateX(0); }
      }
      .glass-card .btn-login {
         width: 100%;
         padding: 1.25rem;
         background: var(--primary);
         color: var(--white);
         border: none;
         border-radius: var(--radius-lg,0.95rem);
         font-size: 1.18rem;
         font-weight: 600;
         cursor: pointer;
         transition: background 0.2s, transform 0.2s;
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 0.7rem;
         margin-top: 0.7rem;
         box-shadow: 0 2px 8px var(--primary-light,#4361ee22);
      }
      .glass-card .btn-login:hover {
         background: var(--primary-dark);
         transform: translateY(-2px) scale(1.03);
      }
      .glass-card .links {
         margin-top: 1.2rem;
         text-align: center;
         font-size: 1.08rem;
         color: var(--gray-600,#666);
         display: flex;
         flex-direction: column;
         gap: 0.3rem;
      }
      .glass-card .links a {
         color: var(--primary);
         text-decoration: none;
         font-weight: 500;
         transition: color 0.2s;
      }
      .glass-card .links a:hover {
         color: var(--primary-dark);
      }
      @media (max-width: 600px) {
         .centered-card, .glass-card { padding: 1rem 0.5rem; }
         .glass-card { max-width: 98vw; min-height: 0; }
      }
   </style>
</head>
<body>
<div class="bg-animated"></div>
<div class="centered-card">
   <div class="glass-card">
      <div class="logo"><i class="fas fa-graduation-cap"></i> Educa.</div>
      <h1>Sign In</h1>
      <form action="" method="post" autocomplete="off">
         <?php if(isset($message)){ foreach($message as $msg){ echo '<div class="alert"><i class="fas fa-exclamation-circle"></i> <span>'.$msg.'</span></div>'; } } ?>
         <div class="form-anim">
            <input type="email" name="email" id="email" required placeholder=" " autocomplete="username" />
            <label for="email">Email Address</label>
            <span class="input-icon"><i class="fas fa-envelope"></i></span>
         </div>
         <div class="form-anim">
            <input type="password" name="pass" id="password" required placeholder=" " autocomplete="current-password" />
            <label for="password">Password</label>
            <span class="input-icon"><i class="fas fa-lock"></i></span>
            <span class="show-hide" onclick="togglePassword()"><i class="fas fa-eye" id="eyeIcon"></i></span>
         </div>
         <button type="submit" name="submit" class="btn-login">
            <span>Login Now</span> <i class="fas fa-arrow-right"></i>
         </button>
      </form>
      <div class="links">
         <a href="#">Forgot password?</a>
         <a href="register.php">Create an account</a>
         <a href="home.php">Back to Home</a>
      </div>
   </div>
</div>
<?php include 'components/footer.php'; ?>

<!-- Custom JS file -->
<script src="../FRONT END/js/modern.js"></script>
<script>
function togglePassword() {
   const passInput = document.getElementById('password');
   const eyeIcon = document.getElementById('eyeIcon');
   if(passInput.type === 'password') {
      passInput.type = 'text';
      eyeIcon.classList.remove('fa-eye');
      eyeIcon.classList.add('fa-eye-slash');
   } else {
      passInput.type = 'password';
      eyeIcon.classList.remove('fa-eye-slash');
      eyeIcon.classList.add('fa-eye');
   }
}
// Floating label effect for autofill
window.addEventListener('DOMContentLoaded', function() {
   document.querySelectorAll('.form-anim input').forEach(function(input) {
      if(input.value) {
         input.classList.add('filled');
      }
      input.addEventListener('input', function() {
         if(this.value) this.classList.add('filled');
         else this.classList.remove('filled');
      });
   });
});
</script>
   
</body>
</html>