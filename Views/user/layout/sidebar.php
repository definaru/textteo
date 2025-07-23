<?php
	use App\Data\Sitebar;
	use App\Services\Profile;

	$user = user_detail(session('user_id'));
	$menuItems = Sitebar::menu();
	$user_profile_image = Profile::avatar($user);

	

	$username = libsodiumDecrypt($user['first_name']).' '.libsodiumDecrypt($user['last_name']);
	$clinicname = $user['clinicname'] == '' ? $user['first_name'] : $user['clinicname'];
	// Role's
	// 1 -> doctor
	// 2 -> patient
	// 4 -> lab
	// 5 -> pharmacy
	// 6 -> clinic

	//echo (!empty($user['dob'])) ? '<p><i class="fas fa-birthday-cake"></i> ' . date('d M Y', strtotime($user['dob'])) . ', ' . age_calculate($user['dob']) . '</p>' : '';
	//echo (!empty($user['city'])) ? '<p class="mb-0"><i class="fas fa-map-marker-alt"></i>' . $user['cityname'] . ', ' . $user['countryname'] . '</p>' : '';
?>
<div class="col-12 col-md-3 theiaStickySidebar">
	<div class="profile-sidebar">
		<div class="widget-profile pro-widget-content">
			<div class="profile-info-widget">
				<div class="booking-doc-img">
					<img 
						src="<?=$user_profile_image; ?>" 
						class="avatar-view-img" 
						alt="<?=$username;?>" 
					/>
				</div>
				<div class="profile-det-info">
					<h3>
						<?php
						if (session('role') == '6') {
							echo libsodiumDecrypt($clinicname);
						} else if (session('role') == '1') {
							echo $language['lg_dr'] ?? "";
							echo $username;
						} else if (session('role') == '2') {
							echo $username;
							// more info
						} else {
							echo $username;
						}
						?>
					</h3>
					<?php if (session('role') == '1') { ?>
						<div class="patient-details">
							<h5 class="mb-0">
								<?=ucfirst(libsodiumDecrypt($user['speciality']));?>
							</h5>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="dashboard-widget">
			<nav class="dashboard-menu">
				<ul>
					<?php foreach ($menuItems as $item) { ?>
						<?php if ($item['show']) { ?>
						<li <?=in_array($page, $item['slug']) ? 'class="active"' : '';?>>
							<a href="<?=$item['href'];?>" <?=$item['extra'] ?? '' ?> class="text-decoration-none">
								<i class="<?=$item['icon'];?>"></i>
								<span><?=$item['label'];?></span>
								<?=$item['count'] !== null ? '<small class="unread-msg unread_msg_count">'.$item['count'].'</small>' : '';?>
							</a>
						</li>
						<?php } ?>
					<?php } ?>
				</ul>
			</nav>
		</div>
	</div>	
</div>
	


<!-- Modal -->
<div class="modal fade" id="signoutBtnModal" tabindex="-1" role="dialog">
  	<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content bg-white">
			<div class="modal-body text-center">
				<div class="vstack gap-3">
					<div class="position-relative">
						<div class="position-absolute top-0 start-100 translate-middle">
							<button 
								type="button" 
								class="btn" 
								data-dismiss="modal" 
								aria-label="Close"
								style="font-size: 20px;color: #757575"
							>
								<span aria-hidden="true">&times;</span>
							</button>							
						</div>
						<h2 class="fw-semibold m-0" style="font-size: 18px">
							Are you sure you want<br />
							to sign out of your account?
						</h2>						
					</div>
					<div class="d-flex gap-2">
						<a href="/user-logout" id="signout" class="btn btn-outline-dark flex-fill text-uppercase border-black fw-semibold">
							Sign out
						</a>
						<button type="button" data-dismiss="modal" class="btn btn-warning flex-fill fw-semibold">
							CANCEL
						</button>
					</div>
				</div>
			</div>
		</div>
  	</div>
</div>


<?php /*
<ul>
	<?php if (session('role') == "2") { ?>
		<li <?=$page == 'searchDoctor' ? 'class="active"' : ''; ?>>
			<a href="/search-veterinary?type=1" ?>
				<i class="fas fa-calendar-check"></i>
				<span>Book Appointments</span>
			</a>
		</li>
	<?php } ?>
	<!-- for all -->
	<li <?=($page == 'doctor_dashboard' || $page == 'patientDashboard' || $page == "lab_dashboard" || $page == 'pharmacyDashboard') ? 'class="active"' : ''; ?>>
		<a href="<?='/'. session('module');?>">
			<i class="fas fa-th-large"></i>
			<span><?=$language['lg_dashboard'] ?? "Dashboard"; ?></span>
		</a>
	</li>

	<li <?php echo ($page == 'profile') ? 'class="active"' : ''; ?>>
		<a href="<?='/'.session('module').'/profile';?>">
			<i class="fas fa-user"></i>
			<span>Patient Card</span>
		</a>
	</li>
	
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
				<span>Upcoming Appointments</span>
			</a>
		</li>
	<?php } ?>
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

	<!-- for all -->
	<li <?php echo ($page == "invoice") ? 'class="active"' : ''; ?>>
		<a href="<?php echo base_url() . session('module'); ?>/invoice">
			<i class="fas fa-file-invoice"></i>
			<span><?php echo $language['lg_invoice'] ?? "Invoice"; ?></span>
		</a>
	</li>

	<!-- for all -->
	<?php if($user['hospital_id'] == 0 && session('role') != '2' ) { ?>
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

	<?php if (session('role') == '6' || session('role') == '1') { ?>
		<!-- only clinic and Veterinary -->
		<li <?php echo ($page == "review") ? 'class="active"' : ''; ?>>
			<a href="<?='/'. session('module') ?>/review">
				<i class="fas fa-star"></i>
				<span><?=$language['lg_reviews'] ?? ""; ?></span>
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

	<?php if (session('role') != '2') { ?>
	<!-- for all -->
	<li <?php echo ($page == "change-password") ? 'class="active"' : ''; ?>>
		<a href="<?php echo base_url(); ?>change-password">
			<i class="fas fa-lock"></i>
			<span><?php echo $language['lg_change_password'] ?? ""; ?></span>
		</a>
	</li>
	<?php } ?>

	<!-- for all -->
	<li <?php echo ($page == "") ? 'class="active"' : ''; ?>>
		<a href="javascript:void(0);" id="signOutBtn" data-toggle="modal" data-target="#signoutBtnModal">
			<i class="fas fa-sign-out-alt"></i>
			<span><?php echo $language['lg_signout'] ?? "Signout"; ?></span>
		</a>
	</li>
</ul>
*/ ?>

<?php /*
<?php if(session('role') == '2') { ?>
	<div class="bottom-nav">
		<a href="<?=base_url() . session('module'); ?>/invoice" <?=($page == "invoice") ? 'class="nav-item active"' : 'class="nav-item"'; ?>>
			<i class="icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" class="<?=($page == 'invoice') ? 'nav-item active' : 'nav-item'; ?>">
					<path fill="currentColor" d="M19.5 10.3h-3v-9c0-.4-.2-.7-.5-.9s-.7-.2-1 0l-3 1.7L9 .4c-.3-.2-.7-.2-1 0L5 2.1 2 .4c-.3-.2-.7-.2-1 0s-.5.5-.5.9v16c0 1.7 1.3 3 3 3h14c1.7 0 3-1.3 3-3v-6c0-.5-.5-1-1-1m-12 6h-2c-.6 0-1-.4-1-1s.4-1 1-1h2c.6 0 1 .4 1 1s-.5 1-1 1m0-4h-2c-.6 0-1-.4-1-1s.4-1 1-1h2c.6 0 1 .4 1 1s-.5 1-1 1m-1-4c-.6 0-1-.4-1-1s.4-1 1-1h4c.6 0 1 .4 1 1s-.4 1-1 1zm5 8c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.5 1-1 1m0-4c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.5 1-1 1m7 5c0 .6-.4 1-1 1s-1-.4-1-1v-5h2z"/>
				</svg>
			</i>
			<span>Invoice</span>
		</a>
		<a href="<?=base_url() . session('module'); ?>" <?=($page == "patientDashboard") ? 'class="nav-item active"' : 'class="nav-item"'; ?>>
			<i class="icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" class="<?=($page == 'patientDashboard') ? 'nav-item active' : 'nav-item'; ?>">
				<g fill="currentColor" clip-path="url(#a)">
					<path d="M2.464.906H18.87c.909 0 1.64.732 1.64 1.64V4.7c0 .909-.731 1.64-1.64 1.64H2.464c-.909 0-1.64-.731-1.64-1.64V2.547c0-.909.731-1.64 1.64-1.64M2.464 7.693h5.665c.909 0 1.64.731 1.64 1.64v9.618c0 .909-.916 1.64-1.825 1.64h-5.48c-.909 0-1.64-.731-1.64-1.64V9.333c0-.909.731-1.64 1.64-1.64M13.204 7.693h5.665c.91 0 1.64.731 1.64 1.64v2.135c0 .91-.915 1.64-1.824 1.64h-5.48c-.91 0-1.642-.73-1.642-1.64V9.333c0-.909.732-1.64 1.641-1.64m0 7.154h5.665c.91 0 1.64.732 1.64 1.64v2.464c0 .909-.915 1.64-1.824 1.64h-5.48c-.91 0-1.642-.731-1.642-1.64v-2.463c0-.91.732-1.64 1.641-1.64" />
				</g>
				<defs>
					<clipPath id="a"><path fill="#fff" d="M.167.25h21v21h-21z"/></clipPath>
				</defs>
			</svg>
			</i>
			<span>Dashboard</span>
		</a>
		<a href="<?=base_url(); ?>search-veterinary?type=1" <?=($page == "searchDoctor") ? 'class="nav-item active"' : 'class="nav-item"'; ?>>
			<i class="icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" class="<?=($page == 'searchDoctor') ? 'nav-item active' : 'nav-item'; ?>" fill="currentColor">
				<g clip-path="url(#a)">
					<path d="M10.593 21.5h-4.26c-1.52 0-2.75-1.23-2.75-2.75v-13c0-1.35.97-2.47 2.25-2.7v.7a2.5 2.5 0 0 0 2.5 2.5h6a2.5 2.5 0 0 0 2.5-2.5v-.7a2.74 2.74 0 0 1 2.25 2.7v1.66c-1.12-.58-2.4-.91-3.75-.91-4.55 0-8.25 3.7-8.25 8.25 0 2.79 1.39 5.25 3.51 6.75m11.49-6.75a6.76 6.76 0 0 1-6.75 6.75 6.76 6.76 0 0 1-6.75-6.75A6.76 6.76 0 0 1 15.333 8a6.76 6.76 0 0 1 6.75 6.75m-3.22-2.53a.75.75 0 0 0-1.061 0l-3.47 3.47-1.47-1.47a.75.75 0 1 0-1.061 1.061l1.646 1.646c.243.244.563.365.884.365s.641-.121.884-.365l3.646-3.646a.75.75 0 0 0 0-1.061zM8.333 4.75h6a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1"/>
				</g>
				<defs>
					<clipPath id="a">
						<path fill="#fff" d="M.333.5h24v24h-24z"/>
					</clipPath>
				</defs>
			</svg>
			</i>
			<span>Book</span>
		</a>
		<a href="<?=base_url() . session('module'); ?>/profile" <?=($page == "profile") ? 'class="nav-item active"' : 'class="nav-item"'; ?>>
			<i class="icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" class="<?=($page == 'profile') ? 'nav-item active' : 'nav-item'; ?>">
					<g fill="currentColor" clip-path="url(#a)">
						<path d="M12.5 1.75a4.75 4.75 0 1 0 0 9.5 4.75 4.75 0 0 0 0-9.5M9.5 12.75a4.75 4.75 0 0 0 0 9.5h6a4.75 4.75 0 1 0 0-9.5z"/>
					</g>
					<defs>
						<clipPath id="a">
							<path fill="#fff" d="M.5.5h24v24H.5z"/>
						</clipPath>
					</defs>
				</svg>
			</i>
			<span>Patient Card</span>
		</a>
	</div>
<?php } ?>
*/ ?>