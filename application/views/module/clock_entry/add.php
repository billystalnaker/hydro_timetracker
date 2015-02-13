<div class="row">
    <div class="col-lg-12">
		<h1 class="page-header">Add a Clock Entry</h1>
    </div>
</div>
<?php
if(!empty($message)){
	?>
		<div id="message">
		<?php echo $message; ?>
	</div>
<?php } ?>

<?php echo validation_errors("<div class='row'> <div class='alert alert-danger col-md-6'>", "</div><div class='col-md-6'></div></div>"); ?>

<form action="<?php echo current_url(); ?>" method="POST">
    <div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-md-3" >Project:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-flag fa"></i></span>
						<?php
						echo form_dropdown('insert_clock_entry_project_id', $project_options, set_value('insert_clock_entry_project_id'), "id='projects' class='form-control'");
						?>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3" >Task:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-flag-o fa"></i></span>
						<?php
						echo form_dropdown('insert_clock_entry_project_task_id', array(), set_value('insert_clock_entry_project_task_id'), "id='project_task_ids' class='form-control'");
						?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >User:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-user fa"></i></span>
						<?php
						echo form_dropdown('insert_clock_entry_user_id', $user_options, set_value('insert_clock_entry_user_id', USER_ID), "class='form-control'");
?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Start Time:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-clock-o fa"></i></span>
						<input class="form-control date-time-picker" type="text" value="<?php echo set_value('insert_clock_entry_start') ?>" name="insert_clock_entry_start">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Stop Time:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-clock-o fa"></i></span>
						<input class="form-control date-time-picker" type="text" value="<?php echo set_value('insert_clock_entry_stop') ?>" name="insert_clock_entry_stop">
					</div>
				</div>
				<div class="form-group">
					<input class="form-control btn btn-primary" type="submit" id="" name="insert_clock_entry_submit">
				</div>
			</div>
		</div>
    </div>
</form>
<script>
	var get_project_task_url='<?php echo site_url('api/get_project_tasks') ?>';
	$(function(){
		var $project_tasks=$('#project_task_ids');
		var blank_option=$("<option />").val('').html('Please Select');
		$('#projects').change(function(){
			var params={
				project_id: $(this).val()
			};
			$.post(get_project_task_url, params, function(data){
				$project_tasks.empty();
				$project_tasks.append(blank_option);
				if(!$.isEmptyObject(data)){
					for(var k in data){
						var option=$('<option />').val(k).html(data[k]);
						$project_tasks.append(option);
					}
				}else{
					bootbox.alert('<h2>No Tasks Assigned</h2>That project does not have any tasks assigned to it.');
				}
			});
		});
	});
</script>
