<?php 
	$this->extend('user/layout/header');

    $role = session('role');
	$user = user_detail(session('user_id'));
    $patient_name = ($role == '4' || $role == '1' || $role == '6') ? $language['lg_patient_name'] ?? '' : 'Particulars';
?>

<?php $this->section('content'); ?>
<div class="breadcrumb-bar">
    <div class="container mt-5 pt-5">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?=base_url().session(('module')); ?>" class="text-decoration-none">
                                <?=$language['lg_dashboard'] ?? ''; ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?=$language['lg_invoice'] ?? ''; ?>
                        </li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">
                    <?=$language['lg_invoice'] ?? ''; ?>
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container">
        <div class="row">
            <?=$user ? view('user/layout/sidebar') : '';?>

            <div class="col-12 <?=$user ? 'col-md-9' : 'col-md-12';?>">
                <div class="profile-sidebar">
                    <div class="border-0 card-table">
                        <div class="card-body p-3">
                            <div class="table-responsive" id="invoice_table_wrapper">
                                <table class="table table-hover table-center mb-0" id="invoice_table">
                                    <thead>
                                        <tr>
                                            <th><?=$language['lg_sno'] ?? '';?></th>
                                            <th><?=$language['lg_invoice_no'] ?? '';?></th>
                                            <th><?=$patient_name;?></th>
                                            <th><?=$language['lg_amount'] ?? ''; ?></th>
                                            <th><?=$language['lg_paid_on'] ?? ''; ?></th>
                                            <th><?=$language['lg_action'] ?? ''; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div id="invoice_cards" class="d-block d-md-none"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- <div class="col-md-7 col-lg-8 col-xl-9 no-invoice" style="display:none;background-color: #F7F7F7;">
                 <h2 style="color:#252525; text-align: center;padding: 3%;font-family:Poppins;font-weight:400;font-size:16px;background-color:#FFFFFF;border-radius:12px;">No invoice data available in table</h2>
            </div> -->
            
        </div>

    </div>

</div>

<script>
    function toggleDropdown(button) {
        const dropdown = button.nextElementSibling;
        dropdown.classList.toggle("show");
    }
</script>

<?php $this->endSection(); ?>