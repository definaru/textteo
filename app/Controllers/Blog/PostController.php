<?php

namespace App\Controllers\Blog;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\DoctorModel;

class PostController extends BaseController
{
    protected mixed $db;
    public mixed $uri;
    public $data;
    public string $timezone;
    public $language;
    public $session;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    /**
     * @var \App\Models\DoctorModel
     */
    public $doctorModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium', 'ckeditor']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'blog';
        $this->data['module'] = 'post';
        $this->data['page'] = '';

        $this->timezone = session('time_zone');
        if (!empty($this->timezone)) {
            date_default_timezone_set($this->timezone);
        }
        $this->data['uri'] = service('uri');

        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        //Define Model
        $this->commonModel = new CommonModel();
        $this->doctorModel = new DoctorModel();
    }
    /**
     * load blog page.
     * 
     * @param int $id
     * @return mixed
     */
    public function blog($id)
    {
        $this->data['page'] = 'index';
        if ($id == 1) {
            echo view('blog/blogActiveList', $this->data);
        } else {
            echo view('blog/blogPendingList', $this->data);
        }
    }
    /**
     * load Adminblog page.
     * 
     * @param int $id
     * @return mixed
     */
    public function adminBlog($id)
    {
        if ($id == 1) {
            $this->data['page'] = 'pending_post';
            echo view('admin/blog/pendingPost', $this->data);
        } else {
            $this->data['page'] = 'index';
            echo view('admin/blog/activePost', $this->data);
        }
    }
    /**
     * Add Admin blog.
     * 
     * @return mixed
     */
    public function adminAddBlog()
    {
        helper('ckeditor');
        // Array with the settings for this instance of CKEditor (you can have more than one)
        $this->data['ckeditor_editor1'] = array(
            //id of the textarea being replaced by CKEditor
            'id' => 'ck_editor_textarea_id',
            // CKEditor path from the folder on the root folder of CodeIgniter
            'path' => 'assets/js/ckeditor',
            // optional settings
            'config' => array(
                'toolbar' => "Full",
                'filebrowserBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );

        $this->data['page'] = 'add_post';
        return view('admin/blog/addPost', $this->data);
    }
    /**
     * Edit Admin blog.
     * 
     * @param int $id
     * @return mixed
     */
    public function adminEditBlog($id)
    {
        helper('ckeditor');
        // Array with the settings for this instance of CKEditor (you can have more than one)
        $this->data['ckeditor_editor1'] = array(
            //id of the textarea being replaced by CKEditor
            'id' => 'ck_editor_textarea_id',
            // CKEditor path from the folder on the root folder of CodeIgniter
            'path' => 'assets/js/ckeditor',
            // optional settings
            'config' => array(
                'toolbar' => "Full",
                'filebrowserBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );

        $id = encryptor_decryptor('decrypt', $id);
        $this->data['page'] = 'edit_post';
        $this->data['posts'] = $this->commonModel->getTblRowOfData('posts', ['id' => $id], '*');
        $this->data['tags'] = $this->commonModel->getTblResultOfData('tags', ['post_id' => $id], '*');
        return view('admin/blog/editPost', $this->data);
    }
    /**
     * Add Admin blog.
     * 
     * @return mixed
     */
    public function addBlog()
    {
        helper('ckeditor');
        // Array with the settings for this instance of CKEditor (you can have more than one)
        $this->data['ckeditor_editor1'] = array(
            //id of the textarea being replaced by CKEditor
            'id' => 'ck_editor_textarea_id',
            // CKEditor path from the folder on the root folder of CodeIgniter
            'path' => 'assets/js/ckeditor',
            // optional settings
            'config' => array(
                'toolbar' => "Full",
                'filebrowserBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );

        $this->data['page'] = 'add_post';
        return view('blog/blogAdd', $this->data);
    }
    /**
     * Edit Admin blog.
     * 
     * @param int $id
     * @return mixed
     */
    public function editBlog($id)
    {
        helper('ckeditor');
        // Array with the settings for this instance of CKEditor (you can have more than one)
        $this->data['ckeditor_editor1'] = array(
            //id of the textarea being replaced by CKEditor
            'id' => 'ck_editor_textarea_id',
            // CKEditor path from the folder on the root folder of CodeIgniter
            'path' => 'assets/js/ckeditor',
            // optional settings
            'config' => array(
                'toolbar' => "Full",
                'filebrowserBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );

        $id = encryptor_decryptor('decrypt', $id);
        $this->data['page'] = 'edit_post';
        $this->data['posts'] = $this->commonModel->getTblRowOfData('posts', ['id' => $id], '*');
        $this->data['tags'] = $this->commonModel->getTblResultOfData('tags', ['post_id' => $id], '*');
        return view('blog/blogEdit', $this->data);
    }
    /**
     * Image Upload.
     * 
     * @return mixed
     */
    public function imageUpload()
    {

        ini_set('max_execution_time', 3000);

        ini_set('memory_limit', '-1');

        $html = $error_msg = $shop_ad_id = '';

        $error_sts = 0;

        $row_id = $this->request->getPost('row_id');
        $image_data = $this->request->getPost('img_data');


        $base64string = str_replace('data:image/png;base64,', '', $image_data);

        $base64string = str_replace(' ', '+', $base64string);

        $data = base64_decode($base64string ?? "");

        $img_name = time();

        $file_name_final = $img_name . ".png";

        if (!is_dir('./uploads/post_image')) {
            mkdir('./uploads/post_image', 0777, TRUE);
        }

        file_put_contents('uploads/post_image/' . $file_name_final, $data);

        $source_image = 'uploads/post_image/' . $file_name_final;
        $upload_url = 'uploads/post_image/';

        $image_url = $this->image_resize(308, 206, $source_image, '308x206_' . $file_name_final, $upload_url);

        $preview_image_url = $this->image_resize(680, 454, $source_image, '680x454_' . $file_name_final, $upload_url);


        $html = '<div id="remove_image_div_' . $row_id . '" class="upload-images">
               <img src="' . base_url() . $image_url . '" alt="" height="42" width="42">
               <a href="javascript:;" onclick="remove_image(\'' . $image_url . '\',\'' . $preview_image_url . '\',\'' . $row_id . '\')"  class="uploaded-remove btn btn-icon btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>
               </div>';

        $row_id = $row_id + 1;

        $response = array(
            'state' => 200,
            'message' => $error_msg,
            'result' => $html,
            'image_url' => $image_url,
            'preview_image_url' => $preview_image_url,
            'sts' => $error_sts,
            'row_id' => $row_id,
        );

        echo json_encode($response);
    }
    /**
     * Image Resize.
     * 
     * @param int $width
     * @param int $height
     * @param string $image_url
     * @param string $filename
     * @param string $upload_url
     * @return mixed
     */
    public function image_resize($width = 0, $height = 0, $image_url = '', $filename = '', $upload_url = '')
    {

        $source_path = FCPATH . $image_url;
        $source_gdim = "";
        list($source_width, $source_height, $source_type) = getimagesize($source_path);
        switch ($source_type) {
            case IMAGETYPE_GIF:
                $source_gdim = imagecreatefromgif($source_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gdim = imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $source_gdim = imagecreatefrompng($source_path);
                break;
        }

        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $width / $height;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $temp_height = $height;
            $temp_width = (int) ($height * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $temp_width = $width;
            $temp_height = (int) ($width / $source_aspect_ratio);
        }

        /*
         * Resize the image into a temporary GD image
         */

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
            $temp_gdim,
            $source_gdim,
            0,
            0,
            0,
            0,
            $temp_width,
            $temp_height,
            $source_width,
            $source_height
        );

        /*
         * Copy cropped region from temporary image into the desired GD image
         */

        $x0 = ($temp_width - $width) / 2;
        $y0 = ($temp_height - $height) / 2;
        $desired_gdim = imagecreatetruecolor($width, $height);
        imagecopy(
            $desired_gdim,
            $temp_gdim,
            0,
            0,
            $x0,
            $y0,
            $width,
            $height
        );

        /*
         * Render the image
         * Alternatively, you can save the image in file-system or database
         */

        $image_url = $upload_url . $filename;

        imagepng($desired_gdim, $image_url);

        return $image_url;

        /*
         * Add clean-up code here
         */
    }
    /**
     * Delete Image.
     *
     * @return mixed
     */
    public function deleteImage()
    {
        $image_url = $this->request->getPost('image_url');
        $preview_image_url = $this->request->getPost('preview_image_url');
        $image_urls = FCPATH . $image_url;
        $preview_image_urls = FCPATH . $preview_image_url;
        $html = 0;
        if (unlink($image_urls) && unlink($preview_image_urls)) {
            $html = 1;
        }
        echo json_encode(array('html' => $html, 'image_url' => $image_url, 'preview_image_url' => $preview_image_url));
    }
    /**
     * Create Post
     * 
     * @return mixed
     */
    public function createBlog()
    {
        $slug = $this->request->getPost('slug');

        if ($slug == "") {
            $slug = $this->request->getPost('title');
        }
        if ($slug != "") {
            $slug = str_replace('  ', ' ', $slug);
            $slug = str_replace('/', '', $slug);
            $slug = str_replace(' ', '-', $slug);
        }


        $data['user_id'] = !empty(session('user_id')) ? session('user_id') : '';
        $data['title'] = libsodiumEncrypt($this->request->getPost('title'));
        $data['slug'] = libsodiumEncrypt($slug);
        $data['description'] = libsodiumEncrypt($this->request->getPost('description'));
        $data['keywords'] = libsodiumEncrypt($this->request->getPost('keywords'));
        $data['category'] = $this->request->getPost('category');
        $data['subcategory'] = $this->request->getPost('subcategory');
        $data['optional_url'] = $this->request->getPost('optional_url');
        $data['upload_image_url'] = $this->request->getPost('upload_image_url');
        $data['upload_preview_image_url'] = $this->request->getPost('upload_preview_image_url');
        $data['content'] = libsodiumEncrypt($this->request->getPost('content'));
        $data['post_by'] = $this->request->getPost('post_by');
        if (empty($data["slug"])) {
            $data["slug"] = str_slug($data["title"]);
        }
        $data['created_date'] = date('Y-m-d H:i:s');

        $result = $this->commonModel->insertData('posts', $data);
        if ($result != false) {
            $post_id = $result['id'];
            $tags = explode(',', $this->request->getPost('tags') ?? "");
            for ($i = 0; $i < count($tags); $i++) {
                $tag_data['post_id'] = $post_id;
                $tag_data['tag'] = $tags[$i];
                $tag_data['slug'] = str_slug($tags[$i]);
                $tag_data['created_at'] = date('Y-m-d H:i:s');
                $this->commonModel->insertData('tags', $tag_data);
            }
            session()->setFlashdata('success_message', $this->language['lg_post_added_succ']);
            $redirect = "";
            if (session('admin_id') != '') {
                $redirect = 'admin/pending-post';
            } else {
                $redirect = session('module') . '/active-blog';
            }
            $response['status'] = 200;
            $response['redirect'] = $redirect;
        } else {
            $response['msg'] = $this->language['lg_post_added_fail'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }
    /**
     * Update Blog.
     * 
     * @return mixed
     */
    public function updateBlog()
    {
        $post_id = $this->request->getPost('post_id');
        $data['title'] = libsodiumEncrypt($this->request->getPost('title'));
        $data['slug'] = libsodiumEncrypt($this->request->getPost('slug'));
        $data['description'] = libsodiumEncrypt($this->request->getPost('description'));
        $data['keywords'] = libsodiumEncrypt($this->request->getPost('keywords'));
        $data['category'] = $this->request->getPost('category');
        $data['subcategory'] = $this->request->getPost('subcategory');
        $data['optional_url'] = $this->request->getPost('optional_url');
        $data['upload_image_url'] = $this->request->getPost('upload_image_url');
        $data['upload_preview_image_url'] = $this->request->getPost('upload_preview_image_url');
        $data['content'] = libsodiumEncrypt($this->request->getPost('content'));
        $data['is_verified'] = 0;
        if (empty($data["slug"])) {
            $data["slug"] = str_slug($data["title"]);
        }

        $result = $this->commonModel->updateData('posts', ['id' => $post_id], $data);
        if ($result == true) {
            $this->commonModel->deleteData('tags', ['post_id' => $post_id]);
            $tags = explode(',', $this->request->getPost('tags') ?? "");
            for ($i = 0; $i < count($tags); $i++) {
                $tag_data['post_id'] = $post_id;
                $tag_data['tag'] = $tags[$i];
                $tag_data['slug'] = str_slug($tags[$i]);
                $tag_data['created_at'] = date('Y-m-d H:i:s');
                $this->commonModel->insertData('tags', $tag_data);
            }

            session()->setFlashdata('success_message', $this->language['lg_post_update_suc']);
            $redirect = "";
            if (session('admin_id') != '') {
                $redirect = 'admin/pending-post';
            } else {
                $redirect = session('module') . '/active-blog';
            }
            $response['status'] = 200;
            $response['redirect'] = $redirect;
        } else {
            $response['msg'] = $this->language['lg_post_update_fai'];
            $response['status'] = 500;
        }

        echo json_encode($response);
    }
    /**
     * Delete Blog.
     * 
     * @param int $id
     * @return mixed
     */
    public function deleteBlog($id)
    {
        // $this->db->where('post_id', $id);
        // $this->db->delete('tags');
        $data = array(
            'status' => 0,
        );
        $this->commonModel->updateData('posts', ['id' => $id], $data);
        echo json_encode(array("status" => TRUE));
    }
    /**
     * Blog List Active Post / Pending Post
     * 
     * @return mixed
     */
    public function postsList()
    {
        $list = $this->doctorModel->getBlogDatatables();
        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $posts) {

            $val = '';
            $type = 0;
            $view_status = "";

            if ($_POST['posts_type'] == 1) {
                $type = 1;
                $view_status = '<span class="badge badge-danger">' . $this->language['lg_declined'] . '</span>';
                if ($posts['is_viewed'] == '1') {
                    $val = 'checked';
                    $view_status = '<span class="badge badge-success">' . $this->language['lg_accept'] . '</span>';
                }
            }
            if ($_POST['posts_type'] == 2) {
                $type = 2;
                if ($posts['is_verified'] == '1') {
                    $val = 'checked';
                    $view_status = '<span class="badge badge-success">' . $this->language['lg_accept'] . '</span>';
                }
            }

            $image_url = explode(',', $posts['upload_image_url']);
            $image = 'assets/img/product.jpg';
            if (isset($image_url[0]) && file_exists($image_url[0])) {
                $image = $image_url[0];
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<img width="100" height="75" src="' . base_url() . $image . '" alt="" class="rounded">';
            $row[] = libsodiumDecrypt($posts['title']);
            $row[] = '<span class="badge badge-primary">' . libsodiumDecrypt($posts['category_name']) . '</span>&nbsp; &nbsp;<span class="badge badge-info">' . libsodiumDecrypt($posts['subcategory_name']) . '</span>';

            $module = "";
            if (session('admin_id') != '') {
                $module = 'admin';
                $row[] = ($posts['post_by'] == 'Admin') ? 'Admin' : '<a target="_blank" href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($posts['username'])) . '">Dr. ' . ucfirst(libsodiumDecrypt($posts['d_first_name'])) . '</a>';
                $row[] = '<div class="status-toggle">
                      <input type="checkbox" onchange="change_status(\'' . $posts['id'] . '\',\'' . $type . '\')" id="status_' . $posts['id'] . '" class="check" ' . $val . '>
                      <label for="status_' . $posts['id'] . '" class="checktoggle">checkbox</label>
                    </div>';
            } else {
                if ($_POST['posts_type'] == 1) {
                    $row[] = $view_status;
                }
                $module = session('module');
            }

            $row[] = date('d M Y', strtotime($posts['created_date'])) . '<br><small>' . date('h:i A', strtotime($posts['created_date'])) . '</small>';
            $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" href="' . base_url() . $module . '\blog-edit/' . encryptor_decryptor('encrypt', $posts['id']) . '">
                    <i class="fe fe-pencil"></i> ' . $this->language['lg_edit2'] . '
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_posts(' . $posts['id'] . ')">
                    <i class="fe fe-trash"></i> ' . $this->language['lg_delete'] . '
                  </a>
                </div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->doctorModel->blogCountAll(),
            "recordsFiltered" => $this->doctorModel->blogCountFiltered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Change Status.
     * 
     * @return mixed
     */
    public function changeStatus()
    {

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $type = $this->request->getPost('type');
        $data = "";
        if ($type === 1) {
            $data = array('is_viewed' => $status);
        }

        if ($type === 2) {
            $data = array('is_verified' => $status);
        }
        $this->commonModel->updateData('posts', ['id' => $id], $data);
        echo json_encode(array("status" => TRUE));
    }
}
