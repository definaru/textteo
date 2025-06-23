<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo base_url().session('module');?>"><?php echo $language['lg_dashboard']??""; ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_reviews']??""; ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title"><?php echo $language['lg_reviews']??""; ?></h2>
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
                <?php echo view('user/layout/sidebar'); ?>
                <!-- /Profile Sidebar -->

            </div>
            <div class="col-md-7 col-lg-8 col-xl-9">
                <div class="doc-review review-listing">

                    <!-- Review Listing -->
                    <ul class="comments-list">

                        <?php
                        
                        if (!empty($reviews)) {
                            foreach ($reviews as $rrows) {

                                $rimg = base_url().'assets/img/user.png';
                                $drimg = base_url().'assets/img/user.png';
                                if (isset($rrows['profileimage']) && file_exists($rrows['profileimage'])) {
                                    $rimg = base_url().$rrows['profileimage'];
                                }
                                if (isset($rrows['doctor_image']) && file_exists($rrows['doctor_image'])) {
                                    $drimg = base_url().$rrows['doctor_image'];
                                }

                                // $rimg = (!empty($rrows['profileimage'])) ? base_url() . $rrows['profileimage'] : base_url() . 'assets/img/user.png';

                                // $drimg = (!empty($rrows['doctor_image'])) ? base_url() . $rrows['doctor_image'] : base_url() . 'assets/img/user.png';
                        ?>

                                <!-- Comment List -->
                                <li>
                                    <div class="comment">
                                        <img class="avatar rounded-circle" alt="User Image" src="<?php echo $rimg; ?>">
                                        <div class="comment-body">
                                            <div class="meta-data">
                                                <span class="comment-author"><?php echo libsodiumDecrypt($rrows['first_name']) . ' ' . libsodiumDecrypt($rrows['last_name']); ?></span>
                                                <span class="comment-date"><?php echo $language['lg_reviewed']??""; ?> <?php echo time_elapsed_string($rrows['created_date']); ?></span>
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
                                            <?php if ($rrows['reply_id'] == '') { ?>
                                                <div class="comment-reply" id="reply_button_<?php echo $rrows['id']; ?>">
                                                    <a class="comment-btn" href="#" onclick="add_reply('<?php echo $rrows['id']; ?>');">
                                                        <i class="fas fa-reply"></i> <?php echo $language['lg_reply']??""; ?>
                                                    </a>

                                                </div>
                                            <?php } ?>

                                        </div>


                                    </div>
                                    <?php if ($rrows['reply_id'] != '') { ?>
                                        <!-- Comment Reply -->
                                        <ul class="comments-reply">

                                            <!-- Comment Reply List -->
                                            <li>
                                                <div class="comment">
                                                    <img class="avatar rounded-circle" alt="User Image" src="<?php echo $drimg; ?>">
                                                    <div class="comment-body">
                                                        <div class="meta-data">
                                                            <span class="comment-author"><?php echo $language['lg_dr']??""; ?> <?php echo libsodiumDecrypt($rrows['doctor_firstname']) . ' ' . libsodiumDecrypt($rrows['doctor_lastname']); ?></span>
                                                            <span class="comment-date"><?php echo $language['lg_replied']??""; ?> <?php echo time_elapsed_string($rrows['reply_date']); ?> </span>
                                                        </div>
                                                        <p class="comment-content">
                                                            <?php echo $rrows['reply']; ?>
                                                        </p>
                                                        <a class="comment-btn text-danger" onclick="return delete_reply('<?php echo $rrows['reply_id']; ?>');" href="javascript:void(0);">
                                                            <i class="fas fa-trash"></i> <?php echo $language['lg_delete']??""; ?>
                                                        </a>
                                                    </div>

                                                </div>

                                            </li>
                                            <!-- /Comment Reply List -->

                                        </ul>
                                        <!-- /Comment Reply -->
                                    <?php } else { ?>
                                        <div id="review_div_<?php echo $rrows['id']; ?>" style="display: none">
                                            <form method="post">
                                                <input type="hidden" id="review_id_<?php echo $rrows['id']; ?>" value="<?php echo $rrows['id']; ?>">
                                                <div class="form-group">
                                                    <textarea class="form-control" name="reply" id="reply_text_<?php echo $rrows['id']; ?>" placeholder="<?php echo $language['lg_reply']??""; ?>" maxlength="999"></textarea>
                                                </div>
                                                <div class="submit-section">
                                                    <button type="button" id="reply_btn_<?php echo $rrows['id']; ?>" onclick="return create_reply('<?php echo $rrows['id']; ?>')" class="btn btn-primary submit-btn">
                                                        <?php echo $language['lg_submit']??""; ?>
                                                    </button>
                                                </div>

                                            </form>
                                        </div>
                                    <?php } ?>

                                </li>

                                <!-- /Comment List -->
                        <?php }
                        } else {
                            $lg_no_reviews_foun=$language['lg_no_reviews_foun']??"";
                            echo '<li>
										<div class="comment">
										<p> ' . $lg_no_reviews_foun . '</p>
										</div>
										</li>';
                        } ?>


                    </ul>
                    <!-- /Comment List -->

                </div>
            </div>
        </div>
    </div>

</div>
<!-- /Page Content -->

<?php $this->endSection(); ?>