<?php
namespace App\Data;

class Sitebar
{

    public static function menu()
    {
        $role = session('role');
        $module = session('module');
        $base = base_url();
        $user_detail = user_detail(session('user_id'));

        return [
            [
                'show' => $role == '2',
                'href' => $base.'search-veterinary?type=1',
                'icon' => 'fas fa-calendar-check',
                'label' => "Book Appointments",
                'slug' => ['searchDoctor'],
                'count' => null,
                'extra' => ''
            ],
            [
                'show' => true,
                'href' => "/$module",
                'icon' => 'fas fa-th-large',
                'label' => $language['lg_dashboard'] ?? 'Dashboard',
                'slug' => [
                    'doctor_dashboard', 
                    'patientDashboard', 
                    'lab_dashboard', 
                    'pharmacyDashboard'
                ],
                'count' => null,
                'extra' => ''
            ],
            [
                'show' => true,
                'href' => "/$module/profile",
                'icon' => 'fas fa-user',
                'label' => "Patient Card",
                'slug' => ['profile'],
                'count' => null,
                'extra' => ''
            ],
            [
                'show' => $role == '4',
                'href' => "$base$module/lab-test",
                'icon' => 'fas fa-calendar-check',
                'label' => $language['lg_lab_tests'] ?? "Lab Tests",
                'slug' => ['lab_tests'],
                'count' => null,
                'extra' => ''
            ],
            [
                'show' => $role != '5' && $role != '2',
                'href' => "$base$module/appointments",
                'icon' => 'fas fa-calendar-check',
                'label' => 'Upcoming Appointments',
                'slug' => ['appoinments'],
                'count' => null,
                'extra' => ''
            ],
            [
                'show' => $role == '6' || $role == '1',
                'href' => "$base$module/my-patients",
                'icon' => 'fas fa-user-injured',
                'label' => $language['lg_my_patients'] ?? "",
                'slug' => ['my_patients'],
                'count' => null,
                'extra' => ''
            ], 
            [
                'show' => $role == '6' || $role == '1',
                'href' => $base.'schedule',
                'icon' => 'fas fa-hourglass-start',
                'label' => $language['lg_schedule_timing'] ?? "",
                'slug' => ['scheduleTime'],
                'count' => null,
                'extra' => ''
            ],
            [
                'show' => true,
                'href' => "$base$module/invoice",
                'icon' => 'fas fa-file-invoice',
                'label' => $language['lg_invoice'] ?? "Invoice",
                'slug' => ['invoice'],
                'count' => null,
                'extra' => ''
            ],
            [
                'show' => $user_detail['hospital_id'] == 0 && $role != '2',
                'href' => "$base$module/accounts",
                'icon' => 'fas fa-address-card',
                'label' => $language['lg_accounts'] ?? "Accounts",
                'slug' => ['accounts'],
                'count' => null,
                'extra' => ''
            ], 
            [
                'show' => $role == '6',
                'href' => "$base$module/doctor",
                'icon' => 'fas fa-user-md',
                'label' => 'Add Veterinary',
                'slug' => ['doctorList'],
                'count' => null,
                'extra' => ''
            ], 
            [
                'show' => $role == '6' || $role == '1',
                'href' => "$base$module/review",
                'icon' => 'fas fa-star',
                'label' => $language['lg_reviews'] ?? '',
                'slug' => ['review'],
                'count' => null,
                'extra' => ''
            ],
            [
                'show' => $role == '6' || $role == '1',
                'href' => "$base$module/message",
                'icon' => 'fas fa-comments',
                'label' => $language['lg_messages'] ?? "",
                'slug' => [''],
                'count' => 0,
                'extra' => ''
            ],
            [
                'show' => $role != '2',
                'href' => "$base$module/profile",
                'icon' => 'fas fa-user-cog',
                'label' => $language['lg_profile_setting'] ?? 'Profile Settings',
                'slug' => ['profile'],
                'count' => null,
                'extra' => ''
            ],
            [
                'show' => $role != '2',
                'href' => "$base/change-password",
                'icon' => 'fas fa-lock',
                'label' => $language['lg_change_password'] ?? 'Change Password',
                'slug' => ['change-password'],
                'count' => null,
                'extra' => ''
            ],
            [
                'show' => true,
                'href' => "javascript:void(0);",
                'icon' => 'fas fa-sign-out-alt',
                'label' => $language['lg_signout'] ?? 'Signout',
                'slug' => [''],
                'count' => null,
                'extra' => 'id="signOutBtn" data-toggle="modal" data-target="#signoutBtnModal"',
            ]
        ];
    }
}