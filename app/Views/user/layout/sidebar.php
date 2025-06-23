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
<!-- 
<script src="<?php //echo base_url();?>assets/js/jquery.min.js"></script>
<script>
	 $(document).ready(function(){
		$(document).on('click', '#signOutBtn', function(){
		   $('#signoutBtnModal').modal('show');
		});
	 });
	
</script> -->
<style>
	/* Hide on tablets and desktops */
.bottom-nav {
  display: none;
}

/* Show only on mobile */
@media screen and (max-width: 768px) {
  .bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 60px;
    background-color: #fff;
    border-top: 1px solid #ddd;
    display: flex;
    justify-content: space-around;
    align-items: center;
    z-index: 1000;
  }

  .bottom-nav .nav-item {
    flex: 1;
    text-align: center;
    color: #555;
    font-size: 12px;
    text-decoration: none;
  }

  .bottom-nav .nav-item .icon {
    display: block;
    font-size: 20px;
    margin-bottom: 4px;
  }

  .bottom-nav .nav-item.active {
    color: #FFA500; /* Orange for active */
  }

  .bottom-nav .nav-item {
  color: #757575;
}

.bottom-nav .nav-item.active {
  color: #FD9720;
}
}
</style>
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
					<li <?php echo ($page == "searchDoctor") ? 'class="active"' : ''; ?>>
						<a href="<?php  echo base_url(); ?>search-veterinary?type=1" ?>
							<i class="fas fa-calendar-check"></i>
							<span><?php //echo $language['lg_appointments'] ?? ""; ?>Book Appointments</span>
						</a>
					</li>


        			
        			
        			<!-- <li <?php //echo ($page == "appoinments") ? 'class="active"' : ''; ?>>
        					<a href="<?php //echo base_url() . session('module'); ?>/appointments">
        						<i class="fas fa-calendar-check"></i>
        						<span><?php //echo $language['lg_appointments'] ?? ""; ?>Upcoming Appointments</span>
        					</a>
        			</li> -->

				<?php } ?>
				<!-- for all -->
				<li <?php echo ($page == 'doctor_dashboard' || $page == 'patientDashboard' || $page == "lab_dashboard" || $page == 'pharmacyDashboard') ? 'class="active"' : ''; ?>>
					<a href="<?php echo base_url() . session('module'); ?>">
						<i class="fas fa-th-large"></i>
						<span><?php
								echo $language['lg_dashboard'] ?? "Dashboard"; ?></span>
					</a>
				</li>

				<li <?php echo ($page == 'profile') ? 'class="active"' : ''; ?>>
        				<a href="<?php echo base_url() . session('module'); ?>/profile">
        					<i class="fas fa-user"></i>
        					<span><?php echo  "Patient Card"; ?></span>
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
						<span><?php echo $language['lg_invoice'] ?? "Invoice"; ?></span>
					</a>
				</li>

				<!-- for all -->
				<?php if($user_detail['hospital_id'] == 0 && session('role') != '2' ) { ?>
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
					<!-- <li <?php //echo ($page == "favourites") ? 'class="active"' : ''; ?>>
						<a href="<?php //echo base_url() . session('module'); ?>/favourites">
							<i class="fas fa-heart"></i>
							<span><?php //echo $language['lg_favourites'] ?? " "; ?></span>
						</a>
					</li> -->
				<?php } ?>

				<?php if (session('role') == '6' || session('role') == '1') { ?>
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

               <?php if (session('role') != '2') { ?>
				<!-- for all -->
				<li <?php echo ($page == "change-password") ? 'class="active"' : ''; ?>>
					<a href="<?php echo base_url(); ?>change-password">
						<i class="fas fa-lock"></i>
						<span><?php echo $language['lg_change_password'] ?? ""; ?></span>
					</a>
				</li>
				<?php } ?>

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
					<a href="javascript:void(0);" id="signOutBtn" data-toggle="modal" data-target="#signoutBtnModal">
						<i class="fas fa-sign-out-alt"></i>
						<span><?php echo $language['lg_signout'] ?? "Signout"; ?></span>
					</a>
				</li>
			</ul>

			<?php if(session('role') == '2'){ ?>
				<div class="bottom-nav">
				<a href="<?php echo base_url() . session('module'); ?>/invoice"  <?php echo ($page == "invoice") ? 'class="nav-item active"' : 'class="nav-item"'; ?>>
					<i class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" class="<?php echo ($page == 'invoice') ? 'nav-item active' : 'nav-item'; ?>">
  <path fill="currentColor" d="M19.5 10.3h-3v-9c0-.4-.2-.7-.5-.9s-.7-.2-1 0l-3 1.7L9 .4c-.3-.2-.7-.2-1 0L5 2.1 2 .4c-.3-.2-.7-.2-1 0s-.5.5-.5.9v16c0 1.7 1.3 3 3 3h14c1.7 0 3-1.3 3-3v-6c0-.5-.5-1-1-1m-12 6h-2c-.6 0-1-.4-1-1s.4-1 1-1h2c.6 0 1 .4 1 1s-.5 1-1 1m0-4h-2c-.6 0-1-.4-1-1s.4-1 1-1h2c.6 0 1 .4 1 1s-.5 1-1 1m-1-4c-.6 0-1-.4-1-1s.4-1 1-1h4c.6 0 1 .4 1 1s-.4 1-1 1zm5 8c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.5 1-1 1m0-4c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.5 1-1 1m7 5c0 .6-.4 1-1 1s-1-.4-1-1v-5h2z"/>
</svg>

				    </i>
					<span>Invoice</span>
				</a>
				<a href="<?php echo base_url() . session('module'); ?>" <?php echo ($page == "patientDashboard") ? 'class="nav-item active"' : 'class="nav-item"'; ?>>
					<i class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" class="<?php echo ($page == 'patientDashboard') ? 'nav-item active' : 'nav-item'; ?>"><g fill="currentColor" clip-path="url(#a)"><path d="M2.464.906H18.87c.909 0 1.64.732 1.64 1.64V4.7c0 .909-.731 1.64-1.64 1.64H2.464c-.909 0-1.64-.731-1.64-1.64V2.547c0-.909.731-1.64 1.64-1.64M2.464 7.693h5.665c.909 0 1.64.731 1.64 1.64v9.618c0 .909-.916 1.64-1.825 1.64h-5.48c-.909 0-1.64-.731-1.64-1.64V9.333c0-.909.731-1.64 1.64-1.64M13.204 7.693h5.665c.91 0 1.64.731 1.64 1.64v2.135c0 .91-.915 1.64-1.824 1.64h-5.48c-.91 0-1.642-.73-1.642-1.64V9.333c0-.909.732-1.64 1.641-1.64m0 7.154h5.665c.91 0 1.64.732 1.64 1.64v2.464c0 .909-.915 1.64-1.824 1.64h-5.48c-.91 0-1.642-.731-1.642-1.64v-2.463c0-.91.732-1.64 1.641-1.64"/></g><defs><clipPath id="a"><path fill="#fff" d="M.167.25h21v21h-21z"/></clipPath></defs></svg>
					</i>
					<span>Dashboard</span>
				</a>
				<a href="<?php  echo base_url(); ?>search-veterinary?type=1" <?php echo ($page == "searchDoctor") ? 'class="nav-item active"' : 'class="nav-item"'; ?>>
					<i class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" class="<?php echo ($page == 'searchDoctor') ? 'nav-item active' : 'nav-item'; ?>" fill="currentColor"><g clip-path="url(#a)"><path d="M10.593 21.5h-4.26c-1.52 0-2.75-1.23-2.75-2.75v-13c0-1.35.97-2.47 2.25-2.7v.7a2.5 2.5 0 0 0 2.5 2.5h6a2.5 2.5 0 0 0 2.5-2.5v-.7a2.74 2.74 0 0 1 2.25 2.7v1.66c-1.12-.58-2.4-.91-3.75-.91-4.55 0-8.25 3.7-8.25 8.25 0 2.79 1.39 5.25 3.51 6.75m11.49-6.75a6.76 6.76 0 0 1-6.75 6.75 6.76 6.76 0 0 1-6.75-6.75A6.76 6.76 0 0 1 15.333 8a6.76 6.76 0 0 1 6.75 6.75m-3.22-2.53a.75.75 0 0 0-1.061 0l-3.47 3.47-1.47-1.47a.75.75 0 1 0-1.061 1.061l1.646 1.646c.243.244.563.365.884.365s.641-.121.884-.365l3.646-3.646a.75.75 0 0 0 0-1.061zM8.333 4.75h6a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1"/></g><defs><clipPath id="a"><path fill="#fff" d="M.333.5h24v24h-24z"/></clipPath></defs></svg>
					</i>
					<span>Book</span>
				</a>
				<a href="<?php echo base_url() . session('module'); ?>/profile" <?php echo ($page == "profile") ? 'class="nav-item active"' : 'class="nav-item"'; ?>>
					<i class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" class="<?php echo ($page == 'profile') ? 'nav-item active' : 'nav-item'; ?>"><g fill="currentColor" clip-path="url(#a)"><path d="M12.5 1.75a4.75 4.75 0 1 0 0 9.5 4.75 4.75 0 0 0 0-9.5M9.5 12.75a4.75 4.75 0 0 0 0 9.5h6a4.75 4.75 0 1 0 0-9.5z"/></g><defs><clipPath id="a"><path fill="#fff" d="M.5.5h24v24H.5z"/></clipPath></defs></svg>
					</i>
					<span>Patient Card</span>
				</a>
				</div>
			<?php } ?>
		</nav>
	</div>
</div>

