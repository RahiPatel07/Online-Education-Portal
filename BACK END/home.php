<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home - E-Learning Platform</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
      .hero-section {
         background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
         padding: var(--spacing-xxl) 0;
         color: var(--white);
         text-align: center;
         margin-bottom: var(--spacing-xl);
      }

      .hero-content {
         max-width: 800px;
         margin: 0 auto;
         padding: 0 var(--spacing-md);
      }

      .hero-title {
         font-size: 4.8rem;
         color: var(--white);
         margin-bottom: var(--spacing-md);
      }

      .hero-subtitle {
         font-size: 2rem;
         opacity: 0.9;
         margin-bottom: var(--spacing-lg);
      }

      .stats-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
         gap: var(--spacing-md);
         padding: var(--spacing-lg);
      }

      .stat-card {
         background: var(--white);
         border-radius: var(--radius-lg);
         padding: var(--spacing-lg);
         text-align: center;
         box-shadow: var(--shadow-md);
         transition: transform var(--transition-medium);
      }

      .stat-card:hover {
         transform: translateY(-5px);
      }

      .stat-icon {
         font-size: 3rem;
         color: var(--primary);
         margin-bottom: var(--spacing-sm);
      }

      .stat-number {
         font-size: 2.4rem;
         font-weight: 600;
         color: var(--secondary);
         margin-bottom: var(--spacing-xs);
      }

      .stat-label {
         color: var(--gray-600);
         font-size: 1.4rem;
      }

      .categories-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
         gap: var(--spacing-md);
         padding: var(--spacing-lg);
      }

      .category-card {
         background: var(--white);
         border-radius: var(--radius-md);
         padding: var(--spacing-md);
         text-align: center;
         transition: all var(--transition-medium);
         cursor: pointer;
      }

      .category-card:hover {
         transform: translateY(-3px);
         box-shadow: var(--shadow-lg);
      }

      .category-icon {
         font-size: 2.4rem;
         color: var(--primary);
         margin-bottom: var(--spacing-sm);
      }

      .category-title {
         font-size: 1.6rem;
         color: var(--secondary);
         margin: 0;
      }

      .courses-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: var(--spacing-lg);
         padding: var(--spacing-lg);
      }

      .course-card {
         background: var(--white);
         border-radius: var(--radius-lg);
         overflow: hidden;
         transition: transform var(--transition-medium);
      }

      .course-card:hover {
         transform: translateY(-5px);
      }

      .course-thumb {
         width: 100%;
         height: 200px;
         object-fit: cover;
      }

      .course-content {
         padding: var(--spacing-md);
      }

      .course-tutor {
         display: flex;
         align-items: center;
         margin-bottom: var(--spacing-sm);
      }

      .tutor-img {
         width: 40px;
         height: 40px;
         border-radius: var(--radius-full);
         margin-right: var(--spacing-sm);
      }

      .tutor-info h3 {
         font-size: 1.4rem;
         margin: 0;
      }

      .tutor-info span {
         font-size: 1.2rem;
         color: var(--gray-600);
      }

      .course-title {
         font-size: 1.8rem;
         margin: var(--spacing-sm) 0;
      }

      .view-more {
         text-align: center;
         margin-top: var(--spacing-xl);
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
   <div class="hero-content">
      <h1 class="hero-title">Welcome to E-Learning</h1>
      <p class="hero-subtitle">Discover a world of knowledge with our expert-led courses</p>
      <?php if($user_id == ''): ?>
         <div class="d-flex justify-content-center">
            <a href="login.php" class="btn btn-light mr-2">Login</a>
            <a href="register.php" class="btn btn-outline-light">Register</a>
         </div>
      <?php endif; ?>
   </div>
</section>

<!-- Stats Section -->
<?php if($user_id != ''): ?>
<section class="container">
   <h2 class="heading">Your Learning Stats</h2>
   <div class="stats-container">
      <div class="stat-card">
         <i class="fas fa-heart stat-icon"></i>
         <div class="stat-number"><?= $total_likes ?></div>
         <div class="stat-label">Total Likes</div>
      </div>
      <div class="stat-card">
         <i class="fas fa-comments stat-icon"></i>
         <div class="stat-number"><?= $total_comments ?></div>
         <div class="stat-label">Total Comments</div>
      </div>
      <div class="stat-card">
         <i class="fas fa-bookmark stat-icon"></i>
         <div class="stat-number"><?= $total_bookmarked ?></div>
         <div class="stat-label">Saved Playlists</div>
      </div>
   </div>
</section>
<?php endif; ?>

<!-- Categories Section -->
<section class="container">
   <h2 class="heading">Popular Categories</h2>
   <div class="categories-grid">
      <a href="search_course.php?" class="category-card">
         <i class="fas fa-code category-icon"></i>
         <h3 class="category-title">Development</h3>
      </a>
      <a href="#" class="category-card">
         <i class="fas fa-chart-simple category-icon"></i>
         <h3 class="category-title">Business</h3>
      </a>
      <a href="#" class="category-card">
         <i class="fas fa-pen category-icon"></i>
         <h3 class="category-title">Design</h3>
      </a>
      <a href="#" class="category-card">
         <i class="fas fa-chart-line category-icon"></i>
         <h3 class="category-title">Marketing</h3>
      </a>
      <a href="#" class="category-card">
         <i class="fas fa-music category-icon"></i>
         <h3 class="category-title">Music</h3>
      </a>
      <a href="#" class="category-card">
         <i class="fas fa-camera category-icon"></i>
         <h3 class="category-title">Photography</h3>
      </a>
   </div>
</section>

<!-- Latest Courses Section -->
<section class="container">
   <h2 class="heading">Latest Courses</h2>
   <div class="courses-grid">
      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute(['active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="course-card">
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="course-thumb" alt="">
         <div class="course-content">
            <div class="course-tutor">
               <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" class="tutor-img" alt="">
               <div class="tutor-info">
                  <h3><?= $fetch_tutor['name']; ?></h3>
                  <span><?= $fetch_course['date']; ?></span>
               </div>
            </div>
            <h3 class="course-title"><?= $fetch_course['title']; ?></h3>
            <a href="playlist.php?get_id=<?= $course_id; ?>" class="btn btn-primary btn-block">View Playlist</a>
         </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="text-center">No courses added yet!</p>';
      }
      ?>
   </div>
   <div class="view-more">
      <a href="courses.php" class="btn btn-outline-primary">View All Courses</a>
   </div>
</section>

<!-- Become a Tutor Section -->
<section class="container mb-5">
   <div class="card">
      <div class="card-body text-center">
         <h2 class="card-title">Become a Tutor</h2>
         <p class="card-text">Share your knowledge with students around the world</p>
         <a href="admin/register.php" class="btn btn-primary">Get Started</a>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="../FRONT END/js/modern.js"></script>
   
</body>
</html>