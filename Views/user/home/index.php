<?php
    if(!empty(session('redirect_activate'))){
        header("Location: https://textteo.com".session('redirect_activate'));
    }
    $title = 'TextTeo AI';
    $color = '#fd9720';
    $appbaseURL = env('app.baseURL');
    $icon = $appbaseURL.'/uploads/logo/1716626275_8357ec0a82c3394e6aa6.png';
    $description = 'Online veterinary clinic of the new generation: AI-based consultations for cats, dogs and other pets 24/7. Fast diagnosis, advice and assistance from AI-veterinarian without waiting in line - take care of your pets` health online!';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?=$title;?></title>
    <meta name="name" content="<?=$title;?>" />
    <meta name="author" content="TextTeo" />
    <meta name="robots" content="index, follow" />
    <meta name="keywords" content="online vet, vet consultation online, AI vet, AI vet artificial intelligence vet, online pet help, virtual vet, animal diagnostics online, vet consultation 24/7, vet chatbot, vet services online, vet for cats online, vet for dogs online, fast vet help online, vet consultation" />
    <meta name="description" content="<?=$description;?>" />
    <meta name="image" content="<?=$appbaseURL;?>/assets/images/background.jpg" />

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Online veterinary clinic" />
    <meta property="og:locale" content="en_EN" />
    <meta property="og:title" content="<?=$title;?>" />
    <meta property="og:url" content="<?=$appbaseURL;?>" />
    <meta property="og:description" content="<?=$description;?>">
    <meta property="og:image" content="<?=$appbaseURL;?>/assets/images/background.jpg" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?=$title;?>" />
    <meta name="twitter:description" content="<?=$description;?>">
    <meta name="twitter:image" content="<?=$appbaseURL;?>/assets/images/background.jpg">

    <meta name="theme-color" content="<?=$color;?>" />
    <meta name="msapplication-navbutton-color" content="<?=$color;?>" />
    <meta name="apple-mobile-web-app-status-bar-style" content="<?=$color;?>" />

    <?php /* 
    /assets/css/font-awesome/awesome.min.css 
    <link rel="preconnect" href="https://mc.google.com" />
    */ ?>
    <link rel="icon" href="<?=$icon;?>" />
    <link rel="shortcut icon" type="image/x-icon" href="<?=$icon;?>" />
    <link rel="apple-touch-icon" href="<?=$icon;?>" />

    <link rel="canonical" href="<?=$appbaseURL;?>" />
    <link rel="stylesheet" href="/assets/css/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="/assets/css/media.min.css" />
    <link rel="stylesheet" href="/styles.css" />
</head>
<body>
    <?=$this->include('layout/header');?>
    <main>
        <?=view_cell('Hero::section');?>
        <?=view_cell('Services::section');?>
        <?=view_cell('Discount::section');?>
        <?=view_cell('Pet::section');?>
        <?=view_cell('Review::section');?>
        <?=view_cell('TrustedVets::section');?>
        <?=view_cell('HowItWorks::section');?>
        <?=view_cell('WhyChooseUs::section');?>
        <?=view_cell('Faq::section');?>
        <?=view_cell('Cta::section');?>
    </main>
    <?=$this->include('layout/footer');?>

    <script src="https://img1.wsimg.com/traffic-assets/js/tccl.min.js"></script>
    <script src="/assets/css/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/index/script.js"></script>
</body>
</html>