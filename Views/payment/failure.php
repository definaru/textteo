<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your payment didn't go through</title>
    <link href="/uploads/logo/1716626275_8357ec0a82c3394e6aa6.png" rel="icon">
    <link rel="stylesheet" href="/assets/css/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="/assets/css/media.min.css" />
    <link rel="stylesheet" href="/styles.css" />
</head>
<body>
    <main class="vh-100 vstack justify-content-center">
        <div class="container">
            <div class="col-12 text-center py-4 text-danger">
                <i class="fa-solid fa-triangle-exclamation display-1"></i>
                <h2 class="m-0 pt-4">
                    Your payment didn't go through
                </h2>
            </div>
            <div class="col-md-4 offset-md-4 text-center">
                <p class="text-muted py-4">The payment was rejected by the bank or your bank card details were not entered correctly.</p>
                <div class="vstack gap-3 px-md-5 px-0 mx-md-5 mx-0">
                    <a href="/" target="_blank" class="btn btn-dark">Back to home page</a>
                    <a href="/payment?data=<?=esc($paymentCode);?>" target="_blank" class="btn btn-warning">Repeat payment</a>
                </div>
            </div>            
        </div>
    </main>

    <script src="https://img1.wsimg.com/traffic-assets/js/tccl.min.js"></script>
    <script src="/assets/css/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/index/script.js"></script>
</body>
</html>

