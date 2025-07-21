<?php

if (!empty($comments)) {
    foreach ($comments as $rows) {
    
        $avatar = base_url() . 'assets/img/user.png';

        if (!empty($rows['profileimage']) && file_exists($rows['profileimage'])) {
            $avatar = base_url() . $rows['profileimage'];
        }
?>
        <li>
            <div class="comment">
                <div class="comment-author">
                    <img class="avatar" alt="" src="<?php echo $avatar; ?>">
                </div>
                <div class="comment-block">
                    <span class="comment-by">
                        <span class="blog-author-name"><?php echo ucfirst(libsodiumDecrypt($rows['name'])); ?></span>
                    </span>
                    <p><?php echo $rows['comments']; ?></p>
                    <p class="blog-date"><?php echo date('d M Y', strtotime($rows['created_date'])); ?></p>
                    <?php if (session('user_id') || session('admin_id')) { ?>
                        <a class="comment-btn" onclick="return add_reply('<?php echo $rows['id']; ?>');" href="javascript:void(0);">
                            <i class="fas fa-reply"></i> <?php echo $language['lg_reply']??""; ?>
                        </a> &nbsp; &nbsp;
                        <?php if (session('user_id') == $rows['user_id']) {
                            /** @phpstan-ignore-line */ ?>
                            <a class="comment-btn text-danger" onclick="return delete_comment_reply('<?php echo $rows['id']; ?>','','1');" href="javascript:void(0);">
                                <i class="fas fa-trash"></i> <?php echo $language['lg_delete']??""; ?>
                            </a>

                    <?php }
                    } ?>
                </div>
            </div>
            <div id="reply_block_<?php echo $rows['id']; ?>" class="leave-reply-body leave-reply-sub-body" style="display: none;">
                <div>
                    <div class="sub-comment-loader-container">
                        <div class="loader"></div>
                    </div>
                    <!-- form make  sub comment -->
                    <form method="post">
                        <input type="hidden" id="comment_id_<?php echo $rows['id']; ?>" value="<?php echo $rows['id']; ?>">
                        <div class="form-group">
                            <textarea class="form-control" name="reply" id="reply_text_<?php echo $rows['id']; ?>" placeholder="<?php echo $language['lg_reply']??""; ?>" maxlength="999"></textarea>
                        </div>
                        <div class="submit-section">
                            <button type="button" id="reply_btn_<?php echo $rows['id']; ?>" onclick="return create_reply('<?php echo $rows['id']; ?>')" class="btn btn-primary submit-btn">
                                <?php echo $language['lg_submit']??""; ?>
                            </button>
                        </div>

                    </form><!-- form end -->
                </div>
            </div>
            <ul class="comments-list reply" id="reply_list_<?php echo $rows['id']; ?>">
                <?php 
                $data['id']=$rows['id'];
                echo view('blog/blogReply',$data);
                ?>

            </ul>
        </li>
<?php }
}
