<section class="services-section" id="services-section">
    <div class="container">
        <div class="services-title row mb-md-5 mb-3">
            <h2 class="col-md-6">
                Help for your pet is always at hand
            </h2>
        </div>
        <div class="services-cards">
            <?php foreach($card as $item) { ?>
            <div class="service-card">
                <div class="card-image">
                    <img src="<?=$item["image"];?>" alt="<?=$item["alt"];?>" />
                </div>
                <div class="card-content text-start">
                    <h3 class="pt-0 pt-md-4 pb-2"><?=$item["title"];?></h3>
                    <p><?=$item["text"];?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>