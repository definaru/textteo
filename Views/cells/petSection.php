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
                <button class="arrow arrow-left" aria-label="Previous">
                    <?=SvgIcons::chevronLeft(['size' => 20, 'fill' => '#252525']);?>
                    <span class="visually-hidden">Left</span>
                </button>
                <button class="arrow arrow-right" aria-label="Next">
                    <?=SvgIcons::chevronRight(['size' => 20, 'fill' => '#252525']);?>
                    <span class="visually-hidden">Right</span>
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
                                    <?=SvgIcons::chevronRight(['size' => 16, 'fill' => '#252525']);?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>