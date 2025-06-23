<?php

function Suffix($num)
{
  if (!in_array(($num % 100), array(11, 12, 13))) {
    switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
      case 1:
        return 'st';
      case 2:
        return 'nd';
      case 3:
        return 'rd';
    }
  }
  return 'th';
}

?>
<div class="modal-header">
  <h5 class="modal-title"><?php
                          /** @var array $language */
                          echo $language['lg_edit_time_slots']; ?></h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">×</span>
  </button>
</div>
<div class="modal-body">
  <form id="edit_schedule_form" name="edit_schedule_form" method="post" autocomplete="off">
    <input type="hidden" id="id_value" value="<?php
                                              /** @var array $edit */
                                              echo strtolower($edit[0]['day_name']); ?>">
    <input type="hidden" name="slot" id="slot" value="<?php echo $slot; ?>">
    <input type="hidden" name="day_id" id="day_id" value="<?php echo $edit[0]['day_id']; ?>">
    <input type="hidden" name="day_name" id="day_name" value="<?php echo $edit[0]['day_name']; ?>">
    <input type="hidden" id="slot_count" value="<?php echo count($edit); ?>">
    <div class="hours-info">


      <div class="col-12 col-md-11">
        <div class="form-group">
          <label><?php echo $language['lg_timing_slot_dur'] ?? ""; ?></label>
          <select class="select form-control" name="slots" id="slots">
            <option value=""><?php echo $language['lg_select8'] ?? ""; ?></option>
            <!--<option value="5" <?php if ($slot == '5') { echo "selected"; } ?>>5<?php echo $language['lg_mins'] ?? ""; ?></option>-->
            <option value="10" <?php if ($slot == '10') { echo "selected"; } ?>>10<?php echo $language['lg_mins'] ?? ""; ?></option>
            <option value="15" <?php if ($slot == '15') { echo "selected"; } ?>>15<?php echo $language['lg_mins'] ?? ""; ?></option>
            <option value="20" <?php if ($slot == '20') { echo "selected"; } ?>>20<?php echo $language['lg_mins'] ?? ""; ?></option>
            <!--<option value="30" <?php if ($slot == '30') { echo "selected"; } ?>>30<?php echo $language['lg_mins'] ?? ""; ?></option>
            <option value="45" <?php if ($slot == '45') { echo "selected"; } ?>>45<?php echo $language['lg_mins'] ?? ""; ?></option>
            <option value="1" <?php if ($slot == '1') { echo "selected"; } ?>>1<?php echo $language['lg_hour'] ?? ""; ?></option>-->
          </select>
        </div>
      </div>

      <?php
      $i = 1;
      /** @var array $edit */
      foreach ($edit as $rows) { ?>

        <div class="row form-row hours-cont" id="hours-cont_<?php echo $i; ?>">

          <div class="col-12 col-md-11">
            <h4 class="h4 text-center breadcrumb-bar px-2 py-1 mx-3 rounded text-white"><?php echo $i; ?><sup><?php echo Suffix($i); ?></sup> <?php echo $language['lg_session']; ?> </h4>
            <input type="hidden" name="sessions[]" id="sessions_<?php echo $i; ?>" value="<?php echo $i; ?>">
            <div class="row form-row mx-3">
              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label><?php echo $language['lg_start_time']; ?></label>
                  <input type="hidden" id="schedule_time_start_<?php echo $i; ?>" value="<?php echo $rows['start_time']; ?>">
                  <select class="form-control start_time" name="start_time[<?php echo $i; ?>]" onchange="get_end_time(<?php echo $i; ?>)" id="start_time_<?php echo $i; ?>">
                    <option value=""><?php echo $language['lg_select']; ?></option>
                  </select>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label><?php echo $language['lg_end_time']; ?></label>
                  <input type="hidden" id="schedule_time_end_<?php echo $i; ?>" value="<?php echo $rows['end_time']; ?>">
                  <select class="form-control end_time" name="end_time[<?php echo $i; ?>]" onchange="get_time_slot(<?php echo ($i + 1); ?>),get_tokens(<?php echo $i; ?>)" id="end_time_<?php echo $i; ?>">
                    <option value=""><?php echo $language['lg_select']; ?></option>
                  </select>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label><?php echo $language['lg_no_of_tokens']; ?></label>
                  <input type="text" class="form-control" value="<?php echo $rows['token']; ?>" id="token_<?php echo $i; ?>" name="token[<?php echo $i; ?>]" readonly="">
                </div>
              </div>
            </div>
          </div>
          <!-- <div id="remove_btn_2" class="col-12 col-md-1 slot-drash-btn"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="javascript:void(0)" onclick="remove_session(2)" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div> -->
          <?php if ($i != '0') {  ?>
            <div id="remove_btn_<?php echo $i; ?>" <?php echo ($i == count($edit)) ? 'style=display:block;' : 'style=display:none;'; ?> class="col-12 col-md-1 slot-drash-btn"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="javascript:void(0)" onclick="remove_session('<?php echo $i; ?>')" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>
          <?php } ?>
        </div>
      <?php $i++;
      } ?>
    </div>

    <div class="add-more mb-3 mx-3">
      <a href="javascript:void(0);" onclick="add_hours()" class="add-hours"><i class="fa fa-plus-circle"></i> <?php echo $language['lg_add_more']; ?></a>
    </div>
    <div class="submit-section text-center">
      <button type="submit" id="submit_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_save_changes']; ?></button>
    </div>
  </form>
</div>

<script type="text/javascript">
  <?php
  $j = 1;
  foreach ($edit as $rows) {

  ?>
    var slot = $('#slot').val();
    var sessions = $('#sessions_<?php echo $j; ?>').val();
    var id = '<?php echo $j; ?>';
    if (id == 1) {
      var end_time = "00:00:00";
    } else {
      var a = Number(id) - 1;
      var end_time = $('#schedule_time_end_<?php echo ($j - 1); ?>').val();
    }

    if (id == 1) {
      $.ajax({
        type: "POST",
        url: base_url + 'schedule/get-available-time-slots',
        data: {
          slot: $('#slots').val(),
          day_id: $('#day_id').val(),
          start_time: "00:00:00",
          sessions: sessions
        },
        beforeSend: function() {
          $("#start_time_<?php echo $j; ?> option:gt(0)").remove();
          $('#start_time_<?php echo $j; ?>').find("option:eq(0)").html("Please wait..");
        },
        success: function(data) {
          $('#start_time_<?php echo $j; ?>').find("option:eq(0)").html("Select time");
          var obj = jQuery.parseJSON(data);
          $(obj).each(function() {
            var option = $('<option />');
            if (this.added == true) {
              option.attr('value', this.value).text(this.label);
              option.attr('disabled', true);
              option.addClass('d-none');
            } else {
              option.attr('value', this.value).text(this.label);
            }
            $('#start_time_<?php echo $j; ?>').append(option);
          });
          var option_str = $('<option />');
          option_str.attr({
            value: $('#schedule_time_start_<?php echo $j; ?>').val(),
            selected: "selected"
          }).text($('#schedule_time_start_<?php echo $j; ?>').val());
          $('#start_time_<?php echo $j; ?>').append(option_str);
          $('#start_time_<?php echo $j; ?>').val($('#schedule_time_start_<?php echo $j; ?>').val());

        }
      });
    }

    if (id > 1 && slot != '') {
      console.log("start date :-> " + end_time);
      $.ajax({
        type: "POST",
        url: base_url + 'schedule/get-available-time-slots',
        data: {
          slot: $('#slots').val(),
          day_id: $('#day_id').val(),
          end_time: end_time,
          sessions: sessions
        },
        beforeSend: function() {
          $("#start_time_<?php echo $j; ?> option:gt(0)").remove();
          $('#start_time_<?php echo $j; ?>').find("option:eq(0)").html("Please wait..");
        },
        success: function(data) {
          $('#start_time_<?php echo $j; ?>').find("option:eq(0)").html("Select time");
          var obj = jQuery.parseJSON(data);
          $(obj).each(function() {
            var option = $('<option />');
            if (this.added == true) {
              option.attr('value', this.value).text(this.label);
              option.attr('disabled', true);
              option.addClass('d-none');
            } else {
              option.attr('value', this.value).text(this.label);
            }
            $('#start_time_<?php echo $j; ?>').append(option);
          });
          var option_str = $('<option />');
          option_str.attr({
            value: $('#schedule_time_start_<?php echo $j; ?>').val(),
            selected: "selected"
          }).text($('#schedule_time_start_<?php echo $j; ?>').val());
          $('#start_time_<?php echo $j; ?>').append(option_str);
          $('#start_time_<?php echo $j; ?>').val($('#schedule_time_start_<?php echo $j; ?>').val());

        }
      });
    } else {
      $("#start_time_<?php echo $j; ?> option:gt(0)").remove();

    }





    var slot = $('#slots').val();
    var start_time = $('#schedule_time_start_<?php echo $j; ?>').val();
    var sessions = $('#sessions_<?php echo $j; ?>').val();
    var lg_please_wait = '<?php echo $language["lg_please_wait"] ?>';
    var lg_select_time = '<?php echo $language["lg_select_time"] ?>';


    if (slot != '' && start_time != '') {
      console.log("end date :-> " + start_time);
      $.ajax({
        type: "POST",
        url: base_url + 'schedule/get-available-time-slots',
        data: {
          slot: slot,
          day_id: $('#day_id').val(),
          start_time: start_time
        },
        beforeSend: function() {
          //$('.overlay').show();          
          $("#end_time_<?php echo $j; ?> option:gt(0)").remove();
          $('#end_time_<?php echo $j; ?>').find("option:eq(0)").html(lg_please_wait);
        },
        success: function(data) {
          // $('.overlay').hide();              
          $('#end_time_<?php echo $j; ?>').find("option:eq(0)").html(lg_select_time);
          var obj = jQuery.parseJSON(data);
          $(obj).each(function() {
            var option = $('<option />');
            if (this.added == true) {
              option.attr('value', this.value).text(this.label);
              option.attr('disabled', true);
              option.addClass('d-none');
            } else {
              option.attr('value', this.value).text(this.label);
            }
            $('#end_time_<?php echo $j; ?>').append(option);
          });
          var option_end = $('<option />');
          option_end.attr({
            value: $('#schedule_time_end_<?php echo $j; ?>').val(),
            selected: "selected"
          }).text($('#schedule_time_end_<?php echo $j; ?>').val());
          $('#end_time_<?php echo $j; ?>').append(option_end);
          $('#end_time_<?php echo $j; ?>').val($('#schedule_time_end_<?php echo $j; ?>').val());
        }
      });

    }


  <?php $j++;
  } ?>
</script>