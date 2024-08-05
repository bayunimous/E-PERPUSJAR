<!DOCTYPE html>
<html lang="en">

<head>
  <?= $this->include('layouts/head') ?>

  <!-- Extra head e.g title -->
  <?= $this->renderSection('head') ?>

  <link rel="stylesheet" href="<?= base_url('assets/css/home.css'); ?>">

  <script src="https://www.google.com/recaptcha/api.js"></script>

  <?php if (strpos($_SERVER['REQUEST_URI'], 'login') === false && strpos($_SERVER['REQUEST_URI'], 'register') === false && strpos($_SERVER['REQUEST_URI'], 'book') === false) { ?>
  <style>
    body {
      padding-top: 50px;
    }
  </style>
  <?php } ?>
  
</head>

<body class="position-relative">
  <!--  Body Wrapper -->
  <div class="background">
  </div>

  <div class="page-wrapper" id="main-wrapper">
    <!--  Main wrapper -->
    <div class="body-wrapper position-relative">
      <?= $this->renderSection('back') ?>
      <div class="container col-xxl-8 px-4 py-5" style="min-height: 100vh;">
        <!-- Main content -->
        <div class="w-100">
          <?= $this->renderSection('content') ?>
        </div>

        <div class="align-self-end w-100">
          <?= $this->include('layouts/footer') ?>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
              var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            
              navLinks.forEach(function(navLink) {
                if (!navLink.classList.contains('login-link')) {
                  navLink.addEventListener('click', function(event) {
                    event.preventDefault();
            
                    var targetSectionId = this.getAttribute('href').substring(1);
                    var targetSection = document.getElementById(targetSectionId);
            
                    if (targetSection) {
                      var offsetTop = targetSection.offsetTop;
                      window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                      });
                    }
                  });
                }
              });
            });
        </script>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <?= $this->include('imports/scripts/basic_scripts') ?>

  <!-- Extra scripts -->
  <?= $this->renderSection('scripts') ?>
</body>

</html>