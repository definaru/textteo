<?php
    $array = isset($paymentCode) ? json_decode($paymentCode, true) : '';
    $title = $array['title'] ?? '';
    $description = $array['description'] ?? '';
    $external_id = $array['external_id'] ?? '';
    $amount = $array['amount'] ?? '';
    $payment_url = $array['payment_url'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepting payment in TextTeo</title>
    <link href="/uploads/logo/1716626275_8357ec0a82c3394e6aa6.png" rel="icon">
    <link rel="stylesheet" href="/assets/css/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="/assets/css/media.min.css" />
    <link rel="stylesheet" href="/styles.css" />
</head>
<body>
    <?=$this->include('layout/header');?>
    <main class="vh-100 vstack justify-content-center">
        <div class="container">
            <div class="col-12 text-center vstack align-items-center gap-2">
                <img 
                    src="/uploads/logo/1716626275_8357ec0a82c3394e6aa6.png" 
                    style="width: 60px;" 
                    alt="Logotipe" 
                />
                <strong><?=esc($title);?></strong>
                <small class="m-0 text-body-tertiary"><?=esc($description);?></small>
                <p class="m-0">#<?=esc($external_id);?></p>
                <p class="m-0 text-success bg-success-subtle rounded px-3 fw-bold">
                    <?=number_format(esc($amount));?> AED
                </p>
                <!-- <h4>Accepting payment in TextTeo</h4> -->
            </div>
            <div class="col-md-4 offset-md-4">
                <iframe class="w-100 mt-4" src="<?=esc($payment_url);?>" style="height:410px" frameborder="0"></iframe>
            
                <?php /*
                <?php if($array !== null) { ?>
                    <pre class="w-50"><?=esc($title);?></pre> 
                    <pre class="w-50">external id: <?=esc($external_id);?></pre> 
                    <pre class="w-50"><?=esc($description);?></pre> 
                    <pre class="w-50">amount: <?=esc($amount);?> AED</pre> 
                    <pre class="w-50">email: <?=esc($customer["email"]);?></pre> 
                    <pre class="w-50">last name: <?=esc($customer["last_name"]);?></pre> 
                    <pre class="w-50">first name: <?=esc($customer["first_name"]);?></pre>   
                <?php } ?>  
                
                <pre class="w-50">
                <?= esc(json_encode(json_decode($paymentCode), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?>
                </pre>                 
                <?=$this->include('layout/footer');?>
                */ ?>
            </div>           
        </div>
    </main>
    <script src="https://img1.wsimg.com/traffic-assets/js/tccl.min.js"></script>
    <script src="/assets/css/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/index/script.js"></script>
</body>
</html>