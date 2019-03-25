<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav" id="navigation">
    <li class="nav-item nav-profile">
      <div class="nav-link">
        <div class="user-wrapper">
          <div class="profile-image">
            <img src="<?php echo $_ROOT ?>images/faces/face1.jpg" alt="profile image">
          </div>
          <div class="text-wrapper">
            <p class="profile-name">Richard V.Welsh</p>
            <div>
              <small class="designation text-muted">Manager</small>
              <span class="status-indicator online"></span>
            </div>
          </div>
        </div>
        <button class="btn btn-success btn-block">New Project
        <i class="mdi mdi-plus"></i>
        </button>
      </div>
    </li>
    <li class="nav-item" v-for="(navigation,index) in Navigation">
      <a class="nav-link" :href="'<?php echo $_ROOT ?>'+navigation.Url">
        <i :class="navigation.Icon"></i>
        <span class="menu-title">{{navigation.NavigationName}}</span>
      </a>
    </li>
   <!--  <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <i class="menu-icon mdi mdi-restart"></i>
        <span class="menu-title">User Pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $_ROOT ?>pages/samples/blank-page.html"> Blank Page </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $_ROOT ?>pages/samples/login.html"> Login </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $_ROOT ?>pages/samples/register.html"> Register </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $_ROOT ?>pages/samples/error-404.html"> 404 </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $_ROOT ?>pages/samples/error-500.html"> 500 </a>
          </li>
        </ul>
      </div>
    </li> -->
  </ul>
</nav>