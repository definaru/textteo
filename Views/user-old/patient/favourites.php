<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url().session('module');?>"><?php echo $language['lg_dashboard']??""; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_favourites']??""; ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title"><?php echo $language['lg_favourites']??""; ?></h2>
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
                <?= view('user/layout/sidebar.php'); ?>
                <!-- /Profile Sidebar -->
            </div>
            <div class="col-md-7 col-lg-8 col-xl-9">
                <div class="row row-grid">

                    <?php if (!empty($favourites)) {
                        foreach ($favourites as $rows) {

                            if($rows['profileimage']=="" || ($rows['profileimage'] !="" && !is_file($rows['profileimage']))){
                                $profileimage=base_url().'assets/img/user.png';
                            }
                            else {
                                $profileimage=base_url().$rows['profileimage'];
                            }

                    ?>

                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="profile-widget">
                                    <div class="doc-img">
                                        <a target="_blank" href="<?php echo base_url(); ?>doctor-preview/<?php echo encryptor_decryptor('encrypt',libsodiumDecrypt($rows['username'])); ?>">
                                            <img class="img-fluid" alt="User Image" src="<?php echo $profileimage; ?>">
                                        </a>

                                    </div>
                                    <div class="pro-content">
                                        <h3 class="title">
                                            <a target="_blank" href="<?php echo base_url(); ?>doctor-preview/<?php echo encryptor_decryptor('encrypt',libsodiumDecrypt($rows['username'])); ?>"><?php echo $language['lg_dr']??""; ?> <?php echo ucfirst(libsodiumDecrypt($rows['first_name']) . ' ' . libsodiumDecrypt($rows['last_name'])); ?></a>
                                            <i class="fas fa-check-circle verified"></i>
                                        </h3>
                                        <p class="speciality"><?php echo ucfirst(libsodiumDecrypt($rows['speciality'])); ?></p>
                                        <div class="rating">
                                            <?php
                                            $rating_value = $rows['rating_value'];
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating_value) {
                                                    echo '<i class="fas fa-star filled"></i>';
                                                } else {
                                                    echo '<i class="fas fa-star"></i>';
                                                }
                                            }
                                            ?>
                                            <span class="d-inline-block average-rating">(<?php echo $rows['rating_count']; ?>)</span>
                                        </div>
                                        <ul class="available-info">
                                            <li>
                                                <i class="fas fa-map-marker-alt"></i> <?php echo $rows['statename'] . ' ' . $rows['countryname']; ?>
                                            </li>
                                            <li>
                                                <i class="far fa-money-bill-alt"></i> <?php echo ($rows['price_type'] == 'Custom Price') ? convert_to_user_currency($rows['amount']) : 'Free'; ?>
                                            </li>
                                        </ul>
                                        <div class="row row-sm">
                                            <div class="col-6">
                                                <a target="_blank" href="<?php echo base_url() . 'doctor-preview/' . encryptor_decryptor("encrypt",libsodiumDecrypt($rows["username"])); ?>" class="btn view-btn"><?php echo $language['lg_view_profile']??""; ?></a>
                                            </div>
                                            <div class="col-6">
                                                <a target="_blank" href="<?php echo base_url() . 'book-appoinments/' . encryptor_decryptor("encrypt",libsodiumDecrypt($rows["username"])); ?>" class="btn book-btn"><?php echo $language['lg_book_now']??""; ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } else {
                        echo '<div class="col-md-12">
									<div class="profile-widget">
										<p> ' . $language['lg_no_favourites_f']??"" . ' </p>
										</div>
										</div>';
                    } ?>


                </div>
            </div>
        </div>
    </div>

</div>
<!-- /Page Content -->

<?php $this->endSection(); ?>