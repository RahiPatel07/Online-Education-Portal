<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$page_title = "Register";

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   
   if($select_user->rowCount() > 0){
      $message[] = 'email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm passowrd not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
         $insert_user->execute([$id, $name, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         
         $verify_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
         $verify_user->execute([$email, $pass]);
         $row = $verify_user->fetch(PDO::FETCH_ASSOC);
         
         if($verify_user->rowCount() > 0){
            setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
            header('location:home.php');
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register | E-Learning Platform</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS files -->
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <link rel="stylesheet" href="../FRONT END/css/features/auth.css">

   <style>
      body {
         min-height: 100vh;
         background: linear-gradient(120deg, #6c5ce7 0%, #4361ee 100%);
         display: flex;
         flex-direction: column;
         overflow-x: hidden;
      }
      .bg-animated {
         position: fixed;
         top: 0; left: 0; width: 100vw; height: 100vh;
         z-index: 0;
         pointer-events: none;
         background: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.08) 0, transparent 60%),
                     radial-gradient(circle at 80% 70%, rgba(255,255,255,0.10) 0, transparent 60%);
         animation: bgMove 10s linear infinite alternate;
      }
      @keyframes bgMove {
         0% { background-position: 20% 30%, 80% 70%; }
         100% { background-position: 30% 40%, 70% 60%; }
      }
      .centered-card {
         flex: 1;
         display: flex;
         align-items: center;
         justify-content: center;
         z-index: 1;
         min-height: 80vh;
      }
      .glass-card {
         background: rgba(255,255,255,0.18);
         box-shadow: 0 8px 32px 0 rgba(31,38,135,0.18);
         backdrop-filter: blur(16px) saturate(180%);
         -webkit-backdrop-filter: blur(16px) saturate(180%);
         border-radius: 2.2rem;
         border: 1.5px solid rgba(255,255,255,0.25);
         padding: 3.5rem 2.8rem 2.8rem 2.8rem;
         width: 100%;
         max-width: 540px;
         min-height: 540px;
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
         color: var(--primary-blue);
         margin-bottom: 1.5rem;
         letter-spacing: 1px;
         justify-content: center;
      }
      .glass-card h1 {
         font-size: 2.3rem;
         font-weight: 800;
         margin-bottom: 1.7rem;
         text-align: center;
         color: #222;
         letter-spacing: 1px;
      }
      .glass-card form {
         display: flex;
         flex-direction: column;
         gap: 1.5rem;
      }
      .form-row {
         display: flex;
         gap: 1rem;
      }
      .form-row .form-anim { flex: 1; margin-bottom: 0; }
      .form-anim {
         position: relative;
         margin-bottom: 0.5rem;
      }
      .form-anim input {
         width: 100%;
         padding: 1.3rem 1.2rem 1.3rem 2.8rem;
         border: 1.5px solid #e0e0e0;
         border-radius: 0.95rem;
         font-size: 1.18rem;
         background: rgba(255,255,255,0.7);
         transition: border 0.2s, box-shadow 0.2s;
         outline: none;
      }
      .form-anim input:focus {
         border-color: var(--primary-blue);
         box-shadow: 0 0 0 2px #4361ee33;
      }
      .form-anim label {
         position: absolute;
         left: 2.8rem;
         top: 50%;
         transform: translateY(-50%);
         color: #888;
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
         color: var(--primary-blue);
         background: #fff;
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
         color: var(--primary-blue);
         font-size: 1.18rem;
         opacity: 0.8;
         transition: transform 0.2s;
      }
      .form-anim input:focus ~ .input-icon {
         transform: translateY(-50%) scale(1.2);
         color: #222;
      }
      .show-hide {
         position: absolute;
         right: 1.2rem;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
         color: #888;
         font-size: 1.18rem;
         opacity: 0.8;
         transition: color 0.2s;
      }
      .show-hide:hover { color: var(--primary-blue); }
      .custom-file {
         position: relative;
         margin-bottom: 0.5rem;
      }
      .custom-file-input {
         position: absolute;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         opacity: 0;
         cursor: pointer;
      }
      .custom-file-label {
         display: flex;
         align-items: center;
         gap: 1rem;
         padding: 1.1rem 1rem;
         border: 2px dashed var(--gray-300);
         border-radius: 0.75rem;
         font-size: 1.1rem;
         color: var(--gray-600);
         background: #f8f9fa;
         transition: border 0.2s, color 0.2s;
         cursor: pointer;
      }
      .custom-file-input:hover + .custom-file-label {
         border-color: var(--primary-blue);
         color: var(--primary-blue);
      }
      .form-check {
         display: flex;
         align-items: flex-start;
         gap: 1rem;
         margin-bottom: 0.5rem;
         font-size: 1rem;
      }
      .form-check-input {
         width: 1.2rem;
         height: 1.2rem;
         margin-top: 0.2rem;
         border-radius: 6px;
         border: 2px solid var(--gray-300);
         cursor: pointer;
      }
      .form-check-label {
         color: var(--gray-600);
         line-height: 1.4;
      }
      .form-check-label a {
         color: var(--primary-blue);
         text-decoration: none;
      }
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
      .glass-card .btn-register {
         width: 100%;
         padding: 1.25rem;
         background: var(--primary-blue);
         color: white;
         border: none;
         border-radius: 0.95rem;
         font-size: 1.18rem;
         font-weight: 600;
         cursor: pointer;
         transition: background 0.2s, transform 0.2s;
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 0.7rem;
         margin-top: 0.7rem;
         box-shadow: 0 2px 8px #4361ee22;
      }
      .glass-card .btn-register:hover {
         background: #3a47d5;
         transform: translateY(-2px) scale(1.03);
      }
      .glass-card .links {
         margin-top: 1.2rem;
         text-align: center;
         font-size: 1.08rem;
         color: #666;
         display: flex;
         flex-direction: column;
         gap: 0.3rem;
      }
      .glass-card .links a {
         color: var(--primary-blue);
         text-decoration: none;
         font-weight: 500;
         transition: color 0.2s;
      }
      .glass-card .links a:hover {
         color: #222;
      }
      @media (max-width: 600px) {
         .centered-card, .glass-card { padding: 1rem 0.5rem; }
         .glass-card { max-width: 98vw; min-height: 0; }
         .form-row { flex-direction: column; gap: 0; }
      }
   </style>
</head>
<body>
<div class="bg-animated"></div>
<div class="centered-card">
   <div class="glass-card">
      <div class="logo"><i class="fas fa-graduation-cap"></i> Educa.</div>
      <h1>Create Account</h1>
      <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
         <?php if(isset($message)){ foreach($message as $msg){ echo '<div class="alert"><i class="fas fa-exclamation-circle"></i> <span>'.$msg.'</span></div>'; } } ?>
         <div class="form-row">
            <div class="form-anim">
               <input type="text" name="name" id="name" required placeholder=" " autocomplete="name" />
               <label for="name">Full Name</label>
               <span class="input-icon"><i class="fas fa-user"></i></span>
            </div>
            <div class="form-anim">
               <input type="email" name="email" id="email" required placeholder=" " autocomplete="email" />
               <label for="email">Email Address</label>
               <span class="input-icon"><i class="fas fa-envelope"></i></span>
            </div>
         </div>
         <div class="form-row">
            <div class="form-anim">
               <input type="password" name="pass" id="password" required placeholder=" " autocomplete="new-password" />
               <label for="password">Password</label>
               <span class="input-icon"><i class="fas fa-lock"></i></span>
               <span class="show-hide" onclick="togglePassword('password', 'eyeIcon1')"><i class="fas fa-eye" id="eyeIcon1"></i></span>
            </div>
            <div class="form-anim">
               <input type="password" name="cpass" id="cpassword" required placeholder=" " autocomplete="new-password" />
               <label for="cpassword">Confirm Password</label>
               <span class="input-icon"><i class="fas fa-lock"></i></span>
               <span class="show-hide" onclick="togglePassword('cpassword', 'eyeIcon2')"><i class="fas fa-eye" id="eyeIcon2"></i></span>
            </div>
         </div>
         <div class="form-anim custom-file">
            <input type="file" name="image" accept="image/*" required class="custom-file-input" id="profilePicInput">
            <label for="profilePicInput" class="custom-file-label">
               <i class="fas fa-cloud-upload-alt"></i>
               <span id="profilePicLabel">Click to upload profile picture</span>
            </label>
         </div>
         <div class="form-check">
            <input type="checkbox" id="terms" required class="form-check-input">
            <label for="terms" class="form-check-label">
               I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
            </label>
         </div>
         <button type="submit" name="submit" class="btn-register">
            <span>Create Account</span> <i class="fas fa-arrow-right"></i>
         </button>
      </form>
      <div class="links">
         <a href="login.php">Already have an account? Login Now</a>
         <a href="home.php">Back to Home</a>
      </div>
   </div>
</div>
<?php include 'components/footer.php'; ?>

<!-- Custom JS file -->
<script src="../FRONT END/js/modern.js"></script>
<script>
function togglePassword(inputId, eyeId) {
   const passInput = document.getElementById(inputId);
   const eyeIcon = document.getElementById(eyeId);
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
   // File input label update
   const fileInput = document.getElementById('profilePicInput');
   const fileLabel = document.getElementById('profilePicLabel');
   if(fileInput && fileLabel) {
      fileInput.addEventListener('change', function() {
         if (this.files.length > 0) {
            fileLabel.textContent = this.files[0].name;
         } else {
            fileLabel.textContent = 'Click to upload profile picture';
         }
      });
   }
});
</script>
   
</body>
</html>