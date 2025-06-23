<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<!-- Breadcrumb -->
<?php 

$time_zone = session('time_zone');
  date_default_timezone_set($time_zone);

?>
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php 
									/** @var array $language */
									echo $language['lg_home']??""; ?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_booking2']??""; ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_booking2']??""; ?></h2>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			<!-- Page Content -->
			<div class="content">
				<div class="container">
				
					<div class="row">
						<div class="col-12">
						
							<div class="card">
								<div class="card-body">
									<div class="booking-doc-info">
										<?php
											/** @var array $doctors */
											$profileimage=(!empty($doctors['profileimage']))&&file_exists($doctors['profileimage'])?base_url().$doctors['profileimage']:base_url().'assets/img/user.png';


										?>
										<a href="<?php //echo base_url().'doctor-preview/'.$doctors['username'];?>" class="booking-doc-img">
											<img src="<?php echo $profileimage;?>" alt="User Image">
										</a>
										<div class="booking-info">
											<h4><a href="<?php //echo base_url().'doctor-preview/'.$doctors['username'];?>"> <?=($doctors['role']!=6)?'':''?> <?php echo ucfirst(libsodiumDecrypt($doctors['first_name']).' '.libsodiumDecrypt($doctors['last_name']));?></a></h4>
											<div class="rating">
												<?php
						                        $rating_value=$doctors['rating_value'];
						                        for( $i=1; $i<=5 ; $i++) {
						                          if($i <= $rating_value){                                        
						                          echo'<i class="fas fa-star filled"></i>';
						                          }else { 
						                          echo'<i class="fas fa-star"></i>';
						                          } 
						                        } 
						                      ?>
												<span class="d-inline-block average-rating">(<?php echo $doctors['rating_count'];?>)</span>
											</div>
											<p class="text-muted mb-0"><i class="fas fa-map-marker-alt"></i> <?php //echo $doctors['cityname'].', '. $doctors['countryname'];?></p>
										</div>
									</div>
								</div>
							</div>
							
							<!-- Schedule Widget -->
							<div class="card booking-schedule schedule-widget">
							
								<!-- Schedule Header -->
								<div class="schedule-header border-0">
									<div class="row text-center">
										<!-- <div class="booking-option">
									<label class="payment-radio credit-card-option">
										<input type="radio" name="type" value="online" id="online">
										<span class="checkmark"></span>
										<?php echo $language['lg_online']??"";?>
									</label>
									<label class="payment-radio credit-card-option">
										<input type="radio" name="type" value="clinic" id="clinic">
										<span class="checkmark"></span>
										<?php echo $language['lg_clinic']??"";?>
									</label>
									<label class="payment-radio credit-card-option">
										<input type="radio" name="type" value="both" id="both" checked="checked">
										<span class="checkmark"></span>
										<?php echo $language['lg_both']??"";?>
									</label>
								</div> -->
										<div class="col-md-12">
											<input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctors['userid'];?>">
											<input type="hidden" name="price_type" id="price_type" value="<?php echo $doctors['price_type'] ?>">
                                            <input type="hidden" name="hourly_rate" id="hourly_rate" value="<?php echo $doctors['amount'] ?>">
                                            <input type="hidden" name="role_id" id="role_id" value="<?php echo $doctors['role'] ?>">
											<div class="row">
                 <!-- Pet update code
    added new on 13rd June 2024 by Muddasar       -->   
										  <div class="col-sm-4 col-12 avail-time">
	                 <!-- Pet update code end
    added new on 13rd June 2024 by Muddasar       -->   									      
											
											<div class="input-group mb-3">
												<div class="input-group-prepend">
													<span class="input-group-text"><?php echo $language['lg_date1']??""; ?>:</span>
												</div>
												<input type="text" class="form-control" name="schedule_date" id="schedule_date" readonly="" value="<?php 
                      /** @var string $schedule_date */
												echo $schedule_date;?>" min="<?php echo date("Y-m-d"); ?>" onchange="getSchedule('');">
												<!-- <div class="input-group-append">
													<input type="button" name="submit" value="Search" class="btn btn-primary" onclick="getSchedule();">
												</div> -->
											</div>
											<div id="schedule_date_error"></div>
										 </div>
                                            
                 <!-- Pet update code
    added new on 13rd June 2024 by Muddasar       -->                            
                                          <div class="col-sm-4 col-12">
                                              
                                              <div class="form-group">
                                                  <select class="select form-control" id="booking_pet_id" name="booking_pet_id">
                                                      <option value="">Select Pet</option>
                                                      <?php if(!empty($user_pets)){
                                                            foreach($user_pets as $pet_data){?>
                                                        <option value="<?=$pet_data['id']?>"><?=$pet_data['pet_name']?></option>
                                                      <?php 
                                                        }
                                                      }
                                                      ?>
                                                  </select>
                                              </div>
                                              
                                                
                                          </div>
                 <!-- Pet update code end
    added new on 13rd June 2024 by Muddasar       -->                                                   
										  <div class="col-sm-4 col-12 schedule-back text-right">           
											<a href="javascript:void(0);" class="btn btn-primary" onclick="history.back();"><i class="fas fa-chevron-left"></i> <?php echo $language['lg_back']??""; ?></a>
										  </div>
										</div>
											<div class="card border-0 mb-0">
												<div class="card-body pb-0 bookings-schedule">
													
													
												</div>
											</div>
											
										</div>
									</div>
								</div>
								<!-- /Schedule Header -->
								
								<!-- Schedule Content -->
								<div class="schedule-cont">
									<div class="row">
										<div class="col-md-12">
										
											
										</div>
									</div>
								</div>
								<!-- /Schedule Content -->
								
							</div>
							<!-- /Schedule Widget -->
							
							<!-- Submit Section -->
							<div class="submit-section proceed-btn text-right">
								<a id="pay_btn" href="javascript:void(0);" onclick="checkout()" class="btn btn-primary submit-btn"><?php echo $language['lg_proceed_to_book']??""; ?></a>
							</div>
							<!-- /Submit Section -->
							
						</div>
					</div>
				</div>

			</div>		
			<!-- /Page Content -->
			
<?php $this->endSection(); ?>