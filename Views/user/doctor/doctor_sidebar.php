<?php
 $user_detail=user_detail($this->session->userdata('user_id'));
 // print_r($user_detail['hospital_id']);exit();
 $user_profile_image=(!empty($user_detail['profileimage']))?base_url().$user_detail['profileimage']:base_url().'assets/img/user.png';
?>
<div class="profile-sidebar">
	<div class="widget-profile pro-widget-content">
		<div class="profile-info-widget">
			<a href="#" class="booking-doc-img">
				<img src="<?php echo $user_profile_image;?>" class="avatar-view-img" alt="User Image">
			</a>
			<div class="profile-det-info">
				<h3><?php if($this->session->userdata('role')=='6'){ 
						 echo ucfirst($user_detail['clinicname'] == "" ? $user_detail['first_name']:$user_detail['clinicname']);
							}else{
						?>
					<?php 
                       /** @var array $language */
					echo $language['lg_dr'];?> <?php echo ucfirst($user_detail['first_name'].' '.$user_detail['last_name']);
						}

					?></h3>
				<?php if($this->session->userdata('role')!='6'){ ?>
				<div class="patient-details">
					<h5 class="mb-0"><?php echo ucfirst($user_detail['speciality']);?></h5>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="dashboard-widget">
		<nav class="dashboard-menu">
			<ul>
				<li <?php 
                      /** @var string $module */
                       /** @var string $page */

				echo ($module == 'doctor' && $page=='doctor_dashboard')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>dashboard">
						<i class="fas fa-columns"></i>
						<span><?php 
                         /** @var array $language */
						echo $language['lg_dashboard'];?></span>
					</a>
				</li>
				<li <?php echo ($module == 'doctor' && $page=='appoinments')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>appoinments">
						<i class="fas fa-calendar-check"></i>
						<span><?php echo $language['lg_appointments'];?></span>
					</a>
				</li>
				<li <?php echo ($module == 'doctor' && $page=='my_patients')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>my-patients">
						<i class="fas fa-user-injured"></i>
						<span><?php echo $language['lg_my_patients'];?></span>
					</a>
				</li>
				<li <?php echo ($module == 'doctor' && $page=='schedule_timings')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>schedule-timings">
						<i class="fas fa-hourglass-start"></i>
						<span><?php echo $language['lg_schedule_timing'];?></span>
					</a>
				</li>
				<li <?php echo ($module == 'calendar' && $page=='calendar')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>calendar">
						<i class="fas fa-calendar-check"></i>
						<span><?php echo $language['lg_calendar']; ?></span>
					</a>
				</li>

				<?php if($user_detail['hospital_id']=='' || $user_detail['hospital_id']==0){ ?>
				<li  <?php echo ($module == 'invoice')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>invoice">
						<i class="fas fa-file-invoice"></i>
						<span><?php echo $language['lg_invoice']; ?></span>
					</a>
				</li>
				<li  <?php echo ($module == 'doctor' && $page=='accounts')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>accounts">
						<i class="fas fa-file-invoice"></i>
						<span><?php echo $language['lg_accounts']; ?></span>
					</a>
				</li>
				<?php } ?>
				<?php if($this->session->userdata('role')=='6'){ ?>
						
				<li  <?php echo ($module == 'doctor'  && $page=='add_doctor')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>doctor">
						<i class="fas fa-file-invoice"></i>
						<span>Add doctor</span>
						
					</a>
				</li>
				<?php } ?>
				
				<li <?php echo ($module == 'doctor' && $page=='reviews')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>reviews">
						<i class="fas fa-star"></i>
						<span><?php echo $language['lg_reviews'];?></span>
					</a>
				</li> 
				<li <?php echo ($module == 'doctor' && $page=='messages')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>messages">
						<i class="fas fa-comments"></i>
						<span><?php echo $language['lg_messages'];?></span>
						<small class="unread-msg unread_msg_count">0</small>
					</a>
				</li>
				<li <?php echo ($module == 'doctor' && $page=='doctor_profile')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>profile">
						<i class="fas fa-user-cog"></i>
						<span><?php echo $language['lg_profile_setting'];?></span>
					</a>
				</li>
				<li <?php echo ($module == 'doctor' && $page=='social_media')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>social-media">
						<i class="fas fa-share-alt"></i>
						<span><?php echo $language['lg_social_media'];?></span>
						</a>
				</li>
				<li <?php echo ($module == 'doctor' && $page=='change_password')?'class="active"':'';?>>
					<a href="<?php echo base_url();?>change-password">
						<i class="fas fa-lock"></i>
						<span><?php echo $language['lg_change_password'];?></span>
					</a>
				</li>
	            <!-- <li>
					<a href="<?php //echo base_url();?>blog/post">
						<i class="fas fa-sign-out-alt"></i>
						<span><?php //echo $language['lg_blog2']; ?></span>
					</a>
				</li>-->
				<li>
					<a href="<?php echo base_url();?>sign-out">
						<i class="fas fa-sign-out-alt"></i>
						<span><?php echo $language['lg_signout'];?></span>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</div>