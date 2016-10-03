<?php


interface iField
{
	
    public function render($initialData);
    public function getData();
	public function getID();
}

class TextArea implements iField
{
	public $ID;
    private $_name;
	private $_class;
	private $_desc;
	function __construct($fieldData) {
		$this->ID=  $fieldData['id'];
		$this->_name = $fieldData['name'];
		$this->_class = $fieldData['class'];
		$this->_desc = $fieldData['desc'];
	}
	public function render($initialData) {		
		echo '<table class="form-table">';	
		echo '<tr>';
		echo '<th><label for="'.$this->ID.'">'. stripslashes($this->_name). '</label></th>';
		echo '<td><textarea style="width:100%; min-height:300px;" name="'.$this->ID.'" id="'.$this->ID.'"  class="'.$this->_class.'" >'. $initialData.'</textarea><br/></td>';		
		echo '</tr>';
		echo '</table>';
	}
	public function getData() {
		return   $_POST[$this->ID];
	}
	public function getID() {
		return $this->ID;
	}
	public function getDefaultData() {
		return "defaultData";
	}
	
}

class TextField implements iField
{
	public $ID;
    private $_name;
	private $_class;
	private $_desc;
	function __construct($fieldData) {
		$this->ID=  $fieldData['id'];
		$this->_name = $fieldData['name'];
		$this->_class = $fieldData['class'];
		$this->_desc = $fieldData['desc'];
	}
	public function render($initialData) {		
		echo '<table class="form-table">';	
		echo '<tr>';
		echo '<th><label for="'.$this->ID.'">'. stripslashes($this->_name). '</label></th>';
		echo '<td><input style="width:100%; " name="'.$this->ID.'" id="'.$this->ID.'"  class="'.$this->_class.'" value="'.$initialData.'" /></td>';		
		echo '</tr>';
		echo '</table>';
	}
	public function getData() {
		return   $_POST[$this->ID];
	}
	public function getID() {
		return $this->ID;
	}
	public function getDefaultData() {
		return "defaultData";
	}
	
}


class RTMSteps implements iField
{
	public $ID;
    private $_name;
	private $_class;
	private $_desc;
	
	function __construct($fieldData) {
		$this->ID=  $fieldData['id'];
		$this->_name = $fieldData['name'];
		$this->_class = $fieldData['class'];
		$this->_desc = $fieldData['desc'];
		
		add_action( 'admin_enqueue_scripts',  array($this,'add_scripts') );
	}
	
	public function render($initialData) {		
		echo '<table class="form-table">';	
		echo '<tr>',
					'<th style="width:10%"><label for="', $field['id'], '">', stripslashes($this->_name), '</label><br /><br /><br /><br /><br />
					<a id="new-step" href="#">'.__('Add new step','retete').'</a></th>',
					'<td > <div id="'.$this->ID.'-accordion">';
					$i= 1;					
					if(count($initialData)!=0) {	
					$q=0;					
						foreach($initialData as $data) {
							
							if(is_array($data)) {
								
								$img = $data['img'];
								$text = $data['text'];
							} else {
								$text = $data;
								$img = "";
							}
							echo "<div>";
							echo "<h3>Step ".$i."</h3>";
							echo "<div>";
							echo "<div style='width:50%;float:left;'>";
							echo '<textarea name="'.$this->ID.'['.$q.'][text]"  class="'.$this->_class.'" >'. $text.'</textarea><br/>', '', stripslashes($this->_desc);
							echo "</div>";
							echo "<div style='width:50%;float:left; text-align:center; padding:10px 0px;'>";
							echo "<div class='image_ct' style='padding:10px'>";
							echo ($img!="")?"<img src='".$img."' style='max-width:200px; max-height:200px;' >":"";
							echo "</div>";
							echo "<input name='".$this->ID."[".$q."][img]' value='".$img."'><input type='button' class='upload_image_button' value='Add Image' />";
							echo "</div>";
							echo "<div style='clear:both;'></div>";
							echo "</div>";
							echo "</div>";
							$i++;
							$q++;
						}
					}
					echo "<div>";
							echo "<h3>Step ".$i."</h3>";
							echo "<div>";
							echo "<div style='width:50%;float:left;'>";
							echo '<textarea name="'.$this->ID.'['.$q.'][text]"  class="'.$this->_class.'" ></textarea><br/>', '', stripslashes($this->_desc);
							echo "</div>";
							echo "<div style='width:50%;float:left; text-align:center; padding:10px 0px;'>";
							echo "<div class='image_ct' style='padding:10px'>";							
							echo "</div>";
							echo "<input name='".$this->ID."[".$q."][img]' value=''><input type='button' class='upload_image_button' value='Add Image' />";
							echo "</div>";
							echo "<div style='clear:both;'></div>";
							echo "</div>";
							echo "</div>";
				echo "</div>";
			echo    '<td>',
				'</tr>';
		echo "</table>";			
		
	}
	
	public function getData() {
		return  $this->_sanitizeArray($_POST[$this->ID]);
	}
	
	public function getID() {
		return  $this->ID;
	}
	
	private function _sanitizeArray($ar) {
		$ret = array();
		foreach($ar as $key => $value) {
			if($value!='' ) {
				if(is_array($value) && $value['text']!="") {
					$ret[$key] =  $value ;
				} else if(!is_array($value)  && $value!="") {
					$ret[$key] =  $value ;
				}
			}
		}
		return $ret;
	}
	public function add_scripts($hook_suffix) {
   if( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) {
		wp_enqueue_style( 'rtmsteps',plugins_url( '/css/rtmsteps.css' , __FILE__ ) );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script(
			'rtmsteps',
			plugins_url( '/js/rtmsteps.js' , __FILE__ ),
			array( 'jquery' )
		);
   }
		
}
}
  