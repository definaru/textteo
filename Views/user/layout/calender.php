<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url().session('module');?>"><?php 
							
						echo $language['lg_dashboard']??"";?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_calendar']??"";?></li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title"><?php echo $language['lg_calendar']??"";?></h2>
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

            <!-- Calendar -->
            <div class="col-md-7 col-lg-8 col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <!-- /Calendar -->


        </div>

    </div>

</div>

<?php $this->endSection(); ?>            