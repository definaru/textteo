<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $language['lg_home'] ?? ""; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo ($role == 6) ? $language['lg_clinic_search'] ?? "" : $language['lg_doctor_search'] ?? ""; ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title search-results"></h2>
            </div>
            <div class="col-md-4 col-12 d-md-block d-none">
                <div class="sort-by">
                    <span class="sort-title"><?php echo $language['lg_sort_by'] ?? ""; ?></span>
                    <span class="sortby-fliter">
                        <select class="select form-control" id="orderby" onchange="search_doctor(0)">
                            <option value=""><?php echo $language['lg_select'] ?? ""; ?></option>
                            <option class="sorting" value="Rating"><?php echo $language['lg_rating'] ?? ""; ?></option>

                            <option class="sorting" value="Latest"><?php echo $language['lg_latest'] ?? ""; ?></option>
                            <option class="sorting" value="Free"><?php echo $language['lg_free'] ?? ""; ?></option>
                        </select>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12 col-lg-4 col-xl-3 theiaStickySidebar">

                <!-- Search Filter -->
                <!-- 	<div class="card search-filter">
								<div class="card-header">
									<h4 class="card-title mb-0"><?php echo $language['lg_search_filter'] ?? ""; ?> <a href="javascript:void(0);" onclick="reset_doctor()" class="text-danger text-sm float-right"><?php echo $language['lg_reset'] ?? ""; ?></a></h4>
								</div>
								<div class="card-body">
									<form id="search_doctor_form">

								<div class="filter-widget">
									<input type="text" class="form-control filter-form ft-search"  name="keywords"  type="text" placeholder="<?php echo $language['lg_keywords'] ?? ""; ?>" value="<?php echo !empty($keywords) ? $keywords : ''; ?>" autocomplete="off" id="keywords">
								</div>
								
								<div class="filter-widget">
									<select class="form-control " name="appointment_type" id="appointment_type">
										<option value=""> <?php echo $language['lg_booking_type'] ?? ""; ?></option>
										<option value="online"><?php echo $language['lg_online'] ?? ""; ?></option>
										<option value="clinic"><?php echo $language['lg_clinic'] ?? ""; ?></option>
									</select>					
						       </div>
								<div class="filter-widget">
									<select class="form-control " name="gender" id="gender" >
										<option value=""> <?php echo $language['lg_select_gender'] ?? ""; ?></option>
										<option value="Male"><?php echo $language['lg_male'] ?? ""; ?></option>
										<option value="Female"><?php echo $language['lg_female'] ?? ""; ?></option>
									</select>							
								</div>

								<div class="filter-widget">
									<select name="specialization" class="form-control " id="specialization">
				                        <option value=""><?php echo $language['lg_select_speciali'] ?? ""; ?></option>
				                     </select>
								</div>
								<div class="filter-widget">
									<select name="country" class="form-control " id="country">
				                        <option value=""><?php echo $language['lg_select_country'] ?? ""; ?></option>
				                     </select>
		                       </div>
								<div class="filter-widget">
									<select name="state" class="form-control " id="state">
			                        <option value=""><?php echo $language['lg_select_state'] ?? ""; ?></option>
			                        </select>
		                       </div>
		                       <div class="filter-widget">
									<select name="city" class="form-control " id="city">
			                        <option value=""><?php echo $language['lg_select_city'] ?? ""; ?></option>
			                        </select>
		                       </div>
									<div class="btn-search">
										<button type="button" onclick="search_doctor(0)" class="btn btn-block"><?php echo $language['lg_search3'] ?? ""; ?></button>
									</div>
									</form>	
								</div>
							</div> -->
                <!-- /Search Filter -->

                <!-- Search Filter -->
                <div class="card search-filter">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><?php echo $language['lg_search_filter'] ?? ""; ?> <a href="javascript:void(0);" onclick="<?php echo ($role == 6 ? 'reset_clinic()' : 'reset_doctor()') ?>" class="text-danger text-sm float-right"><?php echo $language['lg_reset'] ?? ""; ?></a></h4>
                    </div>
                    <div class="card-body">
                        <form id="search_doctor_form">
                            <input type="hidden" class="form-control filter-form ft-search" name="role" value="<?php echo !empty($role) ? $role : ''; ?>" autocomplete="off" id="role">

                            <input type="hidden" class="form-control filter-form ft-search" name="type" value="<?php echo !empty($role) ? $role : ''; ?>" autocomplete="off" id="type">

                            <input type="hidden" class="form-control filter-form ft-search" name="login_role" value="<?php echo !empty($login_role) ? $login_role : ''; ?>" autocomplete="off" id="login_role">

                            <div class="filter-widget">

                                <input type="text" class="form-control filter-form ft-search" name="keywords" type="text" placeholder="<?php echo $language['lg_keywords'] ?? ""; ?>" value="<?php echo !empty($keywords) ? $keywords : ''; ?>" autocomplete="off" id="keywords">
                            </div>

                            <?php if ($role != 6) { ?>



                                <!--<div class="filter-widget">
                                    <h4><?php //echo $language['lg_gender'] ?? ""; ?></h4>
                                    <div>
                                        <label class="custom_check">
                                            <input type="checkbox" class="gender" name="gender[]" value="Male">
                                            <span class="checkmark"></span> <?php //echo $language['lg_male_doctor'] ?? ""; ?>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="custom_check">
                                            <input type="checkbox" class="gender" name="gender[]" value="Female">
                                            <span class="checkmark"></span> <?php //echo $language['lg_female_doctor'] ?? ""; ?>
                                        </label>
                                    </div>
                                </div>-->
                                <div class="filter-widget">
                                    <h4><?php echo $language['lg_select_speciali'] ?? ""; ?></h4>
                                    <?php
                                    /** @var array $specialization  */
                                    foreach ($specialization as $rows) {

                                    ?>
                                        <div>
                                            <label class="custom_check">
                                                <input type="checkbox" class="specialization" name="specialization[]" id="specialization_<?php echo $rows['id']; ?>" value="<?php echo $rows['id']; ?>">
                                                <span class="checkmark"></span> <?php echo libsodiumDecrypt($rows['specialization']); ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="filter-widget">
                                <select name="country" class="form-control " id="country" disabled>
                                    <option value=""><?php echo $language['lg_select_country'] ?? ""; ?></option>
                                </select>
                            </div>
                            <div class="filter-widget">
                                <select name="state" class="form-control " id="state">
                                    <option value=""><?php echo $language['lg_select_state'] ?? ""; ?></option>
                                </select>
                            </div>
                            <!--<div class="filter-widget">
                                <select name="city" class="form-control " id="city">
                                    <option value=""><?php //echo $language['lg_select_city'] ?? ""; ?></option>
                                </select>
                            </div>-->
                            <div class="btn-search">
                                <button type="button" onclick="search_doctor(0)" class="btn btn-block"><?php echo $language['lg_search3'] ?? ""; ?></button>
                            </div>
                    </div>
                </div>
                <!-- /Search Filter -->

            </div>

            <div class="col-md-12 col-lg-8 col-xl-9">
                <input type="hidden" name="page" id="page_no_hidden" value="1">
                <div id="doctor-list"></div>


                <div class="spinner-border text-success text-center" role="status" id="loading" style="text-align: center;margin: 60px 45%;"></div>
                <div class="load-more text-center d-none" id="load_more_btn_doctor">
                    <a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more'] ?? ""; ?></a>
                </div>

                <!-- Doctor Widget -->

                <!-- /Doctor Widget -->


            </div>
        </div>

    </div>

</div>
<!-- /Page Content -->

<script type="text/javascript">
    var country = '';
    var country_code = '';
    var state = '';
    var city = '';
    var citys = '<?php if (isset($_GET['location'])) {
                        echo ($_GET['location']);
                    } ?>';
    var specialization = '';
</script>

<?php $this->endSection(); ?>