<?php
session_start();
if(!isset($_SESSION['user'])){
  header("location:./logout.php");
  exit();
}
require_once "db_functions.php";
$message = "";
$error = "";
//here make sure to delete the selected information
if(@$_GET['action'] == 'delete' && is_numeric($_GET['id'])){
  saveData($db, "DELETE FROM courses WHERE id = ? AND user_id = ?" , [$_GET['id'], $_SESSION['user']['id']]);
  $message = "Course Deleted Successfully";
  $error = "";
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Authentication Page">
    <meta name="author" content="Ruberandinda Patience">
    <meta name="generator" content="Zuri Trainning">
    <title>Zuri Trainning Task 4 - Auth + CRUD</title>

    <!-- Bootstrap core CSS -->
    <link href="./assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" href="./assets/images/login_icon.png" />
    <link rel="icon" href="./assets/images/login_icon.png" />

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <link rel="stylesheet" type="text/css" href="./assets/toastr/toastr.css" />
    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-info flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Zuri Trainning Task-4</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <!-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> -->
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link bg-danger "  href="./logout.php">Sign out</a>
    </li>
  </ul>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="./welcome.php">
              <span data-feather="layers"></span>
              Courses
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./new_course.php">
              <span data-feather="file"></span>
              New Course
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

      <h2>Available Courses</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Course Name</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $counter = 1;
            $courses = returnAllData($db, "SELECT * FROM courses WHERE user_id = ?", [$_SESSION['user']['id']]);
            if(count($courses) > 0){
              foreach($courses AS $course){
                ?>
                <tr>
                  <td><?php echo $counter++ ?></td>
                  <td><?php echo $course['name'] ?></td>
                  <td>
                    <a class="text-success" href="./edit_course.php?id=<?php echo $course['id'] ?>"><span data-feather="edit"></span> Edit</a>
                  </td>
                  <td>
                    <a class="text-danger" onclick="return confirm('Do you realy want to delete selected course!')" href="./welcome.php?id=<?php echo $course['id'] ?>&action=delete"><span data-feather="trash"></span> delete</a>
                  </td>
                </tr>
                <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

    
    <script type="text/javascript" src="./assets/dist/js/jquery-3.6.0.min.js"></script>
    <script src="./assets/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="./assets/toastr/toastr.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
      <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script> -->

      <script src="./dashboard.js"></script>

      <?php
      if($message){
        ?>
        <script type="text/javascript">
          $(document).ready(function(){
            toastr.success("<?= $message ?>", 'Course Inform', {
                positionClass: 'toast-top-center',
                progressBar: true,
                preventDuplicates: true,
            });
          });
          
        </script>
        <?php
      } else if($error){
        ?>
        <script type="text/javascript">
          $(document).ready(function(){
            toastr.error("<?= $error ?>", 'Course Inform', {
                positionClass: 'toast-top-center',
                progressBar: true,
                preventDuplicates: true,
            });
          });
          
        </script>
        <?php
      }
      ?>
  </body>
</html>
