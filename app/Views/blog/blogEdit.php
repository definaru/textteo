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
                <form method="post" action="#" id="edit_post" autocomplete="off">
                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $language['lg_edit_post']??""; ?></h4>
                            <div class="row form-row">
                                <input type="hidden" name="post_id" id="post_id" value="<?php echo $posts['id']; ?>">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_title1']??""; ?> <span class="text-danger">*</span></label>
                                        <input type="text" value="<?php echo libsodiumDecrypt($posts['title']); ?>" class="form-control" id="title" name="title" maxlength="100">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_slug_if_you_lea']??""; ?></label>
                                        <input type="text" value="<?php echo libsodiumDecrypt($posts['slug']); ?>" class="form-control urlfield" id="slug" name="slug" maxlength="100">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_summary__descri']??""; ?></label>
                                        <textarea class="form-control" rows="6" id="description" name="description"><?php echo libsodiumDecrypt($posts['description']); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_keywords_meta_t']??""; ?></label>
                                        <input type="text" value="<?php echo htmlspecialchars(libsodiumDecrypt($posts['keywords'])); ?>" class="form-control" id="keywords" name="keywords" maxlength="150">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_category']??""; ?> <span class="text-danger">*</span></label>
                                        <select class="form-control" name="category" id="category">
                                            <option value=""><?php echo $language['lg_select_category']??""; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_subcategory']??""; ?> <span class="text-danger">*</span></label>
                                        <select class="form-control" name="subcategory" id="subcategory">
                                            <option value=""><?php echo $language['lg_select_subcateg']??""; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                $tag = array();
                                if (!empty($tags)) {
                                    // print_r($tags);
                                    foreach ($tags as $rows) {
                                        $tag[] = $rows['tag'];
                                    }
                                }
                                ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="d-block"><?php echo $language['lg_tags']??""; ?></label>
                                        <input type="text" data-role="tagsinput" class="input-tags form-control" id="tags" value="<?php echo implode(',', $tag); ?>" name="tags">
                                        <small class="form-text text-muted"><?php echo $language['lg_note__type__pre1']??""; ?></small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_optional_url']??""; ?></label>
                                        <input type="text" value="<?php echo $posts['optional_url']; ?>" class="form-control" id="optional_url" name="optional_url" maxlength="250">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="upload-video"><?php echo $language['lg_upload_image_jp']??""; ?> <span class="text-danger">*</span></label>
                                        <!-- <input id="upload_image_url" value="<?php echo $posts['upload_image_url']; ?>" class="form-control bg-input" name="upload_image_url" type="hidden">
                                        <input id="upload_preview_image_url" value="<?php echo $posts['upload_preview_image_url']; ?>" class="form-control bg-input" name="upload_preview_image_url" type="hidden"> -->                                        
                                        <button type="button" class="button blog-img-upload" id="upload_image_btn"><?php echo $language['lg_upload3']??""; ?></button>
                                        <div class="form-group clearfix uploaded-section upload-wrap">

                                            <?php

                                            $image_url = explode(',', $posts['upload_image_url']);
                                            $preview_image_url = explode(',', $posts['upload_preview_image_url']);

                                            $img150=[];
                                            $img450=[];
                                            for ($i = 0; $i < count($image_url); $i++) {
        
                                                if(isset($image_url[$i]) && file_exists($image_url[$i]) && file_exists($preview_image_url[$i]))
                                                {
                                                        array_push($img150,$image_url[$i]);
                                                        array_push($img450,$preview_image_url[$i]);
            
                                                echo '<div id="remove_image_div_' . $i . '" class="upload-images">
													           <img src="' . base_url() . $image_url[$i] . '" alt="" height="42" width="42">
													           <a href="javascript:;" onclick="remove_images(\'' . $image_url[$i] . '\',\'' . $preview_image_url[$i] . '\',\'' . $i . '\')"  class="uploaded-remove btn btn-icon btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>
													           </div>';
                                                }
                                            } ?>

                                        </div>
                                        <label id="image-error" class="error" for="upload_image_url"></label>
                                        <input type="hidden" name="row_id" id="row_id" value="<?php echo ($i + 1); ?>" />

                                        <input id="upload_image_url" value="<?php echo implode(',',$img150); ?>" class="form-control bg-input" name="upload_image_url" type="hidden">
                                        <input id="upload_preview_image_url" value="<?php echo implode(',',$img450); ?>" class="form-control bg-input" name="upload_preview_image_url" type="hidden">
                                        
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $language['lg_content']??""; ?> <span class="text-danger">*</span></label>
                                        <textarea id="ck_editor_textarea_id" class="form-control" name="content" required><?php echo libsodiumDecrypt($posts['content']); ?></textarea>
                                        <?php echo display_ckeditor($ckeditor_editor1); ?>
                                        <label id="content-error" class="error" for="ck_editor_textarea_id"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Basic Information -->
                    <div class="submit-section submit-btn-bottom">
                        <button id="post_btn" class="btn btn-primary" type="submit"><?php echo $language['lg_post1']??""; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Page Content -->
<script type="text/javascript">
    var category = '<?php echo $posts['category']; ?>';
    var subcategory = '<?php echo $posts['subcategory']; ?>';
</script>
<?= $this->endSection(); ?>