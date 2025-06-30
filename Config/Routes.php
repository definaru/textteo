<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');

// For Mobile API's
$routes->group("api", ["namespace" => "App\Controllers\Api"], function ($routes) {
    $routes->get("keygenerate", "ApiController::generate_sodiumkeys");
    $routes->post("encrypt-data", "ApiController::encryptData");
    $routes->post("decrypt-data", "ApiController::decryptData");
    //Registration
    $routes->post("sign-up", "ApiController::signup");
    //login
    $routes->post("login", "ApiController::login");
    //master
    $routes->post("master", "ApiController::master");
    //logout
    $routes->post("logout", "ApiController::logout");
    //pharmacy_profile_update
    $routes->post("update-pharamcy-profile", "ApiController::updatePharamcyProfile");
    //patient_profile_update
    $routes->post("update-patient-profile", "ApiController::updatePatientProfile");
    //lab_profile_update
    $routes->post("update-lab-profile", "ApiController::updateLabProfile");
    //clinic_profile_update
    $routes->post("update-clinic-profile", "ApiController::updateClinicProfile");
    //Change password
    $routes->post("change-password", "ApiController::changePassword");
    //Language List
    $routes->get("language-list", "ApiController::getLanguageList");
    //Language Keywords
    $routes->post("language-keyword", "ApiController::languagekeywords");
    //Profile Details
    $routes->get("profile-details", "ApiController::getProfileDetails");
    //Clinic Profile Details
    $routes->get("clinic-details", "ApiController::getClinicProfilDetails");
    //Doctor Profile Details
    $routes->get("doctor-details", "ApiController::getDoctorProfileDetails");
    //Update Doctor Profile Details
    $routes->post("update-doctor-details", "ApiController::updateDoctorsProfile");
    // Patient list
    $routes->post("patient-list", "ApiController::myPatients");
    //Manage Schedule Time
    $routes->post("manage-schedule-time", "ApiController::manageScheduleTime");
    //Delete profile information
    $routes->post("delete-doctor-profile", "ApiController::deleteProfileInformation");
    // Doctor Preview
    $routes->post("doctor-preview", "ApiController::doctorPreview");
    // Doctor Search
    $routes->post("doctor-search", "ApiController::doctorSearch");
    //Clinic preview
    $routes->post("clinic-preview", "ApiController::clinicPreviewDetails");
    //Specialization List
    $routes->post("specialization-list", "ApiController::specializationList");
    //prescription List
    $routes->post("prescription-list", "ApiController::prescriptionList");
    //Home api
    $routes->get("get-home-details", "ApiController::homeDetails");
    //prescription Detail
    $routes->post("prescription-detail", "ApiController::prescriptionDetail");
    //prescription Insert
    $routes->post("prescription-insert", "ApiController::prescriptionInsert");
    //prescription update
    $routes->post("prescription-update", "ApiController::prescriptionUpdate");
    //prescription Delete
    $routes->post("prescription-delete", "ApiController::prescriptionDelete");
    //Dashboard Count Details
    $routes->post("dashboard-count", "ApiController::dashboardCountDetails");
    //Dashboard Order List
    $routes->post("dashboard-order-list", "ApiController::dashboardOrderList");
    //Medical Record List
    $routes->post("medical-record-list", "ApiController::medicalRecordsList");
    //Medical Record Delete
    $routes->post("medical-record-delete", "ApiController::medicalRecordDelete");
    //Medical Record Upload
    $routes->post("medical-record-upload", "ApiController::uploadMedicalRecord");
    //Lab Dashboard
    $routes->get("lab-dashboard", "ApiController::labDashboard");
    //Billing List
    $routes->post("billing-list", "ApiController::billingList");
    //Favourities List
    $routes->post("favourities-list", "ApiController::favouritiesList");
    //Add Favourities
    $routes->post("add-favourities", "ApiController::addFavourities");
    //Reviews List
    $routes->post("reviews-list", "ApiController::reviewsList");
    //Appointments List
    $routes->post("appointment-list", "ApiController::getAppointmentsList");
    //Add Reviews
    $routes->post("add-reviews", "ApiController::addReviews");
    //Lab Test List
    $routes->post("lab-test-list", "ApiController::labTestList");
    //Lab Test List
    $routes->post("lab-appointment", "ApiController::labAppointments");
    //Add Labtest
    $routes->post("add-labtest", "ApiController::addLabtest");
    //Edit Labtest
    $routes->post("edit-labtest", "ApiController::editLabtest");
    //My Labs
    $routes->post("mylabs", "ApiController::myLabs");
    //Checkout
    $routes->post("checkout", "ApiController::checkout");
    //Lab appointment test list
    $routes->post("lab-appointment-test-list", "ApiController::labAppointmentTestList");
    //configList
    $routes->get("config-list", "ApiController::configList");
    //Invoice list
    $routes->post("invoice-list", "ApiController::invoiceList");
    //Doctor list
    $routes->post("doctor-list", "ApiController::doctorList");
    //Doctor Add
    $routes->post("clinic-doctor-add", "ApiController::clinicAddDoctor");
    //Doctor Delete
    $routes->post("clinic-doctor-delete", "ApiController::clinicDoctorDelete");
    //Assign to Doctor
    $routes->post("assign-to-doctor", "ApiController::assignedToDoctor");
    //Checkout Lab
    $routes->post("checkout-lab", "ApiController::checkoutLab");
    //Chat Users Get
    $routes->get("chat-users-get", "ApiController::chatUsersGet");
    //conversation
    $routes->post("conversation", "ApiController::conversation");
    //Send Message
    $routes->post("send-message", "ApiController::sendMessage");
    //Search Pharmacy
    $routes->post("search-pharmacy", "ApiController::searchPharmacy");
    //Pharmacy Product Search
    $routes->post("pharmacy-product-search", "ApiController::pharmacyProductSearch");
    //Edit Product
    $routes->post("edit-product", "ApiController::editProduct");
    //Create Product
    $routes->post("add-product", "ApiController::createProduct");
    //pharmacyProductAndCategoryList
    $routes->post("Pharmacy-product-and-category-list", "ApiController::pharmacyProductAndCategoryList");
    //Get Phamacy Details
    $routes->post("get-phamacy-details", "ApiController::getPhamacyDetails");
    //Pharmacy Product List.
    $routes->post("pharmacy-product-list", "ApiController::pharmacyProductsList");
    //Get Single Product.
    $routes->post("get-single-product", "ApiController::getSingleProduct");
    //Lab Result Upload.
    $routes->post("lab-result-upload", "ApiController::labResultUpload");
    //Order List
    $routes->post("order-list", "ApiController::orderList");
    //Order Details
    $routes->post("order-details", "ApiController::orderDetails");
    //Pharmacy Invoice Details
    $routes->post("pharmacy-invoice-details", "ApiController::pharmacyInvoiceDetails");
    //Patient Accounts List
    $routes->post("patient-accounts-list", "ApiController::patientAccountsList");
    //Doctor Accounts List
    $routes->post("doctor-accounts-list", "ApiController::doctorAccountsList");
    //Product Delete.
    $routes->post("product-delete", "ApiController::productDelete");
    //Place Order.
    $routes->post("place-order", "ApiController::placeOrder");
    //Account Send Request.
    $routes->post("account-send-request", "ApiController::accountSendRequest");
    //Patient Refund Request.
    $routes->post("patient-refund-request", "ApiController::patientRefundRequest");
    //Doctor Refund Request.
    $routes->post("doctor-refund-request", "ApiController::doctorRefundRequest");
    //Make Outgoing Call.
    $routes->post("make-outgoing-call", "ApiController::makeOutgoingCall");
    //Make Incoming Call.
    $routes->post("make-incoming-call", "ApiController::makeIncomingCall");
    //End Call.
    $routes->post("end-call", "ApiController::endCall");
    //Get Schedule.
    $routes->post("get-schedule-time", "ApiController::getSchedule");
    //Available Time Slots.
    $routes->post("available-time-slots", "ApiController::availableTimeSlots");
    //Add Account Details.
    $routes->post("add-account-details", "ApiController::addAccountDetails");
    //Change Order Status.
    $routes->post("change-order-status", "ApiController::changeOrderStatus");
    //Lab List.
    $routes->post("lab-list", "ApiController::labList");
    //Hospital Doctor List.
    $routes->post("hospital-doctor-list", "ApiController::hospitalDoctorList");
    //Get Account Details.
    $routes->get("get-account-details", "ApiController::getAccountDetails");
    //Paymentrequest.
    $routes->post("paymentrequest", "ApiController::paymentrequest");
    //Get Token Details.
    $routes->post("get-token-details", "ApiController::getTokenDetails");
    //Appoinments Calculation.
    $routes->post("appoinments-calculation", "ApiController::appoinmentsCalculation");
    //Appoinments History.
    $routes->post("appointments-history", "ApiController::appointmentsHistory");
    //My Doctors List.
    $routes->post("my-doctor-list", "ApiController::myDoctorsList");
    $routes->post("mydoctors-list", "ApiController::myDoctorsList");
    //Change Appoinments Status.
    $routes->post("change-appoinments-status", "ApiController::changeAppoinmentsStatus");
    //Pharmacy Accounts List.
    $routes->get("pharmacy-accounts-list", "ApiController::PharmacyAccountsList");
    //Check Otp.
    $routes->post("check-otp", "ApiController::checkOtp");
    //Otp Sign.
    $routes->post("otp-sign", "ApiController::otpSign");
    //Generate Otp.
    $routes->post("generate-otp", "ApiController::generateOtp");
    //Forgot Password
    $routes->post("forgot-password", "ApiController::forgotPassword");
});

$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('login', 'User\SignInController::index', ['filter' => 'auth:0']);
$routes->get('register', 'User\SignInController::register', ['filter' => 'auth:0']);
$routes->post('user-register', 'User\SignInController::signup');
$routes->post('check-register', 'User\SignInController::check_already_register');
$routes->post('social-register', 'User\SignInController::social_register');
$routes->post('social-signin', 'User\SignInController::social_signin');
$routes->post('user-login', 'User\SignInController::login');
$routes->get('user-logout', 'User\SignInController::logout');
$routes->post('check-password', 'User\SignInController::passwordCheck');
$routes->post('check-password-v2', 'User\SignInController::passwordCheckV2');

$routes->get('change-password', 'User\SignInController::changePassword', ['filter' => 'auth:1']);
// Forgot Password Page Load
$routes->get('forgot-password', 'User\SignInController::forgotPassword', ['filter' => 'auth:0']);
// Forgot Password Sumbit
$routes->post('forgot-password', 'User\SignInController::forgotPasswordUpdate', ['filter' => 'auth:0']);
$routes->get('reset-password/(:any)', 'User\SignInController::reset/$1', ['filter' => 'auth:0']);
$routes->post('reset-password', 'User\SignInController::resetPassword');
/**
 * Email Verify
 */
$routes->get('activate/(:any)', 'User\SignInController::activate/$1');

$routes->post('change-password', 'User\SignInController::passwordUpdate');
$routes->post('change-password-v2', 'User\SignInController::passwordUpdateV2');

$routes->post('new-password-update', 'User\SignInController::newPasswordUpdate');

$routes->get('social-media', 'User\ClinicController::socialMedia', ['filter' => 'auth:1']);
$routes->get('calendar', 'User\ClinicController::calendarView');
$routes->get('calendar', 'User\ClinicController::calendarView', ['filter' => 'auth:1']);
$routes->get('calendar-list', 'User\ClinicController::calendarList');
/**
 * Calender View
 */
$routes->get('social-media', 'User\ClinicController::socialMedia', ['filter' => 'auth:1']);
$routes->get('map-direction/(:any)', 'Home::maps/$1', ['filter' => 'auth:1']);
/**
 * Dinesh
 * Pharmacy
 */
$routes->post('get-pharmacy-details', 'User\ProductController::getPhamacyDetails');
$routes->get('pharmacy-search', 'User\ProductController::pharmacySearchBydoctor');
$routes->post('search_pharmacy', 'Home::search_pharmacy');
$routes->get('products-list-by-pharmacy', 'ProductController::view_pharmacy_products');
$routes->post('products-list', 'Home::products_list');
$routes->get('cart-list', 'Home::cart_lists');
$routes->get('cart', 'Home::cart_page', ['filter' => 'auth:1']);
$routes->get('cart-checkout', 'Home::checkout');
//$routes->get('sub-category/(:any)', 'Home::productSubCategory/$1');
$routes->match(['get', 'post'], 'sub-category', 'Home::productSubCategory');
$routes->post('stripe-payment', 'Home::stripePayment');
$routes->get('payment-sucess', 'Home::payment_sucess');
$routes->post('paypal-pay', 'Home::paypalPay');
$routes->post('pharmacy-paypal-initiate', 'Home::pharmacyInitiatePayment');
$routes->get('pharmacy-paypal-success', 'Home::pharmacySuccessPayment');
$routes->get('get-notification', 'Home::notificationPage');
$routes->post('read-notification', 'Home::readNotification');
$routes->post('search-notification', 'Home::searchNotification');
$routes->get('doctor-notification', 'Home::doctorNotificationPage');
$routes->get('product-details/(:any)', 'Home::product_details/$1');
$routes->get('pharmacy-preview/(:any)', 'Home::pharmacyPreview/$1');

/**
 * Doctor Routes
 */
$routes->post('search-keyword', 'Home::searchKeywords');
$routes->get('search-veterinary', 'Home::searchDoctor');
$routes->post('search-veterinary', 'Home::searchDoctorList');
$routes->get('search-veterinary-map', 'Home::searchDoctorOnMap');
$routes->get('doctor-preview/(:any)', 'Home::doctorPreview/$1');
$routes->post('add-favourities', 'Home::addFavourities');

/**
 * Lab Routes
 */
$routes->get('payment-success', 'User\LabController::paymentSuccess');
$routes->post('lab-razorpay-create', 'User\LabController::createRazorpayOrders');
$routes->post('lab-razorpay-payment', 'User\LabController::razorpayAppoinments');
$routes->post('add-lab-appoinment', 'User\LabController::addAppoinments');
$routes->get('search-lab', 'User\LabController::searchLabs');
$routes->post('search-lab', 'User\LabController::searchLabList');
$routes->get('lab-tests/(:any)', 'User\LabController::labTests/$1');
$routes->post('set-booked-session-lab-test', 'User\LabController::setBookedSessionLabTest');
$routes->get('lab-checkout', 'User\LabController::labCheckout', ['filter' => 'auth:1']);

// Login to Access Appointment
$routes->get('book-appoinments/(:any)', 'User\BookAppointment::bookDoctor/$1', ['filter' => 'auth:1']);
$routes->post('get-schedule-from-date', 'User\BookAppointment::getScheduleFromDate');
$routes->get('set-booked-session', 'User\BookAppointment::setBookedSession');
$routes->post('set-booked-session', 'User\BookAppointment::setBookedSession');
$routes->post('set-package-session', 'User\BookAppointment::setPackageSession');
$routes->get('checkout-appoinment', 'User\BookAppointment::checkout', ['filter' => 'auth:1']);
$routes->get('checkout-package', 'User\BookAppointment::checkoutPackage', ['filter' => 'auth:1']);
$routes->post('add-appoinment', 'User\BookAppointment::addAppoinments');
$routes->post('get-amount-info', 'User\BookAppointment::getAppointmentAmountInfo');

/**
 * Audio And Video Call
 */
$routes->post('appt-doctor-details', 'User\BookAppointment::appointmentDoctorDetail', ['filter' => 'auth:1']);
$routes->get('get-appoinment-call', 'User\BookAppointment::appointmentGetCall', ['filter' => 'auth:1']);
$routes->post('appoinment-end-call', 'User\BookAppointment::appointmentEndCall', ['filter' => 'auth:1']);
$routes->get('outgoing-video-call/(:any)', 'User\BookAppointment::outGoingVideoCall/$1', ['filter' => 'auth:1']);
$routes->get('outgoing-call/(:any)', 'User\BookAppointment::outGoingAudioCall/$1', ['filter' => 'auth:1']);
$routes->get('incoming-video-call/(:any)', 'User\BookAppointment::inComingVideoCall/$1', ['filter' => 'auth:1']);
$routes->get('incoming-call/(:any)', 'User\BookAppointment::inComingAudioCall/$1', ['filter' => 'auth:1']);
$routes->post('appt-add-review', 'User\BookAppointment::addApptReviews', ['filter' => 'auth:1']);
$routes->post('appt-change-status', 'User\BookAppointment::changeAppointmentStatus', ['filter' => 'auth:1']);

/**
 * Card Payment of Appt Booking
 */
$routes->post('appointment-stripe-payment', 'User\BookAppointment::makeStripePayment', ['filter' => 'auth:1']);
//Muddasar Ali, Memopay
$routes->post('appointment-mamopay-payment-initiate', 'User\BookAppointment::makeMamoPayPaymentInit', ['filter' => 'auth:1']);
$routes->get('appointment-mamopay-payment-success', 'User\BookAppointment::makeMamoPayPaymentSuccess');
$routes->post('appointment-mamopay-payment-failed', 'User\BookAppointment::makeMamoPayPaymentFail', ['filter' => 'auth:1']);
$routes->post('paypal-pay', 'User\BookAppointment::paypalPay', ['filter' => 'auth:1']);
/**
 * Doctor Appointment Razorpay
 */
$routes->post('appointment-razorpay-create', 'User\BookAppointment::createRazorpayOrders', ['filter' => 'auth:1']);
$routes->post('appointment-razorpay-payment', 'User\BookAppointment::razorpayAppoinments', ['filter' => 'auth:1']);
/**
 * Doctor Appointment Paypal
 */
$routes->post('paypal-initiate', 'User\PayPalController::initiatePayment', ['filter' => 'auth:1']);
$routes->get('paypal-complete', 'User\PayPalController::completePayment', ['filter' => 'auth:1']);
$routes->get('paypal-success', 'User\PayPalController::successPayment', ['filter' => 'auth:1']);
$routes->get('paypal-failed', 'User\PayPalController::failurePayment', ['filter' => 'auth:1']);
/**
 * Lab Appointment Paypal
 */
$routes->post('lab-paypal-initiate', 'User\PayPalController::labInitiatePayment', ['filter' => 'auth:1']);
$routes->get('lab-paypal-success', 'User\PayPalController::labSuccessPayment', ['filter' => 'auth:1']);

/**
 * Success Page
 * */
$routes->get('payment-success/(:any)', 'User\BookAppointment::paymentSuccess/$1', ['filter' => 'auth:1']);

/**
 *   Invoice List And View,Print
 */
$routes->get('invoice-view/(:any)', 'User\InvoiceController::invoiceDetail/$1', ['filter' => 'auth:1']);
$routes->get('invoice-print/(:any)', 'User\InvoiceController::invoicePrint/$1', ['filter' => 'auth:1']);
$routes->get('invoice-products-view/(:any)', 'User\InvoiceController::productView/$1', ['filter' => 'auth:1']);
$routes->get('invoice-products-print/(:any)', 'User\InvoiceController::productInvoicePrint/$1', ['filter' => 'auth:1']);
$routes->post('invoice-list', 'User\InvoiceController::invoiceList', ['filter' => 'auth:1']);
$routes->get('orders-details/(:any)', 'User\PharmacyController::ordersDetails/$1', ['filter' => 'auth:1']);
/**
 * Profile Image Upload
 */
$routes->post('update-profile-image', 'User\PatientController::cropProfileImg', ['filter' => 'auth:1']);
/**
 * Clinic And Doctor Common Route For Getting Appointments
 */
$routes->post('doctor-appointment-list', 'User\PatientController::appointmentListOfDoctor', ['filter' => 'auth:1']);
$routes->post('dashboard/appointment-list', 'User\DashboardController::appoinmentsList', ['filter' => 'auth:1']);
$routes->post('pharmacy/image_upload', 'User\PharmacyController::imageUpload', ['filter' => 'auth:1']);
/**
 * User & Admin Blog Entries
 */
$routes->post('blog/post/image_upload', 'Blog\PostController::imageUpload');
$routes->post('blog/post/delete_image', 'User\PharmacyController::deleteImage');
$routes->post('add-blog', 'Blog\PostController::createBlog');
$routes->post('update-blog', 'Blog\PostController::updateBlog');
$routes->get('delete-blog/(:any)', 'Blog\PostController::deleteBlog/$1');
$routes->post('blogs-list', 'Blog\PostController::postsList');
$routes->post('blog-update-status', 'Blog\PostController::changeStatus');

/**
 * Home Page Blog List And Detail
 */
$routes->get('blogs', 'Home::blogList');
$routes->post('get-blogs', 'Home::getBlogs');
$routes->get('blog-detail/(:any)', 'Home::blogDetails/$1');
$routes->post('add-blog-comments', 'Home::addComments');
$routes->post('get-blog-comments', 'Home::getComments');
$routes->post('add-blog-reply', 'Home::addReply');
$routes->post('get-blog-reply', 'Home::getReplies');
$routes->post('delete-comment-reply', 'Home::deleteCommentReply');

/**
 * Accounts Detail
 */
$routes->post('account-send-request', 'User\AccountsController::sendRequest', ['filter' => 'auth:1']);
$routes->get('get-account-details', 'User\AccountsController::getAccountDetails', ['filter' => 'auth:1']);
$routes->post('add-account-details', 'User\AccountsController::addAccountDetails', ['filter' => 'auth:1']);
$routes->post('doctor-account-list', 'User\AccountsController::doctorAccountsList', ['filter' => 'auth:1']);
$routes->post('patient-account-list', 'User\AccountsController::patientAccountsList', ['filter' => 'auth:1']);
$routes->post('patient-doctor-request', 'User\AccountsController::patientDoctorRequest', ['filter' => 'auth:1']);
$routes->post('patient-refund-request', 'User\AccountsController::patientRefundRequest', ['filter' => 'auth:1']);
$routes->post('account-payment-request', 'User\AccountsController::paymentRequest', ['filter' => 'auth:1']);
// Patient Routes
$routes->group("patient", ["namespace" => "App\Controllers", 'filter' => 'auth:1,patient'], function ($routes) {

    $routes->get('/', 'User\PatientController::index');
    $routes->get('accounts', 'User\AccountsController::index');
    $routes->get('profile', 'User\PatientController::profileSettings');
    $routes->post('update-profile', 'User\PatientController::updateProfile');
    $routes->post('update-required-proile', 'User\PatientController::updateRequiredProfile');
    // $routes->post('update-profileImg', 'User\PatientController::cropProfileImg');
    $routes->post('appointment-list', 'User\PatientController::myAppoinmentsList', ['filter' => 'auth:1']);
    $routes->post('previous-appointment-list', 'User\PatientController::myPrevoiusAppoinmentsList', ['filter' => 'auth:1']);
    $routes->post('appointment-edit', 'User\PatientController::myAppoinmentEdit');
    $routes->get('appointment/(:num)', 'User\PatientController::myAppoinment/$1');

    $routes->post('prescription-list', 'User\PatientController::myPrescriptionList', ['filter' => 'auth:1']);
    $routes->get('appointments', 'User\PatientController::appointmentPage');
    $routes->post('patient-appointment-list', 'User\PatientController::appointmentListOfPatient');
    $routes->get('lab-appointments', 'User\PatientController::labAppointmentPage');
    $routes->post('lab-appointment-list', 'User\PatientController::labAppointmentListOfPatient');
    $routes->get('lab-appointment-doc/(:any)', 'User\PatientController::lbTestDocs/$1');
    //Muddasar Ali - add captions
    $routes->get('appointment-captions/(:any)', 'User\PatientController::appointmentCaptions/$1');
    //Muddasar Ali - add integration with itmedicalvetsolutions.com
    $routes->get('appointment-medicalvet/(:any)', 'User\PatientController::appointmentMedicalvet/$1');

    $routes->get('favourites', 'User\PatientController::favourites');

    $routes->get('invoice', 'User\InvoiceController::index');
    $routes->get('review', 'Home::reviewPage');

    $routes->get('message', 'User\MessageController::index');
    $routes->get('orders-list', 'User\PharmacyController::ordersList');
    $routes->post('orders-list', 'User\PharmacyController::ordersListDatatable');
    
    //added new on 13rd June 2024 by Muddasar
    $routes->post('create-pet', 'User\PatientController::createPet');
    $routes->post('delete-pet', 'User\PatientController::deletePet');
    $routes->post('getPetModal', 'User\PatientController::getPetModal');
    $routes->post('getPetSelectModal', 'User\PatientController::getPatientPets');
    $routes->post('getAdviceSelectModal', 'User\PatientController::getPatientDoctorAdvice');

    $routes->post('edit-pet', 'User\PatientController::editPet');
    //end
});

// Clinic Routes
$routes->group("clinic", ["namespace" => "App\Controllers", 'filter' => 'auth:1,clinic'], function ($routes) {
    $routes->get('/', 'User\ClinicController::index');
    $routes->get('profile', 'User\ClinicController::profileSettings');
    $routes->post('update-profile', 'User\ClinicController::updateProfile', ['filter' => 'auth:1']);
    $routes->post('update-profileImg', 'User\ClinicController::cropProfileImg');
    $routes->post('upload-clinicImag', 'User\ClinicController::uploadClinicImg');

    $routes->get('active-blog', 'Blog\PostController::blog/1');
    $routes->get('pending-blog', 'Blog\PostController::blog/2');
    $routes->get('doctor', 'User\ClinicController::doctor');
    $routes->post('list-doctor', 'User\ClinicController::doctorList');
    $routes->post('get-doctor', 'User\ClinicController::doctorSingle');
    $routes->post('add-doctor', 'User\ClinicController::doctorAdd');
    $routes->get('my-patients', 'MypatientsController::index');

    /**
     * Appointment Page And List
     */
    $routes->get('appointments', 'User\ClinicController::appointmentPage');
    $routes->post('doctor-appointment-list', 'User\PatientController::appointmentListOfDoctor');

    $routes->get('invoice', 'User\InvoiceController::index');
    $routes->get('message', 'User\MessageController::index');
    $routes->get('review', 'Home::reviewPage');

    $routes->get('accounts', 'User\AccountsController::index');

    $routes->get('blog-add', 'Blog\PostController::addBlog');
    $routes->get('blog-edit/(:any)', 'Blog\PostController::editBlog/$1');
    $routes->get('blog', 'Blog\PostController::index');
    $routes->post('blog-list', 'Blog\PostController::index');
});

// Doctor Routes auth argument 0 / 1
$routes->group("message", ["namespace" => "App\Controllers", 'filter' => 'auth:1'], function ($routes) {
    // Get All Msg List And Count
    $routes->post('get-messages', 'User\MessageController::getMessages');
    // List User In Chat
    $routes->post('search-users', 'User\MessageController::searchUsers');
    $routes->post('get-message', 'User\MessageController::getMessage');
    $routes->get('get-chat-user', 'User\MessageController::getChatUser');

    $routes->post('get-chat-img', 'User\MessageController::getChatImg');
    $routes->post('insert-chat', 'User\MessageController::insertChat');
    $routes->post('upload_files', 'User\MessageController::uploadFiles');
});
// Doctor Routes auth argument 0 / 1
//veterinary
//doctor
$routes->group("doctor", ["namespace" => "App\Controllers", 'filter' => 'auth:1,doctor'], function ($routes) {
    $routes->get('/', 'User\DoctorController::index');
    $routes->get('profile', 'User\DoctorController::profileSettings');
    $routes->get('my-patients', 'MypatientsController::index');

    /**
     * Doctor Appointment Page And List
     */
    $routes->get('appointments', 'User\ClinicController::appointmentPage');
    $routes->post('doctor-appointment-list', 'User\PatientController::appointmentListOfDoctor');

    $routes->get('invoice', 'User\InvoiceController::index');
    $routes->get('message', 'User\MessageController::index');
    $routes->get('review', 'Home::reviewPage');

    $routes->get('accounts', 'User\AccountsController::index');
    $routes->get('active-blog', 'Blog\PostController::blog/1');
    $routes->get('pending-blog', 'Blog\PostController::blog/2');
    $routes->get('blog-add', 'Blog\PostController::addBlog');
    $routes->get('blog-edit/(:any)', 'Blog\PostController::editBlog/$1');
});

$routes->group("my_patients", ["namespace" => "App\Controllers", 'filter' => 'auth:1'], function ($routes) {
    $routes->post('patient_list', 'MypatientsController::patientList');
    $routes->get('mypatient-preview/(:any)', 'MypatientsController::mypatientPreview/$1');
    $routes->post('appoinments_list', 'MypatientsController::appoinmentsList');
    $routes->post('change_appointment_status', 'MypatientsController::changeAppointmentStatus');
    $routes->post('prescriptions_list', 'MypatientsController::prescriptionsList');
    $routes->get('print-prescription/(:any)', 'MypatientsController::printPrescription/$1');
    $routes->post('get_prescription_details', 'MypatientsController::get_prescription_details');
    $routes->post('get_prescription_details-v2', 'MypatientsController::getPrescriptionDetailsV2');

    $routes->get('edit-prescription/(:any)/(:any)', 'MypatientsController::editPrescription/$1/$2');
    $routes->post('insert_signature', 'MypatientsController::insertSignature');
    $routes->post('update_prescription', 'MypatientsController::updatePrescription');
    $routes->get('add-prescription/(:any)', 'MypatientsController::addPrescription/$1');
    $routes->post('save_prescription', 'MypatientsController::savePrescription');
    $routes->post('upload_medical_records', 'MypatientsController::uploadMedicalRecords');
    $routes->post('medical_records_list', 'MypatientsController::medicalRecordsList');
    $routes->post('view_dec', 'MypatientsController::viewDec');
    $routes->post('billing_list', 'MypatientsController::billingList');
    $routes->get('add-billing/(:any)', 'MypatientsController::addBilling/$1');
    $routes->post('save_billing', 'MypatientsController::saveBilling');
    $routes->post('get_billing_details', 'MypatientsController::getBillingDetails');
    $routes->get('edit-billing/(:any)/(:any)', 'MypatientsController::editBilling/$1/$2');
    $routes->post('update_billing', 'MypatientsController::updateBilling');
    $routes->get('print-billing/(:any)', 'MypatientsController::printBilling/$1');
    $routes->get('print-medical-records/(:any)', 'MypatientsController::printMedicalRecords/$1');
});

// Schedule Routes
$routes->group("schedule", ["namespace" => "App\Controllers", 'filter' => 'auth:1'], function ($routes) {
    $routes->get('/', 'User\ScheduleTimeController::index');
    //Day Slot List
    $routes->post('schedule-list', 'User\ScheduleTimeController::scheduleList');
    //Add Model
    $routes->post('get-slots', 'User\ScheduleTimeController::getSlots');
    //Edit Model 
    $routes->post('get-day-slots', 'User\ScheduleTimeController::getDaySlots');
    $routes->post('get-available-time-slots', 'User\ScheduleTimeController::getAvailableTimeSlots');
    $routes->post('add-schedule', 'User\ScheduleTimeController::scheduleAdd');
    $routes->post('update-schedule', 'User\ScheduleTimeController::scheduleUpdate');
});
$routes->post('delete-schedule-time', 'User\ScheduleTimeController::delete_schedule_time');

// Pharmacy Routes auth argument 0 / 1
$routes->group("pharmacy", ["namespace" => "App\Controllers", 'filter' => 'auth:1,pharmacy'], function ($routes) {
    $routes->get('/', 'User\PharmacyController::index');
    $routes->get('profile', 'User\PharmacyController::profileSettings');
    $routes->post('update-profile', 'User\PharmacyController::updateProfile');
    $routes->get('orders-list', 'User\PharmacyController::ordersList');
    $routes->post('orders-list', 'User\PharmacyController::ordersListDatatable');
    $routes->post('changeOrderStatus', 'User\PharmacyController::changeOrderStatus');
    $routes->get('invoice', 'User\InvoiceController::index');
    $routes->get('orders-details/(:any)', 'User\PharmacyController::ordersDetails/$1');
    $routes->get('accounts', 'User\AccountsController::index');
    $routes->get('product-list', 'User\PharmacyController::productList');
    $routes->post('product-list', 'User\PharmacyController::getProductsList');
    $routes->get('product-add', 'User\PharmacyController::productAdd');
    $routes->get('product-edit/(:any)', 'User\PharmacyController::productEdit/$1');
    $routes->post('image-delete', 'User\PharmacyController::deleteImage');
    $routes->post('create-product', 'User\PharmacyController::createProduct');
    $routes->post('update-product', 'User\PharmacyController::updateProduct');
    $routes->get('product-delete/(:any)', 'User\PharmacyController::deleteProduct/$1');
    $routes->post('update-status', 'User\PharmacyController::updateStatus');
});

// Lab Routes auth argument 0 / 1
$routes->group("lab", ["namespace" => "App\Controllers", 'filter' => 'auth:1,lab'], function ($routes) {
    $routes->get('/', 'User\LabController::index');
    $routes->get('profile', 'User\LabController::profileSettings');
    $routes->post('update-profile', 'User\LabController::updateProfile');
    $routes->get('lab-test', 'User\LabController::labTest');
    $routes->post('lab-list', 'User\LabController::labList');
    $routes->post('lab-test-save', 'User\LabController::labTestSave');
    $routes->get('lab-test-edit/(:num)', 'User\LabController::labTestEdit/$1');
    $routes->post('lab-test-delete', 'User\LabController::labTestDelete');
    $routes->post('lab-appointment-list', 'User\LabController::labAppointmentList');
    $routes->get('appointments', 'User\LabController::appointments');
    $routes->post('lab-appointment-details', 'User\LabController::labAppointmentDetails');
    $routes->post('stripe-payment', 'User\LabController::stripePayment', ['filter' => 'auth:1']);
    $routes->post('paypal-pay', 'User\LabController::stripePayment', ['filter' => 'auth:1']);

    $routes->post('change-appointment-status', 'User\LabController::changeAppointmentStatus');
    $routes->post('lab_upload_docs', 'User\LabController::labUploadDocs');

    $routes->get('invoice', 'User\InvoiceController::index');

    $routes->get('accounts', 'User\AccountsController::index');
});

//admin routes
$routes->get('admin', 'Admin\AdminController::index', ['filter' => 'adminauth:0']);
$routes->post('admin/login', 'Admin\AdminController::isValidLogin', ['filter' => 'adminauth:0']);
//Terms-conditions
$routes->get('termsandconditions', 'Admin\TermsConditionsController::index');
$routes->post('terms-update', 'Admin\TermsConditionsController::update');
$routes->post('change-language', 'Admin\TermsConditionsController::change_language');
$routes->get('terms-conditions', 'Home::getTermsConditions');
//privacy policy
$routes->get('privacypolicy', 'Admin\PrivacyPolicy::index');
$routes->post('privacypolicy-update', 'Admin\PrivacyPolicy::update');
$routes->post('privacypolicy-change-language', 'Admin\PrivacyPolicy::change_language');
$routes->get('privacy-policy', 'Home::getPrivacyPolicy');

$routes->group("admin", ["namespace" => "App\Controllers", 'filter' => 'adminauth:1'], function ($routes) {
    $routes->get('logout', 'Admin\AdminController::logout');
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->post('dashboard/revenue_graph', 'Admin\DashboardController::revenueGraph');
    $routes->post('dashboard/status_graph', 'Admin\DashboardController::statusGraph');
    $routes->get('specialization', 'Admin\SpecializationController::index');
    $routes->post('specialization-list', 'Admin\SpecializationController::specialization_list');
    $routes->post('create-specialization', 'Admin\SpecializationController::createSpecialization');
    $routes->get('specialization-edit/(:num)', 'Admin\SpecializationController::specializationEdit/$1');
    $routes->post('specialization-delete/(:num)', 'Admin\SpecializationController::specializationDelete/$1');
    $routes->get('patient-preview/(:any)', 'MypatientsController::mypatientPreview/$1');

    /* user routes */
    $routes->get('doctors', 'Admin\UserController::index');
    $routes->post('doctors-list', 'Admin\UserController::doctorsList');
    $routes->post('doctor/changeUsersStatus', 'Admin\UserController::changeUsersStatus');
    $routes->get('doctorpreview/(:any)', 'Admin\UserController::doctorPreview/$1');
    $routes->post('checkemail', 'Admin\UserController::checkEmail');
    $routes->post('signup', 'Admin\UserController::signup');
    $routes->get('patients', 'Admin\UserController::patients');
    $routes->post('patients_list', 'Admin\UserController::patientsList');
    $routes->get('users/clinic', 'Admin\UserController::clinic');
    $routes->post('users/clinic_list', 'Admin\UserController::clinicList');
    $routes->post('users/clinic_delete/(:num)', 'Admin\UserController::clinicDelete/$1');
    $routes->get('users/clinic_edit/(:num)', 'Admin\UserController::clinicEdit/$1');
    $routes->post('users/get_clinic_doctors/(:num)', 'Admin\UserController::getClinicDoctors/$1');
    $routes->post('users/add_clinic_doctor/', 'Admin\UserController::addClinicDoctor');
    $routes->get('clinic-preview/(:any)', 'Admin\UserController::clinicPreview/$1');
    $routes->get('users/labs', 'Admin\UserController::labs');
    $routes->post('users/lab_list_data', 'Admin\UserController::labListData');
    $routes->get('users/labtest-booked', 'Admin\UserController::labtestBooked');
    $routes->get('users/lab-tests', 'Admin\UserController::labTests');
    $routes->post('users/booked_labtest_list_data', 'Admin\UserController::bookedLabtestListData');
    $routes->post('users/lab_tests_list', 'Admin\UserController::labTestsList');
    $routes->get('users/pharmacies', 'Admin\UserController::pharmacies');
    $routes->post('users/pharmacies_list', 'Admin\UserController::pharmaciesList');
    $routes->post('users/update_pharmacy', 'Admin\UserController::updatePharmacy');
    $routes->get('users/orders', 'Admin\UserController::pharmacyOrders');
    $routes->post('users/orders_list', 'Admin\UserController::ordersList');

    //reviews
    //Dinesh
    $routes->get('review-page', 'Admin\ReviewController::index');
    $routes->post('reviews-list', 'Admin\ReviewController::reviews_list');
    $routes->post('reviews-delete', 'Admin\ReviewController::reviews_delete');
    $routes->get('admin-notification', 'Admin\ReviewController::adminNotificationPage');
    $routes->get('email-template', 'Admin\ReviewController::adminEmailTemplate');
    $routes->post('email-template-list', 'Admin\ReviewController::emailTemplateList');
    $routes->get('email-template-edit/(:any)', 'Admin\ReviewController::emailTemplateEdit/$1');
    $routes->post('template-edit/(:any)', 'Admin\ReviewController::edit/$1');
    $routes->post('admin-search-notification', 'Admin\ReviewController::adminSearchNotification');
    $routes->post('delete-notification', 'Admin\ReviewController::deleteNotification');
    $routes->post('update-notification', 'Admin\ReviewController::updateNotification');
    /* user routes */
    $routes->get('settings', 'Admin\SettingsController::index');
    $routes->post('settings_submit', 'Admin\SettingsController::settingsSubmit');
    $routes->get('cms', 'Admin\CmsController::index');
    $routes->post('cms_submit', 'Admin\CmsController::cmsSubmit');
    $routes->get('language', 'Admin\LanguageController::index');
    $routes->post('language/update_language_default', 'Admin\LanguageController::updateLanguageDefault');
    $routes->post('language/check_language', 'Admin\LanguageController::checkLanguage');
    $routes->post('language/check_language_value', 'Admin\LanguageController::checkLanguageValue');
    $routes->post('language/addlanguage', 'Admin\LanguageController::addLanguage');
    $routes->get('language/keywords', 'Admin\LanguageController::keywords');
    $routes->post('language/language_list', 'Admin\LanguageController::languageList');
    $routes->post('language/update_language', 'Admin\LanguageController::updateLanguage');
    $routes->get('language/add_keywords', 'Admin\LanguageController::addKeywords');
    $routes->post('language/add_keywords', 'Admin\LanguageController::addKeywords');
    $routes->get('language/pages', 'Admin\LanguageController::pages');
    $routes->get('language/addPage', 'Admin\LanguageController::addPage');
    $routes->post('language/addPage', 'Admin\LanguageController::addPage');
    $routes->get('language/pages/(:any)', 'Admin\LanguageController::appKeywords/$1');
    $routes->get('language/addAppKeywords/(:any)', 'Admin\LanguageController::addAppKeywords');
    $routes->post('language/addAppKeywords', 'Admin\LanguageController::addAppKeywords');
    $routes->post('language/appLanguageList', 'Admin\LanguageController::appLanguageList');
    $routes->post('language/update_language_status', 'Admin\LanguageController::updateLanguageStatus');
    $routes->post('language/updateAppLanguage', 'Admin\LanguageController::updateAppLanguage');
    $routes->get('profile', 'Admin\ProfileController::index');
    $routes->post('profile/check_currentpassword', 'Admin\ProfileController::checkCurrentpassword');
    $routes->post('profile/check_newpassword', 'Admin\ProfileController::checkNewpassword');
    $routes->post('profile/change_password', 'Admin\ProfileController::changePassword');
    $routes->post('profile/update_profile', 'Admin\ProfileController::updateProfile');
    $routes->post('profile/crop_profile_img', 'Admin\ProfileController::cropProfileImg');
    $routes->get('country', 'Admin\CountryController::country');
    $routes->post('country-add', 'Admin\CountryController::countryAdd');
    $routes->post('country/check_sortname', 'Admin\CountryController::checkSortname');
    $routes->post('country/check_country', 'Admin\CountryController::checkCountry');
    $routes->post('country/check_phonecode', 'Admin\CountryController::checkPhonecode');
    $routes->get('state', 'Admin\CountryController::state');
    $routes->post("country/state_list", "Admin\CountryController::stateList");
    $routes->post("country/state_insert", "Admin\CountryController::stateInsert");
    $routes->post("country/state_edit", "Admin\CountryController::stateEdit");
    $routes->post("country/state_update", "Admin\CountryController::stateUpdate");
    $routes->post("country/state_delete/(:any)", "Admin\CountryController::stateDelete/$1");
    $routes->get('city', 'Admin\CountryController::city');
    $routes->post('country/city_list', 'Admin\CountryController::cityList');
    $routes->post('country/city_edit', 'Admin\CountryController::cityEdit');
    $routes->post('country/city_update', 'Admin\CountryController::cityUpdate');
    $routes->post('country/city_insert', 'Admin\CountryController::cityInsert');
    $routes->post('country/city_delete/(:any)', 'Admin\CountryController::cityDelete/$1');
    $routes->get('categories', 'Blog\CategoryController::index');
    $routes->get('subcategories', 'Blog\SubcategoryController::index');
    $routes->get('products', 'Admin\ProductController::index');
    $routes->post('products/product_list', 'Admin\ProductController::productList');
    $routes->get('unit', 'Admin\UnitController::index');
    $routes->post('unit/unit_list', 'Admin\UnitController::unitList');
    $routes->post('unit/create_unit', 'Admin\UnitController::createUnit');
    $routes->get('unit/unit_edit/(:any)', 'Admin\UnitController::unitEdit/$1');
    $routes->post('unit/unit_delete/(:any)', 'Admin\UnitController::unitDelete/$1');
    $routes->get('products/categories', 'Admin\CategoryController::index');
    $routes->get('products/subcategories', 'Admin\SubcategoryController::index');
    $routes->post('categories/categories_list', 'Admin\CategoryController::categoriesList');
    $routes->post('categories/create_categories', 'Admin\CategoryController::createCategories');
    $routes->get('categories/categories_edit/(:any)', 'Admin\CategoryController::categoriesEdit/$1');
    $routes->post('categories/categories_delete/(:any)', 'Admin\CategoryController::categoriesDelete/$1');
    $routes->post('subcategories/subcategories_list', 'Admin\SubcategoryController::subcategoriesList');
    $routes->post('subcategories/create_subcategories', 'Admin\SubcategoryController::createSubcategories');
    $routes->get('subcategories/subcategories_edit/(:any)', 'Admin\SubcategoryController::subcategoriesEdit/$1');
    $routes->post('subcategories/subcategories_delete/(:any)', 'Admin\SubcategoryController::subcategoriesDelete/$1');
    $routes->post('pharmacy/get_product_subcategory/', 'Admin\ProductController::getProductSubcategory');
    $routes->post('products/image_upload/', 'Admin\ProductController::imageUpload');
    $routes->post('products/create_admin_products/', 'Admin\ProductController::createAdminProducts');
    $routes->get('products/edit_product_admin/(:any)', 'Admin\ProductController::editProductAdmin/$1');
    $routes->post('products/delete_image', 'Admin\ProductController::deleteImage');
    $routes->post('products/delete_product_admin/(:any)', 'Admin\ProductController::deleteProductAdmin/$1');
    $routes->get('promo', 'Admin\PromoController::index');
    $routes->post('add-promo', 'Admin\PromoController::promoAdd');
    $routes->post('edit-promo', 'Admin\PromoController::promoEdit');
    $routes->get('appointments', 'Admin\AppointmentController::index');
    $routes->post('appointments/appoinments_list', 'Admin\AppointmentController::appoinmentsList');
    $routes->post('appointments/upappoinments_list', 'Admin\AppointmentController::upappoinmentsList');
    $routes->post('appointments/missedappoinments_list', 'Admin\AppointmentController::missedappoinmentsList');

    $routes->get('pending-post', 'Blog\PostController::adminBlog/1');
    $routes->get('active-post', 'Blog\PostController::adminBlog/2');
    $routes->get('add-post', 'Blog\PostController::adminAddBlog');
    $routes->get('blog-edit/(:any)', 'Blog\PostController::adminEditBlog/$1');

    $routes->get('payment-request', 'Admin\DashboardController::paymentRequest');
    $routes->post('payment-request-list', 'Admin\DashboardController::paymentRequestList');
    $routes->post('payment-requests-status', 'Admin\DashboardController::paymentRequestStatus');
});

// Blog Routes auth argument 0 / 1
$routes->group("blog", ["namespace" => "App\Controllers", 'filter' => 'adminauth:1'], function ($routes) {
    $routes->post('categories/categories_list', 'Blog\CategoryController::categoriesList');
    $routes->post('categories/create_categories', 'Blog\CategoryController::createCategories');
    $routes->get('categories/categories_edit/(:any)', 'Blog\CategoryController::categoriesEdit/$1');
    $routes->post('categories/categories_delete/(:any)', 'Blog\CategoryController::categoriesDelete/$1');
    $routes->post('subcategories/subcategories_list', 'Admin\SubcategoryController::subcategoriesList');
    $routes->post('subcategories/create_subcategories', 'Admin\SubcategoryController::createSubcategories');
    $routes->get('subcategories/subcategories_edit/(:any)', 'Admin\SubcategoryController::subcategoriesEdit/$1');
    $routes->post('subcategories/subcategories_delete/(:any)', 'Admin\SubcategoryController::subcategoriesDelete/$1');

    $routes->post('blog_subcategories/subcategories_list', 'Blog\SubcategoryController::subcategoriesList');
    $routes->post('blog_subcategories/create_subcategories', 'Blog\SubcategoryController::createSubcategories');
    $routes->get('blog_subcategories/subcategories_edit/(:any)', 'Blog\SubcategoryController::subcategoriesEdit/$1');
    $routes->post('blog_subcategories/subcategories_delete/(:any)', 'Blog\SubcategoryController::subcategoriesDelete/$1');
});

// Ajax Call
$routes->group("ajax", ["namespace" => "App\Controllers"], function ($routes) {

    // Home Page Loading
    $routes->post("set-timezone", "AjaxController::setTimeZone");
    $routes->post("currency-rate", "AjaxController::currencyRate");
    $routes->post("update-user-status", "AjaxController::updateUserStatus");
    $routes->post("update-user-currency", "AjaxController::addUserCurrency");
    $routes->get("user-email-verify", "AjaxController::userEmailVerification");

    $routes->post('appointment-list', 'User\PatientController::myAppoinmentsList');
    $routes->post('prescriptions_list', 'MypatientsController::prescriptionsList');
    $routes->post('medical_records_list', 'MypatientsController::medicalRecordsList');
    $routes->post('billing_list', 'MypatientsController::billingList');

    $routes->get("get-country-code", "AjaxController::getCountryCode");
    $routes->get("get-specialization", "AjaxController::getSpecialization");
    $routes->post("check-email", "AjaxController::checkEmail");
    $routes->post("check-mobile-no", "AjaxController::checkMobNo");
    $routes->post("register-email", "AjaxController::registerEmail");

    $routes->get("get-country", "AjaxController::getCountry");
    $routes->post("get-state", "AjaxController::getState");
    $routes->post("get-city", "AjaxController::getCity");
    $routes->post("set-language", "AjaxController::setLanguage");

    $routes->post("deleteClinicImg", "AjaxController::deleteClinicImg");
    $routes->get("image", "AjaxController::image");
    $routes->post("delete-user", "AjaxController::deleteUser");

    // Test Routes
    $routes->get("sendMail", "AjaxController::sendMail");
    $routes->get("encrypt/(:any)", "AjaxController::encrypt/$1");
    $routes->get("get_country/(:any)", "AjaxController::getCountry/$1");
    $routes->get("get_category", "AjaxController::getCategory");
    $routes->get("get_subcategory/(:any)", "AjaxController::getSubCategory/$1");

    $routes->get("get_product_category", "AjaxController::getProductCategory");
    $routes->get("get_product_subcategory/(:any)", "AjaxController::getProductSubategory/$1");
    $routes->get("get_product_unit", "AjaxController::getProductUnit");

    // Clinic Doctor List
    $routes->get("get-clinic-doctors", "AjaxController::getHospitalDoctor");
    $routes->post("get-clinic-schedule-from-date", "User\BookAppointment::getClinicScheduleFromDate");
    $routes->post("clinic-assign-doctor", "AjaxController::clinicAssignDoctor");
    $routes->post("get_city_of_country", "AjaxController::getCityOfCountry");
    $routes->get("cart_insert", "AjaxController::cart");

    $routes->post("check-product-exists", "AjaxController::checkProductExists");
    $routes->post('orders-list', 'User\PharmacyController::ordersListDatatable');
    $routes->post('check-slug', 'AjaxController::checkBlogSlug');
});

//review routes
$routes->group("dashboard", ["namespace" => "App\Controllers", 'filter' => 'auth:1'], function ($routes) {
    $routes->get('reviews', 'User\DashboardController::reviews');
    $routes->post('delete_reply', 'User\DashboardController::deleteReply');
    $routes->post('add_review_reply', 'User\DashboardController::addReviewReply');
});

// API Mamo Pay
$routes->group('payment', function($routes){
    $routes->get('/', 'Payment\PaymentController::index');
    $routes->get('success/(:segment)', 'Payment\PaymentController::success/$1');
    $routes->get('failure/(:segment)', 'Payment\PaymentController::failure/$1');
    $routes->get('card/(:segment)', 'Payment\PaymentController::card/$1');
    $routes->post('pay', 'Payment\PaymentController::pay');
    $routes->post('callback', 'Payment\PaymentController::callback');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
