<?php
    use App\Libraries\SvgIcons;
?>
<section class="pet-problem-section" id="pet-problem-section">
    <div class="container">
        <div class="pet-problem-header">
            <h2 class="mb-md-2 mb-3 pe-md-0 pe-4">What’s bothering your pet?</h2>
            <p>Choose a problem and our AI assistant will select the best doctor for you</p>
        </div>

        <div class="pet-problem-content">
            <div class="problem-arrows d-flex">
                <button class="arrow arrow-left">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <button class="arrow arrow-right">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        
            <div class="problem-cards">
                <?php foreach($card as $item) { $icon = $item["icon"]; ?>
                    <div class="problem-card">
                        <div class="card-icon">
                            <?=SvgIcons::$icon(['class' => 'icon-img', 'size' => 44, 'fill' => '#252525']);?>
                        </div>
                        <div class="card-text">
                            <h3><?=$item["title"];?></h3>
                            <div class="card-arrow">
                                <a href="<?=$item["href"];?>">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?php /*
        <?= SvgIcons::skin(['class' => 'icon-img', 'size' => 30, 'fill' => '#252525']);?>
        <?= SvgIcons::dental(['class' => 'icon-img', 'size' => 30, 'fill' => '#252525']);?>
        <?= SvgIcons::trauma(['class' => 'icon-img', 'size' => 30, 'fill' => '#252525']);?>
        <?= SvgIcons::parasites(['class' => 'icon-img', 'size' => 30, 'fill' => '#252525']);?>
        <?= SvgIcons::infection(['class' => 'icon-img', 'size' => 30, 'fill' => '#252525']);?>
        <?= SvgIcons::stomach(['class' => 'icon-img', 'size' => 30, 'fill' => '#252525']);?>        
        */ ?>

    </div>
</section>