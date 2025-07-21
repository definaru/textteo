<?php
namespace App\Services;


class AppointmentsFormatterService
{
    protected $myPatientModel;
    protected $userModel;
    protected $language;

    public function __construct($myPatientModel, $userModel, $language)
    {
        $this->myPatientModel = $myPatientModel;
        $this->userModel = $userModel;
        $this->language = $language;
    }

    public function getFormattedAppointments(int $userId, int $start): array
    {
        $list = $this->myPatientModel->patientAppointments($userId);
        $data = [];
        $sno = $start + 1;
        $counter = 0;

        foreach ($list as $appointment) {
            $data[] = $this->formatAppointmentRow($appointment, $sno++, ++$counter);
        }
        return $data;
    }

    protected function formatAppointmentRow(array $appointment, int $sno, int $index): array
    {
        $row = [];
        $row[] = $sno;

        // Профиль
        $profileImage = base_url('assets/img/user.png');
        if (!empty($appointment['profileimage']) && is_file($appointment['profileimage'])) {
            $profileImage = base_url($appointment['profileimage']);
        }

        $row[] = $this->buildDoctorSection($appointment, $profileImage);
        $row[] = $this->buildDateTimeSlot($appointment);
        $row[] = $this->getPetName($appointment['pet_id'] ?? null);

        $row[] = $this->buildActionMenu($appointment);
        $row[] = base_url('uploads/pet_images/' . ($this->getPetData($appointment['pet_id'])['pet_photo'] ?? ''));
        $row[] = $this->getPetData($appointment['pet_id'])['pet_type'] ?? '-';
        $row[] = base_url('icons/Vector.svg');
        $row[] = date('d M Y', strtotime($appointment['created_date']));
        $row[] = ucfirst($appointment['type']);
        $row[] = $this->buildArchiveLink($appointment);
        $row[] = $this->buildAppointmentStatusDropdown($appointment);

        return $row;
    }

    protected function buildDoctorSection(array $a, string $profileImage): string
    {
        $username = encryptor_decryptor('encrypt', libsodiumDecrypt($a['clinic_username'] ?? $a['username']));
        $name = libsodiumDecrypt($a['clinic_first_name'] ?? $a['first_name']);
        $specialization = ucfirst(libsodiumDecrypt($a['specialization']));
        $drPrefix = ($a['role'] == 1 || isset($a['clinic_first_name'])) ? $this->language['lg_dr'] : '';

        return view('partials/appointment_doctor_info', [
            'username' => $username,
            'name' => $name,
            'specialization' => $specialization,
            'dr' => $drPrefix,
            'profileImage' => $profileImage,
        ]);
    }

    protected function buildDateTimeSlot(array $a): string
    {
        if (!empty($a['time_zone'])) {
            $from = $a['from_date_time'];
            //$fromTz = $a['time_zone'];
            //$toTz = date_default_timezone_get();
            //$converted = converToTz($a['to_date_time'], $toTz, $fromTz);
            return date('d M Y', strtotime($from)) . 
                '<span class="d-block text-info">Starts at ' . 
                date('h:i A', strtotime($from)) . 
                '</span>';
        }
        return '-';
    }

    protected function getPetName(?string $petId): string
    {
        if (!$petId) return '-';
        $pet = $this->userModel->getPetById($petId);
        return $pet['pet_name'] ?? '-';
    }

    protected function getPetData(?string $petId): array
    {
        if (!$petId) return [];
        return $this->userModel->getPetById($petId) ?? [];
    }

    protected function buildActionMenu(array $a): string
    {
        return view('partials/appointment_menu', [
            'appointment' => $a,
            'dateSlot' => !empty($a['from_date_time']) ? date('Y-m-d', strtotime($a['from_date_time'])) : date('Y-m-d'),
            'timeSlot' => !empty($a['from_date_time']) ? date('h:i A', strtotime($a['from_date_time'])) : '-',
        ]);
    }

    protected function buildArchiveLink(array $a): string
    {
        $id = $a['tokbox_archive_id'];
        if (!empty($id)) {
            return '<a href="/ajax.php?archiveId='.$id.'" target="_blank">archive link</a>';
        }
        return '-';
    }

    protected function buildAppointmentStatusDropdown(array $a): string
    {
        if ($a['approved'] != 1 || $a['type'] === 'Online') {
            return '-';
        }

        $status = $a['appointment_status'];
        $callStatus = $a['call_status'];
        if ($callStatus == 0 && $status != 2) {
            return view('partials/appointment_status_dropdown', [
                'id' => $a['id'],
                'selected' => $status,
            ]);
        }

        return match ($status) {
            1 => 'Completed',
            2 => 'Expired',
            default => '-',
        };
    }
}