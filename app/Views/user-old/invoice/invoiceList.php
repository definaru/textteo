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
                            <a href="<?php echo base_url().session(('module')); ?>">
                                <?php echo $language['lg_dashboard']??""; ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_invoice']??""; ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title"><?php echo $language['lg_invoice']??""; ?></h2>
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
                <?php
                    echo view('user/layout/sidebar');

                ?>
                <!-- /Profile Sidebar -->

            </div>

            <div class="col-md-7 col-lg-8 col-xl-9">
                <div class="card card-table">
                    <div class="card-body">

                        <!-- Invoice Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-center mb-0" id="invoice_table">
                                <thead>
                                    <tr>
                                        <th><?php echo $language['lg_sno']??""; ?> </th>
                                        <th><?php echo $language['lg_invoice_no']??""; ?> </th>

                                        <th>
                                            <?php
                                            if (session('role') == '4' || session('role') == '1' || session('role') == '6') {
                                                echo $language['lg_patient_name']??"";
                                            } 
                                            else {
                                                echo "Particulars";
                                            }
                                            ?>
                                        </th>

                                        <th><?php echo $language['lg_amount']??""; ?></th>
                                        <th><?php echo $language['lg_paid_on']??""; ?></th>
                                        <th><?php echo $language['lg_action']??""; ?></th>
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

<?php $this->endSection(); ?>