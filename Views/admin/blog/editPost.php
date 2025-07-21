<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Post</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Post</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-10">
                                <form method="post" id="edit_post" autocomplete="off" action="#">
                                    <input type="hidden" name="post_id" id="post_id" value="<?php echo $posts['id']; ?>">
                                    <div class="form-group">
                                        <label>Title <span class="text-danger">*</span></label>
                                        <input type="text" value="<?php echo libsodiumDecrypt($posts['title']); ?>" class="form-control" id="title" name="title">
                                    </div>
                                    <div class="form-group">
                                        <label>Slug (If you leave it blank, it will be generated automatically.)</label>
                                        <input type="text" value="<?php echo libsodiumDecrypt($posts['slug']); ?>" class="form-control" id="slug" name="slug">
                                    </div>
                                    <div class="form-group">
                                        <label>Summary & Description (Meta Tag)</label>
                                        <textarea class="form-control" rows="6" id="description" name="description"><?php echo libsodiumDecrypt($posts['description']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Keywords (Meta Tag)</label>
                                        <input type="text" value="<?php echo htmlspecialchars(libsodiumDecrypt($posts['keywords'])); ?>" class="form-control" id="keywords" name="keywords">
                                    </div>
                                    <div class="form-group">
                                        <label>Category <span class="text-danger">*</span></label>
                                        <select class="form-control" name="category" id="category">
                                            <option value="">Select Category</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Subcategory <span class="text-danger">*</span></label>
                                        <select class="form-control" name="subcategory" id="subcategory">
                                            <option value="">Select Subcategory</option>
                                        </select>
                                    </div>
                                    <?php
                                    $tag = array();
                                    if (!empty($tags)) {
                                        foreach ($tags as $rows) {
                                            $tag[] = $rows['tag'];
                                        }
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label>Tags</label>
                                        <input type="text" data-role="tagsinput" class="input-tags form-control" value="<?php echo implode(',', $tag); ?>" id="tags" name="tags">
                                        <small class="form-text text-muted">Note : Type & Press enter to add new tags</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Optional Url</label>
                                        <input type="text" value="<?php echo $posts['optional_url']; ?>" class="form-control" id="optional_url" name="optional_url">
                                    </div>
                                    <div class="form-group">
                                        <label for="upload-video">Upload Image (jpeg, png, jpg) Recomended size (680X454) <span class="text-danger">*</span></label>

                                        <input id="upload_image_url" value="<?php echo $posts['upload_image_url']; ?>" class="form-control bg-input" name="upload_image_url" type="hidden">
                                        <input id="upload_preview_image_url" value="<?php echo $posts['upload_preview_image_url']; ?>" class="form-control bg-input" name="upload_preview_image_url" type="hidden">
                                        <button type="button" class="button blog-img-upload" id="upload_image_btn">Upload</button>
                                        <div class="uploaded-section upload-wrap">
                                            <?php
                                            $image_url = explode(',', $posts['upload_image_url']);
                                            $preview_image_url = explode(',', $posts['upload_preview_image_url']);

                                            for ($i = 0; $i < count($image_url); $i++) {

                                                echo '<div id="remove_image_div_' . $i . '" class="upload-images">
                                                    <img src="' . base_url() . $image_url[$i] . '" alt="" height="42" width="42">
                                                    <a href="javascript:;" onclick="remove_images(\'' . $image_url[$i] . '\',\'' . $preview_image_url[$i] . '\',\'' . $i . '\')"  class="uploaded-remove btn btn-icon btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>
                                                    </div>';
                                            } ?>

                                        </div>
                                        <label id="image-error" class="error" for="upload_image_url"></label>
                                        <input type="hidden" name="row_id" id="row_id" value="<?php echo ($i + 1); ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Content <span class="text-danger">*</span></label>
                                        <textarea id="ck_editor_textarea_id" class="form-control" name="content" required><?php echo libsodiumDecrypt($posts['content']); ?></textarea>
                                        <?php
                                        /** @var string $ckeditor_editor1 */
                                        echo display_ckeditor($ckeditor_editor1); ?>
                                        <label id="content-error" class="error" for="ck_editor_textarea_id"></label>
                                    </div>

                                    <button id="post_btn" class="btn btn-primary" type="submit">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- /Page Wrapper -->

</div>


<div class="modal fade" id="avatar-image-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Image</h4>
                <span id="image_size"> Please Upload a Image of size above 680x454 </span>
            </div>

            <div class="modal-body">
                <div id="imageimg_loader" class="loader-wrap" style="display: none;">
                    <div class="loader">Loading...</div>
                </div>

                <div class="image-editor">

                    <input type="file" id="fileopen" name="file" class="cropit-image-input">
                    <span class="error_msg help-block" id="error_msg_model"></span>
                    <div class="cropit-preview"></div>
                    <div class="row resize-bottom">
                        <div class="col-md-4">
                            <div class="image-size-label">Resize Image</div>
                        </div>
                        <div class="col-md-4"><input type="range" class="custom cropit-image-zoom-input"></div>
                        <div class="col-md-4 text-right"><button class="btn btn-primary export">Done</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var category = '<?php echo $posts['category']; ?>';
    var subcategory = '<?php echo $posts['subcategory']; ?>';
</script>
<?php $this->endSection(); ?>