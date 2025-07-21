<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
      <ul>
        <li class="menu-title">
          <span>Main</span>
        </li>
        <li <?php
            /**
             * @var string $module
             */
            echo ($module == 'dashboard') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/dashboard"><i class="fas fa-home"></i> <span>Dashboard</span></a>
        </li>
        <li <?php echo ($module == 'appointments') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/appointments"><i class="far fa-calendar-check"></i> <span>Appointments</span></a>
        </li>
        <li <?php echo ($module == 'specialization') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/specialization"><i class="fas fa-stethoscope"></i> <span>Specialization</span></a>
        </li>
        <li <?php echo ($module == 'users' && $page == 'clinic') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/users/clinic"><i class="fas fa-clinic-medical"></i> <span>Veterinary Clinics</span></a>
        </li>
        <li <?php
            /**
             * @var string $page
             */

            echo ($module == 'users' && $page == 'doctors') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/doctors"><i class="fas fa-user-md"></i> <span>Veterinarians</span></a>
        </li>

        <li <?php echo ($module == 'users' && $page == 'patients') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/patients"><i class="fas fa-user-injured"></i> <span>Patients</span></a>
        </li>
        <li <?php echo ($module == 'payment_requests' && $page == 'index') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/payment-request"><i class="fas fa-file-invoice-dollar"></i> <span>Payment Requests</span></a>
        </li>
        <!--<li class="submenu">
          <a href="#"><i class="fas fa-hospital"></i> <span> Pharmacy </span> <span class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li> <a href="<?php echo base_url(); ?>admin/users/pharmacies"> Pharmacies </a>
            </li>
            <li><a href="<?php echo base_url(); ?>admin/products"> Products </a></li>
            <li><a href="<?php echo base_url(); ?>admin/products/categories"> Categories </a></li>
            <li><a href="<?php echo base_url(); ?>admin/products/subcategories"> Sub Categories </a></li>
            <li><a href="<?php echo base_url(); ?>admin/unit"> Units </a></li>
            <li> <a href="<?php echo base_url(); ?>admin/users/orders"> Pharmacy Orders </a>
            </li>
          </ul>
        </li>
        <li class="submenu">
          <a href="#"><i class="fe fe-user"></i> <span> Lab </span> <span class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li> <a href="<?php echo base_url(); ?>admin/users/labs"> Lab Lists </a>
            </li>
            <li> <a href="<?php echo base_url(); ?>admin/users/labtest-booked"> Lab Booking </a>
            </li>
            <li><a href="<?php echo base_url(); ?>admin/users/lab-tests"> Lab Test Details </a></li>
          </ul>
        </li>-->
        <li <?php echo ($module == 'reviews') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/review-page"><i class="fe fe-star-o"></i> <span>Reviews</span></a>
        </li>
        <li <?php echo ($module == 'settings') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/settings"><i class="fa fa-cog font-set"></i> <span>Settings</span></a>
        </li>
        <li <?php echo ($module == 'PromoModel') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/promo"><i class="fa fa-cog font-set"></i> <span>Promo</span></a>
        </li>
        <li <?php echo ($module == 'email_template') ? 'class="active"' : ''; ?>>
          <a href="email-template"><i class="fa fa-envelope font-set"></i> <span>Email Template</span></a>
        </li>
        <li <?php echo ($module == 'cms') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/cms"><i class="fas fa-layer-group"></i> <span>CMS</span></a>
        </li>

        <li class="submenu">
          <a href="#"><i class="fa fa-language font-set"></i> <span> Language </span> <span class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li><a href="<?php echo base_url(); ?>admin/language"> Language </a></li>
            <li><a href="<?php echo base_url(); ?>admin/language/keywords">Language Keywords </a></li>
            <li><a href="<?php echo base_url(); ?>admin/language/pages"> App Language Keywords </a></li>
          </ul>
        </li>

        <li <?php echo ($module == 'profile') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>admin/profile"><i class="fe fe-user-plus"></i> <span>Profile</span></a>
        </li>
        <li <?php echo ($module == 'terms_conditions') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>termsandconditions"><i class="fa fa-sticky-note"></i> <span>Terms and Conditions</span></a>
        </li>
        <li <?php echo ($module == 'privacy_policy') ? 'class="active"' : ''; ?>>
          <a href="<?php echo base_url(); ?>privacypolicy"><i class="fa fa-lock"></i> <span>Privacy Policy</span></a>
        </li>
        <!--<li class="menu-title">
          <span>Blogs</span>
        </li>
        <li class="submenu">
          <a href="#"><i class="fas fa-sitemap"></i> <span> Categories </span> <span class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li><a href="<?php echo base_url(); ?>admin/categories"> Categories </a></li>
            <li><a href="<?php echo base_url(); ?>admin/subcategories"> Sub Categories </a></li>
          </ul>
        </li>
        <li class="submenu">
          <a href="#"><i class="fa fa-blog font-set"></i> <span> Post </span> <span class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li><a href="<?php echo base_url(); ?>admin/active-post"> Post </a></li>
            <li><a href="<?php echo base_url(); ?>admin/pending-post">Pending Post </a></li>
            <li><a href="<?php echo base_url(); ?>admin/add-post"> Add Post </a></li>
          </ul>
        </li>-->

        <li class="submenu">
          <a href="#"><i class="fas fa-globe"></i> <span> Country Config </span> <span class="menu-arrow"></span></a>
          <ul style="display: none;">
            <li><a href="<?php echo base_url(); ?>admin/country"> Add Country </a></li>
            <li><a href="<?php echo base_url(); ?>admin/state">Add State </a></li>
            <li><a href="<?php echo base_url(); ?>admin/city"> Add City </a></li>
          </ul>
        </li>

      </ul>
    </div>
  </div>
</div>
<!-- /Sidebar -->