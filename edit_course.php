<?php
session_start();
if(!isset($_SESSION['user'])){
  header("location:./logout.php");
  exit();
}
require_once "db_functions.php";
$data = first($db, "SELECT * FROM courses WHERE id = ?", [$_GET['id']]);
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

      <div class="py-5 text-center">
      <h2>Update Selected Course</h2>
    </div>

    <div class="row g-5">
      
      <div class="col-md-7 col-lg-8">
        <form class="needs-validation" method="POST" action="./cmd/save_edit_course.php" id="register_course">
          <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
          <div class="row g-3 mb-2">
            <div class="col-sm-12">
              <label for="firstName" class="form-label">Course Name</label>
              <input type="text" value="<?php echo $data['name'] ?>" name="name" class="form-control" id="firstName" placeholder="Enter Course Name" value="" required>
              <div class="invalid-feedback">
                Valid first name is required.
              </div>
            </div>
          </div>

          <!-- <hr class="my-4"> -->

          <button id="submit_form" class="btn btn-outline-success btn-sm" type="submit">Update Course</button>
        </form>
      </div>
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

      <script type="text/javascript">
  //Here Make sure to submit the login page
  $(document).ready(function(){
    // console.log("The Jquery is ready for use");
    $("#register_course").submit(function(e){
      e.preventDefault(); //to stop the normal form submittion process

      //Now make sure to use ajax and submit the request
      var my_form = $(this);
      var submit_button = $("#submit_form");
      $.ajax({
        type: my_form.attr('method'), //send with post
        url: my_form.attr('action'),  
        data: my_form.serialize(),
        beforeSend: function(){
          submit_button.html('<i class="fas fa-sync fa-spin"></i> Checking...');
          submit_button.attr("disabled", "disabled");
        },
        success: function(response){
          if(response.success){
            // alert("Authentication succeed");
            //Build the Toastr to notify the success
            toastr.success(response.message, 'Update Course', {
                      positionClass: 'toast-top-center',
                      progressBar: true,
                      preventDuplicates: true,
                  });

            setTimeout(function(e){
              window.location = "./" + response.url;
            }, 1000);
          } else {
            toastr.error(response.message, 'Update Course', {
                  positionClass: 'toast-top-center',
                  progressBar: true,
                  preventDuplicates: true,
              });
          }
          submit_button.html("Update Course");
          submit_button.removeAttr("disabled");
        },
        error: function(error){
          // console.log(error);
          toastr.error(error, 'Update Course', {
            positionClass: 'toast-top-center',
            progressBar: true,
            preventDuplicates: true,
          });
          submit_button.html("Update Course");
          submit_button.removeAttr("disabled");
        }
      });
    });
  });
</script>
  </body>
</html>
