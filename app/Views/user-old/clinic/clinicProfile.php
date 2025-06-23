<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>
<?php     
    $profile=user_detail(session('user_id'));
?>
 <!-- Profile same for doctor and clinic -->
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url().session('module');?>/dashboard"><?php 
                        /** @var array $language */
                        echo $language['lg_dashboard']??"" ?? " ";?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_profile_setting']??"" ?? " ";?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title"><?php echo $language['lg_profile_setting']??"" ?? " ";?></h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">						
                <!-- Profile Sidebar -->
                <?= view('user/layout/sidebar'); ?>
                <!-- /Profile Sidebar -->							
            </div>
            <div class="col-md-7 col-lg-8 col-xl-9">

                <form method="post" action="javascript:void(0);" id="doctor_profile_form" autocomplete="off">

                    <input type="hidden" value="<?php echo date('d/m/Y', strtotime('-20 years')); ?>" id="maxDate">
                    <input type="hidden" value="<?php echo session('user_id'); ?>" id="user_id">
                    <input type="hidden" value="<?php echo session('role'); ?>" id="role_id">
                    <input type="hidden"  id="country_id" name="country_id" value="<?php echo $profile['country_id'];?>">

                    <?php                    
                    if($profile['profileimage']=="" || ($profile['profileimage']!="" && !is_file($profile['profileimage']))){
                        $user_profile_image=base_url().'assets/img/user.png';
                    }
                    else {
                        $user_profile_image=(!empty($profile['profileimage']??""))?base_url().$profile['profileimage']??"":base_url().'assets/img/user.png';
                    }
                    ?>
            
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php 
                            /** @var array $language */
                        echo $language['lg_basic_informati']??"";?></h4>
                        <div class="row form-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="change-avatar">
                                        <div class="profile-img">
                                            <img src="<?php echo $user_profile_image;?>" alt="User Image" class="avatar-view-img">
                                        </div>
                                        <div class="upload-img">
                                            <div class="change-photo-btn avatar-view-btn">
                                                <span><i class="fa fa-upload"></i> <?php echo $language['lg_upload_photo']??"";?></span>
                                                <input type="hidden" id="crop_prof_img" name="profile_image">
                                            </div>
                                            <small class="form-text text-muted"><?php echo $language['lg_allowed_jpg_gif']??"";?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php 
                            if(session('role')!=4 && session('role')!=5 && session('role')!=6)
                            { 
                            ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>

                                                <?php 
                                                if(session('role')!=6){ 
                                                    echo $language['lg_first_name']??"";
                                                }
                                                else
                                                {
                                                    echo $language['lg_first_name']??"";
                                                }
                                                ?>

                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" id="first_name" value="<?php echo libsodiumDecrypt($profile['first_name']);?>" class="form-control"  maxlength="100" onpaste="return false;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $language['lg_last_name']??"";?> <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" id="last_name" value="<?php echo libsodiumDecrypt($profile['last_name']);?>" class="form-control"  maxlength="100" onpaste="return false;">
                                        </div>
                                    </div>
                           <?php 
                            } 
                            ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_email']??"";?> <span class="text-danger">*</span></label>
                                    <input type="email"  value="<?php echo libsodiumDecrypt($profile['email']);?>" class="form-control" disabled onpaste="return false;">
                                </div>
                            </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_country_code']??""; ?><span class="text-danger">*</span></label>
                                    <select name="country_code" class="form-control select" id="country_code">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_mobile_number']??"";?> <span class="text-danger">*</span></label>
                                    <input type="text" name="mobileno" id="mobileno" value="<?php echo libsodiumDecrypt($profile['mobileno']);?>" class="form-control mobileNoOnly"  maxlength="15" onpaste="return false;">
                                </div>
                            </div>
                                <?php 
                            if(session('role')!=4 && session('role')!=5 && session('role')!=6)
                            { 
                                ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_select_gender']??"";?> <span class="text-danger">*</span></label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value=""><?php echo $language['lg_select']??"";?></option>
                                        <option value="Male" <?php echo ($profile['gender']=='Male')?'selected':'';?>><?php echo $language['lg_male']??"";?></option>
                                        <option value="Female" <?php echo ($profile['gender']=='Female')?'selected':'';?>><?php echo $language['lg_female']??"";?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group mb-0">
                            <label>
                                <?php echo $language['lg_date_of_birth'] ?? ""; ?> 
                                <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="dob" 
                                id="dob" 
                                value="<?php echo !empty($profile['dob']) ? date('d/m/Y', strtotime(str_replace('-', '/', $profile['dob']))) : ''; ?>" 
                                class="form-control" 
                                min="<?php echo date('Y-m-d', strtotime('-100 years')); ?>" 
                                max="<?php echo date('Y-m-d', strtotime('-21 years')); ?>" 
                                readonly
                            >
                        </div>
                            </div>
                                <?php 
                            } 
                                ?>
                        </div>
                    </div>
                </div>
                <!-- /Basic Information -->
                
                <!-- About Me -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $language['lg_about_me']??"";?></h4>
                        <div class="form-group mb-0">
                            <label><?php echo $language['lg_biography']??"";?></label>
                            <textarea class="form-control" name="biography" id="biography" rows="5"><?php echo libsodiumDecrypt($profile['biography']);?></textarea>
                        </div>
                    </div>
                </div>
                <!-- /About Me -->
                <?php 
                if($profile['hospital_id'] == 0 || session('role')=='6') 
                { 
                    ?>
                <!-- Clinic Info -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $language['lg_clinic_info']??"";?></h4>
                        <div class="row form-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo $language['lg_clinic_name']??"";?><span class="text-danger"><?php if(session('role')==6){ echo "*";} ?></span></label>
                                    <input type="text" value="<?php echo libsodiumDecrypt($profile['clinic_name']??"");?>" name="clinic_name" id="clinic_name" class="form-control"  maxlength="150" <?php if(session('role')==6){ echo"required";} ?>>
                                </div>
                            </div>  
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo $language['lg_clinic_images']??"";?>  [allowed types: .JPEG|.JPG|.PNG|.GIF Only]</label>
                                    <div action="<?php echo base_url(); ?>clinic/upload-clinicImag" class="dropzone" data-accepted-types=".jpg,.jpeg,.png"></div>                                    
                                </div>
                                <div class="upload-wrap">

                                    <?php 

                                    if(!empty($clinic_images)){
                                        $i=1;
                                        foreach ($clinic_images as $c) {
                                            echo '
                                        <div class="upload-images" id="clinic_'.$c['id'].'">
                                            <img src="'.base_url().$c['clinic_image'].'" alt="">
                                            <a href="javascript:void(0);" class="btn btn-icon btn-danger btn-sm" onclick="delete_clinic_image('.$c['id'].')"><i class="far fa-trash-alt"></i></a>
                                        </div>';
                                        }
                                    }

                                    ?>    
                                </div>
                            </div>
                        
                    <?php 
                        if(session('role')=='1')
                        {
                    ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_clinic_address1']??"";?></label></label>
                                    <input type="text" value="<?php echo libsodiumDecrypt($profile['clinic_address']);?>" name="clinic_address" id="clinic_address" class="form-control addressfield"  maxlength="150">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_clinic_address2']??"";?></label>
                                    <input type="text" value="<?php echo libsodiumDecrypt($profile['clinic_address2']);?>" name="clinic_address2" id="clinic_address2" class="form-control addressfield"  maxlength="150">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_clinic_city']??"";?></label></label>
                                    <input type="text" value="<?php echo libsodiumDecrypt($profile['clinic_city']);?>" name="clinic_city" id="clinic_city" class="form-control namefield"  maxlength="100">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_clinic_state']??"";?></label></label>
                                    <input type="text" value="<?php echo libsodiumDecrypt($profile['clinic_state']);?>" name="clinic_state" id="clinic_state" class="form-control namefield"  maxlength="100">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_clinic_country']??"";?></label></label>
                                    <input type="text" value="<?php echo libsodiumDecrypt($profile['clinic_country']);?>" name="clinic_country" id="clinic_country" class="form-control namefield"  maxlength="100">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_clinic_postal']??"";?></label>
                                    <input type="text" value="<?php echo libsodiumDecrypt($profile['clinic_postal']);?>" name="clinic_postal" id="clinic_postal" class="form-control numericOnly"  maxlength="10">
                                </div>
                            </div>
                    <?php 
                        }
                    ?>
                        </div>
                    </div>
                </div>
                <!-- /Clinic Info -->
                    <?php
                 } 
                    ?>
                <!-- Contact Details -->
                <div class="card contact-card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $language['lg_contact_details']??"";?></h4>
                        <div class="row form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $language['lg_address_line_1']??"";?> <span class="text-danger">*</span></label>
                                    <input type="text" name="address1" id="address1" value="<?php echo libsodiumDecrypt($profile['address1']);?>" class="form-control addressfield"  maxlength="150">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo $language['lg_address_line_2']??"";?> <span class="text-danger"></span></label>
                                    <input type="text" name="address2" id="address2" value="<?php echo libsodiumDecrypt($profile['address2']);?>" class="form-control addressfield"  maxlength="150">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo $language['lg_country']??"";?> <span class="text-danger">*</span></label>
                                    <select class="form-control" name="country" id="country">
                                        <option value=""><?php echo $language['lg_select_country']??"";?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo $language['lg_state__province']??"";?> <span class="text-danger">*</span></label>
                                    <select class="form-control" name="state" id="state">
                                        <option value=""><?php echo $language['lg_select_state']??"";?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo $language['lg_city']??"";?> <span class="text-danger">*</span></label>
                                    <select class="form-control" name="city" id="city">
                                        <option value=""><?php echo $language['lg_select_city']??"";?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?php echo $language['lg_postal_code']??"";?> <span class="text-danger">*</span></label>
                                    <input type="text" name="postal_code" id="postal_code" value="<?php echo libsodiumDecrypt($profile['postal_code']);?>" class="form-control numericOnly"  maxlength="10">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Contact Details -->
                    <?php 
                if($profile['hospital_id'] == 0 || session('role')=='6') 
                { 
                    ?>
                <!-- Pricing -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $language['lg_pricing']??"";?> <span class="text-danger">*</span></h4>
                        
                        <div class="form-group mb-0">
                            <div id="pricing_select">
                                <!--<div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="price_type" name="price_type" class="custom-control-input" value="Free" checked <?php echo ($profile['price_type']=='Free')?'checked':'';?>>
                                    <label class="custom-control-label" for="price_type"><?php //echo $language['lg_free']??"";?></label>
                                </div>-->
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="price_type1" name="price_type" value="Custom Price" class="custom-control-input" checked <?php echo ($profile['price_type']=='Custom Price')?'checked':'';?>>
                                    <label class="custom-control-label" for="price_type1"><?php echo $language['lg_custom_price1']??"";?> (<?php echo $language['lg_per_slot']??"";?>)</label>
                                </div>
                            </div>

                        </div>
                        
                        <div class="row custom_price_cont" id="custom_price_cont" style="display: <?php echo ($profile['price_type']=='Free' || empty($profile['price_type']))?'none':'block';?>;">
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="amount"  name="amount" value="<?php echo $profile['amount'];?>" placeholder="20">
                                <small class="form-text text-muted"><?php echo $language['lg_custom_price_yo']??"";?></small>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- /Pricing -->
                <?php } ?>
                <?php if(session('role')!='6'){ ?>
                <!-- Services and Specialization -->
                <div class="card services-card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $language['lg_services_and_sp']??"";?></h4>
                        <div class="form-group">
                            <label><?php echo $language['lg_services']??"";?> <span class="text-danger">*</span></label>
                            <input type="hidden" data-role="tagsinput" class="input-tags form-control inputtagcls" placeholder="Enter Services" name="services" value="<?php echo $profile['services'];?>" id="services" >
                            <small class="form-text text-muted"><?php echo $language['lg_note__type__pre']??"";?></small>
                            <small class="err_service text-danger"></small>
                        </div> 
                        <div class="form-group mb-0">
                            <label><?php echo $language['lg_specialization1']??"";?> <span class="text-danger">*</span></label>
                            <select class="form-control select" name="specialization" id="specialization">
                                        <option value=""><?php echo $language['lg_select_speciali']??"";?></option>
                            </select>
                            <input type="text" id="other_specialization" name="other_specialization" class="form-control mt-2" placeholder="Enter your specialization" style="display: none;" />
                        </div> 
                    </div>              
                </div>
                <?php } ?>
                
                <!-- /Services and Specialization -->
                <?php if(session('role')=='1'){ ?>
                <!-- Education -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $language['lg_education']??"";?></h4>
                        <div class="education-info">
                            <?php
                            
                            $i=1;
                            if(!empty($education)){
                                foreach ($education as $erows) {?> 
                            <div class="row form-row education-cont">
                                <div class="col-11 col-md-11 col-lg-11">
                                    <div class="row form-row">
                                        <div class="col-12 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_degree']??"" ?> <span class="text-danger">*</span></label>
                                                <input type="text" name="degree[]" value="<?php echo  $erows['degree'] ?>" class="form-control degree inputcls" maxlength="100">
                                            </div> 
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label><?php echo  $language['lg_collegeinstitut']??"" ?> <span class="text-danger">*</span></label>
                                                <input type="text" name="institute[]" value="<?php echo  $erows['institute'] ?>" class="form-control institute inputcls" maxlength="200">
                                            </div> 
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_year_of_complet']??"" ?> <span class="text-danger">*</span></label>
                                                <input type="text" name="year_of_completion[]" value="<?php echo $erows['year_of_completion']; ?>" readonly class="form-control years year_of_completion inputcls">
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                if($i!=1){ 
                                    ?>
                                <div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>
                                <?php }
                                echo'</div>';
                            $i++; } }
                            if($i==1){
                            ?>

                            <div class="row form-row education-cont">
                                <div class="col-12 col-md-10 col-lg-11">
                                    <div class="row form-row">
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_degree']??"";?> <span class="text-danger">*</span></label>
                                                <input type="text" name="degree[]" class="form-control degree inputcls" maxlength="100">
                                            </div> 
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_collegeinstitut']??"";?> <span class="text-danger">*</span></label>
                                                <input type="text" name="institute[]" class="form-control institute inputcls" maxlength="200">
                                            </div> 
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_year_of_complet']??"";?> <span class="text-danger">*</span></label>
                                                <input type="text" name="year_of_completion[]" readonly class="form-control years year_of_completion inputcls">
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                /** @var array $education */
                                    if(count($education)>=1){ 
                                echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
                                }
                                    ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="add-more">
                            <a href="javascript:void(0);" class="add-education"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_more']??"";?> </a>
                        </div>
                    </div>
                </div>
                <!-- /Education -->
                <?php } ?>

                <?php if(session('role')!='6'){ ?>
                <!-- Experience -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $language['lg_experience']??"";?></h4>
                        <div class="experience-info">
                            <?php
                            
                            if(!empty($experience)){
                                $j=1;
                            foreach ($experience as $exrows) {
                                 ?>
                            <div class="row form-row experience-cont">
                                <div class="col-12 col-md-10 col-lg-11">
                                    <div class="row form-row">
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label>
                                                    <?php echo $language['lg_hospital_name']??"" ?>
                                                    <!-- <span class="text-danger">*</span> -->
                                                </label>
                                                <input type="text" name="hospital_name[]" value="<?php echo $exrows['hospital_name'];?>" class="form-control" maxlength="100">
                                            </div> 
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_from']??"" ?></label>
                                                <input type="text" name="from[]" id="from" value="<?php echo $exrows['from'];?>" readonly class="form-control years">
                                            </div> 
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_to3']??"" ?></label>
                                                <input type="text" name="to[]" id="to" value="<?php echo $exrows['to'];?>" readonly class="form-control years">
                                            </div> 
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_designation']??"" ?>
                                                    <!-- <span class="text-danger">*</span> -->
                                                </label>
                                                <input type="text" name="designation[]" value="<?php echo $exrows['designation'];?>" class="form-control" maxlength="100">
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                if($j!=1){ 
                                 ?>
                                 <div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>
                                <?php 
                                } ?>
                            </div>
                                <?php $j++; } } ?>
                                <div class="row form-row experience-cont">
                                <div class="col-12 col-md-10 col-lg-11">
                                    <div class="row form-row">
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_hospital_name']??"";?>
                                                    <!-- <span class="text-danger">*</span></label> -->
                                                </label>
                                                <input type="text" name="hospital_name[]" class="form-control" maxlength="100">
                                            </div> 
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_from']??"";?></label>
                                                <input type="text" name="from[]" id="from" readonly class="form-control years">
                                            </div> 
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label><?php echo $language['lg_to3']??"";?></label>
                                                <input type="text" name="to[]" id="to" readonly class="form-control years">
                                            </div> 
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label>
                                                    <?php echo $language['lg_designation']??"";?>
                                                    <!-- <span class="text-danger">*</span></label> -->
                                                </label>
                                                <input type="text" name="designation[]" class="form-control" maxlength="100">
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <?php 

                                    /** @var array $experience */
                                    if(count($experience)>=1){ 
                                echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
                                }
                                    ?>
                                    <input type="hidden" id="experience_count" value="<?php echo count($experience);?>">
                            </div>
                        </div>
                        <div class="add-more">
                            <a href="javascript:void(0);" class="add-experience"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_more']??"";?></a>
                        </div>
                    </div>
                </div>
                <!-- /Experience -->
                
                <!-- Awards -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $language['lg_awards']??"";?></h4>
                        <div class="awards-info">
                            <?php
                            if(!empty($awards)){
                                $k=1;
                            foreach ($awards as $arows) {
                            ?> 
                            <div class="row form-row awards-cont">
                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_awards']??""; ?>
                                            <!-- <span class="text-danger">*</span></label> -->
                                        </label>
                                        <input type="text" name="awards[]" value="<?php echo $arows['awards']; ?>" class="form-control" maxlength="100">
                                    </div> 
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_year']??""; ?></label>
                                        <input type="text" name="awards_year[]" value="<?php echo $arows['awards_year']; ?>" readonly class="form-control years">
                                    </div> 
                                </div>
                                <?php if($k!=1){ ?>
                                <div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>
                                <?php } ?>
                                </div>
                                <?php $k++; } } ?>
                            <div class="row form-row awards-cont">
                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_awards']??"";?>
                                            <!-- <span class="text-danger">*</span></label> -->
                                        </label>
                                        <input type="text" name="awards[]" class="form-control" maxlength="100">
                                    </div> 
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_year']??"";?></label>
                                        <input type="text" name="awards_year[]" readonly class="form-control years">
                                    </div> 
                                </div>
                                <?php 
                                /** @var array $awards */
                                    if(count($awards)>=1){ 
                                echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
                                }
                                    ?>
                            </div>
                        </div>
                        <div class="add-more">
                            <a href="javascript:void(0);" class="add-award"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_more']??"";?></a>
                        </div>
                    </div>
                </div>
                <!-- /Awards -->
                
                <!-- Memberships -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $language['lg_memberships']??"";?></h4>
                        <div class="membership-info">
                            <?php
                            
                            if(!empty($memberships)){
                                $l=1;
                            foreach ($memberships as $mrows) {
                            ?>
                            <div class="row form-row membership-cont">
                                <div class="col-12 col-md-10 col-lg-5">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_memberships']??""; ?>
                                            <!-- <span class="text-danger">*</span></label> -->
                                        </label>
                                        <input type="text" name="memberships[]" value="<?php echo $mrows['memberships']; ?>" class="form-control" maxlength="100">
                                    </div> 
                                </div>
                                <?php if($l!=1){  ?>
                                <div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>
                                <?php }
                                echo'</div>';
                                $l++; } } ?>
                            <div class="row form-row membership-cont">
                                <div class="col-12 col-md-10 col-lg-5">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_memberships']??"";?>
                                            <!-- <span class="text-danger">*</span></label> -->
                                        </label>
                                        <input type="text" name="memberships[]" class="form-control" maxlength="100">
                                    </div> 
                                </div>
                                <?php 
                                /** @var array $memberships*/
                                    if(count($memberships)>=1){ 
                                echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
                                }
                                    ?>
                            </div>
                        </div>
                        <div class="add-more">
                            <a href="javascript:void(0);" class="add-membership"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_more']??"";?> </a>
                        </div>
                    </div>
                </div>
                <!-- /Memberships -->
                
                <!-- Registrations -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $language['lg_registrations']??"";?></h4>
                        <div class="registrations-info">
                            <?php
                            /** @var array $registrations */
                            if(!empty($registrations)){
                                $m=1;
                            foreach ($registrations as $rrows) {
                                ?>
                            <div class="row form-row reg-cont">
                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_registrations']??"";?>
                                            <!-- <span class="text-danger">*</span></label> -->
                                        </label>
                                        <input type="text" name="registrations[]" value="<?php echo $rrows['registrations']; ?>" class="form-control" maxlength="100">
                                    </div> 
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_year']??"";?></label>
                                        <input type="text" readonly name="registrations_year[]" value="<?php echo $rrows['registrations_year']; ?>" class="form-control years">
                                    </div> 
                                </div>
                                <?php if($m!=1){ 
                                echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
                                }
                                echo'</div>';
                                } } ?>
                            <div class="row form-row reg-cont">
                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_registrations']??"";?>
                                            <!-- <span class="text-danger">*</span></label> -->
                                        </label>
                                        <input type="text" name="registrations[]" class="form-control" maxlength="100">
                                    </div> 
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_year']??"";?></label>
                                        <input type="text" readonly name="registrations_year[]" class="form-control years">
                                    </div> 
                                </div>
                                <?php 
                                    if(count($registrations)>=1){ 
                                echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
                                }
                                    ?>
                            </div>
                        </div>
                        <div class="add-more">
                            <a href="javascript:void(0);" class="add-reg"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_more']??"";?> </a>
                        </div>
                    </div>
                </div>
                <!-- /Registrations -->
                <?php } ?>
                
                <div class="submit-section submit-btn-bottom">
                    <button type="submit" id="save_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_save_changes']??"";?></button>
                </div>
                </form>
            </div>
        </div>

    </div>

</div>		
			<!-- /Page Content -->
            
			<script type="text/javascript">
				var country='<?php echo $profile['country'];?>';
			    var state='<?php echo $profile['state'];?>';
			    var city='<?php echo $profile['city'];?>';
			    var specialization='<?php echo $profile['specialization'];?>';
			    var country_code='<?php echo $profile['country_code'];?>';
			</script>

<?php $this->endSection(); ?>