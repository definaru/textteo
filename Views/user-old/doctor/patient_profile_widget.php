<?php

/** @var array $user_detail */
/** @var int $patient_id */
$user_detail = user_detail($patient_id ?? "");
if($user_detail['profileimage']=="" || ($user_detail['profileimage']!="" && !is_file($user_detail['profileimage']))){
	$profile_image=base_url().'assets/img/user.png';
}
else {
	$profile_image=(!empty($user_detail['profileimage']??""))?base_url().$user_detail['profileimage']??"":base_url().'assets/img/user.png';
}

?>


<div class="card widget-profile pat-widget-profile">
	<div class="card-body">
		<div class="pro-widget-content">
			<div class="profile-info-widget">
				<a href="javascript:void(0);" class="booking-doc-img">
					<img src="<?php echo $profile_image; ?>" alt="User Image">
				</a>
				<div class="profile-det-info">
					<h3 class="w-100"><?php echo ucfirst(libsodiumDecrypt($user_detail['first_name'] ?? "") . ' ' . libsodiumDecrypt($user_detail['last_name'] ?? "")); ?></h3>

					<div class="patient-details">
						<h5><b><?php
								/** @var array $language */
								echo $language['lg_patient_id'] ?? ""; ?> :</b> #PT00<?php echo $user_detail['userid'] ?? ""; ?></h5>
						<h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> <?php echo $user_detail['cityname'] ?? "" . ', ' . $user_detail['countryname'] ?? ""; ?></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="patient-info">
			<ul>
				<li><?php echo $language['lg_email'] ?? ""; ?> <span class="w-100"><?php echo libsodiumDecrypt($user_detail['email'] ?? ""); ?></span></li>
				<li><?php echo $language['lg_phone'] ?? ""; ?> <span><?php echo libsodiumDecrypt($user_detail['mobileno'] ?? ""); ?></span></li>
				<li><?php echo $language['lg_age'] ?? ""; ?> <span><?php echo age_calculate($user_detail['dob'] ?? ""); ?>, <?php echo libsodiumDecrypt($user_detail['gender'] ?? ""); ?></span></li>
				<li><?php echo $language['lg_blood_group'] ?? ""; ?> <span><?php echo libsodiumDecrypt($user_detail['blood_group'] ?? ""); ?></span></li>
			</ul>
		</div>
	</div>
</div>

<!-- Last Booking -->
<?php if (!empty($last_booking)) { ?>
	<div class="card">
		<div class="card-header">
			<h4 class="card-title"><?php echo $language['lg_last_booking']; ?></h4>
		</div>


		<ul class="list-group list-group-flush">
			<?php foreach ($last_booking as $rows) {

				$profile_image = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
			?>
				<li class="list-group-item">
					<div class="media align-items-center">
						<div class="mr-3">
							<img alt="Image placeholder" src="<?php echo $profile_image; ?>" class="avatar  rounded-circle">
						</div>
						<div class="media-body">
							<h5 class="d-block mb-0">Dr. <?php echo libsodiumDecrypt($rows['first_name']) . "" . libsodiumDecrypt($rows['last_name']); ?> </h5>
							<span class="d-block text-sm text-muted"><?php echo libsodiumDecrypt($rows['specialization']); ?></span>
							<span class="d-block text-sm text-muted"><?php
																		$from_timezone = $rows['time_zone'];
																		$to_timezone = date_default_timezone_get();
																		$from_date_time = $rows['from_date_time'];
																		$from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
																		$date_app = date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info">' . date('h:i A', strtotime($from_date_time)) . '</span>';


																		echo $date_app; ?></span>
						</div>
					</div>
				</li>
			<?php } ?>
		</ul>

	</div>
<?php } ?>
<!-- /Last Booking -->