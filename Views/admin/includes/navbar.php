<body>

  <!-- Main Wrapper -->
  <div class="main-wrapper">

    <!-- Header -->
    <div class="header">

      <!-- Logo -->
      <div class="header-left">
        <a href="<?php echo base_url(); ?>admin/dashboard" class="logo">
          <img src="<?php echo !empty(base_url() . settings("logo_front")) ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png"; ?>" alt="Logo">
        </a>
        <a href="<?php echo base_url(); ?>admin/dashboard" class="logo logo-small">
          <img src="<?php echo !empty(base_url() . settings("favicon")) && file_exists(settings("favicon")) ? base_url() . settings("favicon") : base_url() . "assets/img/logo-small.png"; ?>" alt="Logo" width="30" height="30">
        </a>
      </div>
      <!-- /Logo -->

      <a href="javascript:void(0);" id="toggle_btn">
        <i class="fe fe-text-align-left"></i>
      </a>

      <div class="top-nav-search">
        <div>
          <input type="text" class="form-control" id="search_keywords" placeholder="Search here">
          <button class="btn" id="search_button"><i class="fa fa-search"></i></button>
        </div>
      </div>



      <!-- Mobile Menu Toggle -->
      <a class="mobile_btn" id="mobile_btn">
        <i class="fa fa-bars"></i>
      </a>
      <!-- /Mobile Menu Toggle -->

      <!-- Header Right Menu -->
      <ul class="nav user-menu">
        <?php

        $notification_list = notification_list();
        $count = count($notification_list);

        // print_r($notification_list);
        // exit;
        ?>

        <!-- Notifications -->
        <li class="nav-item dropdown noti-dropdown">
          <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
            <i class="fe fe-bell"></i> <span class="badge badge-pill"><?php echo $count; ?></span>
          </a>
          <div class="dropdown-menu notifications">
            <div class="topnav-dropdown-header">
              <span class="notification-title">Notifications</span>
              <?php if ($count > 0) { ?>
                <a href="" onclick="clear_all()" class="clear-noti"> Clear All </a>
              <?php } ?>
            </div>

            <div class="noti-content">
              <ul class="notification-list">
                <?php foreach ($notification_list as $rows) {
                  $decryptFirstName = libsodiumDecrypt($rows['first_name']);
                  $decryptLastName = libsodiumDecrypt($rows['last_name']);
                  $fromName = $decryptFirstName . ' ' . $decryptLastName;
                  // echo $rows['user_id'] . "-" . $rows['to_user_id'];
                  // echo $rows['user_id'];echo '/n';
                  // echo $rows['to_user_id'];

                  $decryptToFirstName = libsodiumDecrypt($rows['doctor_first_name']);
                  $decryptToLastName = libsodiumDecrypt($rows['doctor_last_name']);
                  $toName = $decryptToFirstName . ' ' . $decryptToLastName;
                  $url_link = 'javascript:void(0)';
                  if ($rows['type'] == 'Payment Request') {
                    $url_link =  base_url() . 'admin/payment-request';
                  } else if ($rows['type'] == 'Appointment Cancel' || $rows['type'] == 'Appointment Accept' || $rows['type'] == 'Appointment') {
                    $url_link =  base_url() . 'admin/appointments';
                  }
                  $img = (!empty($rows['profile_image'])) && file_exists($rows['profile_image']) ? base_url() . $rows['profile_image'] : base_url() . 'assets/img/user.png';
                ?>
                  <li class="notification-message">
                    <a href="<?= $url_link ?>">
                      <div class="media">
                        <span class="avatar avatar-sm">
                          <img class="avatar-img rounded-circle" alt="User Image" src="<?php echo $img; ?>">
                        </span>
                        <div class="media-body">

                          <p class="noti-details"><span class="noti-title"><?php echo $fromName; ?></span> <span class="noti-title"><?php echo $rows['text']; ?> </span> <?php echo $toName; ?></p>
                          <p class="noti-time"><span class="notification-time"><?php echo time_elapsed_string($rows['notification_date']); ?></span></p>
                        </div>
                      </div>
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </div>
            <div class="topnav-dropdown-footer">
              <a href="admin-notification">View all Notifications</a>
            </div>
          </div>
        </li>
        <!-- /Notifications -->

        <?php
        /**
         * @property object $session
         */
        $admin_detail = admin_detail(session('admin_id'));

        if ($admin_detail['profileimage'] == "" || ($admin_detail['profileimage'] != "" && !is_file($admin_detail['profileimage']))) {
          $profile_image = base_url() . 'assets/img/user.png';
        } else {
          $profile_image = (!empty($admin_detail['profileimage'] ?? "")) && file_exists($admin_detail['profileimage']) ? base_url() . $admin_detail['profileimage'] ?? "" : base_url() . 'assets/img/user.png';
        }

        ?>

        <!-- User Menu -->
        <li class="nav-item dropdown has-arrow">
          <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
            <span class="user-img"><img class="rounded-circle avatar-view-img" src="<?php echo $profile_image; ?>" width="31" alt="Ryan Taylor"></span>
          </a>
          <div class="dropdown-menu">
            <div class="user-header">
              <div class="avatar avatar-sm">
                <img src="<?php echo $profile_image; ?>" alt="User Image" class="avatar-img rounded-circle avatar-view-img">
              </div>
              <div class="user-text">
                <h6 class="admin_name"><?php echo (!empty($admin_detail['name']) ? libsodiumDecrypt($admin_detail['name']) : ""); ?></h6>
                <p class="text-muted mb-0">Administrator</p>
              </div>
            </div>
            <a class="dropdown-item" href="<?php echo base_url(); ?>admin/profile">My Profile</a>
            <a class="dropdown-item" href="<?php echo base_url(); ?>admin/settings">Settings</a>
            <a class="dropdown-item" href="<?php echo base_url(); ?>admin/logout">Logout</a>
          </div>
        </li>
        <!-- /User Menu -->

      </ul>
      <!-- /Header Right Menu -->

    </div>
    <!-- /Header -->