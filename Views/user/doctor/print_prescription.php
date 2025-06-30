<!-- <title> -->
  <?php
//  echo !empty(settings("meta_title"))?settings("meta_title"):"Doccure";
 ?>
 <!-- </title> -->
<?php
// require_once(APPPATH . '../vendor/autoload.php');
// $mpdf = new \Mpdf\Mpdf();
// /** @var array $language */
//   /** @var array $prescription */

$logo=!empty(base_url().settings("logo_front"))?base_url().settings("logo_front"):base_url()."assets/img/logo.png";
  //$user = get_userdata();
 ?>
<style type="text/css">

@import url("https://fonts.googleapis.com/css?family=Poppins:300,400,500,700,900");
@font-face {
  font-family: "Material Icons";
  font-style: normal;
  font-weight: 400;
  src: url(<?=base_url()?>"assets/fonts/MaterialIcons-Regular.eot"); /* For IE6-8 */
  src: local("Material Icons"),
  local("MaterialIcons-Regular"),
  url("'.base_url().'assets//fonts/MaterialIcons-Regular.woff2") format("woff2"),
  url("'.base_url().'assets/fonts/MaterialIcons-Regular.woff") format("woff"),
  url("'.base_url().'assets/fonts/MaterialIcons-Regular.ttf") format("truetype");
}    
.table-bg{width:100%;}
.bg{
  background:url("'.base_url().'assets/img/invoice-bg.png");
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
<td width="50%" style="color:#fff;" align="left">
<b style="font-size:19px;"><?=$language['lg_patient_name']?> : </b>
<p><?=strtoupper(libsodiumDecrypt($prescription[0]['pat_first_name'])).' '.libsodiumDecrypt($prescription[0]['pat_last_name'])?></p>

</td>  
<td></td>
<td width="50%" style="color:#fff;vertical-align: top;" align="right"><b style="font-size:19px;"><?=$language['lg_doctor_name']?> : </b><br>
<p><?=strtoupper(libsodiumDecrypt($prescription[0]['doc_firstname'])).' '.libsodiumDecrypt($prescription[0]['doc_last_name'])?></p>

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
<p> Date : <?=$prescription[0]['prescription_date']?></p>

</td>
</tr>               
</table>
<div style="padding:20px">
<table class="table-bg" style="border:1px solid #ececec;font-family:lato;border-spacing: 0px;" cellpadding="20" width="100%" cellspacing="20">
<tr style="background:#78bd34;">
<td style="color:white;border-right:none;"><b><?=$language['lg_sno']?></b></td>
<td style="color:white;border-right:none;"><b><?=$language['lg_drug_name']?></b></td>
<td style="color:white;border-right:none;"><b><?=$language['lg_quantity']?></b></td> 
<td style="color:white;border-right:none;"><b><?=$language['lg_type']?></b></td>
<td style="color:white;border-right:none;"><b><?=$language['lg_days']?></b></td>
<td style="color:white;border-right:none;"><b><?=$language['lg_time']?></b></td>                                                   
</tr>   
<?php                           
$i=1;
foreach ($prescription as $p) { ?>  
  <tr align="center">
  <td valign="middle" style="border-bottom:1px solid #bfc0cd;color:#808080;!important"><?=$i++?></td>
  <td valign="middle" style="border-bottom:1px solid #bfc0cd;color:#808080;!important"><?=$p['drug_name']?></td>
  <td valign="middle" style="border-bottom:1px solid #bfc0cd;color:#808080;!important"><?=$p['qty']?></td>
   <td valign="middle" style="border-bottom:1px solid #bfc0cd;color:#808080;!important"><?=ucfirst(str_replace('_', ' ', $p['type']))?></td>
    <td valign="middle" style="border-bottom:1px solid #bfc0cd;color:#808080;!important"><?=$p['days']?></td>
     <td valign="middle" style="border-bottom:1px solid #bfc0cd;color:#808080;!important"><?=$p['time']?></td>

  </tr>
<?php }
?>
</table>

<table  style="border-collapse: collapse;position: absolute;bottom:0px;right:0px;" cellpadding="0">
  <tr>
    <td style="width:60%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>    
    <td style="text-align:right"><?=$language['lg_signature']?></td>
  </tr>
  <tr align="right">
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>    
    <td style="text-align:right"><img src="<?=base_url().$prescription[0]['img']?>" style="width:200px;height:50px;"></td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>    
    <td style="text-align:right">( <?=$language['lg_dr'].' '.strtoupper(libsodiumDecrypt($prescription[0]['doc_firstname'])).' '.libsodiumDecrypt($prescription[0]['doc_last_name'])?> )</td>
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
// $mpdf->Output(); -->