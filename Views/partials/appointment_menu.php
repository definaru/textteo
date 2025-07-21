<?php 
    $appointmentId = esc($appointment['id']);
    $doctorId = esc($appointment['hospital_id']);
?>
<div class="dropdown">
    <button onclick="toggleDropdown(this)" class="btn p-0 border-0 bg-transparent">&#x22EE;</button>
    <div class="dropdown-menu">
        <a class="dropdown-item" 
           id="appointment-edit-id" 
           data-time="<?= esc($timeSlot) ?>" 
           data-date="<?= esc($dateSlot) ?>" 
           data-doctor-id="<?= esc($appointment['hospital_id']) ?>" 
           data-appointment-id="<?=$appointmentId;?>" 
           href="javascript:void(0);"
        >
            Edit Appointment
        </a>
        <a class="dropdown-item" href="#">
            Cancel Appointment
        </a>
    </div>
</div>
<a href="<?=base_url("patient/appointment/$appointmentId");?>" class="app_details"> &gt; </a>

<div class="modal fade" id="editAppointmentModal-<?=$appointmentId;?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="slot-container">
                <h2 class="title-slot">Select a slot</h2>
                <div class="slot-header">
                    <input 
                        type="date" 
                        name="schedule_date"
                        data-doctor-id="<?=$doctorId?>"
                        data-appointment-id="<?=$appointmentId;?>"
                        id="schedule_date"
                        value="<?=esc($dateSlot);?>"
                        min="<?=date('Y-m-d');?>"
                    />
                </div>
                <div class="slots-doctor-day-<?=$appointmentId;?>"></div>
                <div class="apt-btn-div">
                    <button
                        data-appointment-id="<?=$appointmentId;?>"
                        id="edit-appointment-btn" 
                        class="btn btn-default apt-btn w-100"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>