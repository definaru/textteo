<?php 
    $this->extend('user/layout/header'); 
    $user = user_detail(session('user_id'));
    // $role == 6 ? $language['lg_book_appointmen'] ?? "" : $language['lg_book_appointmen'] ?? "";
    $breadcrumb_title = $language['lg_book_appointmen'] ?? '';
?>
<?php $this->section('content'); ?>
<main class="pt-4 pb-5">
    <div class="breadcrumb-bar mt-5 pt-4">
        <div class="container mt-2">
            <div class="row align-items-center">
                <div class="col-md-8 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/" class="text-decoration-none">
                                    <?=$language['lg_home'] ?? ""; ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?=$breadcrumb_title; ?>
                            </li>
                        </ol>
                    </nav>
                    <!-- <h2 class="breadcrumb-title search-results"></h2> -->
                    <h2 class="breadcrumb-title"><?=$language['lg_book_appointmen']; ?></h2>
                </div>
                <div class="col-md-4 col-12 d-md-block d-none">
                    <div class="sort-by">
                        <!-- <span class="sort-title"><?php //echo $language['lg_sort_by'] ?? ""; ?></span>
                        <span class="sortby-fliter">
                            <select class="select form-control" id="orderby" onchange="search_doctor(0)">
                                <option value=""><?php //echo $language['lg_select'] ?? ""; ?></option>
                                <option class="sorting" value="Rating"><?php //echo $language['lg_rating'] ?? ""; ?></option>

                                <option class="sorting" value="Latest"><?php //echo $language['lg_latest'] ?? ""; ?></option>
                                <option class="sorting" value="Free"><?php //echo $language['lg_free'] ?? ""; ?></option>
                            </select>
                        </span> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <div class="row">
                <?php if($user) { ?>
                    <div class="col-12 col-md-4 theiaStickySidebar">
                        <?=view('user/layout/sidebar');?>
                    </div>
                <?php } ?>
                <div 
                    class="col-12 <?=$user ? 'col-md-8' : 'col-md-12';?>" 
                    style="background-color: #F7F7F7;padding: 1%;border-radius:12px 12px;"
                >
                    <input 
                        type="hidden" 
                        name="page" 
                        id="page_no_hidden" 
                        value="1" 
                    />
                    <h2 class="breadcrumb-title">Veterinarians</h2>
                    <div id="doctor-list"></div>

                    <div 
                        class="spinner-border text-success text-center" 
                        role="status" 
                        id="loading" 
                        style="margin: 20% 45%;"
                    ></div>

                    <div class="load-more text-center d-none" id="load_more_btn_doctor">
                        <a class="btn btn-primary btn-sm" href="javascript:void(0);">
                            <?=$language['lg_load_more'] ?? ""; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $this->endSection(); ?>