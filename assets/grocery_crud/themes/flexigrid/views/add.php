<?php

	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_theme_path.'/flexigrid/js/jquery.form.js');
	$this->set_js_config($this->default_theme_path.'/flexigrid/js/flexigrid-add.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
	
?>
<script type="text/javascript" src="<?php echo asset_url(); ?>fancyBox-v2.1.5/lib/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="<?php echo asset_url(); ?>fancyBox-v2.1.5/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url(); ?>fancyBox-v2.1.5/source/jquery.fancybox.css?v=2.1.5" media="screen" />

<script type="application/javascript">
// Added By Hafiz Saqib Javed to send newly added Value Label to Parent
// ANd Parent methods to recieve and display
var optionLabel = "";
var selectedParent = "";
function setNewParentId(parentId){
	selectedParent = parentId;
}

function receiveNewParentValue(value){
	optionLabel = value;
}

function receiveNewParentKey(key){
	$("#"+selectedParent).prepend("<option value='"+key+"'>"+optionLabel+"</option>");
	$("#"+selectedParent+" :nth-child(1)").prop('selected', true);  // Select first child
	selectedParent = "";
	optionLabel = "";
}

function sendDataToParent(){
	<?php if($parent_add_form_field!=null){ ?>
		var entryLabel = $('#field-<?=$parent_add_form_field?>').val();
		parent.receiveNewParentValue(entryLabel);
		<?php 
	}
		?>
	return true;
}

function sendKeyToParent(key){
	parent.receiveNewParentKey(entryLabel);
}
</script>

<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
	<div class="mDiv">
		<div class="ftitle">
			<div class='ftitle-left'>
				<?php echo $this->l('form_add'); ?> <?php echo $subject?>
			</div>
			<div class='clear'></div>
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>
	</div>
<div id='main-table-box'>
	<?php echo form_open( $insert_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
		<div class='form-div'>
			<?php
			$counter = 0;
				foreach($fields as $field)
				{
					$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
					$counter++;
					
			?>
			<div class='form-field-box <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
				<div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
					<?php echo $input_fields[$field->field_name]->display_as; ?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""; ?> :
				</div>
				<div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
					<?php echo $input_fields[$field->field_name]->input?> 
					<?php if(isset($parent_add_form[$field->field_name])){ ?>
				<div class="fbutton" style="float: right; margin-left: 20px;">
					<span class="add"><a onclick="setNewParentId('<?="field-".$field->field_name?>');" class="fancybox fancybox.iframe" href="<?=$parent_add_form[$field->field_name][1]?>">Add <?=$field->field_name;?> </a></span>
				</div>
			
					<?php }
					?>
				</div>
				
				<div class='clear'></div>
			</div>
			<?php }?>
			<!-- Start of hidden inputs -->
				<?php
					foreach($hidden_fields as $hidden_field){
						echo $hidden_field->input;
					}
				?>
			<!-- End of hidden inputs -->
			<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>

			<div id='report-error' class='report-div error'></div>
			<div id='report-success' class='report-div success'></div>
		</div>
		<div class="pDiv">
			<div class='form-button-box'>
				<input id="form-button-save" onclick="sendDataToParent();" type='submit' value='<?php echo $this->l('form_save'); ?>'  class="btn btn-large"/>
			</div>
<?php 	if(!$this->unset_back_to_list) { ?>
			<div class='form-button-box'>
				<input type='button' value='<?php echo $this->l('form_save_and_go_back'); ?>' id="save-and-go-back-button"  class="btn btn-large"/>
			</div>
			<div class='form-button-box'>
				<input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-large" id="cancel-button" />
			</div>
<?php 	} ?>
			<div class='form-button-box'>
				<div class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></div>
			</div>
			<div class='clear'></div>
		</div>
	<?php echo form_close(); ?>
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';
	var message_alert_add_form = "<?php echo $this->l('alert_add_form')?>";
	var message_insert_error = "<?php echo $this->l('insert_error')?>";
</script>