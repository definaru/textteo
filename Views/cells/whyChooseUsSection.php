<section class="why-choose-us-section position-relative">
    <!-- <div class="why-bg"></div> -->
    <div class="container position-relative" style="z-index: 1;">
        <div class="row">
            <div class="col-12">
                <h2 class="why-title">Why Choose Us?</h2>
            </div>
        </div>
        <div class="row g-4 mt-md-4 mt-0">
            <?php foreach($card as $item) { ?>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon">
                        <img 
                            src="<?=$item['image'];?>" 
                            alt="<?=$item['alt'];?>" 
                        />
                    </div>
                    <div class="why-card-text">
                        <h3><?=$item['title'];?></h3>
                        <p><?=$item['text'];?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>