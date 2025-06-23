<body <?php
      /** @var string $page */
      /** @var string $module */
      echo ($page == 'doctors_searchmap') ? 'class="map-page"' : ''; ?> <?php echo ($module == 'messages') ? 'class="chat-page"' : ''; ?><?php echo ($module == 'signin') ? 'class="account-page"' : ''; ?>>

  <!-- Main Wrapper -->
  <div class="main-wrapper">

    <!-- Header -->
    <header class="header">
      <nav class="navbar navbar-expand-lg header-nav">
        <div class="navbar-header">
          <a id="mobile_btn" href="javascript:void(0);">
            <span class="bar-icon">
              <span></span>
              <span></span>
              <span></span>
            </span>
          </a>
          <a href="<?php echo base_url(); ?>" class="navbar-brand logo">
            <img src="<?php echo !empty(base_url() . settings("logo_front")) && file_exists(settings("logo_front")) ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png"; ?>" class="img-fluid" alt="Logo">
          </a>
          <a  href="<?php echo base_url(); ?>login">
            <img src="<?php echo base_url() . "assets/img/login.png"; ?>" class="mobile-login-button" style="width:30px" alt="Login">
          </a>
        </div>
        <div class="main-menu-wrapper">
          <div class="menu-header">
            <a href="<?php echo base_url(); ?>" class="menu-logo">
              <img src="<?php echo !empty(base_url() . settings("logo_front")) && file_exists(settings("logo_front"))  ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png"; ?>" class="img-fluid" alt="Logo">
            </a>
            <a id="menu_close" class="menu-close" href="javascript:void(0);">
              <i class="fas fa-times"></i>
            </a>
          </div>
          <ul class="main-nav">
            <li <?php
                /** @var string $theme */

                echo ($theme == 'web' && $module == 'home' && $page == 'index') ? 'class="active"' : ''; ?>>
              <a href="<?php echo base_url(); ?>"><?php
                                                  /** @var array $language */
                                                  echo $language['lg_home'] ?? " "; ?></a>
            </li>
            <!--<li <?php //echo (($theme == 'blog' && $module == 'home') && ($page == 'blogList')) ? 'class="active"' : ''; ?>>
              <a href="<?php //echo base_url(); ?>blogs"><?php //echo $language['lg_blog2'] ?? " "; ?></a>
            </li>-->
            <?php if (session('user_id')) { ?>
              <li <?php echo (($module == 'doctor' || $module == 'patient') && ($page == 'doctor_dashboard' || $page == 'patient_dashboard')) ? 'class="active"' : ''; ?>>
                <a href="<?php echo base_url() . session('module'); ?>"><?php echo $language['lg_dashboard'] ?? " "; ?></a>
              </li>
            <?php }
            //if (session('user_id') == '' || session('user_id') != '' || is_patient()) {  ?>
              <!--<li <?php //echo ($module == 'home' && ($page == 'doctors_search' || $page == 'doctors_mapsearch') && isset($_GET['type'])) ? 'class="active"' : ''; ?>>
                <a href="<?php //echo base_url(); ?>search-veterinary?type=6"><?php //echo $language['lg_clinic'] ?? " "; ?></a>
              </li>-->
            <?php //}
            if (session('user_id') == '' || session('user_id') != '' || is_patient()) {  ?>
              <li class=" <?php echo ($module == 'home' && ($page == 'doctors_search' || $page == 'doctors_mapsearch') && (empty($_GET['type']))) ? 'active' : ''; ?>">
                  <a href="<?php echo base_url(); ?>search-veterinary?type=1"><?php echo "Book Appointment" ?></i></a>
                <!--<a href="<?php echo base_url(); ?>search-doctor"><?php echo $language['lg_doctors'] ?? " "; ?><i class="fas fa-chevron-down"></i></a>-->
                <!--<ul class="submenu">
                  <li <?php //echo ($module == 'home' && ($page == 'doctors_search')) ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>search-doctor"><?php //echo $language['lg_search_doctor2'] ?? " "; ?></a></li>
                  <li <?php //echo ($module == 'home' && ($page == 'doctors_mapsearch')) ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>search-doctor-map"><?php //echo $language['lg_map_grid_list'] ?? " "; ?></a></li>
                </ul>-->
              </li>
              <?php  }
            if (session('user_id')) {
              if (is_doctor()) { ?>
                <li <?php echo ($module == 'home' && $page == 'patients_search') ? 'class="active"' : ''; ?>>
                  <a href="<?php echo base_url() . session('module'); ?>/my-patients"><?php echo $language['lg_patients1'] ?? " "; ?></a>
                </li>
            <?php }
            } ?>

            <?php
            /** @var integer $lab_acess_status */
            $lab_acess_status = 0;
            /*if (is_patient()) {
            ?>
              <li <?php echo ($module == 'home' && ($page == 'labs_search' || $page == 'labs_searchmap')) ? 'class="active"' : ''; ?>>
                <a href="<?php echo base_url(); ?>search-lab">Labs</a>
              </li>
            <?php } ?>

            <?php if (session('user_id') == '' || session('user_id') != '') {
              if (is_pharmacy()) {
            ?>
                <li <?php echo ($module == 'pharmacy' && $page == 'product_list') ? 'class="active"' : ''; ?>>
                  <a href="<?php echo base_url() . session('module'); ?>/product-list"><?php echo $language['lg_products'] ?? " "; ?></a>
                </li>
            <?php }
            } ?>

            <?php if (session('user_id')) {
              if (is_patient()) { ?>

                <li class="has-submenu" <?php echo (($module == 'home' || $module == 'pharmacy') && ($page == 'pharmacy-search' || $page == 'products')) ? 'class="active"' : ''; ?>>
                  <a href="javascript:void(0);"><?php echo $language['lg_pharmacy'] ?? " "; ?><i class="fas fa-chevron-down"></i></a>
                  <ul class="submenu">

                    <li <?php echo ($module == 'home' && ($page == 'pharmacy-search')) ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>pharmacy-search"><?php echo $language['lg_pharmacy'] ?? " "; ?></a></li>
                    <li <?php echo ($module == 'pharmacy' && ($page == 'products')) ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>home/view_pharmacy_products"><?php echo $language['lg_products'] ?? " "; ?></a></li>

                  </ul>
                </li>

            <?php }
            } ?>

            <?php if (!session('user_id')) { ?>

              <li class="has-submenu" <?php echo (($module == 'home' || $module == 'pharmacy') && ($page == 'pharmacy-search' || $page == 'products')) ? 'class="active"' : ''; ?>>
                <a href="<?php echo base_url(); ?>pharmacy-search"><?php echo $language['lg_pharmacy'] ?? " "; ?><i class="fas fa-chevron-down"></i></a>
                <ul class="submenu">

                  <li <?php echo ($module == 'home' && ($page == 'pharmacy-search')) ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>pharmacy-search"><?php echo $language['lg_pharmacy'] ?? " "; ?></a></li>
                  <li <?php echo ($module == 'pharmacy' && ($page == 'products')) ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>home/view_pharmacy_products"><?php echo $language['lg_products'] ?? " "; ?></a></li>

                </ul>
              </li>

            <?php } */?>

            <?php if (session('user_id') || session('admin_id')) { ?>
              <li class="login-link">
                <a class="dropdown-item" href="<?php echo base_url(); ?>user-logout"><?php echo $language['lg_signout'] ?? " "; ?></a>
              </li>
            <?php } else { ?>
              <li class="login-link">
                <a href="<?php echo base_url(); ?>login"><?php echo $language['lg_signin__signup'] ?? " "; ?></a>
              </li>
            <?php } ?>

                      <?php
                      $page_user = "patient";
                      $type_user = $language['lg_patient4'] ?? "";
                      if (session('role') == '1') {
                        $type_user = $language['lg_doctor2'] ?? "";
                        $page_user = "doctor";
                      } else if (session('role') == '5') {
                        $type_user = $language['lg_pharmacy'] ?? "";
                        $page_user = "pharmacy";
                      } else if (session('role') == '4') {
                        $type_user = $language['lg_lab15'] ?? "";
                        $page_user = "lab";
                      } else if (session('role') == '6') {
                        $type_user = 'Clinic';
                        $page_user = "clinic";
                      }
                      ?>
            <li class="profile-setting">
                  <a class="dropdown-item" href="<?php echo base_url() . $page_user; ?>/profile"><?php echo $language['lg_profile_setting'] ?? " "; ?></a>
            </li>



          </ul>
        </div>
        <ul class="nav header-navbar-rht">
          <?php

          $default_language = default_language();
          $active_language = active_language();

          if (session('lang') == '') {
            $lang = $default_language['language_value'];
          } else {
            $lang = session('lang');
          }

          ?>



          <?php if (session('user_id')) {

            if ($page != "checkout" && $page != "invoice_view") {
          ?>

              <li>
                <?php

                $get_currency = get_currency();
                $user_currency = get_user_currency();
                $user_currency_code = $user_currency['user_currency_code'];

                ?>
                <select class="form-control" onchange="user_currency(this.value)">
                  <?php foreach ($get_currency as $row) { ?>
                    <option value="<?= $row['currency_code']; ?>" <?= ($row['currency_code'] == $user_currency_code) ? 'selected' : ''; ?>><?= $row['currency_code']; ?></option>
                  <?php } ?>
                </select>
              </li>
          <?php }
          } ?>

          <!-- Notifications -->
          <?php

          if (!empty(session('user_id'))) {
            if (is_patient() || is_doctor() || is_lab() || is_pharmacy() || is_clinic()) {
              $notification_list = notification_list(session('user_id'));
              $count = count($notification_list);
          ?>
              <li class="nav-item dropdown noti-dropdown">
                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <i class="fas fa-bell" style="font-size: 20px;"></i> <span class="badge badge-pill noti-badge"><?php echo $count; ?></span>
                </a>
                <div class="dropdown-menu notifications">
                  <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
								<?php
								/** @var int $count  */
							// $notification_list = notification_list();
							// $count = count($notification_list);
								if ($count > 0) { ?>
									<div class="notification-header">
										<a href="javascript:void(0)" class="clear-notifications" onclick="delete_notification(0)"> Delete All </a>
										<div class="clearfix"></div>
									</div>
								<?php } ?>
                  </div>

                  <div class="noti-content">
                    <ul class="notification-list">
                      <?php foreach ($notification_list as $rows) {
                        $url_link = '#';
                        if ($rows['type'] == 'Payment Request') {
                          $url_link =  base_url() . session('module') . '/accounts';
                        } else if ($rows['type'] == 'Appointment Cancel' || $rows['type'] == 'Appointment Accept' || $rows['type'] == 'Appointment') {
                          $url_link =  base_url() . session('module') . '/appointments';
                        }
                        if (is_doctor()) {
                          if ($rows['type'] == 'Prescription' || $rows['type'] == 'Medical-records') {
                            $url_link = base_url() . '/my_patients/mypatient-preview/' . base64_encode($rows['user_id']);
                          }
                        }
                        if (is_patient()) {
                          if ($rows['type'] == 'Prescription' || $rows['type'] == 'Medical-records') {
                            $url_link = base_url() . 'patient';
                          }
                          if ($rows['type'] == 'Pharmacy') {
                            $url_link = base_url() . 'patient/orders-list';
                          }
                        }
                        if (is_pharmacy()) {
                          if ($rows['type'] == 'Pharmacy') {
                            $url_link = base_url() . 'pharmacy/orders-list';
                          }
                        }
                        if ($rows['user_id'] == 0) {
                          $fromName = 'Admin';
                        } else {
                          $fromName = $rows['from_name'];
                        }

                        if (session('user_id') == $rows['user_id'])
                          $img = (!empty($rows['to_profile_image'])) && file_exists($rows['to_profile_image'])  ? base_url() . $rows['to_profile_image'] : base_url() . 'assets/img/user.png';
                        else
                          $img = (!empty($rows['profile_image'])) && file_exists($rows['profile_image'])  ? base_url() . $rows['profile_image'] : base_url() . 'assets/img/user.png';
                      ?>
                        <li class="notification-message">
                          <a href="<?= $url_link ?>" data-id="<?php echo $rows['id']; ?>" onclick=clear_all(<?php echo $rows['id']; ?>) id="notify_link">
                            <div class="media">
                              <span class="avatar avatar-sm">
                                <img class="avatar-img rounded-circle" alt="User Image" src="<?php echo $img; ?>">
                              </span>
                              <div class="media-body">
                                <p class="noti-details"><span class="noti-title"><?php echo (session('user_id') == $rows['user_id']) ? 'You' : libsodiumDecrypt($fromName); ?></span> <span class="noti-title"><?php echo (session('user_id') == $rows['user_id']) ? str_replace('has', 'have', $rows['text']) : $rows['text']; ?> </span> <?php echo (session('user_id') == $rows['to_user_id']) ? 'You' : libsodiumDecrypt($rows['to_name']); ?></p>
                                <p class="noti-time"><span class="notification-time"><?php echo time_elapsed_string($rows['notification_date']); ?></span></p>
                              </div>
                            </div>
                          </a>
                        </li>
                      <?php } ?>
                    </ul>
                  </div>
                  <div class="topnav-dropdown-footer">
                    <a href="<?= base_url() ?>get-notification">View all Notifications</a>
                  </div>
                </div>
              </li>
          <?php }
          } ?>
          <!-- /Notifications -->

          <li class="nav-item dropdown has-arrow logged-item">
           <!-- <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
              <?php echo lang_name($lang); ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <?php foreach ($active_language as $active) { ?>
                <a class="dropdown-item" onclick="change_language('<?php //echo $active['language_value']; ?>','<?php //echo $active['language']; ?>')" href="javascript:void(0);"><?php //echo $active['language']; ?></a>
              <?php  } ?>
            </div>-->
          </li>

          <!--<li class="nav-item contact-item">
            <div class="header-contact-img">
              <i class="far fa-hospital"></i>
            </div>
            <div class="header-contact-detail">
              <p class="contact-header"><?php //echo $language['lg_contact2'] ?? " "; ?></p>
              <p class="contact-info-header"> <?php //echo !empty(settings("contact_no")) ? settings("contact_no") : "9876543210"; ?></p>
            </div>
          </li>-->

          <?php /*if (is_patient()) { ?>
            <li class="nav-item view-cart-header mr-3">
              <a href="<?php echo base_url(); ?>cart" class=""><i class="fas fa-shopping-cart"></i>
                <small class="unread-msg1 cart_count">
                  <?php
                  // echo $this->cart->total_items();
                  ?>
                </small>
              </a>
            </li>
          <?php } */?>

          <?php if (session('user_id') || session('admin_id')) {

            if (session('user_id')) {
              $user_detail = user_detail(session('user_id'));
              // $user_profile_image=(!empty($user_detail['profileimage']))?base_url().$user_detail['profileimage']:base_url().'assets/img/user.png';

              if (!isset($user_detail) || $user_detail['profileimage'] == "" || ($user_detail['profileimage'] != "" && !file_exists($user_detail['profileimage']))) {
                $user_profile_image = base_url() . 'assets/img/user.png';
              } else {
                $user_profile_image = (!empty($user_detail['profileimage'] ?? "")) ? base_url() . $user_detail['profileimage'] ?? "" : base_url() . 'assets/img/user.png';
              }
          ?>
              <li class="nav-item dropdown has-arrow logged-item">
                <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                  <span class="user-img">
                    <img class="rounded-circle avatar-view-img" src="<?php echo $user_profile_image; ?>" width="31" alt="<?php echo $user_detail['first_name'] . ' ' . $user_detail['last_name']; ?>">
                  </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  <div class="user-header">
                    <div class="avatar avatar-sm">
                      <img src="<?php echo $user_profile_image; ?>" alt="User Image" class="avatar-img avatar-view-img rounded-circle">
                    </div>
                    <div class="user-text">
                      <h6><?php if (session('role') == '6') {
                            echo libsodiumDecrypt($user_detail['first_name']);
                          } else {
                            echo libsodiumDecrypt($user_detail['first_name']) . ' ' . libsodiumDecrypt($user_detail['last_name']);
                          } ?></h6>
                      <?php
                      $page_user = "patient";
                      $type_user = $language['lg_patient4'] ?? "";
                      if (session('role') == '1') {
                        $type_user = $language['lg_doctor2'] ?? "";
                        $page_user = "doctor";
                      } else if (session('role') == '5') {
                        $type_user = $language['lg_pharmacy'] ?? "";
                        $page_user = "pharmacy";
                      } else if (session('role') == '4') {
                        $type_user = $language['lg_lab15'] ?? "";
                        $page_user = "lab";
                      } else if (session('role') == '6') {
                        $type_user = 'Clinic';
                        $page_user = "clinic";
                      }
                      ?>
                      <p class="text-muted mb-0"><?php echo $type_user; ?></p>

                    </div>
                  </div>
                  <a class="dropdown-item" href="<?php echo base_url($page_user); ?>"><?php echo $language['lg_dashboard'] ?? " "; ?></a>
                  <a class="dropdown-item" href="<?php echo base_url() . $page_user; ?>/profile"><?php echo $language['lg_profile_setting'] ?? " "; ?></a>
                  <a class="dropdown-item" href="<?php echo base_url(); ?>user-logout"><?php echo $language['lg_signout'] ?? " "; ?></a>
                </div>
              </li>
            <?php } else {

              $admin_detail = admin_detail(session('admin_id'));
              $adminprofile_image = (!empty($admin_detail['profileimage'])) ? base_url() . $admin_detail['profileimage'] : base_url() . 'assets/img/user.png';
            ?>

              <li class="nav-item dropdown has-arrow logged-item">
                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <span class="user-img">
                    <img class="rounded-circle avatar-view-img" src="<?php echo $adminprofile_image; ?>" width="31" alt="<?php echo ucfirst($admin_detail['name']); ?>">
                  </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  <div class="user-header">
                    <div class="avatar avatar-sm">
                      <img src="<?php echo $adminprofile_image; ?>" alt="User Image" class="avatar-img avatar-view-img rounded-circle">
                    </div>
                    <div class="user-text">
                      <h6><?php echo ucfirst(libsodiumDecrypt($admin_detail['name'])); ?></h6>
                      <p class="text-muted mb-0">Admin</p>
                    </div>
                  </div>
                  <a class="dropdown-item" href="<?php echo base_url(); ?>admin/dashboard"><?php echo $language['lg_dashboard'] ?? " "; ?></a>
                  <a class="dropdown-item" href="<?php echo base_url(); ?>admin/logout"><?php echo $language['lg_signout'] ?? " "; ?></a>
                </div>
              </li>

            <?php }
          } else { ?>
            <li class="nav-item">
              <a class="nav-link header-login" href="<?php echo base_url(); ?>login"><?php echo $language['lg_signin__signup'] ?? " "; ?> </a>
            </li>
          <?php } ?>

        </ul>
      </nav>
    </header>
    <!-- /Header -->