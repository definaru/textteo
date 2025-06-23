<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<style>
    @media (max-width: 768px) {
  thead {
    display: none;
  }

  .invoice-card td {
    display: block;
    padding: 0 !important;
    border: none;
  }

  .invoice-card-wrapper {
    background: #fff;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 16px;
    padding: 16px;
    position: relative;
    background-color: #E1E1E1;
    padding:2%;
  }

  .invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .invoice-number {
    font-weight: 600;
  }

  .invoice-amount {
    font-weight: bold;
    color: #333;
  }

  .invoice-meta {
    display: flex;
    justify-content: space-between;
    margin-top: 8px;
    font-size: 14px;
  }

  .invoice-status.paid {
    color: green;
    font-weight: 500;
  }

  .menu-btn {
    background: transparent;
    border: none;
    font-size: 18px;
    cursor: pointer;
  }

  .dropdown-content {
    display: none;
    margin-top: 12px;
    border-top: 1px solid #eaeaea;
  }

  .dropdown-content.show {
    display: block;
  }

  .dropdown-content a {
    display: block;
    padding: 10px 0;
    color: #545454;
    text-decoration: none;
    font-size: 14px;
    border-bottom: 1px solid #eee;
  }

  .dropdown-content a:last-child {
    border-bottom: none;
  }
}
</style>
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

            <div class="col-md-7 col-lg-8 col-xl-9 invoice-table-list-div" style="display:block;background-color: #F7F7F7;padding: 1.5%;border-radius: 12px;height: fit-content;">
                <div class="card card-table">
                    <div class="card-body">

                        <!-- Invoice Table -->
                        <div class="table-responsive" id="invoice_table_wrapper">
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
                            <div id="invoice_cards" class="d-block d-md-none"></div>
                        </div>
                        <!-- /Invoice Table -->
                    </div>
                </div>
            </div>

            <div class="col-md-7 col-lg-8 col-xl-9 no-invoice" style="display:none;background-color: #F7F7F7;">
                 <h2 style="color:#252525; text-align: center;padding: 3%;font-family:Poppins;font-weight:400;font-size:16px;background-color:#FFFFFF;border-radius:12px;">No invoice data available in table</h2>
            </div>
            
        </div>

    </div>

</div>
<!-- /Page Content -->

<script>
function toggleDropdown(button) {
    const dropdown = button.nextElementSibling;
    dropdown.classList.toggle("show");
}


</script>

<?php $this->endSection(); ?>