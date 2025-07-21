<?= $this->extend('user/layout/header'); ?>
<?= $this->section('content'); ?>
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

                <form method="post" action="#" id="add_post" autocomplete="off">



                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $language['lg_add_post'] ?? ""; ?></h4>
                            <div class="row form-row">

                                <input type="hidden" name="post_by" value="Doctor">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_title1'] ?? ""; ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title" maxlength="100">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_slug_if_you_lea'] ?? ""; ?></label>
                                        <input type="text" class="form-control urlfield" id="slug" name="slug" maxlength="100" placeholder="Ex: blog-url-name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_summary__descri'] ?? ""; ?></label>
                                        <textarea class="form-control" rows="6" id="description" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_keywords_meta_t'] ?? ""; ?></label>
                                        <input type="text" class="form-control" id="keywords" name="keywords" maxlength="150">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_category'] ?? ""; ?> <span class="text-danger">*</span></label>
                                        <select class="form-control" name="category" id="category">
                                            <option value=""><?php echo $language['lg_select_category'] ?? ""; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_subcategory'] ?? ""; ?> <span class="text-danger">*</span></label>
                                        <select class="form-control" name="subcategory" id="subcategory">
                                            <option value=""><?php echo $language['lg_select_subcateg'] ?? ""; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_tags'] ?? ""; ?></label>
                                        <input type="text" data-role="tagsinput" class="input-tags form-control" id="tags" name="tags">
                                        <small class="form-text text-muted"><?php echo $language['lg_note__type__pre1'] ?? ""; ?></small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_optional_url'] ?? ""; ?></label>
                                        <input type="text" class="form-control" id="optional_url" name="optional_url" maxlength="250">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="upload-video"><?php echo $language['lg_upload_image_jp'] ?? ""; ?> <span class="text-danger">*</span></label>
                                        <input id="upload_image_url" class="form-control bg-input" name="upload_image_url" type="hidden">
                                        <input id="upload_preview_image_url" class="form-control bg-input" name="upload_preview_image_url" type="hidden">
                                        <button type="button" class="button blog-img-upload" id="upload_image_btn"><?php echo $language['lg_upload3'] ?? ""; ?></button>
                                        <div class="uploaded-section upload-wrap"> </div>
                                        <label id="image-error" class="error" for="upload_image_url"></label>
                                        <input type="hidden" name="row_id" id="row_id" value="1" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_content'] ?? ""; ?> <span class="text-danger">*</span></label>
                                        <textarea id="ck_editor_textarea_id" class="form-control" name="content" required></textarea>
                                        <?php  echo display_ckeditor($ckeditor_editor1); ?>
                                        <label id="content-error" class="error" for="ck_editor_textarea_id"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Basic Information -->







                    <div class="submit-section submit-btn-bottom">
                        <button id="post_btn" class="btn btn-primary" type="submit"><?php echo $language['lg_post1'] ?? ""; ?></button>
                        <button type="button" id="post_btn" class="btn btn-secondary" onclick="location.href='<?php echo base_url(); ?>doctor/active-blog';"><?php echo $language['lg_cancel'] ?? ""; ?></button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
<!-- /Page Content -->
<script type="text/javascript">
    var category = '';
    var subcategory = '';
</script>

<?= $this->endSection(); ?>