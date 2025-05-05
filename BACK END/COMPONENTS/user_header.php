<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header class="header">
   <nav class="navbar">
      <button type="button" class="menu-btn" id="menu-btn">
         <i class="fas fa-bars"></i>
      </button>
      
      <a href="home.php" class="logo">
         <i class="fas fa-graduation-cap"></i>
         <span>Educa.</span>
      </a>

      <?php if($current_page != 'login.php' && $current_page != 'register.php'): ?>
      <div class="nav-links">
         <a href="home.php" class="nav-link <?= $current_page == 'home.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Home
         </a>
         <a href="courses.php" class="nav-link <?= $current_page == 'courses.php' ? 'active' : ''; ?>">
            <i class="fas fa-graduation-cap"></i> Courses
         </a>
         <a href="teachers.php" class="nav-link <?= $current_page == 'teachers.php' ? 'active' : ''; ?>">
            <i class="fas fa-chalkboard-user"></i> Teachers
         </a>
         <a href="about.php" class="nav-link <?= $current_page == 'about.php' ? 'active' : ''; ?>">
            <i class="fas fa-question"></i> About
         </a>
         <a href="contact.php" class="nav-link <?= $current_page == 'contact.php' ? 'active' : ''; ?>">
            <i class="fas fa-headset"></i> Contact
         </a>
      </div>

      <div class="nav-right">
         <form action="search_course.php" method="post" class="search-box">
            <input type="text" name="search_course" class="search-input" placeholder="Search courses..." required maxlength="100">
            <button type="submit" class="search-btn" name="search_course_btn">
               <i class="fas fa-search"></i>
            </button>
         </form>

         <div class="theme-toggle" id="theme-toggle">
            <i class="fas fa-sun"></i>
         </div>

         <div class="profile-dropdown">
            <?php
               $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_profile->execute([$user_id]);
               if($select_profile->rowCount() > 0){
                  $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="profile-trigger">
               <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="" class="profile-img">
            </div>
            <?php } else { ?>
            <div class="profile-trigger">
               <i class="fas fa-user-circle profile-icon"></i>
            </div>
            <?php } ?>

            <div class="profile-menu">
               <?php if($select_profile->rowCount() > 0){ ?>
               <div class="profile-header">
                  <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
                  <div class="profile-info">
                     <h3><?= $fetch_profile['name']; ?></h3>
                     <span>student</span>
                  </div>
               </div>
               <div class="profile-actions">
                  <a href="profile.php" class="menu-item">
                     <i class="fas fa-user"></i>View Profile
                  </a>
                  <a href="progress.php" class="menu-item">
                     <i class="fas fa-chart-line"></i>My Progress
                  </a>
                  <a href="bookmarks.php" class="menu-item">
                     <i class="fas fa-bookmark"></i>Bookmarks
                  </a>
                  <a href="components/user_logout.php" class="menu-item" onclick="return confirm('Logout from this website?');">
                     <i class="fas fa-sign-out-alt"></i>Logout
                  </a>
               </div>
               <?php } else { ?>
               <div class="profile-actions">
                  <a href="login.php" class="menu-item">
                     <i class="fas fa-sign-in-alt"></i>Login
                  </a>
                  <a href="register.php" class="menu-item">
                     <i class="fas fa-user-plus"></i>Register
                  </a>
               </div>
               <?php } ?>
            </div>
         </div>
      </div>
      <?php endif; ?>
   </nav>
</header>

<style>
.header {
   position: sticky;
   top: 0;
   z-index: 1000;
   background: var(--white);
   border-bottom: 1px solid var(--gray-200);
   transition: background-color var(--transition-medium), border-color var(--transition-medium);
}

.dark .header {
   background: var(--bg-surface);
   border-color: var(--border-color);
}

.navbar {
   display: flex;
   align-items: center;
   justify-content: space-between;
   padding: 1.2rem 2.4rem;
   max-width: 1400px;
   margin: 0 auto;
}

.logo {
   display: flex;
   align-items: center;
   gap: 1rem;
   font-size: 2.4rem;
   font-weight: 600;
   color: var(--primary);
   text-decoration: none;
}

.logo i {
   font-size: 2.8rem;
}

.nav-links {
   display: flex;
   align-items: center;
   gap: 3.2rem;
}

.nav-link {
   color: var(--gray-600);
   text-decoration: none;
   font-size: 1.6rem;
   font-weight: 500;
   transition: color var(--transition-fast);
   display: flex;
   align-items: center;
   gap: 0.8rem;
}

.nav-link i {
   font-size: 1.8rem;
}

.nav-link:hover {
   color: var(--primary);
}

.nav-link.active {
   color: var(--primary);
   font-weight: 600;
}

.search-box {
   position: relative;
   margin: 0 2rem;
}

.search-input {
   background: var(--gray-100);
   border: 2px solid transparent;
   border-radius: var(--radius-full);
   padding: 1rem 4rem 1rem 1.6rem;
   color: var(--gray-900);
   font-size: 1.4rem;
   width: 28rem;
   transition: all var(--transition-fast);
}

.dark .search-input {
   background: var(--gray-800);
   color: var(--white);
}

.search-input:focus {
   background: var(--white);
   border-color: var(--primary);
   box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

.dark .search-input:focus {
   background: var(--gray-800);
}

.search-btn {
   position: absolute;
   right: 1.2rem;
   top: 50%;
   transform: translateY(-50%);
   background: none;
   border: none;
   color: var(--gray-500);
   cursor: pointer;
   font-size: 1.6rem;
   transition: color var(--transition-fast);
}

.search-btn:hover {
   color: var(--primary);
}

.nav-right {
   display: flex;
   align-items: center;
   gap: 2rem;
}

.theme-toggle {
   color: var(--gray-600);
   font-size: 1.8rem;
   cursor: pointer;
   transition: color var(--transition-fast);
   padding: 0.8rem;
   border-radius: var(--radius-full);
   background: var(--gray-100);
}

.dark .theme-toggle {
   background: var(--gray-800);
   color: var(--gray-400);
}

.theme-toggle:hover {
   color: var(--primary);
}

.profile-img {
   width: 4rem;
   height: 4rem;
   border-radius: var(--radius-full);
   object-fit: cover;
   cursor: pointer;
   border: 2px solid var(--primary);
}

.profile-icon {
   font-size: 4rem;
   color: var(--gray-400);
   cursor: pointer;
}

.profile-menu {
   position: absolute;
   top: calc(100% + 1rem);
   right: 2rem;
   background: var(--white);
   border-radius: var(--radius-lg);
   padding: 1rem 0;
   min-width: 22rem;
   box-shadow: var(--shadow-lg);
   border: 1px solid var(--gray-200);
   display: none;
   animation: fadeIn var(--transition-medium) forwards;
}

.dark .profile-menu {
   background: var(--bg-surface);
   border-color: var(--border-color);
}

.profile-menu.active {
   display: block;
}

.profile-header {
   display: flex;
   align-items: center;
   padding: 1.6rem;
   border-bottom: 1px solid var(--gray-200);
}

.dark .profile-header {
   border-color: var(--border-color);
}

.profile-header img {
   width: 4.8rem;
   height: 4.8rem;
   border-radius: var(--radius-full);
   margin-right: 1.6rem;
}

.profile-info h3 {
   color: var(--gray-900);
   font-size: 1.6rem;
   margin-bottom: 0.4rem;
}

.dark .profile-info h3 {
   color: var(--white);
}

.profile-info span {
   color: var(--primary);
   font-size: 1.4rem;
   font-weight: 500;
}

.menu-item {
   display: flex;
   align-items: center;
   padding: 1.2rem 1.6rem;
   color: var(--gray-700);
   text-decoration: none;
   font-size: 1.4rem;
   transition: all var(--transition-fast);
}

.dark .menu-item {
   color: var(--gray-300);
}

.menu-item i {
   margin-right: 1.2rem;
   font-size: 1.6rem;
   color: var(--gray-500);
   transition: color var(--transition-fast);
}

.menu-item:hover {
   background: var(--gray-100);
   color: var(--primary);
}

.dark .menu-item:hover {
   background: var(--gray-800);
}

.menu-item:hover i {
   color: var(--primary);
}

.message {
   position: fixed;
   top: 2rem;
   right: 2rem;
   z-index: 10000;
   background: var(--white);
   padding: 1.2rem 2.4rem;
   border-radius: var(--radius-md);
   box-shadow: var(--shadow-lg);
   border: 1px solid var(--gray-200);
   display: flex;
   align-items: center;
   gap: 1.2rem;
   animation: slideIn var(--transition-medium) forwards;
}

.dark .message {
   background: var(--bg-surface);
   border-color: var(--border-color);
}

.message span {
   font-size: 1.4rem;
   color: var(--gray-700);
}

.dark .message span {
   color: var(--gray-300);
}

.message i {
   font-size: 1.6rem;
   color: var(--gray-500);
   cursor: pointer;
   transition: color var(--transition-fast);
}

.message i:hover {
   color: var(--danger);
}

@keyframes slideIn {
   from {
      transform: translateX(100%);
      opacity: 0;
   }
   to {
      transform: translateX(0);
      opacity: 1;
   }
}

@keyframes fadeIn {
   from {
      opacity: 0;
      transform: translateY(1rem);
   }
   to {
      opacity: 1;
      transform: translateY(0);
   }
}

/* Responsive Styles */
@media (max-width: 1200px) {
   .search-input {
      width: 24rem;
   }
}

@media (max-width: 991px) {
   .nav-links {
      display: none;
   }
   
   .search-box {
      display: none;
   }

   .menu-btn {
      display: block;
   }
}

.menu-btn {
   display: none;
   background: none;
   border: none;
   font-size: 2.4rem;
   color: var(--gray-600);
   cursor: pointer;
   padding: 0.8rem;
   border-radius: var(--radius-full);
   transition: all var(--transition-fast);
}

.menu-btn:hover {
   background: var(--gray-100);
   color: var(--primary);
}

.dark .menu-btn {
   color: var(--gray-400);
}

.dark .menu-btn:hover {
   background: var(--gray-800);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
   // Menu button functionality
   const menuBtn = document.getElementById('menu-btn');
   const sideBar = document.querySelector('.side-bar');
   
   if (menuBtn && sideBar) {
      menuBtn.addEventListener('click', function() {
         sideBar.classList.toggle('active');
         menuBtn.classList.toggle('active');
      });

      // Close sidebar when clicking outside
      document.addEventListener('click', function(e) {
         if (!menuBtn.contains(e.target) && !sideBar.contains(e.target)) {
            sideBar.classList.remove('active');
            menuBtn.classList.remove('active');
         }
      });
   }

   // Profile dropdown toggle
   const profileTrigger = document.querySelector('.profile-trigger');
   const profileMenu = document.querySelector('.profile-menu');
   
   if (profileTrigger && profileMenu) {
      profileTrigger.addEventListener('click', function(e) {
         e.stopPropagation();
         profileMenu.classList.toggle('active');
      });

      // Close profile menu when clicking outside
      document.addEventListener('click', function(e) {
         if (!profileTrigger.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.classList.remove('active');
         }
      });
   }

   // Theme toggle
   const themeToggle = document.getElementById('theme-toggle');
   const body = document.body;
   
   if (themeToggle) {
      // Check for saved theme preference
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme) {
         body.classList.add(savedTheme);
         updateThemeIcon(savedTheme === 'dark');
      }

      themeToggle.addEventListener('click', function() {
         body.classList.toggle('dark');
         const isDark = body.classList.contains('dark');
         updateThemeIcon(isDark);
         localStorage.setItem('theme', isDark ? 'dark' : 'light');
      });
   }

   function updateThemeIcon(isDark) {
      const icon = themeToggle.querySelector('i');
      icon.className = isDark ? 'fas fa-moon' : 'fas fa-sun';
   }
});
</script>