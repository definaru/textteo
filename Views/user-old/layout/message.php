<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>
<style>
    .pe-auto {
        cursor: pointer;
    }
</style>
<!--Page Content -->
<div class="content">
    <div class="container-fluid">
        <a class="d-block mt-3 mb-3 ml-2 pe-auto" onclick="history.back()"><i class="fas fa-arrow-left mr-1"></i><?php echo $language['lg_back'] ?? ""; ?></a>
        <div class="row">
            <div class="col-xl-12">
                <div class="chat-window">

                    <!-- Chat Left -->
                    <div class="chat-cont-left">
                        <div class="chat-header">
                            <span><?php echo $language['lg_chats'] ?? ""; ?></span>

                        </div>
                        <form class="chat-search">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="fas fa-search"></i>
                                </div>
                                <input type="text" onkeyup="search_user()" id="search_users" class="form-control" placeholder="<?php echo $language['lg_search3'] ?? ""; ?>">
                            </div>
                        </form>
                        <input type="hidden" name="" id="user_selected_id" value="">
                        <div class="chat-users-list">
                            <div class="chat-scroll chat_users">

                            </div>
                        </div>
                    </div>
                    <!-- /Chat Left -->

                    <!-- Chat Right -->
                    <div class="chat-cont-right">
                        <div class="chat-header">
                            <a id="back_user_list" href="javascript:void(0)" class="back-user-list">
                                <i class="material-icons">chevron_left</i>
                            </a>
                            <div class="media">
                                <div class="media-img-wrap chat-img">
                                    <div class="avatar  ">

                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="user-name user-info pull-left openchat"></div>
                                    <div class="user-status"></div>
                                </div>
                            </div>
                            <!-- <div class="chat-options chattrash">
											<a href="javascript:void(0)" onclick="delete_conversation();" title="Delete Chat History">
												<i class="far fa-trash-alt"></i>
											</a>
										</div>  -->
                            <div class="progress upload-progress d-none">
                                <div class="progress-bar progress-bar-success active progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width: 100%;">
                                    <?php echo $language['lg_uploading'] ?? ""; ?>
                                </div>
                            </div>
                        </div>
                        <div id="chat-box" class="chatbox-message chat-body">
                            <div class="chat-scroll slimscrollleft">
                                <ul class="list-unstyled chats">

                                </ul>
                            </div>
                        </div>

                        <div class="chat-footer" id="chat" onsubmit="return false">
                            <form class="input-group" name="chat_form" id="chat_form" enctype="multipart/form-data" method="post" action="<?php echo base_url(); ?>message/upload_files">
                                <div class="input-group-prepend">
                                    <a class="link attach-icon btn-file btn" href="javascript:void(0)" onclick="$('#user_file').click();">
                                        <i class="fa fa-paperclip"></i>
                                    </a>
                                </div>
                                <input type="text" name="input_message" id="input_message" placeholder="<?php echo $language['lg_type_something'] ?? "" ?>" class="input-msg-send form-control chat-input" autocomplete="off">
                                <input type="hidden" id="recipients" value="username">
                                <input type="hidden" name="receiver_id" id="receiver_id">
                                <input type="hidden" name="to_user_id" id="to_user_id">
                                <input type="hidden" name="time" id="time">
                                <input type="file" name="userfile" id="user_file" class="d-none">
                                <div class="input-group-append">
                                    <a class="link btn msg-send-btn chat-send-btn" href="javascript:void(0)" id="chat-send-btn"><i class="fab fa-telegram-plane"></i></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /Chat Right -->

                </div>
            </div>
        </div>
        <!-- /Row -->

    </div>

</div>
<!-- /Page Content-->
<?php
$user_detail = user_detail(session('user_id'));
if ($user_detail['profileimage'] == "" || ($user_detail['profileimage'] != "" && !is_file($user_detail['profileimage']))) {
    $user_profile_image = base_url() . 'assets/img/user.png';
} else {
    $user_profile_image = (!empty($user_detail['profileimage'] ?? "")) ? base_url() . $user_detail['profileimage'] ?? "" : base_url() . 'assets/img/user.png';
}
?>
<input type="hidden" name="img" id="img" value="<?php echo $user_profile_image; ?>">

<?php $this->endSection(); ?>