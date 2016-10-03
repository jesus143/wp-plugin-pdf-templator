<?php

class oap { 
    
    public $appid = "2_7818_gJU2tb18o";
	public $key = "ziq90r5HScqQfyx";
    
	
	public function registercontact($data=array()) {
		if(!empty($data)) {	
			$data = $this->create_contact_array($data);
			$data['Sequences and Tags'] = array(
				'Contact Tags' => 'Complete register'
			);
			return $this->oaprequest('add',$this->create_xml($data));	
		}
		return false;
	}
	function add_anything($data) {
		return $this->oaprequest("add",$this->create_xml($data));
	}
	function update($id=false,$data=array()) {
		if($id!=false && !empty($data)) {		
			$xml = simplexml_load_string($this->create_xml($data));
			$xml->addAttribute('id',$id);
			$dom = dom_import_simplexml($xml);
			$data = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);					
			$ret = simplexml_load_string($this->oaprequest('update',$data));	
			return (@$ret->status[0]=='Success')?true:false;		
		}
		return false;
	}
	public function updatecontact($id=false,$data=array()) {
		
		if($id!=false && !empty($data)) {
			$data = $this->create_contact_array($data);
			
			$xml = simplexml_load_string($this->create_xml($data));
			$xml->addAttribute('id',$id);
			$dom = dom_import_simplexml($xml);
			$data = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);		
			$ret =  simplexml_load_string($this->oaprequest('update',$data));	
			return (@$ret->status[0]=='Success')?true:false;		
		}
		return false;
	}
	/*update mobile recicling section*/
	/*
	  $id - INTEGER oap contact id
	  $data - array fields to update
	  
	  
	  
	/**/
	public function updatemobilerecicling($id=false,$data=array()) {
		
		$contactinfo = array('Title','Passport Expiration','Travelling to...','Date of Call Back','Date You Leave','Time','First Name','Reason You Need Address...',
		'Passport Office Addresses...','Passport Fees','Last Name','E-Mail','Home Phone','Office Phone','Cell Phone','Fax','Address','Address 2','County','City','Town','State',
		'Country','Zip Code','Company','Website','Birthday');
		
		$leadinfo = array('Contact Owner','First Referrer','Date You Return...','Text Messages','Minimum Order','You Are Aware This is a £100 Minumum Investment?',
		'Last Referrer','Bulk E-mail Status','Lead Source','Campaign','Ad','Media');
		
		$mobilerecycling = array('Recycled Phone Model','Barcode Number','Price Offered','Phone Condition','Delivery Method','Need Sales Pack Posted?','Sales Pack URL','Postage Address URL',
		'Mobile Network','Handset IMEI');
		
		$add_tag = array();
		
		if($id!=false && !empty($data)) {
			$formatedData = array();
			//$formatedData['Mobile Recycling']=array();
			foreach($data as $fieldname => $value) {
				
				if(in_array(str_replace("_"," ",$fieldname),$contactinfo)) {
					$formatedData['Contact Information'][str_replace("_"," ",$fieldname)]  = $value[0];					
				} else if( in_array(str_replace("_"," ",$fieldname),$leadinfo)) {
					$formatedData['Lead Information'][str_replace("_"," ",$fieldname)]  = $value[0];		
				} else if( in_array(str_replace("_"," ",$fieldname),$mobilerecycling)) {
					$formatedData['Mobile Recycling'][str_replace("_"," ",$fieldname)]  = $value[0];
				} else if ($fieldname=="Add_Tag") {
					$add_tag[]=$value[0];
				}
				
				
			}
			if(!empty($formatedData)) {
			$xml = simplexml_load_string($this->create_xml($formatedData));
			$xml->addAttribute('id',$id);
			$dom = dom_import_simplexml($xml);
			$data = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);						
			$ret =  simplexml_load_string($this->oaprequest('update',$data));	
			//$ret = (@$ret->status[0]=='Success')?true:false;
			}
			if(!empty($add_tag)) {
				$this->addTag($id,$add_tag);
			}
			
			 	return false;
		}
		return false;
	}
	
	public function fetchcontact($id=false) {
		 $usr =  $this->oaprequest('fetch','<contact_id>'.$id.'</contact_id>');	
		 $xml = simplexml_load_string($usr);
		 $user = array();
		 $userarr = $xml->contact->Group_Tag->field ;
		 if(!empty($userarr)) {
			 foreach ($userarr as $element) {			
				$user[str_replace(' ','_',$element['name'])] =(string) $element;			
			 }
		 } else {
			 $user = false;
		 }		 
		return  $user;
	}
	
	public function searchcontact($email) {
		$usr =  $this->oaprequest('search','<search><equation><field>E-Mail</field><op>e</op><value>'.$email.'</value></equation></search>');	
		$xml = simplexml_load_string($usr);
		 $user = array();
		//
		 $userarr = $xml->contact->Group_Tag;		
		 if(!empty($userarr)) {
			 foreach($xml->contact[0]->attributes() as $a => $b) {
			 		if($a=="id") { $user['ID']=(string)$b; }
			 }
			 foreach ($userarr->children() as $element) {			
				$user[str_replace(' ','_',$element['name'])] =(string) $element;			
			 }
		 } else {
			 $user = false;
		 }		
		 
		return  $user;
	}
	
	public function addTag($id=false,$tag='') {
		if($id!=false && $tag!='')  {			
			if(is_array($tag)) {	
				$data="<contact id='".$id."'>";
							
				foreach($tag as $tagv) {
						$data.="<tag>".$tagv."</tag>";		
				}
				$data.="</contact>";
			} else {
			$data="<contact id='".$id."'><tag>".$tag."</tag></contact>";			
			
			}
			return $this->oaprequest('add_tag',$data);
			
		}
		
		
	}
	public function addMobileSection($id=false,$data=array()) {
		
		if($id!=false && !empty($data)) {
			//$data = $this->create_contact_array($data);
			$xml = simplexml_load_string($this->create_xml($data));
			$xml->addAttribute('id',$id);
			$dom = dom_import_simplexml($xml);
			$data = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);		
			$ret =  simplexml_load_string($this->oaprequest('update',$data));	
			
			return (@$ret->status[0]=='Success')?true:false;			
		} else { //echo 'id or data not cool';
		}
		return false;		
	}
	
	function create_contact_array($data=array()) {
		
		foreach($data as $key =>$value) {
			switch($key) {
				case "user_fname":
				 $contact['Contact Information']['First Name'] = $value;
				 break;
				case "user_lname":
				 $contact['Contact Information']['Last Name'] = $value;;
				 break;
				case "user_email":
				 $contact['Contact Information']['E-Mail'] = $value;;
				 break;
				case "user_add1":
				 $contact['Contact Information']['Address'] = $value;;
				 break;
				case "user_add2":
				 $contact['Contact Information']['Address 2'] = $value;;
				 break;
				case "user_town":
				 $contact['Contact Information']['Town'] = $value;;
				 break;
				case "user_city":
				 $contact['Contact Information']['City'] = $value;;
				 break;
				case "user_state":
				 $contact['Contact Information']['State'] = $value;;
				 break;
				case "user_country":
				 $contact['Contact Information']['Country'] = $value;;
				 break;
				case "user_county":
				 $contact['Contact Information']['County'] = $value;;
				 break;
				case "user_postalcode":
				 $contact['Contact Information']['Zip Code'] = $value;;
				 break;
				case "user_phone":
				 $contact['Contact Information']['Home Phone'] = $value;;
				 break;
				case "user_mobile":
				 $contact['Contact Information']['Cell Phone'] = $value;;
				 break;
			}
		}		
		return $contact;
		
		
	}
	
	function create_xml($data=array()) {		
		if(!empty($data)) {
			$xml = new SimpleXMLElement('<contact/>');
			
			foreach ($data as $groupname => $fields) {
				$group = $xml->addChild('Group_Tag');
				$group->addAttribute('name', $groupname);	
				
				foreach($fields as $name => $value) {
					$field = $group->addChild('field',$value);
					$field->addAttribute('name', $name);
				}
			}
			$dom = dom_import_simplexml($xml);
			$contactData = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);
			return $contactData;			
		}
		return false;
	}
	
    public function oaprequest($reqType,$data) { 
       //Set your request type and construct the POST request
	   $data = urlencode(urlencode($data));
	   $postargs = "appid=".$this->appid."&key=".$this->key."&return_id=2&reqType=".$reqType. "&data=" . $data;
	   $request = "https://api.moon-ray.com/cdata.php";
	   
	   //Start the curl session and send the data
	   $session = curl_init($request);
	   curl_setopt ($session, CURLOPT_POST, true);
	   curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
	   curl_setopt($session, CURLOPT_HEADER, false);
	   curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	   
	   //Store the response from the API for confirmation or to process data
	   return curl_exec($session);
    } 
} 
?>