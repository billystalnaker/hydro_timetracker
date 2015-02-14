<div class="row">
    <div class="col-lg-12">
		<h1 class="page-header">Analyze Clock Entries Per Project</h1>
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
					<label class="col-md-3" >Project ID:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa fa-flag"></i></span>
						<?php
						echo form_dropdown('project_id', $project_options, set_value('project_id'), "id='project_id' class='form-control'");
						?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Task:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-flag-o fa"></i></span>
						<?php
						$task_value = set_value('task_id');
						echo form_dropdown('task_id', [], $task_value, "id='task_id' class='form-control'");
						?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >User:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-user fa"></i></span>	
						<?php
						echo form_dropdown('user_id', $user_options, set_value('user_id'), "id='user_id' class='form-control'");
						?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Date From:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-clock-o fa"></i></span>
						<input id="date_le" class="form-control date-time-picker" type="text" value="<?php echo set_value('date_le') ?>" name="date_le">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Date To:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-clock-o fa"></i></span>
						<input id="date_ge" class="form-control date-time-picker" type="text" value="<?php echo set_value('date_ge') ?>" name="date_ge">
					</div>
				</div>
				<div class="form-group">
					<input class="form-control btn btn-primary" type="submit" id="" name="analyze">
				</div>
			</div>
		</div>
    </div>
</form>
<script>
	var get_project_task_url='<?php echo site_url('api/get_project_tasks') ?>';
	var task_value='<?php echo $task_value ?>';
	$(function(){
		$('#task_id').change(function(){
			$('.download_task_id').val($(this).val());
		});
		$('#user_id').change(function(){
			$('.download_user_id').val($(this).val());
		});
		$('#date_le').change(function(){
			$('.download_date_le').val($(this).val());
		});
		$('#date_ge').change(function(){
			$('.download_date_ge').val($(this).val());
		});
		var $project_tasks=$('#task_id');
		var blank_option=$("<option />").val('').html('Please Select');
		$('#project_id').change(function(){
			var params={
				project_id: $(this).val()
			};
			if($(this).val()!=''){
				$('.download_project_id').val($(this).val());
				$.post(get_project_task_url, params, function(data){
					$project_tasks.empty();
					$project_tasks.append(blank_option);
					if(!$.isEmptyObject(data)){
						for(var k in data){
							var option=$('<option />').val(k).html(data[k]);
							if(k==task_value){
								option.attr('selected', true);
							}
							$project_tasks.append(option);
						}
						$('#task_id').change();
					}else{
						bootbox.alert('<h2>No Tasks Assigned</h2>That project does not have any tasks assigned to it.');
					}
				});
			}
		});
		$('#project_id').change();
//		$('#task_id').change();
		$('#user_id').change();
		$('#date_le').change();
		$('#date_ge').change();
	});
</script>
<?php
if($this->input->post()){
	?>
	<form class="download_form" action="<?php echo site_url('download/per_project_pdf'); ?>" method="POST">
		<input class="download_project_id" type="hidden" name="project_id"/>
		<input class="download_task_id" type="hidden" name="task_id"/>
		<input class="download_user_id" type="hidden" name="user_id"/>
		<input class="download_date_le" type="hidden" name="date_le"/>
		<input class="download_date_ge" type="hidden" name="date_ge"/>
		<input class="btn btn-primary" type="submit" name="download_pdf_submit" value="Get PDF"/>
	</form>
	<form class="download_form" action="<?php echo site_url('download/per_project_xls'); ?>" method="POST">
		<input class="download_project_id" type="hidden" name="project_id"/>
		<input class="download_task_id" type="hidden" name="task_id"/>
		<input class="download_user_id" type="hidden" name="user_id"/>
		<input class="download_date_le" type="hidden" name="date_le"/>
		<input class="download_date_ge" type="hidden" name="date_ge"/>
		<input class="btn btn-primary" type="submit" name="download_xls_submit" value="Get XLS"/>
	</form>
	<?php
}
$this->load->view('tpl/result/per_project_results', $this->data);
