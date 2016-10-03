<?php

function templator_to_pdf_new($content) {
require_once('mpdf60beta/mpdf.php');  
	//fill in the blanks 
	//$content = fill_in_the_blanks($content);
	
	//create pdf from template
	$mpdf=new mPDF('c'); 
	$mpdf->SetDisplayMode('fullpage');

	//$mpdf->debugfonts=true;
	$mpdf->debug=true;
	$mpdf->showImageErrors = true;
	
	$html = '<!DOCTYPE >
	<html><head xmlns="http://www.w3.org/1999/xhtml">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></meta>
    <link href="/wp-content/plugins/pdf-templator/css/style.css"  rel="stylesheet" type="text/css">
	<style> body { width:210mm; font-family:Arial,sans;  font-size:10pt;} 
	table.bluetable, table.bluetable td {  border: 1px solid #1086b6; border-collapse: separate; border-spacing:2px;}
table { margin-bottom:20px}
	</style>
	
	</head><body >';
	//$html=wpautop($content);
	//$html.="</body></html>";
	$mpdf->WriteHTML($content);
	$mpdf->Output();
	exit;

}

templator_to_pdf_new('<p><img class="alignleft" alt="BPS Logo HD" src="http://www.britishpassportservices.co.uk/wp-content/uploads/2013/11/BPS-Logo-HD-1024x147.jpg" width="430" height="62" /></p>
<address>                   {First_Name} {Last_Name}</address><address>                   {Address}, {Address_2}</address><address>                   {Town}, {City}</address><address>                   {County}, {Zip_Code}</address>
<p style="text-align: center;"><span style="font-size: x-large; color: #800000; font-family: verdana, geneva;"><strong>Official Passport Application &amp; Renewal Form</strong></span></p>
<p><span style="color: #000000; font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">Dear {First_Name} {Last_Name},</span></p>
<p><span face="tahoma, arial, helvetica, sans-serif" size="2" style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">Please find enclosed the Official Passport Application &amp; Renewal form which has begun the service with us. You need to complete the form using in <strong>black</strong> ink and return the form, 2 passport size and quality photographs, the old passport (or LS01 Lost and Stolen form. Printable version found at: http://forms.britishpassportservices.co.uk) using the silver bag provided.</span></p>
<p><span face="tahoma, arial, helvetica, sans-serif" size="2" style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">We have already pre-paid for the postage for the Special Delivery service on the polythene bag that has been enclosed. This service comes with £500.00 insurance against loss and theft and is guaranteed to arrive out our offices the next working day.</span></p>
<p><span face="tahoma, arial, helvetica, sans-serif" size="2" style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;"></span><span face="tahoma, arial, helvetica, sans-serif" size="2" style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;"><strong>Payment Required:</strong> You need to include <strong>£50 for each child or £70 for each adult </strong>to use the service. You can enclose either a cash payment (as the Special Delivery service is designed to securely deliver cash payments) or a cheque payable to Passport Services Worldwide. You must enclose the </span><span face="tahoma, arial, helvetica, sans-serif" size="2" style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">Payment Tracking Receipt so that we can credit the payment to you and find your details on our system.</span></p>
<p style="text-align: center;"><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;"><em style="text-align: center;"><span style="color: #ff0000;">As stated in our terms payment is required service <strong>within the next 2 days</strong> to avoid any surcharges. </span></em></span></p>
<p style="text-align: left;"><strong>How to Pay via the Post Office *</strong></p>
<p style="text-align: left;"><span style="font-size: small; font-family: tahoma, arial, helvetica, sans-serif;"><img class="wp-image-1993 alignleft" alt="Post Office No R" src="http://www.britishpassportservices.co.uk/wp-content/uploads/2013/12/Post-Office-No-R.png" width="91" height="69" />You now need to forward payment and track-able receipt via the Post Office. Simpy place the Payment Tracking Receipt, and cash or cheque payment into the enclosed silver delivery bag &amp; post it at your nearest post box or leave it at your local Post Office. The following day before noon the package will arrive via secure delivery at our London Headquarters. <strong>No stamps are required</strong> as we have already paid for the postage and insurance on your behalf.</span></p>
<p style="text-align: left;"><strong>What Will Happen Next?</strong></p>
<p style="text-align: left;"><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">Once we receive the payment for our service we will perform a document checking service to confirm that the Application form has been completed correct as well as check over the two passport photographs that you have provided to ensure that they are compliant and perform the other services that we provide as part of our service. You will be contacted if we find any issues.</span></p>
<p style="text-align: center;"><span style="color: #ff0000; font-size: x-large; font-family: tahoma, arial, helvetica, sans-serif;"><strong>Just Posted the Forms &amp; Payment?</strong></span></p>
<p style="text-align: center;"><span style="font-size: medium; font-family: tahoma, arial, helvetica, sans-serif;">Once you\'ve posted the polythene bag at the Post Office simply visit this unique website address:<strong><span style="color: #800000;"> {Payment_Received_Passport_Package}</span> </strong>or<strong> </strong>text: <span style="color: #800000;"><strong>PAID</strong> <strong>{contact_id}</strong></span> to <strong><span style="color: #800000;">07786 200 351</span> </strong>(standard rate text service) as doing so will notify the system that you have sent the payment.</span></p>
<p style="text-align: left;"><span style="font-size: medium; font-family: tahoma, arial, helvetica, sans-serif;"></span><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">By using this service you are agreeing to the Terms of Service found at <strong>http://04form.PassportTerms.co.uk</strong> and understand that the service has already begun. In addition to our document and form checking service you will also receive the following services...</span></p>
<p style="text-align: center;"><strong style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;"><strong><img class="wp-image-2088" alt="Standard Package Bullet Points" src="http://www.britishpassportservices.co.uk/wp-content/uploads/2014/01/Standard-Package-Bullet-Points.png" width="759" height="67" /></strong></strong></p>
<address><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">Kind Regards</span></address><address><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;"></span><strong style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">{Call_Agent}</strong></address><address><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;"><strong>Tel:</strong> 0203 0120 255    <strong>Email:</strong> form@britishpassportservices.org.uk   </span><span style="font-size: small; font-family: tahoma, arial, helvetica, sans-serif;"><strong>Website:</strong> www.BritishPassportServices.org.uk</span></address><address><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;"><strong>Address:</strong> {Office_Address}</span></address><address><span style="font-size: small;">* Service not affiliated with the Post Office. Logo and name property of the Post Office</span></address><address> </address>
<p style="text-align: center;"><img class="alignleft" alt="BPS Logo HD" src="http://www.britishpassportservices.co.uk/wp-content/uploads/2013/11/BPS-Logo-HD-1024x147.jpg" width="430" height="62" /></p>
<p style="text-align: center;"> <span style="color: #ff0000;"><em><strong>IMPORTANT:</strong>  Return this receipt along with your documents and cash or cheque payment.</em></span></p>
<address style="text-align: right;">{First_Name} {Last_Name}</address><address style="text-align: right;">                   {Address}, {Address_2}</address><address style="text-align: right;">                   {Town}, {City}</address><address style="text-align: right;">                   {County}, {Zip_Code}</address>
<p style="text-align: center;"><strong><span style="font-size: xx-large;  color: #800000;">Payment Tracking Receipt</span></strong></p>
<p style="text-align: left;">Dear British Passport Services,</p>
<p style="text-align: left;">Please find enclosed the cash payment (cheques payable to Passport Services Worldwide) for the passport package (Service fee £70 for the adult service / £50 for the child service). The agent assisting me with the application is {Call_Agent} and my <strong>unique payment reference number is {contact_id}. </strong></p>
<p style="text-align: left;">I have read, understood and agree to the Terms of Service found at <strong>http://04form.passportterms.co.uk</strong> and understand that the unaccredited service provided by British Passport Services is non-refundable and that by receiving the Application form and Special Delivery bag that the service has already begun.</p>
<p style="text-align: left;"><strong>P.S.</strong> I am looking to use the Standard 3-6 Week Application Service and not the express and more expensive Premium 1 day or Fast Track 1 Week renewal services all of which provided by Her Majesty\'s Passport Office.</p>
<p style="text-align: left;">Name: {First_Name} {Last_Name}</p>
<p style="text-align: left;"><span style="color: #ff0000;">Signature: ......................................................</span></p>
<address style="text-align: left;">Kind Regards</address><address style="text-align: left;">{First_Name} {Last_Name}</address><address style="text-align: left;">Tel: {Cell_Phone}</address><address style="text-align: left;">Email: {E-Mail}</address><center><img class="alignnone size-full wp-image-1863" alt="barcode2" src="http://www.britishpassportservices.co.uk/wp-content/uploads/2013/12/barcode2.png" width="766" height="154" /></center>
<p style="text-align: center;"><span style="color: #ff0000; font-size: small;"><em><strong>IMPORTANT: Return this receipt along with your documents and a cash or cheque payment.</strong></em></span></p>
<p style="text-align: center;"><span style="color: #800000;"><strong>Prefer to pay by Online, Mobile or Telephone Banking?</strong></span><strong> Text</strong> <span style="color: #800000;"><strong>BANK {contact_id}</strong></span> to <span style="color: #800000;"><strong>07786 200 351</strong></span> for to receive our bank details so that you can make an instant money transfer into our commercial bank account.</p>')

?>