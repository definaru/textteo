<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
  <div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <h3 class="page-title">CMS</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">CMS</li>
          </ul>
        </div>
      </div>
    </div>
    <!-- /Page Header -->

    <div class="row">

      <div class="col-12">

        <!-- General -->


        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Banner</h4>
          </div>
          <div class="card-body">
           
            <form action="<?=base_url('admin/cms_submit')?>" method="POST" autocomplete="off" enctype="multipart/form-data">
              <div class="settings-tabs">
                <ul class="nav nav-tabs nav-tabs-solid">
                  <li class="nav-item">
                    <a class="nav-link active" href="#banner" data-toggle="tab">Banner</a>
                  </li>
                  <!-- <li class="nav-item">
                    <a class="nav-link" href="#specialities" data-toggle="tab"><span>Specialities Content</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#doctor_book" data-toggle="tab"><span>Book Our Doctor Content</span></a>
                  </li> -->
                  <li class="nav-item">
                    <a class="nav-link" href="#feature_image" data-toggle="tab"><span>Availabe Features Image</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#login_image" data-toggle="tab"><span>Login Image</span></a>
                  </li>
                </ul>
              </div>

              <div class="row">
                <div class="col-lg-9">
                  <div class="tab-content">

                     

                   <div class="tab-pane show active" id="banner">
                   
                      <!-- <div class="form-group">
                        <label>Title</label>
                       <input type="text" id="banner_title" required name="banner_title" class="form-control" value="<?php if (isset($banner_title)) echo $banner_title;?>">
                       <span class="title_error"></span>
                      </div>
                      <div class="form-group">
                        <label>Sub Title</label>
                       <input type="text" id="banner_sub_title" required name="banner_sub_title" class="form-control" value="<?php if (isset($banner_sub_title)) echo $banner_sub_title;?>">
                       <span class="title_error"></span>
                      </div> -->
                      <div class="form-group">
                        <label>Banner Image</label>
                        <div class="uploader"><input type="file" id="banners"  class="form-control" name="banner_image" placeholder="Select file"></div>
                        <p class="form-text text-muted small mb-0">Recommended image size is <b>1600 x 210</b></p>  <span class="banner_error"></span>
                         <?php if (!empty($banner_image)){ ?><img src="<?php echo !empty(base_url() . $banner_image)&&file_exists($banner_image)?base_url() . $banner_image: '';?>" class="site-logo" style="width: 120px;"><?php } ?>
                        <div id="img_upload_error" class="text-danger"  style="display:none"><b>Please upload valid image file.</b></div>
                      </div>
                  </div>

                  <div class="tab-pane show" id="specialities">
                    
                      <div class="form-group">
                        <label>Title</label>
                       <input type="text" id="specialities_title" required name="specialities_title" class="form-control" value="<?php if (isset($specialities_title)) echo $specialities_title;?>">
                       <span class="title_error"></span>
                      </div>
                      <div class="form-group">
                        <label>Content</label>
                       <textarea  rows="4" cols="50" class="form-control" required name="specialities_content" id="specialities_content"><?php if (isset($specialities_content)) echo $specialities_content;?></textarea>
                       <span class="content_error"></span>
                      </div>
                      
                  </div>
                  <div class="tab-pane show" id="doctor_book">
                   
                      <div class="form-group">
                        <label>Title</label>
                       <input type="text" id="doctor_title" name="doctor_title" required class="form-control" value="<?php if (isset($doctor_title)) echo $doctor_title;?>">
                       <span class="doctor_title_error"></span>
                      </div>
                      <div class="form-group">
                        <label>Content</label>
                       <textarea  rows="4" cols="50" class="form-control" required name="doctor_content" id="doctor_content"><?php if (isset($doctor_content)) echo $doctor_content;?></textarea>
                       <span class="doctor_content_error"></span>
                      </div>
                     
                  </div>

                  <div class="tab-pane show" id="feature_image">

                      <!-- <div class="form-group">
                        <label>Title</label>
                       <input type="text" id="feature_title" name="feature_title" required class="form-control" value="<?php if (isset($feature_title)) echo $feature_title;?>">
                       <span class="title_error"></span>
                      </div>
                      <div class="form-group">
                        <label>Sub Title</label>
                       <input type="text" id="feature_sub_title" name="feature_sub_title" required class="form-control" value="<?php if (isset($feature_sub_title)) echo $feature_sub_title;?>">
                       <span class="title_error"></span>
                      </div> -->
                      <div class="form-group">
                        <label>Availabe Features Image</label>
                        <div class="uploader"><input type="file" id="feature_image"  class="form-control" name="feature_image" placeholder="Select file"></div>
                        <p class="form-text text-muted small mb-0">Recommended image size is <b>421 x 376</b></p>  <span class="banner_error"></span>
                         <?php if (!empty($feature_image)){ ?><img src="<?php echo !empty(base_url() . $feature_image)&&file_exists($feature_image)?base_url() . $feature_image: '';?>" class="site-logo" style="width: 120px;"><?php } ?>
                        <div id="img_upload_error" class="text-danger"  style="display:none"><b>Please upload valid image file.</b></div>
                      </div>
                   
                  </div>

                   <div class="tab-pane show" id="login_image">
                   
                      <div class="form-group">
                        <label>Login Image</label>
                        <div class="uploader"><input type="file" id="login_image"  class="form-control" name="login_image" placeholder="Select file"></div>
                        <p class="form-text text-muted small mb-0">Recommended image size is <b>1000 x 650</b></p>  <span class="banner_error"></span>
                         <?php if (!empty($login_image)){ ?><img src="<?php echo !empty(base_url() . $login_image)&&file_exists($login_image)?base_url() . $login_image: '';?>" class="site-logo" style="width: 120px;"><?php } ?>
                        <div id="img_upload_error" class="text-danger"  style="display:none"><b>Please upload valid image file.</b></div>
                      </div>
                     
                  </div>

                   <button id="login_image_submit" type="submit" name="form_submit" value="true" class="btn btn-primary center-block">Save Changes</button>
                 
                </div>



              </div>
            </div>
               </form>

         
        </div>
      </div>

      <!-- /General -->

    </div>
  </div>

</div>      
</div>
<!-- /Page Wrapper -->

</div>
<?php $this->endSection(); ?>

