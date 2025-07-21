<?php
    use App\Libraries\SvgIcons;
?>
<section class="trusted-vets-section" id="trusted-vets-section">
    <div class="container">
        <div class="row align-items-center flex-column-reverse flex-lg-row">
            <div class="col-12 col-lg-6">
                <div class="row gy-4">
                    <div class="vstack gap-3 justify-content-start col-md-4 col-6">
                        <div class="icon-trusted-vets">
                            <?=SvgIcons::career(['size' => 34, 'fill' => '#FD9720']);?>
                        </div>
                        <h3 class="trusted-vet-number">5+</h3>
                        <p class="trusted-vet-label">Expertise</p>
                    </div>
                    <div class="vstack gap-3 justify-content-start col-md-4 col-6">
                        <div class="icon-trusted-vets">
                            <?=SvgIcons::rating(['size' => 34, 'fill' => '#FD9720']);?>
                        </div>
                        <h3 class="trusted-vet-number">4.9</h3>
                        <p class="trusted-vet-label">Avg. Rating</p>
                    </div>
                    <div class="vstack gap-3 justify-content-start col-md-4 col-12">
                        <div class="icon-trusted-vets">
                            <?=SvgIcons::certified(['size' => 34, 'fill' => '#FD9720']);?>
                        </div>
                        <h3 class="trusted-vet-number">100%</h3>
                        <p class="trusted-vet-label">Certified Vets</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 text-lg-start mb-4 mb-lg-0">
                <div class="ms-md-5 ms-0 ps-md-4 ps-0">
                    <h2 class="trusted-vets-title m-0">Your trusted vets</h2>
                    <p class="trusted-vets-description mt-3 mb-0">
                    At TextTeo, your pet’s health is our priority. Our certified
                    veterinarians are highly qualified professionals, thoroughly vetted
                    to meet the highest standards. You can trust our expert team to
                    provide the best care and advice for your pet.
                    </p>                        
                </div>
            </div>
        </div>
    </div>
</section>