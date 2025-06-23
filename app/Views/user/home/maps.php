<?= $this->extend('user/layout/header'); ?>
<?= $this->section('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $language['lg_dashboard']??""; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_marketplace_map']??""; ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title"><?php echo $language['lg_marketplace_map']??""; ?></h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- /Page Content -->

<!--- ./end of Services Top Heading Content -->

<!-- Main content -->
<section class="content mt0 pt0">
    <div class="card no-border" style="box-shadow: none; border: 0px;">
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="card no-border;" style="box-shadow: none; border:0px;">
                    <div class="card-header no-border" data-background-color="graphite">
                        <div class="card-content no-border">
                            <h4 class="uppercase thin text-white"><?php echo $language['lg_dashboard_locat']??""; ?></h4>
                            <hr />
                            <p class="category pb16 text-white"><?php echo $language['lg_click_the_map_t']??""; ?></p>
                            <h4 class="uppercase thin text-white"><?php echo $language['lg_refresh_and_rem']??""; ?></h5>
                                <hr />
                                <p class="category pb8"><?php echo $language['lg_click_the_map_t1']??""; ?></p>
                                <form>
                                    <div class="row" id="floating-panel">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group label-floating">
                                                <button class="btn btn-primary bg-purple no-border" style="border-radius: 5px; font-size: 15px;" onclick="deleteMarkers();"><?php echo $language['lg_clear_markers']??""; ?></button>
                                            </div>
                                            <!-- /.form.group -->
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->
                                </form>
                                <!-- /.form -->
                        </div>
                        <!-- /.card.content -->
                        <div id="map" style="height: 600px; margin-top:0px;"></div>
                        <div id="pano" style="height: 600px;
			     margin-top:50px;
			     margin-bottom: 50px"></div>
                    </div>
                    <!-- /.card.header -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="card" style="background-color: #fff; border: 0px;">
                    <div class="card-header" data-background-color="graphite">
                        <h4 class="uppercase thin" style="color:#777;"><?php echo $language['lg_building_the_ne']??""; ?></h4>
                        <p class="category"><?php echo $language['lg_decrease_missed']??""; ?></p>
                    </div>
                    <div class="card-content" style="padding: 20px 20px;">
                        <form>
                            <h5 class="thin uppercase" style="color:#333;"><?php echo $language['lg_place_id_finder']??""; ?></h5>
                            <p class="category"><?php echo $language['lg_powered_by_goog']??""; ?></p>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label uppercase"><?php echo $language['lg_enter_a_locatio']??""; ?></label>
                                        <input type="text" class="form-control" id="pac-input" placeholder="<?php echo $language['lg_enter_a_locatio']??""; ?>">
                                    </div>
                                    <div id="infowindow-content">
                                        <span id="place-name" class="title"></span><br>
                                        <?php echo $language['lg_place_id1']??""; ?> <span id="place-id"></span><br>
                                        <span id="place-address"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr />
                    <div class="card-content" style="padding: 20px 20px; border: 0px;">
                        <form>
                            <pregion></pregion>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label uppercase"><?php echo $language['lg_enter_a_startin']??""; ?></label>
                                        <input type="text" class="form-control" id="origin-input" placeholder="<?php echo $language['lg_enter_a_startin']??""; ?>" value="<?php echo isset($from_address) ? $from_address : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group ">
                                        <label class="control-label uppercase"><?php echo $language['lg_enter_a_destina']??""; ?></label>
                                        <input type="text" class="form-control" id="destination-input" placeholder="<?php echo $language['lg_enter_a_destina']??""; ?>" value="<?php echo isset($to_address) ? $to_address : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group" id="mode-selector">
                                        <p class="category uppercase"><?php echo $language['lg_choose_mode_of_']??""; ?></p>
                                        <hr />
                                        <input type="radio" name="type" id="changemode-walking">
                                        <label for="changemode-walking">&nbsp;<?php echo $language['lg_walking']??""; ?></label>&nbsp;&nbsp;
                                        <input type="radio" name="type" id="changemode-transit">
                                        <label for="changemode-transit">&nbsp;<?php echo $language['lg_transit']??""; ?></label>&nbsp;&nbsp;
                                        <input type="radio" name="type" id="changemode-driving" checked="checked">
                                        <label for="changemode-driving">&nbsp;<?php echo $language['lg_driving']??""; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="card" style="background-color: #FFF; padding-bottom:20px; border: 0px;">
                    <div class="card-header" id="direction-panel" data-background-color="white">
                        <h4 class="uppercase thin"><?php echo $language['lg_location_and_di']??""; ?></h4>
                        <p class="category" style="color: #999;"><?php echo $language['lg_complete']??""; ?>
                            <b><?php echo $language['lg_directions_form']??""; ?></b> <?php echo $language['lg_to_view_directi']??""; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    var map;
    var panorama;

    function initMap() {
        var cityView = {
            lat: 25.7686937,
            lng: -80.1890184
        };
        var sv = new google.maps.StreetViewService();

        panorama = new google.maps.StreetViewPanorama(document.getElementById('pano'));

        map = new google.maps.Map(document.getElementById('map'), {
            center: cityView,
            zoom: 10,
            streetViewControl: false,

            styles: [{
                    elementType: 'geometry',
                    stylers: [{
                        color: '#232323'
                    }]
                },
                {
                    elementType: 'labels.text.stroke',
                    stylers: [{
                        color: '#232323'
                    }]
                },
                {
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#746855'
                    }]
                },
                {
                    featureType: 'administrative.locality',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#669933'
                    }]
                },
                {
                    featureType: 'poi',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#669933'
                    }]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#263c3f'
                    }]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#6b9a76'
                    }]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#38414e'
                    }]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry.stroke',
                    stylers: [{
                        color: '#212a37'
                    }]
                },
                {
                    featureType: 'road',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#9ca5b3'
                    }]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#746855'
                    }]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry.stroke',
                    stylers: [{
                        color: '#1f2835'
                    }]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#f3d19c'
                    }]
                },
                {
                    featureType: 'transit',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#2f3948'
                    }]
                },
                {
                    featureType: 'transit.station',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#d59563'
                    }]
                },
                {
                    featureType: 'water',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#151515'
                    }]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#515c6d'
                    }]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.stroke',
                    stylers: [{
                        color: '#333333'
                    }]
                }
            ]
        });

        new AutocompleteDirectionsHandler(map);

        sv.getPanorama({
            location: cityView,
            radius: 50
        }, processSVData);

        map.addListener('click', function(event) {
            sv.getPanorama({
                location: event.latLng,
                radius: 50
            }, processSVData);

        });

        var input = document.getElementById('pac-input');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);
        var marker = new google.maps.Marker({
            map: map
        });

        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });

        autocomplete.addListener('place_changed', function() {

            infowindow.close();
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(12);
            }

            // Set the position of the marker using the place ID and location.
            marker.setPlace({
                placeId: place.place_id,
                location: place.geometry.location
            });

            marker.setVisible(true);

            infowindowContent.children['place-name'].textContent = place.name;
            infowindowContent.children['place-id'].textContent = place.place_id;
            infowindowContent.children['place-address'].textContent =
                place.formatted_address;
            infowindow.open(map, marker);
            sv.getPanorama({
                location: place.geometry.location,
                radius: 50
            }, processSVData);
        });

        /*
         // This event listener will call addMarker() when the map is clicked.
         map.addListener('click', function(event) {
         addMarker(event.latLng);
         });

         // Adds a marker at the center of the map.
         addMarker(cityView);
         */
    }

    // Adds a marker to the map and push to the array.
    function addMarker(location) {
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });

        markers.push(marker);
    }

    function processSVData(data, status) {
        if (status === 'OK') {
            var marker = new google.maps.Marker({
                position: data.location.latLng,
                map: map,
                title: data.location.description
            });

            panorama.setPano(data.location.pano);
            panorama.setPov({
                heading: 270,
                pitch: 0
            });

            panorama.setVisible(true);

            marker.addListener('click', function() {
                var markerPanoID = data.location.pano;

                // Set the Pano to use the passed panoID.
                panorama.setPano(markerPanoID);
                panorama.setPov({
                    heading: 270,
                    pitch: 0
                });
                panorama.setVisible(true);
            });
        } else {
            console.error('Street View data not found for this location.');
        }
    }

    /**
     * @constructor
     */
    function AutocompleteDirectionsHandler(map) {
        this.map = map;
        this.originPlaceId = null;
        this.destinationPlaceId = null;
        this.travelMode = 'DRIVING';
        var originInput = document.getElementById('origin-input');
        var destinationInput = document.getElementById('destination-input');
        var modeSelector = document.getElementById('mode-selector');
        this.directionsService = new google.maps.DirectionsService;
        this.directionsDisplay = new google.maps.DirectionsRenderer;
        this.directionsDisplay.setMap(map);
        this.directionsDisplay.setPanel(document.getElementById('direction-panel'));

        var originAutocomplete = new google.maps.places.Autocomplete(
            originInput, {
                placeIdOnly: true
            });
        var destinationAutocomplete = new google.maps.places.Autocomplete(
            destinationInput, {
                placeIdOnly: true
            });

        this.setupClickListener('changemode-walking', 'WALKING');
        this.setupClickListener('changemode-transit', 'TRANSIT');
        this.setupClickListener('changemode-driving', 'DRIVING');

        this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
        this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');
        this.setupAutoPlaceListener(destinationAutocomplete);

        // this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
        // this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(destinationInput);
        // this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(modeSelector);
    }

    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        var start = document.getElementById('start').value;
        var end = document.getElementById('end').value;
        directionsService.route({
            origin: start,
            destination: end,
            travelMode: 'DRIVING'
        }, function(response, status) {
            if (status === 'OK') {
                directionsDisplay.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    }

    // Sets a listener on a radio button to change the filter type on Places
    // Autocomplete.

    AutocompleteDirectionsHandler.prototype.setupClickListener = function(id, mode) {
        var radioButton = document.getElementById(id);
        var me = this;
        radioButton.addEventListener('click', function() {
            me.travelMode = mode;
            me.route();
        });
    };

    AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
        var me = this;
        autocomplete.bindTo('bounds', this.map);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.place_id) {
                window.alert("Please select an option from the dropdown list.");
                return;
            }
            if (mode === 'ORIG') {
                me.originPlaceId = place.place_id;
            } else {
                me.destinationPlaceId = place.place_id;
            }
            me.route();
        });
    };

    AutocompleteDirectionsHandler.prototype.setupAutoPlaceListener = function(autocomplete) {
        var me = this;
        autocomplete.bindTo('bounds', this.map);
        geocoder = new google.maps.Geocoder();

        var from_address = document.getElementById('origin-input').value;
        var to_address = document.getElementById('destination-input').value;

        if (from_address) {
            //alert(from_address);
            geocoder.geocode({
                'address': from_address
            }, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {
                    //console.log(results);
                    if (results[0]) {
                        me.originPlaceId = results[0].place_id;
                        console.log(results[0].place_id);
                        if (to_address) {
                            geocoder.geocode({
                                'address': to_address
                            }, function(results, status) {

                                if (status == google.maps.GeocoderStatus.OK) {
                                    //var latitude = results[0].geometry.location.lat();
                                    // var longitude = results[0].geometry.location.lng();
                                    if (results[0]) {
                                        me.destinationPlaceId = results[0].place_id;
                                        console.log(results[0].place_id);
                                        me.route();
                                    }
                                    //alert(latitude);
                                }
                            });
                        }
                    }
                    //alert(latitude);
                }
            });
        }
    };

    AutocompleteDirectionsHandler.prototype.route = function() {
        if (!this.originPlaceId || !this.destinationPlaceId) {
            return;
        }

        var me = this;
        this.directionsService.route({
            origin: {
                'placeId': this.originPlaceId
            },
            destination: {
                'placeId': this.destinationPlaceId
            },
            travelMode: this.travelMode
        }, function(response, status) {
            if (status === 'OK') {
                me.directionsDisplay.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    };
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo !empty(settings("google_map_api")) ? libsodiumDecrypt(settings("google_map_api")) : ''; ?>&libraries=places&callback=initMap"></script>
<?= $this->endSection(); ?>