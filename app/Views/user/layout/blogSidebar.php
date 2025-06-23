<!-- Search -->
<div class="card search-widget">
    <div class="card-body">
        <form class="search-form" method="get" action="<?= base_url('blogs') ?>">
            <div class="input-group">
                <input type="text" placeholder="<?= $language['lg_search6']??"" ?>" name="keywords" class="form-control" value="<?php if (isset($_GET['keywords']) && !empty($_GET['keywords'])) echo $_GET['keywords']; ?>" required>
                <div class="input-group-append">
                    <button type="submit" id="search_blog" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /Search -->

<!-- Latest Posts -->
<div class="card post-widget">
    <div class="card-header">
        <h4 class="card-title"><?= $language['lg_latest_posts']??"" ?></h4>
    </div>
    <div class="card-body">
        <ul class="latest-posts">
            <?php
            
            $latestPosts=getTblLast10('posts',['status'=>1,'is_verified'=>1,'is_viewed'=>1],'*',false);
            
            if (!empty($latestPosts)) :
                foreach ($latestPosts as $lrows) :
                    $image_url=explode(',', $lrows['upload_image_url']);
                    $postimage=base_url().'assets/img/image-not-found.png';

                    if (!empty($image_url[0]) && file_exists($image_url[0])) {
                        $postimage = base_url().$image_url[0];
                    }
                ?>
                    <li>
                        <div class="post-thumb">
                            <a href="<?= base_url('blog-detail/' . libsodiumDecrypt($lrows['slug'])) ?>">
                                <img class="img-fluid" src="<?= $postimage; ?>" alt="">
                            </a>
                        </div>
                        <div class="post-info">
                            <h4><a href="<?= base_url('blog-detail/' . libsodiumDecrypt($lrows['slug'])) ?>"><?= libsodiumDecrypt($lrows['title']) ?></a></h4>
                            <p><?= date('d M Y', strtotime($lrows['created_date'])) ?></p>
                        </div>
                    </li>
                <?php endforeach;
            endif; ?>
        </ul>
    </div>
</div>
<!-- /Latest Posts -->

<!-- Categories -->
<div class="card category-widget">
    <div class="card-header">
        <h4 class="card-title"><?= $language['lg_blog_categories']??"" ?></h4>
    </div>
    <div class="card-body">
        <ul class="categories">
            <?php
            $tasks =  model('HomeModel'); 
            $categories=$tasks->getCategories();
            if (!empty($categories)) :
                foreach ($categories as $crows) : ?>
                    <li><a href="<?= base_url('blogs?category=' . libsodiumDecrypt($crows['category_name'])) ?>"><?= libsodiumDecrypt($crows['category_name']) ?> <span>(<?= $crows['count'] ?>)</span></a></li>
                <?php endforeach;
            endif; ?>
        </ul>
    </div>
</div>
<!-- /Categories -->

<!-- Tags -->
<div class="card tags-widget">
    <div class="card-header">
        <h4 class="card-title"><?= $language['lg_tags']??"" ?></h4>
    </div>
    <div class="card-body">
        <ul class="tags">
            <?php 
            $tasks =  model('HomeModel'); 
            $tags=$tasks->tags();
            if (!empty($tags)) :
                foreach ($tags as $trows) :
                    if (!empty($trows['tag'])) : ?>
                        <li><a href="<?= base_url('blogs?tags=' . $trows['tag']) ?>" class="tag"><?= $trows['tag'] ?></a></li>
                    <?php endif;
                endforeach;
            endif; ?>
        </ul>
    </div>
</div>
<!-- /Tags -->
