<title><?php echo !empty(settings("meta_title"))?settings("meta_title"):"Doccure";?></title>
<?php
// require_once(APPPATH . '../vendor/autoload.php');
// include($_SERVER['DOCUMENT_ROOT'].  '\vendor\mpdf');
// $mpdf = new \mpdf\Mpdf();

$logo=!empty(base_url().settings("logo_front"))?base_url().settings("logo_front"):base_url()."assets/img/logo.png";
 /** @var array $language */
  /** @var array $billing */
  //$user = get_userdata();
// $html = ' ?>
<style type="text/css">

@import url("https://fonts.googleapis.com/css?family=Poppins:300,400,500,700,900");
@font-face {
  font-family: "Material Icons";
  font-style: normal;
  font-weight: 400;
  src: url("'.base_url().'assets/fonts/MaterialIcons-Regular.eot"); /* For IE6-8 */
  src: local("Material Icons"),
  local("MaterialIcons-Regular"),
  url("<?=base_url()?>'assets//fonts/MaterialIcons-Regular.woff2") format("woff2"),
  url("<?=base_url()?>'assets/fonts/MaterialIcons-Regular.woff") format("woff"),
  url("<?=base_url()?>'assets/fonts/MaterialIcons-Regular.ttf") format("truetype");
}    
.table-bg{width:100%;}
.bg{
  background:url("<?=base_url()?>assets/img/invoice-bg.png");
  width:100%;
  background-size: auto 100%;
  background-repeat: no-repeat;
  background-position: left top;
  height:250px;
  position:relative;
}
</style>
<div style="border:1px solid #ececec;">
<table cellspacing="0" cellpadding="0" width="100%" class="bg" align="center">
<tr>                    
<td width="40%"> 
<img src="<?=$logo?>" style="padding-left:10px;width: 180px;">
</td>
<td width="60%">
<table>
<tbody>
<tr align="left">
<td width="50%" style="color:#fff;" align="center">
<b style="font-size:19px;"><?=$language['lg_patient_name']??""?> : </b>
<p><?=strtoupper(libsodiumDecrypt($billing[0]['pat_first_name'])).' '.strtoupper(libsodiumDecrypt($billing[0]['pat_last_name']))?></p>
</td>  
<td></td>
<td width="50%" style="color:#fff;vertical-align: top;" align="center"><b style="font-size:19px;"><?=$language['lg_doctor_name']??""?> : </b><br>
<p><?=strtoupper(libsodiumDecrypt($billing[0]['doc_firstname'])).' '.strtoupper(libsodiumDecrypt($billing[0]['doc_last_name']))?></p>

</td>  
<td></td>
</tr>       
</tbody>
</table>
</td>                    
</tr>

</table>
<table class="table-bg" cellpadding="20" width="100%" style="width:100%;border-collapse: collapse;" >
<tr>
<td width="50%" align="left"> 
<b></b><br><br>                                   
</td>
<td width="50%" align="right" style="vertical-align: top"> 
<p> Date : <?=$billing[0]['billing_date']?></p>

</td>
</tr>               
</table>
<div style="padding:20px">
<table class="table-bg" style="border:1px solid #ececec;font-family:lato;border-spacing: 0px;" cellpadding="20" width="100%" cellspacing="20">
<tr style="background:#78bd34;">
<td style="color:white;border-right:none;"><b><?=$language['lg_sno']??""?></b></td>
<td style="color:white;border-right:none;"><b><?=$language['lg_name']??""?></b></td>
<td style="color:white;border-right:none;"><b><?=$language['lg_amount']??""?></b></td> 
                                                  
</tr>
<?php                          
$i=1;
foreach ($billing as $p) { ?>  
  <tr align="center">
  <td valign="middle" style="border-bottom:1px solid #bfc0cd;color:#808080;!important"><?=$i++?></td>
  <td valign="middle" style="border-bottom:1px solid #bfc0cd;color:#808080;!important"><?=$p['name']?></td>
  <td valign="middle" style="border-bottom:1px solid #bfc0cd;color:#808080;!important"><?=convert_to_user_currency($p['amount'])?></td>
   </tr>
<?php } ?>
</table>

<table  style="border-collapse: collapse;position: absolute;bottom:0px;right:0px;" cellpadding="0">
  <tr>
    <td style="width:60%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>    
    <td style="text-align:right"><?=$language['lg_signature']??""?></td>
  </tr>
  <tr align="right">
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>    
    <td style="text-align:right"><img src="<?=base_url().$billing[0]['img']??""?>" style="width:200px;height:50px;"></td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>    
    <td style="text-align:right">( <?=$language['lg_dr']??"".' '.strtoupper(libsodiumDecrypt($billing[0]['doc_firstname'])).' '.strtoupper(libsodiumDecrypt($billing[0]['doc_last_name']))?> )</td>
  </tr>
</table>
</div>
</div>

  <!-- // echo $html; // HTML 

/* PDF VIew */
// $mpdf->autoScriptToLang = true;
// $mpdf->autoLangToFont = true;
// $mpdf->curlAllowUnsafeSslRequests = true;  //This code is used for windows server 
// $mpdf->WriteHTML($html);
// $mpdf->Output();

echo $html; -->