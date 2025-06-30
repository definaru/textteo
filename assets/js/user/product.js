if (modules == 'home' && pages == 'pharmacy_search_bydoctor') {
    $(document).on('click', '.pharmacy_profile_btn', function () {
        $("body").removeClass("modal-open");
        var pharmacy_id = $(this).data('pharmacy-id');
        $.ajax({
            //url:  base_url +'my_patients/get_phamacy_details',
            url: base_url + 'pharmacy-search',
            type: 'POST',
            data: { pharmacy_id: pharmacy_id },
            success: function (response) {
                // console.log(response);
                var obj = JSON.parse(response);
                //console.log(obj);
                if (obj.status === 200) {
                    if (obj.data.length >= 1) {
                        var html = '';
                        $(obj.data).each(function () {

                            var pharmacy_name = (this.pharmacy_name != '' && this.pharmacy_name != null) ? this.pharmacy_name : '';
                            var first_name = (this.first_name != '' && this.first_name != null) ? this.first_name : '';
                            var last_name = (this.last_name != '' && this.last_name != null) ? this.last_name : '';
                            var profileimage = (this.profileimage != '' && this.profileimage != null) ? this.profileimage : '';
                            var phonecode = (this.phonecode != '' && this.phonecode != null) ? this.phonecode : '';
                            var mobileno = (this.mobileno != '' && this.mobileno != null) ? this.mobileno : '';
                            var address1 = (this.address1 != '' && this.address1 != null) ? this.address1 : '';
                            var address2 = (this.address2 != '' && this.address2 != null) ? this.address2 : '';
                            var city = (this.city != '' && this.city != null) ? this.city : '';
                            var statename = (this.statename != '' && this.statename != null) ? this.statename : '';
                            var country = (this.country != '' && this.country != null) ? this.country : '';
                            var pharmacy_opens_at = (this.pharamcy_opens_at != '' && this.pharamcy_opens_at != null) ? this.pharamcy_opens_at : '';
                            var home_delivery = (this.home_delivery != '' && this.home_delivery != null) ? this.home_delivery : '';
                            var hrsopen = (this.hrsopen != '' && this.hrsopen != null) ? this.hrsopen : '';

                            html += '<div class="card-body">';
                            html += '<center><img src="' + base_url + profileimage + '" class="img-fluid" alt="' + pharmacy_name + '" title="' + pharmacy_name + '" ></center><br />';
                            html += '<table class="table table-bordered table-hover">';
                            html += '<tr>';
                            html += '<td>Pharmacy name</td>';
                            html += '<td>' + pharmacy_name + '</td>';
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td>User name</td>';
                            html += '<td>' + first_name + ' ' + last_name + '</td>';
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td>Mobile no</td>';
                            html += '<td>(+' + phonecode + ') ' + mobileno + '</td>';
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td>Address 1</td>';
                            html += '<td>' + address1 + '</td>';
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td>Address 2</td>';
                            html += '<td>' + address2 + '</td>';
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td>City</td>';
                            html += '<td>' + city + '</td>';
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td>State name</td>';
                            html += '<td>' + statename + '</td>';
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td>Country</td>';
                            html += '<td>' + country + '</td>';
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td>Pharmacy opens at</td>';
                            html += '<td>' + pharmacy_opens_at + '</td>';
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td>Home delivery avalable</td>';
                            html += '<td>' + home_delivery + '</td>';
                            html += '</tr>';
                            html += '<tr>';
                            html += '<td>24Hrs Open</td>';
                            html += '<td>' + hrsopen + '</td>';
                            html += '</tr>';
                            html += '</table>';
                            html += '</div>';

                        });

                        $(".view_pharmacy_details").html(html);
                    } else {
                        var html = '<p>' + lg_pharmacy_detail + '</p>';
                        $(".view_pharmacy_details").html(html);
                    }
                } else {
                    var html = '<p>' + lg_pharmacy_detail + '</p>';
                    $(".view_pharmacy_details").html(html);
                }
            }
        });
    });
}
