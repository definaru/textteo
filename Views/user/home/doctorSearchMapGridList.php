<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<?php
// $user_detail = user_detail(session('user_id'));
// $user_profile_image = (!empty($user_detail['profileimage'])) ? base_url() . $user_detail['profileimage'] : base_url() . 'assets/img/user.png';
?>
<style>
    .multiselect-container {
        width: 100%;
        height: 250px;
        min-height: 250px;
        overflow: auto;
    }

    .multiselect {
        text-align: left;
        border: 0;
        font-size: 15px;
        font-weight: 400;
        font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
        /* border: 1px solid #dcdcdc; */
        height: 46px;
        background-color: #f2f2f2;
        color: #777;
    }

    .btn-group {
        width: 100%;
    }

    .dropdown-toggle::after {
        float: right;
        position: relative;
        top: 5px;
        color: #222;
        border-top: 0;
        border-left: 0;
        border-bottom: 2px solid #757575;
        border-right: 2px solid #757575;
        content: '';
        height: 8px;
        display: inline-block;
        pointer-events: none;
        -webkit-transform-origin: 66% 66%;
        -ms-transform-origin: 66% 66%;
        transform-origin: 66% 66%;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
        -webkit-transition: all 0.15s ease-in-out;
        transition: all 0.15s ease-in-out;
        width: 8px;
        vertical-align: 2px;
        margin-left: 10px;
    }
</style>


<!-- <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_QD2_rlwEFGhCK0oj2n6cixsvX0D3zgk&callback=initAutocomplete&libraries=places&v=weekly"
    defer>
</script> -->

<script>
    let placeSearch;
    let autocomplete;
    const componentForm = {
        //street_number: "short_name",
        //route: "long_name",
        locality: "long_name",
        administrative_area_level_1: "long_name",
        country: "long_name",
        postal_code: "short_name",
    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search predictions to
        // geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById("search_location"), {
                types: ["geocode"]
            }
        );
        // Avoid paying for data that you don't need by restricting the set of
        // place fields that are returned to just the address components.
        autocomplete.setFields(["address_component"]);
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();

        for (const component in componentForm) {
            //console.log(component);
            document.getElementById(component).value = "";
            document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        console.log(place.address_components);
        for (const component of place.address_components) {
            const addressType = component.types[0];
            //console.log(componentForm[addressType]);
            if (componentForm[addressType]) {
                const val = component[componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
        var address = $.trim($('#search_location').val());
        get_lat_long(address);
    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                const circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy,
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }

    function locate() {
        geocoder = new google.maps.Geocoder();
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var currentLatitude = position.coords.latitude;
                var currentLongitude = position.coords.longitude;

                var infoWindowHTML = "Latitude: " + currentLatitude + "<br>Longitude: " + currentLongitude;
                var infoWindow = new google.maps.InfoWindow({
                    map: map,
                    content: infoWindowHTML
                });
                var currentLocation = {
                    lat: currentLatitude,
                    lng: currentLongitude
                };
                infoWindow.setPosition(currentLocation);
                var lat = currentLocation.lat;
                var lng = currentLocation.lng;
                //lat = '34.052235';
                //lng = '-118.243683';
                $('#s_lat').val(lat);
                $('#s_long').val(lng);
                var latlng = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                };
                //alert(lat + '---' + lng);
                geocoder.geocode({
                    location: latlng
                }, function(results, status) {
                    var res = (results[0].formatted_address);
                    //var arr = res.split(",");
                    //console.log(res);
                    //console.log(arr.slice(-1).pop().toLowerCase());
                    //var country = arr.slice(-1).pop().toLowerCase();
                    $('#search_location').val(res);
                });

            });
        }
    }

    function get_lat_long(address) {
        //alert(address);
        geocoder = new google.maps.Geocoder();

        geocoder.geocode({
            'address': address
        }, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                var latitude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();
                $('#s_lat').val(latitude);
                $('#s_long').val(longitude);
                //alert(latitude);
            }
        });
    }
    initAutocomplete();
</script>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php
                                                                                        /** @var array $language  */
                                                                                        echo $language['lg_home']??""; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_doctor_search']??""; ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title search-results"><?php echo $language['lg_doctor_search_f']??""; ?></h2>
            </div>
            <div class="col-md-4 col-12 d-md-block d-none">
                <div class="sort-by">
                    <span class="sort-title"><?php echo $language['lg_sort_by']??""; ?></span>
                    <span class="sortby-fliter">
                        <select class="select form-control" id="orderby" onchange="search_doctor(0)">
                            <option value=""><?php echo $language['lg_select']??""; ?></option>
                            <!--<option class="sorting" value="Rating"><?php //echo $language['lg_rating']??"";           
                                                                        ?></option>-->
                            <option class="sorting" value="Popular"><?php echo $language['lg_popular']??""; ?></option>
                            <option class="sorting" value="Latest"><?php echo $language['lg_latest']??""; ?></option>
                            <!--<option class="sorting" value="Free"><?php //echo $language['lg_free']??"";           
                                                                        ?></option>-->
                        </select>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ./ end of Breadcrumb -->

<div class="content">

    <div class="container-fluid">

        <div class="card filter-card">
            <div class="card-body pb-0">

                <div class="row mb-3">
                    <div class="col">
                        <h4><?php echo $language['lg_doctor_search_f']??""; ?> </h4>
                    </div>
                    <div class="col-auto">
                        <a href="javascript:void(0);" onclick="reset_doctor()" class="text-danger">
                            <?php echo $language['lg_reset']??""; ?>
                        </a>
                    </div>
                </div>

                <form id="search_doctor_form">
                    <div class="row filter-row">
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label><?php echo $language['lg_search_by_locat']??""; ?></label>
                                <input type="hidden" class="form-control filter-form ft-search" name="role" value="<?php echo !empty($role) ? $role : ''; ?>" autocomplete="off" id="role">
                                <input type="text" class="form-control" autocomplete="off" id="search_location" name="location" placeholder="<?php echo $language['lg_enter_location']??"" ?>" value="<?php echo isset($search_data['s_location']) && trim($search_data['s_location']) != '' ? $search_data['s_location'] : '' ?>">
                                <input type="hidden" name="s_country" id="country" value="<?php echo isset($search_data['s_country']) && trim($search_data['s_country']) != '' ? $search_data['s_country'] : '' ?>">
                                <input type="hidden" name="s_state" id="administrative_area_level_1" value="<?php echo isset($search_data['s_state']) && trim($search_data['s_state']) != '' ? $search_data['s_state'] : '' ?>">
                                <input type="hidden" name="s_locality" id="locality" value="<?php echo isset($search_data['s_locality']) && trim($search_data['s_locality']) != '' ? $search_data['s_locality'] : '' ?>">
                                <input type="hidden" name="s_postal_code" id="postal_code" value="<?php echo isset($search_data['s_postal_code']) && trim($search_data['s_postal_code']) != '' ? $search_data['s_postal_code'] : '' ?>">
                                <input type="hidden" name="s_lat" id="s_lat" value="<?php echo isset($search_data['s_lat']) && trim($search_data['s_lat']) != '' ? $search_data['s_lat'] : '' ?>">
                                <input type="hidden" name="s_long" id="s_long" value="<?php echo isset($search_data['s_long']) && trim($search_data['s_long']) != '' ? $search_data['s_long'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label><?php echo $language['lg_search_by_docto']??""; ?></label>
                                <input type="text" class="form-control uppercase text-muted" autocomplete="off" id="search_keywords" name="keywords" placeholder="<?php echo $language['lg_enter_doctor_ty']??"" ?>" value="<?php echo isset($search_data['keywords']) && trim($search_data['keywords']) != '' ? $search_data['keywords'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label><?php echo $language['lg_search_radius']??""; ?></label>
                                <select class="form-control uppercase" name="search_radius" id="search_radius">
                                    <option value=""><?php echo $language['lg_radius']??""; ?></option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label><?php echo $language['lg_search_units']??""; ?></label>
                                <select class="form-control uppercase" name="s_unit" id="s_unit">
                                    <option value=""><?php echo $language['lg_units']??""; ?></option>
                                    <option value="Mi"><?php echo $language['lg_miles']??""; ?></option>
                                    <option value="Km"><?php echo $language['lg_km']??""; ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label><?php echo $language['lg_select_speciali']??""; ?></label>
                                <select class="form-control" id="services" name="services[]" multiple="multiple">
                                    <?php
                                    if (!empty($specialization)) {
                                        foreach ($specialization as $rows) {
                                    ?>
                                            <option value="<?php echo $rows['id']; ?>">
                                                <?php echo libsodiumDecrypt($rows['specialization']); ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!--<div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label><?php //echo $language['lg_gender']??""; ?></label>
                                <select class="form-control" name="gender[]" id="gender" multiple="multiple">
                                    <option value="Male"><?php //echo $language['lg_male']??""; ?></option>
                                    <option value="Female"><?php //echo $language['lg_female']??""; ?></option>
                                </select>
                            </div>
                        </div>-->
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary btn-block" type="button" onclick="search_doctor(0)" id="doctor-search"><?php echo $language['lg_search3']??""; ?></button>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-7 col-xl-6 ">
                <input type="hidden" name="page" id="page_no_hidden" value="1">
                <div id="doctor-list"></div>
                <div class="load-more text-center d-none" id="load_more_btn">
                    <a class="btn btn-primary btn-sm" href="javascript:void(0);">
                        <?php echo $language['lg_load_more']??""; ?>
                    </a>
                </div>
            </div>

            <div class="col-md-12 col-lg-5 col-xl-6 theiaStickySidebar">
                <div id="map" class="map-listing mt24 pt24" style="height: 100vh;"></div>
            </div>

        </div>
    </div>

</div>


<script type="text/javascript">
    var country = '';
    var country_code = '';
    var state = '';
    var city = '';
    var citys = '<?php
                    /** @var string $city  */
                    echo $city; ?>';
    var specialization = '';
    var lang = '';
    var ethnicity = '';
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo 'AIzaSyA_QD2_rlwEFGhCK0oj2n6cixsvX0D3zgk' ? libsodiumDecrypt(settings("google_map_api")) : ''; ?>&callback=initAutocomplete&libraries=places&v=weekly"></script>
<script src="assets/js/map.js"></script>

<?php $this->endSection(); ?>