<?php
$user_detail = user_detail(session('user_id'));
if ($user_detail['profileimage'] == "" || ($user_detail['profileimage'] != "" && !file_exists($user_detail['profileimage']))) {
	$user_profile_image = base_url() . 'assets/img/user.png';
} else {
	$user_profile_image = (!empty($user_detail['profileimage'] ?? "")) ? base_url() . $user_detail['profileimage'] ?? "" : base_url() . 'assets/img/user.png';
}

// Role's
// 1 -> doctor
// 2 -> patient
// 4 -> lab
// 5 -> pharmacy
// 6 -> clinic

?>
<div class="profile-sidebar">

	<div class="widget-profile pro-widget-content">
		<div class="profile-info-widget">
			<a href="#" class="booking-doc-img">
				<img src="<?php echo $user_profile_image; ?>" class="avatar-view-img" alt="User Image">
			</a>
			<div class="profile-det-info">
				<h3>
					<?php
					if (session('role') == '6') {
						echo libsodiumDecrypt($user_detail['clinicname'] == "" ? $user_detail['first_name'] : $user_detail['clinicname']);
					} else if (session('role') == '1') {
						echo $language['lg_dr'] ?? "";
						echo libsodiumDecrypt($user_detail['first_name']) . ' ' . libsodiumDecrypt($user_detail['last_name']);
					} else if (session('role') == '2') {
						echo libsodiumDecrypt($user_detail['first_name']) . ' ' . libsodiumDecrypt($user_detail['last_name']);
						//echo (!empty($user_detail['dob'])) ? '<p><i class="fas fa-birthday-cake"></i> ' . date('d M Y', strtotime($user_detail['dob'])) . ', ' . age_calculate($user_detail['dob']) . '</p>' : '';
						//echo (!empty($user_detail['city'])) ? '<p class="mb-0"><i class="fas fa-map-marker-alt"></i>' . $user_detail['cityname'] . ', ' . $user_detail['countryname'] . '</p>' : '';
					} else {
						echo libsodiumDecrypt($user_detail['first_name']) . ' ' . libsodiumDecrypt($user_detail['last_name']);
					}
					?>
				</h3>
				<?php if (session('role') == '1') { ?>
					<div class="patient-details">
						<h5 class="mb-0"><?php echo ucfirst(libsodiumDecrypt($user_detail['speciality'])); ?></h5>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="dashboard-widget">
		<nav class="dashboard-menu">
			<ul>
			  <!-- for  Veterinary  -->
			    <?php if (session('role') == "2") { ?>
					<!-- without lab and phar -->
					<li <?php echo ($page == "search_veterinary") ? 'class="active"' : ''; ?>>
						<a href="<?php  echo base_url(); ?>search-veterinary?type=6" ?>
							<i class="fas fa-calendar-check"></i>
							<span><?php //echo $language['lg_appointments'] ?? ""; ?>Book Appointments</span>
						</a>
					</li>


        			<li <?php echo ($page == 'profile') ? 'class="active"' : ''; ?>>
        				<a href="<?php echo base_url() . session('module'); ?>/profile">
        					<i class="fas fa-user-cog"></i>
        					<span><?php echo  "Patient Card"; ?></span>
        				</a>
        			</li>
        			
        			<li <?php echo ($page == "appoinments") ? 'class="active"' : ''; ?>>
        					<a href="<?php echo base_url() . session('module'); ?>/appointments">
        						<i class="fas fa-calendar-check"></i>
        						<span><?php //echo $language['lg_appointments'] ?? ""; ?>Upcoming Appointments</span>
        					</a>
        			</li>

				<?php } ?>
				<!-- for all -->
				<li <?php echo ($page == 'doctor_dashboard' || $page == 'patientDashboard' || $page == "lab_dashboard" || $page == 'pharmacyDashboard') ? 'class="active"' : ''; ?>>
					<a href="<?php echo base_url() . session('module'); ?>">
						<i class="fas fa-columns"></i>
						<span><?php
								echo $language['lg_dashboard'] ?? ""; ?></span>
					</a>
				</li>
				
				<?php //if (session('role') == '2') { ?>
					<!-- only patient , clinic and Veterinary -->
				<!--	<li <?php //echo ($page == "") ? 'class="active"' : ''; ?>>
						<a href="<?php //echo base_url() . session('module'); ?>/message">
							<i class="fas fa-comments"></i>
							<span><?php //echo $language['lg_messages'] ?? ""; ?></span>
							<small class="unread-msg unread_msg_count">0</small>
						</a>
					</li>-->
				<?php //} ?>

				<?php if (session('role') == '4') { ?>
					<!--  lab -->
					<li <?php echo ($page == "lab_tests") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/lab-test">
							<i class="fas fa-calendar-check"></i>
							<span><?php echo $language['lg_lab_tests'] ?? ""; ?></span>
						</a>
					</li>
				<?php } ?>

				
				<?php if (session('role') != "5" && session('role') != "2") { ?>
					<!-- without lab and phar -->
					<li <?php echo ($page == "appoinments") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/appointments">
							<i class="fas fa-calendar-check"></i>
							<span><?php //echo $language['lg_appointments'] ?? ""; ?>Upcoming Appointments</span>
						</a>
					</li>
				<?php } ?>

				<?php /*if (session('role') == '2') { ?>
					<!-- only patient -->
					<li <?php echo ($page == "lab_appoinments") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/lab-appointments">
							<i class="fas fa-flask"></i>
							<span>Lab <?php echo $language['lg_appointments'] ?? ""; ?></span>
						</a>
					</li>
					<li <?php echo ($page == "orderlist") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/orders-list">
							<i class="fas fa-calendar-check"></i>
							<span><?php echo $language['lg_order_list'] ?? ""; ?></span>
						</a>
					</li>
				<?php } */?>

				<?php if (session('role') == '6' || session('role') == '1') { ?>
					<!-- only clinic and doctor -->
					<li <?php echo ($page == "my_patients") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/my-patients">
							<i class="fas fa-user-injured"></i>
							<span><?php echo $language['lg_my_patients'] ?? ""; ?></span>
						</a>
					</li>

					<!-- only clinic and doctor -->
					<li <?php echo ($page == "scheduleTime") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url(); ?>schedule">
							<i class="fas fa-hourglass-start"></i>
							<span><?php echo $language['lg_schedule_timing'] ?? ""; ?></span>
						</a>
					</li>

				<?php } ?>

				<?php /*if (session('role') == '5') { ?>
					<li <?php echo ($module == 'pharmacy' && $page == 'product_list') ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/product-list">
							<i class="fas fa-calendar-check"></i>
							<span><?php echo $language['lg_products'] ?? ""; ?></span>
						</a>
					</li>

					<li <?php echo ($module == 'pharmacy' && $page == 'add_product') ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/product-add">
							<i class="fas fa-calendar-check"></i>
							<span><?php echo $language['lg_add_product'] ?? ""; ?></span>
						</a>
					</li>

					<li <?php echo ($module == 'pharmacy' && $page == 'orderlist') ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/orders-list">
							<i class="fas fa-calendar-check"></i>
							<span><?php echo $language['lg_order_list'] ?? ""; ?></span>
						</a>
					</li>
				<?php } */?>

				<?php /*if (session('role') != '4' && session('role') != '5') { ?>
					<!-- without lab and pharm -->
					<li <?php echo ($page == "calendar") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url(); ?>calendar">
							<i class="fas fa-calendar-check"></i>
							<span><?php echo $language['lg_calendar'] ?? ""; ?></span>
						</a>
					</li>
				<?php } */?>

				<!-- for all -->
				<li <?php echo ($page == "invoice") ? 'class="active"' : ''; ?>>
					<a href="<?php echo base_url() . session('module'); ?>/invoice">
						<i class="fas fa-file-invoice"></i>
						<span><?php echo $language['lg_invoice'] ?? ""; ?></span>
					</a>
				</li>

				<!-- for all -->
				<?php if($user_detail['hospital_id'] == 0 ) { ?>
				<li <?php echo ($page == "accounts") ? 'class="active"' : ''; ?>>
					<a href="<?php echo base_url() . session('module'); ?>/accounts">
						<i class="fas fa-address-card"></i>
						<span><?php echo $language['lg_accounts'] ?? ""; ?></span>
					</a>
				</li>
				<?php } ?>

				<?php if (session('role') == '6') { ?>
					<!-- only clinic -->
					<li <?php echo ($page == "doctorList") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/doctor">
							<i class="fas fa-user-md"></i>
							<span>Add Veterinary</span>
						</a>
					</li>
				<?php } ?>

				<!-- only patient -->
				<?php if (session('role') == '2') { ?>
					<li <?php echo ($page == "favourites") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/favourites">
							<i class="fas fa-heart"></i>
							<span><?php echo $language['lg_favourites'] ?? " "; ?></span>
						</a>
					</li>
				<?php } ?>

				<?php if (session('role') == '6' || session('role') == '1' || session('role') == '2') { ?>
					<!-- only clinic and Veterinary -->
					<li <?php echo ($page == "review") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module') ?>/review">
							<i class="fas fa-star"></i>
							<span><?php echo $language['lg_reviews'] ?? ""; ?></span>
						</a>
					</li>
				<?php } ?>

				<?php if (session('role') == '1' || session('role') == '6') { ?>
					<!-- only patient , clinic and Veterinary -->
					<li <?php echo ($page == "") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/message">
							<i class="fas fa-comments"></i>
							<span><?php echo $language['lg_messages'] ?? ""; ?></span>
							<small class="unread-msg unread_msg_count">0</small>
						</a>
					</li>
				<?php } ?>

				<!-- for all, not Veterinary  -->
				<?php if (session('role') != '2') { ?>
				<li <?php echo ($page == 'profile') ? 'class="active"' : ''; ?>>
					<a href="<?php echo base_url() . session('module'); ?>/profile">
						<i class="fas fa-user-cog"></i>
						<span><?php echo $language['lg_profile_setting'] ?? ""; ?></span>
					</a>
				</li>
				<?php } ?>
				<?php /*if (session('role') == '6' || session('role') == '1') { ?>
					<!-- only clinic and doctor -->
					<li <?php echo ($page == "social-media") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url(); ?>social-media">
							<i class="fas fa-share-alt"></i>
							<span><?php echo $language['lg_social_media'] ?? ""; ?></span>
						</a>
					</li>
				<?php } */?>

				<!-- for all -->
				<li <?php echo ($page == "change-password") ? 'class="active"' : ''; ?>>
					<a href="<?php echo base_url(); ?>change-password">
						<i class="fas fa-lock"></i>
						<span><?php echo $language['lg_change_password'] ?? ""; ?></span>
					</a>
				</li>

				<?php /*if (session('role') == '6' || session('role') == '1') { ?>
					<!-- only clinic and doctor -->
					<li <?php echo ($theme == "blog") ? 'class="active"' : ''; ?>>
						<a href="<?php echo base_url() . session('module'); ?>/active-blog">
							<i class="fas fa-rss"></i>
							<span><?php echo $language['lg_blog2'] ?? ""; ?></span>
						</a>
					</li>
				<?php } */?>

				<!-- for all -->
				<li <?php echo ($page == "") ? 'class="active"' : ''; ?>>
					<a href="<?php echo base_url(); ?>user-logout">
						<i class="fas fa-sign-out-alt"></i>
						<span><?php echo $language['lg_signout'] ?? ""; ?></span>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</div>