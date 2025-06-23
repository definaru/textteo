<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<style>
  .slot-container {
    width: 100%;
    max-width: 350px;
    text-align: center;
    font-family: 'Poppins', sans-serif;
    background-color: #FFFFFF;
    border-radius: 20px;
}

.slots-grid {
    display: flex;
    flex-wrap: nowrap; /* Keeps all slots in one line, add scroll if overflow */
    overflow-x: auto;   /* Allows scrolling if they overflow the container */
    gap: 8px;
    justify-content: flex-start;
}

.slot {
    padding: 2px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 400;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #252525;
    background-color: #FFFFFF;
    white-space: nowrap; /* Prevent slot text from wrapping */
}

.slot:hover {
    border-color: #FD9720;
    color: #FD9720;
}

.see-more {
    font-weight: 400;
    color: #252525;
    cursor: pointer;
    background-color: #FFFFFF;
    padding: 2px;
    border-radius: 8px;
}

.no-slots{
    font-weight: 400;
    color: #252525;
    cursor: pointer;
    background-color: #FFFFFF;
    padding: 2px;
    border-radius: 8px;
}
.popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
        z-index: 999;
        overflow: auto; /* enable scrolling if content overflows */
        padding: 20px;
    }
    .popup-content {
        background: #fff;
        max-height: 80vh; /* limits popup height */
        overflow-y: auto; /* makes only the popup scrollable */
        width: 100%;
        max-width: 600px; /* optional width limit */
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    .close-btn {
        margin-top: 20px;
        background: #FD9720;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        cursor: pointer;
    }

    .breadcrumb-title{
        font-weight: 500;
        color:#252525;
        font-size: 24px;
        font-family: 'Poppins';
    }
    .doc-info-left {
        display: flex;
        align-items: center; /* Optional: vertically center align the image and text */
        gap: 0px; /* Optional: spacing between image and text */
    }

    .doc-info-cont {
      margin-left: 0 !important; /* Remove the manual negative margin */
    }

    .doctor-img{
        margin-top: -6%;
    }
</style>
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $language['lg_home'] ?? ""; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo ($role == 6) ? $language['lg_book_appointmen'] ?? "" : $language['lg_book_appointmen'] ?? ""; ?></li>
                    </ol>
                </nav>
                <!-- <h2 class="breadcrumb-title search-results"></h2> -->
                 <h2 class="breadcrumb-title">
                    <?php echo $language['lg_book_appointmen']; ?>
                 </h2>
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
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12 col-lg-4 col-xl-3 theiaStickySidebar">
            
            <?=
            
            user_detail(session('user_id')) ? view('user/layout/sidebar') : ''; 
             ?>
                <!-- Search Filter -->
               
                <!-- /Search Filter -->

            </div>

            <div class="col-md-12 col-lg-8 col-xl-9" style="background-color: #F7F7F7;padding: 1%;border-radius:12px 12px;">
                <input type="hidden" name="page" id="page_no_hidden" value="1">
                <h2 class="breadcrumb-title">Veterinarians</h2>
                <div id="doctor-list"></div>


                <div class="spinner-border text-success text-center" role="status" id="loading" style="text-align: center;margin: 60px 45%;"></div>
                <div class="load-more text-center d-none" id="load_more_btn_doctor">
                    <a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more'] ?? ""; ?></a>
                </div>

                <!-- Doctor Widget -->

                <!-- /Doctor Widget -->


            </div>
        </div>

    </div>

</div>
<!-- /Page Content -->

<script type="text/javascript">
    var country = '';
    var country_code = '';
    var state = '';
    var city = '';
    var citys = '<?php if (isset($_GET['location'])) {
                        echo ($_GET['location']);
                    } ?>';
    var specialization = '';
</script>
<script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>

<script>
$(document).ready(function () {
    // Set the number of slots to be shown on the page

    

    // Initialize slots on page load
   //initSlots();
  
});
</script>

<?php $this->endSection(); ?>