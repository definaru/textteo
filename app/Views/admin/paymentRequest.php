<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?> 
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Payment Requests</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payment Requests</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-md-12">

                <!-- Recent Orders -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="payment_request_table" class="table table-hover table-center w-100 mb-0">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Request Date</th>
                                        <th>Request Amount</th>
                                        <th>Description</th>
                                        <th>User</th>
                                        <th>Type</th>
                                        <th>Account Details</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /Recent Orders -->

            </div>
        </div>
    </div>
</div>
<!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->


<div class="modal fade" id="bank_details_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Bank Details</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="form-horzontal">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Bank Name :&nbsp;</label>
                            <label class="control-label bankname"></label>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Branch Name :&nbsp;</label>
                            <label class="control-label branchname"></label>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Account Number :&nbsp;</label>
                            <label class="control-label accountno"></label>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Account Name :&nbsp;</label>
                            <label class="control-label accountname"></label>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $this->endSection(); ?>