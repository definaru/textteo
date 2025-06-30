<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
  <div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7 col-auto">
          <h3 class="page-title">Email Template</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Email Template</li>
          </ul>
        </div>

      </div>
    </div>
    <!-- /Page Header -->
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table id="emailtemplate_table" class="table table-hover table-center mb-0 w-100">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Email Templates</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /Page Wrapper -->






</div>
<!-- /Main Wrapper -->
<?php $this->endSection(); ?>