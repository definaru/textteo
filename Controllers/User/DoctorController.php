<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\CommonModel;
use App\Models\UserModel;

class DoctorController extends BaseController
{
    public mixed $uri;
    public mixed $data;
    public mixed $timezone;
    public mixed $lang;
    public mixed $language;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    /**
     * @var \App\Models\UserModel
     */
    public $userModel;
    /**
     * @var \App\Models\AppointmentModel
     */
    public $appointmentModel;

    public function __construct()
    {
        $this->data['theme'] = 'user';
        $this->data['module'] = 'doctor';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->data['url_segment1'] = service('uri')->getSegment(1);
        $this->data['url_segment2'] = service('uri')->getSegment(2);
        $this->timezone = session('time_zone');
        if (!empty($this->timezone)) {
            date_default_timezone_set($this->timezone);
        }
        $this->data['uri'] = service('uri');

        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        $this->commonModel = new CommonModel();
        $this->userModel = new UserModel();
        $this->appointmentModel = new AppointmentModel();
    }
    /**
     * Doctor Dashboard Page.
     * 
     * @return mixed
     */
    public function index()
    {
        $user_id = session('user_id');
        $this->data['page'] = 'doctor_dashboard';
        $this->data['total_patient'] = $this->appointmentModel->getTotalPatient($user_id);
        $this->data['today_patient'] = $this->appointmentModel->getTodayPatient($user_id);
        $this->data['recent'] = $this->commonModel->countTblResult('payments', array('doctor_id' => $user_id));
        return view('user/doctor/doctorDashboard', $this->data);
    }
    /**
     * Clinic Profile Page.
     * 
     * @return mixed
     */
    public function profileSettings()
    {
        $this->data['page'] = 'profile';
        $this->data['profile'] = $this->userModel->getUserDetails(session('user_id'));
        $this->data['user_detail'] = $this->userModel->getUserDetails(session('user_id'));
        $where = array('user_id' => session('user_id'));
        $this->data['clinic_images'] = $this->commonModel->getTblResultOfData('clinic_images', $where, 'clinic_image,id,user_id');
        $this->data['education'] = $this->commonModel->getTblResultOfData('education_details', $where, '*');
        $this->data['experience'] = $this->commonModel->getTblResultOfData('experience_details', $where, '*');
        $this->data['awards'] = $this->commonModel->getTblResultOfData('awards_details', $where, '*');
        $this->data['memberships'] = $this->commonModel->getTblResultOfData('memberships_details', $where, '*');
        $this->data['registrations'] = $this->commonModel->getTblResultOfData('registrations_details', $where, '*');
        $this->data['clinic_images'] = $this->commonModel->getTblResultOfData('clinic_images', $where, '*');
        $this->data['business_hours'] = $this->commonModel->getTblResultOfData('business_hours', $where, '*');

        return view('user/clinic/clinicProfile', $this->data);
    }
}
