<?php 
$tasks =  model('HomeModel'); 
$replies=$tasks->getReplies($id);
if (!empty($replies)) {
    foreach ($replies as $rows) {

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
                    <p><?php echo $rows['replies']; ?></p>
                    <p class="blog-date"><?php echo date('d M Y', strtotime($rows['created_date'])); ?></p>
                    <?php 
                        if (session('user_id') && (session('user_id') == $rows['user_id']))
                        {
                    ?>
                            <a class="comment-btn text-danger" onclick="return delete_comment_reply('<?php echo $rows['id']; ?>','<?php echo $rows['comment_id']; ?>','2');" href="javascript:void(0);">
                                <i class="fas fa-trash"></i> 
                                <?php echo $language['lg_delete']??""; ?>
                            </a>
                    <?php 
                        }
                     ?>
                </div>
            </div>
        </li>
<?php  }
} ?>