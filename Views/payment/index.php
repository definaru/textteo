<?php
$array = isset($data) ? json_decode($data, true) : '';
$title = $array['title'] ?? '';
$external_id = $array['external_id'] ?? '';
$description = $array['description'] ?? '';
$amount = $array['amount'] ?? '';
$customer = $array['prefilled_customer'] ?? '';
?>
<div>Form Mamo Pay</div>
<form action="/payment/pay" method="post">
    <div class="w-100">
        <input type="hidden" name="service_id" value="<?=time();?>" />
    </div>
    <div class="w-100">
        <input type="number" name="amount" min="2" placeholder="amount"/>
    </div>
    <div class="w-100">
        <button type="submit">Pay</button>
    </div>
    
    <?php if($array !== null) { ?>
    <pre class="w-50"><?=esc($title);?></pre> 
    <pre class="w-50">external id: <?=esc($external_id);?></pre> 
    <pre class="w-50"><?=esc($description);?></pre> 
    <pre class="w-50">amount: <?=esc($amount);?> AED</pre> 
    <pre class="w-50">email: <?=esc($customer["email"]);?></pre> 
    <pre class="w-50">last name: <?=esc($customer["last_name"]);?></pre> 
    <pre class="w-50">first name: <?=esc($customer["first_name"]);?></pre>   
    <?php } ?>
    <?php /*
     
  <pre class="w-50"><?php var_dump($array);?></pre>
    */ ?>

</form>