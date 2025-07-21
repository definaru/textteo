<?php

namespace App\Services;

use App\Models\HomeModel;
use App\Models\LabPaymentModel;

class LabAppointmentService
{
    protected $homeModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->homeModel = new HomeModel();
        $this->paymentModel = new LabPaymentModel();
    }

    public function getAppointmentsTableData($userId, $start)
    {
        $list = $this->paymentModel->getLabAppointmentDetails($userId);
        $data = [];
        $no = $start;
        $a = $no + 1;

        foreach ($list as $appointment) {
            $data[] = $this->formatAppointmentRow($appointment, $a++);
        }

        return $data;
    }

    protected function formatAppointmentRow($appointment, $index)
    {
        $status = $appointment['payment_status'] == '1' ? 'Success' : 'Failed';
        $profileImage = $this->getProfileImage($appointment['profileimage']);

        $fullName = libsodiumDecrypt($appointment['first_name']) . " " . libsodiumDecrypt($appointment['last_name']);

        $userCurrency = get_user_currency();
        $currencyCode = $userCurrency['user_currency_code'] ?? $appointment['currency_code'];
        $rateSymbol = currency_code_sign($currencyCode);

        $amount = get_doccure_currency($appointment['total_amount'], $appointment['currency_code'], $currencyCode);

        $testNames = $this->getTestNames($appointment['test_ids']);

        return [
            $index,
            '<h2 class="table-avatar">
                <a target="_blank" href="#" class="avatar avatar-sm mr-2">
                <img class="avatar-img rounded-circle" src="' . $profileImage . '" alt="User Image">
                </a>
                <a target="_blank" href="#">' . $fullName . '</a>
            </h2>',
            $testNames,
            date('d M Y', strtotime($appointment['lab_test_date'])),
            $rateSymbol . $amount,
            date('d M Y', strtotime($appointment['payment_date'])),
            $status,
            $appointment['cancel_status'],
            '<a class="btn btn-sm bg-success-light" onclick="view_docs(' . $appointment['id'] . ')" href="javascript:void(0)">
                <i class="fe fe-eye"></i> View Document
            </a>'
        ];
    }

    protected function getProfileImage($path)
    {
        return (empty($path) || !is_file(FCPATH . $path))
            ? base_url('assets/img/user.png')
            : base_url($path);
    }

    protected function getTestNames($testIds)
    {
        $names = [];
        $ids = explode(',', $testIds);
        foreach ($ids as $id) {
            $test = $this->homeModel->getTblRowOfData('lab_tests', ['id' => $id], '*');
            if ($test) {
                $names[] = libsodiumDecrypt($test['lab_test_name']);
            }
        }
        return implode(', ', $names);
    }
}