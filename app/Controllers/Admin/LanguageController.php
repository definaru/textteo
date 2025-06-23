<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\LanguageModel;
use App\Models\SettingsModel;
use App\Models\UserModel;

class LanguageController extends BaseController
{

    public $data;
    public $db;
    public $session;
    /**
     * @var \App\Models\UserModel
     */
    public $userModel;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    public $language;
    /**
     * @var \App\Models\SettingsModel
     */
    public $settingsModel;
    /**
     * @var \App\Models\LanguageModel
     */
    public $languageModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'language';
        $this->data['page'] = '';

        $lan = helper('default_language');
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        //Define Model
        $this->userModel = new UserModel();
        $this->commonModel = new CommonModel();
        $this->settingsModel = new SettingsModel();
        $this->languageModel = new LanguageModel();

        $this->db = \Config\Database::connect();
    }
    /**
     * load language page.
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        $this->data['list'] = $this->languageModel->languagesList();
        echo view('admin/language/index', $this->data);
    }
    /**
     * Update Language Default.
     *
     * @return mixed
     */
    public function updateLanguageDefault()
    {
        $id = $this->request->getPost('id');
        $data = $this->commonModel->getTblResultOfData('language', array('id' => $id, 'status' => 1), '*');

        if (!empty($data)) {
            $data = $this->commonModel->updateData('language', array('default_language' => 1), array('default_language' => 0));
            $data = $this->commonModel->updateData('language', array('id' => $id), array('default_language' => 1));
            echo "1";
        } else {
            echo "0";
        }
    }
    /**
     * Check Language.
     *
     * @return mixed
     */
    public function checkLanguage()
    {
        $language = $this->request->getPost('language');
        $result = $this->commonModel->getTblRowOfData('language', array('language' => $language), 'id,language');
        if ($result > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    /**
     * Check Language Value.
     *
     * @return mixed
     */
    public function checkLanguageValue()
    {
        $language_value = $this->request->getPost('language_value');
        $result = $this->commonModel->getTblRowOfData('language', array('language_value' => $language_value), 'id,language_value');
        if ($result > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    /**
     *Add Language.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function addLanguage()
    {
        $where = array();
        $where['tag'] = $this->request->getPost('tag');
        $where['language'] = $this->request->getPost('language');
        $where['language_value'] = $this->request->getPost('language_value');
        $record = $this->commonModel->countTblResult('language', $where);
        if ($record >= 1) {
            session()->setFlashdata('message', 'Already exists');
            redirect(base_url('admin/language'));
        } else {

            $data = array(
                'language_value' => trim($this->request->getPost('language_value') ?? ""),
                'language' => trim($this->request->getPost('language') ?? ""),
                'tag' => trim($this->request->getPost('tag') ?? ""),
                'status' => 2,
            );
            $record = $this->commonModel->insertData('language', $data);
        }

        session()->setFlashdata('success_message', 'The Language has been added successfully...');
        return redirect('admin/language');
    }
    /**
     * Keywords.
     *
     * @return mixed
     */
    public function keywords()
    {
        $this->data['page'] = 'keywords';
        $this->data['active_language'] = $this->commonModel->getTblResultOfData('language', array('status' => 1), '*');
        echo view('admin/language/keywords', $this->data);
    }
    /**
     * Add Keywords.
     *
     * @return mixed
     */
    public function addKeywords()
    {
        if ($this->request->getPost('form_submit')) {

            $data = array();
            $pdata = array();
            $multiple = $this->request->getPost('multiple_key');
            $multiple_keyword = explode('|', $multiple ?? "");
            $multiple_keyword = array_filter($multiple_keyword);

            if (!empty($multiple_keyword)) {
                foreach ($multiple_keyword as $lang) {
                    $lang = trim($lang);
                    if ($lang != null) {
                        $lang_for_key = preg_replace("/[^ \w]+/", "", $lang);
                        $count = strlen($lang_for_key);
                        if ($count > 15) {
                            $lang_for_key = substr($lang_for_key, 0, 15);
                        }

                        $language = 'lg_' . str_replace(array(' ', '!', '&'), '_', strtolower($lang_for_key));
                        $data['lang_key'] = $language;
                        $data['lang_value'] = $pdata['lang_value'] = $lang;
                        $data['language'] = $pdata['language'] = 'en';
                        $record = $this->commonModel->countTblResult('language_management', $pdata);
                        if ($record > 0) {
                            $already_exits[] = $lang;
                        } else {
                            $cdata['lang_key'] = $language;
                            $cdata['language'] = 'en';
                            $chk_record = $this->commonModel->countTblResult('language_management', array('lang_key LIKE' => $language . '%', 'language' => 'en'));
                            if ($chk_record > 0) {
                                $data['lang_key'] = $language . $chk_record;
                            }
                            $record = $this->commonModel->insertData('language_management', $data);
                            language_file_create(); // language folder create
                        }
                    }
                }
            }
            if (!empty($already_exits)) {
                session()->setFlashdata('success_message', 'Keywords added successfully, But some keywords already exist');
                return redirect('admin/language/keywords');
            } else {
                session()->setFlashdata('success_message', 'Keywords added successfully');
                return redirect('admin/language/keywords');
            }
        }
        $this->data['page'] = 'add_keywords';
        echo view('admin/language/add_keywords', $this->data);
    }
    /**
     * Language List.
     *
     * @return mixed
     */
    public function languageList()
    {
        $lists = $this->languageModel->language_list($this->request->getPost());
        $data = array();
        $no = $this->request->getPost('start');
        $active_language = $this->commonModel->getTblResultOfData('language', array('status' => 1), '*');
        foreach ($lists as $keyword) {
            $no++;
            $row = array();
            $row[] = $no;
            $exist_key = array();
            if (!empty($active_language)) {
                $l = 0;
                foreach ($active_language as $lang) {
                    $lg_language_name = $keyword['lang_key'];
                    $language_key = $lang['language_value'];
                    $key = $keyword['language'];
                    $value = ($language_key == $key) ? $keyword['lang_value'] : '';
                    $key = $keyword['language'];
                    $this->data['currenct_page_key_value'] = $this->languageModel->currenctPageKeyValue($lists);
                    $value = (!empty($this->data['currenct_page_key_value'][$lg_language_name][$language_key])) ? $this->data['currenct_page_key_value'][$lg_language_name][$language_key] : '';
                    $row[] = $lg_language_name . '<input type="text" class="form-control" name="' . $lg_language_name . '[' . $language_key . ']" value="' . $value . '" onchange=update_language(\'' . $lg_language_name . '\',\'' . $language_key . '\')>
                       <input type="hidden" class="form-control" name="prev_' . $lg_language_name . '[' . $language_key . ']" value="' . $value . '">';
                    $l++;
                }
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->languageModel->languageListAll(),
            "recordsFiltered" => $this->languageModel->languageListAll(),
            "data" => $data,
        );

        //output to json format
        echo json_encode($output);
    }
    /**
     * Update Language.
     *
     * @return mixed
     */
    public function updateLanguage()
    {
        $lang_key = $insert['lang_key'] = $this->request->getPost('lang_key');
        $lang = $insert['language'] = $this->request->getPost('lang');
        $data['lang_value'] = $insert['lang_value'] = $this->request->getPost('cur_val');
        $ext = $this->commonModel->countTblResult('language_management', array('lang_key' => $lang_key, 'language' => $lang));
        if ($ext > 0) {
            if ($lang == 'en') {
                if (!empty($data['lang_value'])) {

                    $check['lang_value'] = $data['lang_value'];
                    $check['language'] = 'en';
                    $record = $this->commonModel->countTblResult('language_management', $check);
                    if ($record == 0) {
                        $result = $this->commonModel->updateData('language_management', array('lang_key' => $lang_key, 'language' => $lang), $data);
                        language_file_create(); // language folder create
                    } else {
                        $result = 0;
                    }
                } else {
                    $result = 2;
                }
            } else {
                $result = $this->commonModel->updateData('language_management', array('lang_key' => $lang_key, 'language' => $lang), $data);

                language_file_create(); // language folder create
            }
        } else {
            $this->commonModel->insertData('language_management', $insert);
            $result = true;
            language_file_create(); // language folder create
        }
        echo $result;
        die();
    }
    /**
     * Update Language Status.
     *
     * @return mixed
     */
    public function updateLanguageStatus()
    {

        $id = $this->request->getPost('id');

        $status = $this->request->getPost('update_language');
        $inputdata['status'] = $status;

        if ($status === 2) {
            $data = $this->commonModel->getTblResultOfData('language', array('id' => $id, 'default_language' => 1), '*');
            if (!empty($data)) {
                echo "0";
            } else {
                $this->commonModel->updateData('language', array('id' => $id), $inputdata);
                echo "1";
            }
        } else {
            $this->commonModel->updateData('language', array('id' => $id), $inputdata);
            echo "1";
        }
    }
    /**
     * Language Page.
     *
     * @return mixed
     */
    public function pages()
    {
        $this->data['list'] = $this->commonModel->getTblResultOfData('pages', '', '*');
        $this->data['page'] = 'pages';
        echo view('admin/language/page', $this->data);
    }
    /**
     * Add Page.
     *
     * @return mixed
     */
    public function addPage()
    {
        if ($this->request->getPost()) {
            $result = $this->languageModel->addPage();
            if ($result == true) {
                session()->setFlashdata('success_message', 'The page has been added successfully...');
                return redirect('admin/language/pages');
            } else {
                session()->setFlashdata('error_message', 'Already exists');
            }
            return redirect('admin/language/addPage');
        }
        $this->data['page'] = 'addPage';
        echo view('admin/language/addPage', $this->data);
    }
    /**
     * App Keywords.
     *
     * @return mixed
     */
    public function appKeywords()
    {
        $this->data['page'] = 'appKeywords';
        $this->data['active_language'] = $this->commonModel->getTblResultOfData('language', array('status' => 1), '*');
        return view('admin/language/appKeywords', $this->data);
    }
    /**
     * Add App Keywords.
     *
     * @return mixed
     */
    public function addAppKeywords()
    {
        if ($this->request->getPost()) {
            $page_key = $this->request->getPost('page_key');
            $result = $this->languageModel->addAppKeywords();
            if ($result == true) {
                session()->setFlashdata('success_message', 'The Keyword has been added successfully...');
            } elseif (is_array($result) && count($result) == 0) {
                session()->setFlashdata('success_message', 'The Keyword has been added successfully...');
            } elseif (is_array($result) && count($result) != 0) {
                session()->setFlashdata('error_message', 'Already exists' . implode(',', $result));
            } else {

                session()->setFlashdata('error_message', 'Already exists');
            }
            // return view('admin/language/pages/' . $page_key);
            return redirect()->to('admin/language/pages/' . $page_key);
        }
        $this->data['page'] = 'addAppKeywords';
        echo view('admin/language/addAppKeywords', $this->data);
    }
    /**
     * App Language List.
     *
     * @return mixed
     */
    public function appLanguageList()
    {
        $page_key = $this->request->getPost('page_key');
        $lists = $this->languageModel->appLanguageList($page_key);
        $data = array();
        $no = $_POST['start'];
        $active_language = $this->commonModel->getTblResultOfData('language', array('status' => 1), '*');
        foreach ($lists as $keyword) {
            // $no++;
            $row = array();
            // $row[] = $no;
            if (!empty($active_language)) {
                foreach ($active_language as $rows) {
                    $lg_language_name = $keyword['lang_key'];
                    $language_key = $rows['language_value'];
                    $key = $keyword['language'];
                    $value = ($language_key == $key) ? $keyword['lang_value'] : '';
                    $key = $keyword['language'];
                    $currenct_page_key_value = $this->languageModel->appCurrenctPageKeyValue($lists);
                    $name = (!empty($currenct_page_key_value[$lg_language_name][$language_key]['name'])) ? $currenct_page_key_value[$lg_language_name][$language_key]['name'] : '';
                    $lang_key = (!empty($currenct_page_key_value[$lg_language_name][$language_key]['lang_key'])) ? $currenct_page_key_value[$lg_language_name][$language_key]['lang_key'] : '';
                    $type = $currenct_page_key_value[$lg_language_name]['en']['type'];

                    //$readonly = ($language_key=='en')?'readonly':'';
                    $readonly = '';
                    $row[] = '<input type="text" class="form-control" placeholder="Name" name="' . $lg_language_name . '[' . $language_key . '][lang_value]" value="' . $name . '" ' . $readonly . ' >
                    <input type="text" class="form-control" value="' . $lang_key . '" readonly >
                    <input type="hidden" class="form-control" name="' . $lg_language_name . '[' . $language_key . '][type]" value="' . $type . '" ' . $readonly . ' >';
                }
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->languageModel->appLanguageListAll($page_key),
            "recordsFiltered" => $this->languageModel->appLanguageListFiltered($page_key),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * App Language Update.
     *
     * @return mixed
     */
    public function updateAppLanguage()
    {
        $page_key = "";
        if ($this->request->getPost()) {
            $page_key = $this->request->getPost('page_key');
            $data = $this->request->getPost();
            foreach ($data as $row => $object) {
                if (!empty($object) && is_array($object)) {
                    foreach ($object as $key => $value) {
                        $record = $this->db->table('app_language_management')->where(["language" => $key, "lang_key" => $row, "type" => $value['type'], "page_key" => $page_key])->countAllResults();
                        if ($record == 0) {
                            $array = array(
                                'language' => $key,
                                'lang_key' => $row,
                                'lang_value' => $value['lang_value'],
                                'type' => $value['type'],
                                'page_key' => $page_key,
                            );
                            $this->db->table('app_language_management')->insert($array);
                        } else {
                            $array = array(
                                'lang_value' => $value['lang_value'],
                                'type' => $value['type'],
                                'page_key' => $page_key,
                            );
                            $this->db->table("app_language_management")->where(["language" => $key, "lang_key" => $row, "type" => $value['type'], "page_key" => $page_key])->set($array)->update();
                        }
                    }
                }
            }
        }

        return redirect()->to('admin/language/pages/' . $page_key);
    }
}