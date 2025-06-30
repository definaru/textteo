<section class="review-carousel-section position-relative" id="review-carousel-section">
    <div class="review-bg"></div>
    <div class="container position-relative">
        <div class="review-slider-wrapper">
            <div class="review-slider-track">
                <?php foreach($card as $item) { ?>
                <div class="review-slide">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-12">
                            <h2><?=$item['title'];?></h2>
                        </div>
                        <div class="col-lg-6 col-12">
                            <h3>“<?=$item['subtitle'];?>”</h3>
                            <p class="reviewer-text">
                                <?=$item['text'];?>
                            </p>
                            <div class="mt-3">
                                <p class="reviewer-name mb-0">
                                    <?=$item['person'];?>
                                </p>
                                <p class="reviewer-dog mt-1">
                                    and his dog <?=$item['pet'];?>
                                </p>
                                <a 
                                    href="https://instagram.com/<?=$item['instagram'];?>/" 
                                    target="_blank" 
                                    rel="noopener noreferrer" 
                                    class="d-flex align-items-center gap-2 mt-1 text-black text-decoration-none"
                                >
                                    <i class="fa-brands fa-instagram" style="font-size:16px;"></i>
                                    <span><?=$item['instagram'];?></span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 text-center mt-4 mt-lg-0 pe-0">
                            <img 
                                src="<?=$item['image'];?>"
                                alt="Dog image"
                                class="img-fluid rounded-3 pe-1" 
                            />
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="ms-2 ms-md-0 review-nav d-flex align-items-center justify-content-between position-relative">
            <div class="dots-wrapper d-flex gap-2">
                <div class="dot active" data-slide="0"></div>
                <div class="dot" data-slide="1"></div>
                <div class="dot" data-slide="2"></div>
            </div>
            <div class="arrows-wrapper d-flex gap-2">
                <button class="arrow arrow-left">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <button class="arrow arrow-right">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>