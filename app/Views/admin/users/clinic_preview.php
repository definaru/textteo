<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php
																						/** @var array $language  */
																						echo $language['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_clinic_profile']; ?></li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title"><?php echo $language['lg_clinic_profile']; ?></h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
	<div class="container">

		<!-- Doctor Widget -->
		<div class="card">
			<div class="card-body">
				<div class="doctor-widget">
					<div class="doc-info-left">
						<div class="doctor-img">
							<?php
							/** @var array $doctors  */
							/** @var array $clinic_images  */
							$profileimage = (!empty($doctors['profileimage'])) ? base_url() . $doctors['profileimage'] : base_url() . 'assets/img/user.png';
							?>
							<img src="<?php echo $profileimage; ?>" class="img-fluid" alt="User Image">
						</div>
						<div class="doc-info-cont">
							<h4 class="doc-name"><?php //echo $language['lg_dr'];
													?> <?php echo ucfirst(libsodiumDecrypt($doctors['first_name']) . ' ' . libsodiumDecrypt($doctors['last_name'])); ?></h4>
							<p class="doc-department">
								<?php if (!empty($doctors['specialization_img']) && file_exists(FCPATH . $doctors['specialization_img'])) { ?>
									<img src="<?php echo base_url() . $doctors['specialization_img']; ?>" class="img-fluid" alt="Speciality">
								<?php } ?>
								<?php if (!empty($doctors['speciality'])) {
									echo ucfirst(libsodiumDecrypt($doctors['speciality']));
								} ?>
							</p>
							<div class="rating">
								<?php
								$rating_value = $doctors['rating_value'];
								for ($i = 1; $i <= 5; $i++) {
									if ($i <= $rating_value) {
										echo '<i class="fas fa-star filled"></i>';
									} else {
										echo '<i class="fas fa-star"></i>';
									}
								}
								?>
								<span class="d-inline-block average-rating">(<?php echo $doctors['rating_count']; ?>)</span>
							</div>
							<div class="clinic-details">
								<p class="doc-location">
									<i class="fas fa-map-marker-alt"></i>
									<?php
									echo ($doctors['cityname'] ?? '') . (!empty($doctors['countryname'] ? ', ' . $doctors['countryname'] : ''));
									?>
								</p> <span> </span>
								<i class="fa fa-map-marker" aria-hidden="true"></i>
								<a target="_blank" href="<?php echo base_url() . "maps/" . base64_encode($doctors['userid']); ?>"><?php echo $language['lg_get_directions']; ?></a>
								<ul class="clinic-gallery">
									<?php

									foreach ($clinic_images as $clinic_img) { ?>

										<li>
											<a href="<?php echo base_url(); ?>uploads/clinic_uploads/<?php echo $clinic_img['user_id']; ?>/<?php echo $clinic_img['clinic_image']; ?>" data-fancybox="gallery">
												<img src="<?php echo base_url(); ?>uploads/clinic_uploads/<?php echo $clinic_img['user_id']; ?>/<?php echo $clinic_img['clinic_image']; ?>" alt="Feature">
											</a>
										</li>
									<?php } ?>

								</ul>
							</div>
							<?php if (!empty($doctors['services'])) {
								$services = explode(',', $doctors['services']);
								echo '<div class="clinic-services">';
								for ($i = 0; $i < count($services); $i++) {

									echo '<span>' . $services[$i] . '</span>';
								}
								echo '</div>';
							} ?>
						</div>
					</div>
					<div class="doc-info-right">
						<div class="clini-infos">
							<ul>
								<li><i class="far fa-comment"></i> <?php echo $doctors['rating_count']; ?> <?php echo $language['lg_feedback']; ?></li>
								<li><i class="fas fa-map-marker-alt"></i> </i> <?php echo ($doctors['cityname'] ?? '') . (!empty($doctors['countryname'] ? ', ' . $doctors['countryname'] : '')); ?></li>
								<li><i class="far fa-money-bill-alt"></i> <?php
																			/** @var float $amount  */
																			//echo $amount.' '.$language['lg_per_slot'];
																			?> </li>
							</ul>
						</div>
						<?php
						// $where=array('patient_id' =>session('user_id'),'doctor_id'=>$doctors['user_id']);
						$favourites = '';
						// $is_favourite=$this->db->get_where('favourities',$where)->result_array();
						if (count($is_favourite) > 0) {
							$favourites = 'fav-btns';
						}
						?>

						<div class="doctor-action">


							<a href="javascript:void(0)" id="favourities_<?php echo $doctors['user_id']; ?>" onclick="add_favourities('<?php echo $doctors['user_id']; ?>')" class="btn btn-white fav-btn <?php echo $favourites; ?>">
								<i class="fas fa-heart"></i>
							</a>

							<a href="<?php echo base_url() . 'book-appoinments/' . $doctors['username']; ?>" class="btn btn-white msg-btn">
								<i class="far fa-comment-alt"></i>
							</a>

							<a href="<?php echo base_url() . 'appoinments'; ?>" class="btn btn-white call-btn">
								<i class="fas fa-phone"></i>
							</a>

							<a href="<?php echo base_url() . 'appoinments'; ?>" class="btn btn-white call-btn">
								<i class="fas fa-video"></i>
							</a>


							<!-- <a href="<?php echo base_url() . 'appoinments'; ?>" class="btn btn-white call-btn" data-toggle="modal" >
											<i class="fas fa-phone"></i>
										</a>
										
										<a href="<?php echo base_url() . 'appoinments'; ?>" class="btn btn-white call-btn" data-toggle="modal" >
											<i class="fas fa-video"></i>
										</a> 
										 -->
						</div>

						<div class="clinic-booking">


							<a class="apt-btn" href="<?php echo base_url() . 'book-appoinments/' . $doctors['username']; ?>"><?php echo $language['lg_book_appointmen']; ?></a>

						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- /Doctor Widget -->

		<!-- Doctor Details Tab -->
		<div class="card">
			<div class="card-body pt-0">

				<!-- Tab Menu -->
				<nav class="user-tabs mb-4">
					<ul class="nav nav-tabs nav-tabs-bottom nav-justified">
						<li class="nav-item">
							<a class="nav-link active" href="#doc_overview" data-toggle="tab"><?php echo $language['lg_overview']; ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#doc_locations" data-toggle="tab"><?php echo $language['lg_locations']; ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#doc_reviews" data-toggle="tab"><?php echo $language['lg_reviews']; ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#doc_business_hours" data-toggle="tab"><?php echo $language['lg_business_hours']; ?></a>
						</li>
					</ul>
				</nav>
				<!-- /Tab Menu -->

				<!-- Tab Content -->
				<div class="tab-content pt-0">

					<!-- Overview Content -->
					<div role="tabpanel" id="doc_overview" class="tab-pane fade show active">
						<div class="row">
							<div class="col-md-12 col-lg-9">

								<!-- About Details -->
								<div class="widget about-widget">
									<h4 class="widget-title"><?php echo $language['lg_about_me']; ?></h4>
									<p><?php echo $doctors['biography']; ?></p>
								</div>
								<!-- /About Details -->

								<!-- Education Details -->
								<?php if (!empty($education)) { ?>
									<div class="widget education-widget">
										<h4 class="widget-title"><?php echo $language['lg_education']; ?></h4>
										<div class="experience-box">
											<ul class="experience-list">
												<?php
												foreach ($education as $erows) {
													echo '<li>
															<div class="experience-user">
																<div class="before-circle"></div>
															</div>
															<div class="experience-content">
																<div class="timeline-content">
																	<a href="#/" class="name">' . ucfirst($erows['institute']) . '</a>
																	<div>' . $erows['degree'] . '</div>
																	<span class="time">' . $erows['year_of_completion'] . '</span>
																</div>
															</div>
														</li>';
												} ?>
											</ul>
										</div>
									</div>
								<?php } ?>

								<!-- /Education Details -->


								<!-- Experience Details -->
								<?php if (!empty($experience)) { ?>
									<div class="widget experience-widget">
										<h4 class="widget-title"><?php echo $language['lg_work__experienc']; ?></h4>
										<div class="experience-box">
											<ul class="experience-list">
												<?php foreach ($experience as $exrows) {
													echo '<li>
														<div class="experience-user">
															<div class="before-circle"></div>
														</div>
														<div class="experience-content">
															<div class="timeline-content">
																<p class="exp-year">' . $exrows['designation'] . '</p>
																<a href="#/" class="name">' . $exrows['hospital_name'] . '</a>
																<span class="time">' . $exrows['from'] . ' - ' . $exrows['to'] . '</span>
															</div>
														</div>
													</li>';
												} ?>

											</ul>
										</div>
									</div>
								<?php } ?>
								<!-- /Experience Details -->

								<!-- Awards Details -->
								<?php if (!empty($awards)) { ?>
									<div class="widget awards-widget">
										<h4 class="widget-title"><?php echo $language['lg_awards']; ?></h4>
										<div class="experience-box">
											<ul class="experience-list">
												<?php foreach ($awards as $arows) {

													echo '<li>
															<div class="experience-user">
																<div class="before-circle"></div>
															</div>
															<div class="experience-content">
																<div class="timeline-content">
																	<p class="exp-year">' . $arows['awards_year'] . '</p>
																	<h4 class="exp-title">' . ucfirst($arows['awards']) . '</h4>
																</div>
															</div>
														</li>';
												} ?>

											</ul>
										</div>
									</div>
								<?php } ?>
								<!-- /Awards Details -->


								<!-- Services List -->
								<?php if (!empty($doctors['services'])) {
									$services = explode(',', $doctors['services']);
								?>
									<div class="service-list">
										<h4><?php echo $language['lg_services']; ?></h4>
										<ul class="clearfix">
											<?php
											for ($i = 0; $i < count($services); $i++) {

												echo '<li>' . $services[$i] . '</li>';
											}
											?>

										</ul>
									</div>
								<?php } ?>
								<!-- /Services List -->

								<!-- Specializations List -->
								<div class="service-list">
									<h4><?php echo $language['lg_specializations']; ?></h4>
									<ul class="clearfix">
										<li><?php echo ucfirst(libsodiumDecrypt($doctors['speciality'])); ?></li>

									</ul>
								</div>
								<!-- /Specializations List -->

							</div>
						</div>
					</div>
					<!-- /Overview Content -->

					<div role="tabpanel" id="doc_locations" class="tab-pane fade">

						<!-- Location List -->
						<div class="location-list">
							<div class="row">

								<!-- Clinic Content -->
								<div class="col-md-6">
									<div class="clinic-content">
										<!-- <h4 class="clinic-name"><?php echo $doctors['clinic_name']; ?></h4> -->


										<div class="clinic-details mb-0">
											<h5 class="clinic-direction"> <i class="fas fa-map-marker-alt"></i>

												<?php echo $doctors['address1']; ?> <?php echo ($doctors['address2']); ?>&nbsp;<?php echo ($doctors['cityname'] . ","); ?> <?php echo ($doctors['statename'] . ","); ?>&nbsp;<?php echo ($doctors['countryname'] . " - "); ?> <?php echo ($doctors['postal_code']); ?>
											</h5>
											<ul class="clinic-gallery">
												<?php foreach ($clinic_images as $clinic_img) { ?>

													<li>
														<a href="<?php echo base_url(); ?>uploads/clinic_uploads/<?php echo $clinic_img['user_id']; ?>/<?php echo $clinic_img['clinic_image']; ?>" data-fancybox="gallery">
															<img src="<?php echo base_url(); ?>uploads/clinic_uploads/<?php echo $clinic_img['user_id']; ?>/<?php echo $clinic_img['clinic_image']; ?>" alt="Feature">
														</a>
													</li>
												<?php } ?>

											</ul>
										</div>
									</div>
								</div>
								<!-- /Clinic Content -->


							</div>
						</div>
						<!-- /Location List -->
					</div>

					<!-- Reviews Content -->
					<div role="tabpanel" id="doc_reviews" class="tab-pane fade">

						<!-- Review Listing -->
						<div class="widget review-listing">
							<ul class="comments-list">
								<?php if (!empty($reviews)) {
									foreach ($reviews as $rrows) {

										$rimg = (!empty($rrows['profileimage'])) ? base_url() . $rrows['profileimage'] : base_url() . 'assets/img/user.png';
										$drimg = (!empty($rrows['doctor_image'])) ? base_url() . $rrows['doctor_image'] : base_url() . 'assets/img/user.png';

								?>

										<!-- Comment List -->
										<li>
											<div class="comment">
												<img class="avatar avatar-sm rounded-circle" alt="User Image" src="<?php echo $rimg; ?>">
												<div class="comment-body">
													<div class="meta-data">
														<span class="comment-author"><?php echo $rrows['first_name'] . ' ' . $rrows['last_name']; ?></span>
														<span class="comment-date"><?php echo $language['lg_reviewed']; ?> <?php echo time_elapsed_string($rrows['created_date']); ?></span>
														<div class="review-count rating">
															<?php for ($i = 1; $i <= 5; $i++) {
																if ($i <= $rrows['rating']) {
															?>
																	<i class="fas fa-star filled"></i>
																<?php } else { ?>
																	<i class="fas fa-star"></i>
															<?php }
															} ?>

														</div>
													</div>
													<p class="comment-content">
														<?php echo $rrows['review']; ?>
													</p>
													<div class="comment-reply">

													</div>
												</div>
											</div>
											<?php if ($rrows['reply_id'] != '') { ?>
												<ul class="comments-reply">

													<!-- Comment Reply List -->
													<li>
														<div class="comment">
															<img class="avatar rounded-circle" alt="User Image" src="<?php echo $drimg; ?>">
															<div class="comment-body">
																<div class="meta-data">
																	<span class="comment-author"><?php echo $language['lg_dr']; ?> <?php echo $rrows['doctor_firstname'] . ' ' . $rrows['doctor_lastname']; ?></span>
																	<span class="comment-date"><?php echo $language['lg_replied']; ?> <?php echo time_elapsed_string($rrows['reply_date']); ?> </span>
																</div>
																<p class="comment-content">
																	<?php echo $rrows['reply']; ?>
																</p>

															</div>
														</div>
													</li>
													<!-- /Comment Reply List -->

												</ul>
												<!-- /Comment Reply -->
											<?php } ?>

										</li>

								<?php }
								} else {

									echo '<li>
										<div class="comment">
										<p>' . $language['lg_no_reviews_foun'] . '</p>
										</div>
										</li>';
								} ?>

							</ul>

							<!-- Show All -->

							<!-- /Show All -->

						</div>
						<!-- /Review Listing -->

						<!-- Write Review -->

						<!-- /Write Review -->

					</div>
					<!-- /Reviews Content -->

					<!-- Business Hours Content -->
					<div role="tabpanel" id="doc_business_hours" class="tab-pane fade">
						<div class="row">
							<div class="col-md-6 offset-md-3">



								<!-- Business Hours Widget -->
								<div class="widget business-widget">
									<div class="widget-content">
										<div class="listing-hours">



											<?php
											$weekday1 = 'Monday';
											$weekday2 = 'Tuesday';
											$weekday3 = 'Wednesday';
											$weekday4 = 'Thursday';
											$weekday5 = 'Friday';
											$weekday6 = 'Saturday';
											$weekday7 = 'Sunday';

											$mon_start = '';
											$mon_end = '';
											$tue_start = '';
											$tue_end = '';
											$wed_start = '';
											$wed_end = '';
											$thu_start = '';
											$thu_end = '';
											$fri_start = '';
											$fri_end = '';
											$sat_start = '';
											$sat_end = '';
											$sun_start = '';
											$sun_end = '';

											//Monday start		
											if (!empty($monday_hours)) {

												$counter = 0;
												foreach ($monday_hours as $key => $value) {

													if ($counter == 0) {

														$mon_start = date('h:i A', strtotime($value['start_time']));
													}

													if ($counter == count($monday_hours) - 1) {

														$mon_end = date('h:i A', strtotime($value['end_time']));
													} ?>

												<?php $counter++;
												} ?>


												<?php if (date('l') == $weekday1) {

												?>


													<div class="listing-day current">
														<div class="day"><?php echo $language['lg_today']; ?> <span><?php echo date('d M Y'); ?></span></div>
														<div class="time-items">
															<?php
															if (date('h:i A') < $mon_end) {
																echo '<span class="open-status"><span class="badge bg-success-light">' . $language['lg_open_now'] . '</span></span>';
															} else {
																echo '<span class="open-status"><span class="badge bg-danger-light">' . $language['lg_closed'] . '</span></span>';
															}
															?>
															<span class="time"><?php echo $mon_start . " to " . $mon_end; ?></span>
														</div>
													</div>

												<?php } else { ?>

													<div class="listing-day">
														<div class="day"><?php echo ucfirst($weekday1); ?></div>
														<div class="time-items">
															<span class="time"><?php echo $mon_start . " to " . $mon_end; ?></span>
														</div>
													</div>
												<?php } ?>




											<?php } else { ?>
												<div class="listing-day">
													<div class="day"><?php echo ucfirst($weekday1); ?></div>
													<div class="time-items">
														<span class="time"><?php echo $language['lg_holiday']; ?></span>
													</div>
												</div>
											<?php } ?>

											<!-- Tuesday Start-->
											<?php if (!empty($tue_hours)) {

												$counter = 0;
												foreach ($tue_hours as $key => $value) {

													if ($counter == 0) {

														$tue_start = date('h:i A', strtotime($value['start_time']));
													}

													if ($counter == count($tue_hours) - 1) {

														$tue_end = date('h:i A', strtotime($value['end_time']));
													} ?>

												<?php $counter++;
												} ?>


												<?php if (date('l') == $weekday2) {

												?>


													<div class="listing-day current">
														<div class="day"><?php echo $language['lg_today']; ?> <span><?php echo date('d M Y'); ?></span></div>
														<div class="time-items">
															<?php
															if (date('h:i A') < $tue_end) {
																echo '<span class="open-status"><span class="badge bg-success-light">' . $language['lg_open_now'] . '</span></span>';
															} else {
																echo '<span class="open-status"><span class="badge bg-danger-light">' . $language['lg_closed'] . '</span></span>';
															}
															?>
															<span class="time"><?php echo $tue_start . " to " . $tue_end; ?></span>
														</div>
													</div>

												<?php } else { ?>

													<div class="listing-day">
														<div class="day"><?php echo ucfirst($weekday2); ?></div>
														<div class="time-items">
															<span class="time"><?php echo $tue_start . " to " . $tue_end; ?></span>
														</div>
													</div>
												<?php } ?>




											<?php } else { ?>
												<div class="listing-day">
													<div class="day"><?php echo ucfirst($weekday2); ?></div>
													<div class="time-items">
														<span class="time"><?php echo $language['lg_holiday']; ?></span>
													</div>
												</div>
											<?php } ?>

											<!-- Wednesday Start-->
											<?php if (!empty($wed_hours)) {

												$counter = 0;
												foreach ($wed_hours as $key => $value) {

													if ($counter == 0) {

														$wed_start = date('h:i A', strtotime($value['start_time']));
													}

													if ($counter == count($wed_hours) - 1) {

														$wed_end = date('h:i A', strtotime($value['end_time']));
													} ?>

												<?php $counter++;
												} ?>


												<?php if (date('l') == $weekday3) {

												?>


													<div class="listing-day current">
														<div class="day"><?php echo $language['lg_today']; ?> <span><?php echo date('d M Y'); ?></span></div>
														<div class="time-items">
															<?php
															if (date('h:i A') < $wed_end) {
																echo '<span class="open-status"><span class="badge bg-success-light">' . $language['lg_open_now'] . '</span></span>';
															} else {
																echo '<span class="open-status"><span class="badge bg-danger-light">' . $language['lg_closed'] . '</span></span>';
															}
															?>
															<span class="time"><?php echo $wed_start . " to " . $wed_end; ?></span>
														</div>
													</div>

												<?php } else { ?>

													<div class="listing-day">
														<div class="day"><?php echo ucfirst($weekday3); ?></div>
														<div class="time-items">
															<span class="time"><?php echo $wed_start . " to " . $wed_end; ?></span>
														</div>
													</div>
												<?php } ?>




											<?php } else { ?>
												<div class="listing-day">
													<div class="day"><?php echo ucfirst($weekday3); ?></div>
													<div class="time-items">
														<span class="time"><?php echo $language['lg_holiday']; ?></span>
													</div>
												</div>
											<?php } ?>

											<!-- Thursday Start-->
											<?php if (!empty($thu_hours)) {

												$counter = 0;
												foreach ($thu_hours as $key => $value) {

													if ($counter == 0) {

														$thu_start = date('h:i A', strtotime($value['start_time']));
													}

													if ($counter == count($thu_hours) - 1) {

														$thu_end = date('h:i A', strtotime($value['end_time']));
													} ?>

												<?php $counter++;
												} ?>


												<?php if (date('l') == $weekday4) {

												?>


													<div class="listing-day current">
														<div class="day"><?php echo $language['lg_today']; ?> <span><?php echo date('d M Y'); ?></span></div>
														<div class="time-items">
															<?php
															if (date('h:i A') < $thu_end) {
																echo '<span class="open-status"><span class="badge bg-success-light">' . $language['lg_open_now'] . '</span></span>';
															} else {
																echo '<span class="open-status"><span class="badge bg-danger-light">' . $language['lg_closed'] . '</span></span>';
															}
															?>
															<span class="time"><?php echo $thu_start . " to " . $thu_end; ?></span>
														</div>
													</div>

												<?php } else { ?>

													<div class="listing-day">
														<div class="day"><?php echo ucfirst($weekday4); ?></div>
														<div class="time-items">
															<span class="time"><?php echo $thu_start . " to " . $thu_end; ?></span>
														</div>
													</div>
												<?php } ?>




											<?php } else { ?>
												<div class="listing-day">
													<div class="day"><?php echo ucfirst($weekday4); ?></div>
													<div class="time-items">
														<span class="time"><?php echo $language['lg_holiday']; ?></span>
													</div>
												</div>
											<?php } ?>

											<!-- Friday Start-->
											<?php if (!empty($fri_hours)) {

												$counter = 0;
												foreach ($fri_hours as $key => $value) {

													if ($counter == 0) {

														$fri_start = date('h:i A', strtotime($value['start_time']));
													}

													if ($counter == count($fri_hours) - 1) {

														$fri_end = date('h:i A', strtotime($value['end_time']));
													} ?>

												<?php $counter++;
												} ?>


												<?php if (date('l') == $weekday5) {

												?>


													<div class="listing-day current">
														<div class="day"><?php echo $language['lg_today']; ?> <span><?php echo date('d M Y'); ?></span></div>
														<div class="time-items">
															<?php
															if (date('h:i A') < $fri_end) {
																echo '<span class="open-status"><span class="badge bg-success-light">' . $language['lg_open_now'] . '</span></span>';
															} else {
																echo '<span class="open-status"><span class="badge bg-danger-light">' . $language['lg_closed'] . '</span></span>';
															}
															?>
															<span class="time"><?php echo $fri_start . " to " . $fri_end; ?></span>
														</div>
													</div>

												<?php } else { ?>

													<div class="listing-day">
														<div class="day"><?php echo ucfirst($weekday5); ?></div>
														<div class="time-items">
															<span class="time"><?php echo $fri_start . " to " . $fri_end; ?></span>
														</div>
													</div>
												<?php } ?>




											<?php } else { ?>
												<div class="listing-day">
													<div class="day"><?php echo ucfirst($weekday5); ?></div>
													<div class="time-items">
														<span class="time"><?php echo $language['lg_holiday']; ?></span>
													</div>
												</div>
											<?php } ?>

											<!-- Saturday Start-->
											<?php if (!empty($sat_hours)) {

												$counter = 0;
												foreach ($sat_hours as $key => $value) {

													if ($counter == 0) {

														$sat_start = date('h:i A', strtotime($value['start_time']));
													}

													if ($counter == count($sat_hours) - 1) {

														$sat_end = date('h:i A', strtotime($value['end_time']));
													} ?>

												<?php $counter++;
												} ?>


												<?php if (date('l') == $weekday6) {

												?>


													<div class="listing-day current">
														<div class="day"><?php echo $language['lg_today']; ?> <span><?php echo date('d M Y'); ?></span></div>
														<div class="time-items">
															<?php
															if (date('h:i A') < $sat_end) {
																echo '<span class="open-status"><span class="badge bg-success-light">' . $language['lg_open_now'] . '</span></span>';
															} else {
																echo '<span class="open-status"><span class="badge bg-danger-light">' . $language['lg_closed'] . '</span></span>';
															}
															?>
															<span class="time"><?php echo $sat_start . " to " . $sat_end; ?></span>
														</div>
													</div>

												<?php } else { ?>

													<div class="listing-day">
														<div class="day"><?php echo ucfirst($weekday6); ?></div>
														<div class="time-items">
															<span class="time"><?php echo $sat_start . " to " . $sat_end; ?></span>
														</div>
													</div>
												<?php } ?>




											<?php } else { ?>
												<div class="listing-day">
													<div class="day"><?php echo ucfirst($weekday6); ?></div>
													<div class="time-items">
														<span class="time"><?php echo $language['lg_holiday']; ?></span>
													</div>
												</div>
											<?php } ?>

											<!-- Sunday Start-->
											<?php if (!empty($sunday_hours)) {

												$counter = 0;
												foreach ($sunday_hours as $key => $value) {

													if ($counter == 0) {

														$sun_start = date('h:i A', strtotime($value['start_time']));
													}

													if ($counter == count($sunday_hours) - 1) {

														$sun_end = date('h:i A', strtotime($value['end_time']));
													} ?>

												<?php $counter++;
												} ?>


												<?php if (date('l') == $weekday7) {

												?>


													<div class="listing-day current">
														<div class="day"><?php echo $language['lg_today']; ?> <span><?php echo date('d M Y'); ?></span></div>
														<div class="time-items">
															<?php
															if (date('h:i A') < $sun_end) {
																echo '<span class="open-status"><span class="badge bg-success-light">' . $language['lg_open_now'] . '</span></span>';
															} else {
																echo '<span class="open-status"><span class="badge bg-danger-light">' . $language['lg_closed'] . '</span></span>';
															}
															?>
															<span class="time"><?php echo $sun_start . " to " . $sun_end; ?></span>
														</div>
													</div>

												<?php } else { ?>

													<div class="listing-day">
														<div class="day"><?php echo ucfirst($weekday7); ?></div>
														<div class="time-items">
															<span class="time"><?php echo $sun_start . " to " . $sun_end; ?></span>
														</div>
													</div>
												<?php } ?>




											<?php } else { ?>
												<div class="listing-day">
													<div class="day"><?php echo ucfirst($weekday7); ?></div>
													<div class="time-items">
														<span class="time"><?php echo $language['lg_holiday']; ?></span>
													</div>
												</div>
											<?php } ?>






										</div>
									</div>
								</div>

								<!-- /Business Hours Widget -->

							</div>
						</div>
					</div>
					<!-- /Business Hours Content -->

				</div>
			</div>
		</div>
		<!-- /Doctor Details Tab -->

	</div>
</div>
<!-- /Page Content -->

<script type="text/javascript">
	var country = '';
	var state = '';
	var city = '';
	var specialization = '';
</script>
<?php $this->endSection(); ?>