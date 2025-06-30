<section class="faq-section mb-0" id="faq">
    <div class="container">
        <div class="col-12">
            <h2 class="faq-title mb-4">FAQ</h2>
            <?php foreach($card as $item) { ?>
                <div class="faq-item<?=$item["is_open"];?>">
                    <div class="faq-question">
                        <span><?=$item["issue"];?></span>
                        <i class="faq-icon fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p><?=$item["answer"];?></p>
                    </div>
                    <hr class="faq-divider" />
                </div>
            <?php } ?>
        </div>
    </div>
</section>