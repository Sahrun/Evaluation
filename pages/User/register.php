<?php
$SERVER = $_SERVER['DOCUMENT_ROOT'];
include_once $SERVER."/Evaluation/Config/AutorizationLogin.php";
include_once $SERVER."/Evaluation/Config/Config.php";

?>
<!DOCTYPE html>
<html lang="en">
<?php include_once $_SERVER."Config/Head.php"; ?>
<body>
  <div class="container-scroller" id="register">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
      <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <h2 class="text-center mb-4">Register</h2>
            <div class="auto-form-wrapper">
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Username" v-model="User.UserName">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <input type="password" class="form-control" placeholder="Password" v-model="User.Password">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" v-model="User.ConfirmPassword">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block" v-on:click="Register">Register</button>
                </div>
                <div class="text-block text-center my-3">
                  <span class="text-small font-weight-semibold">Demi keamanan silahkan ganti password anda</span>
                </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <?php include "../../Config/IncludeJs.php"; ?>
  <script src="../../js/App/Register.js"></script>
</body>

</html>
