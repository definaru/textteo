<?= $this->extend('user/layout/header'); ?>
<?= $this->section('content'); ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $language['lg_home']??""; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_post1']??""; ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title"><?php echo $language['lg_post1']??""; ?></h2>
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

                <div class="card card-table">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded">
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo base_url().session('module'); ?>/active-blog"><?php echo $language['lg_acitive_blog']??""; ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="<?php echo base_url().session('module'); ?>/pending-blog"><?php echo $language['lg_pending_blog']??""; ?></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-primary" href="<?php echo base_url().session('module'); ?>/blog-add"><i class="fas fa-plus mr-1"></i> <?php echo $language['lg_add_blog']??""; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <!-- Invoice Table -->
                        <div class="table-responsive">
                            <input type="hidden" id="posts_type" value="2">
                            <table id="posts_table" class="table table-hover table-center mb-0 w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $language['lg_image'];?></th>
                                        <th><?php echo $language['lg_title1'];?></th>
                                        <th><?php echo $language['lg_category'];?></th>
                                        <th><?php echo $language['lg_created_date'];?></th>
                                        <th><?php echo $language['lg_options'];?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /Invoice Table -->

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
<!-- /Page Content -->
<?= $this->endSection(); ?>