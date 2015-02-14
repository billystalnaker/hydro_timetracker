<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<b style='text-decoration: underline'>Finished Tasks (Last 10)</b>
				<div id='finshed_tasks'>

				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<b style='text-decoration: underline'>Un-Finished Tasks</b>
				<div id='unfinished_tasks'>

				</div>
			</div>
		</div>
	</div>
</div>
<?php
if($is_logged){
	if($this->flexi_auth->is_privileged('User Clock Entries')){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						User Clock Entries
					</div>
					<div class="panel-body">
						<div class="panel-group" id="user_clock_entry_accordion"><?php
							if($this->flexi_auth->is_privileged('View User Clock Entries')){
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#user_clock_entry_accordion" href="#view_user_clock_entry_panel" >View</a>
										</h4>
									</div>
									<div class="panel-collapse collapse in" id="view_user_panel">
										<div class="panel-body">
											<h3>View User Clock Entries</h3>
											<p>Here you can view user clock entries</p>
											<!--<a href="<?php echo site_url('module/users/view'); ?>">View Users</a>-->
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Start User Clock Entries')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#user_clock_entry_accordion" href="#add_user_clock_entry_panel">Start</a>
										</h4>
									</div>
									<div class="panel-collapse in" id="add_user_clock_entry_panel">
										<div class="panel-body">
											<h3>Start a User Clock Entries</h3>
											<p>Here you can start user clock entries.</p>
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
													<label class="col-md-3" >Start the Clock:</label>
													<div class="input-group col-md-9">
														<input class="btn btn-primary" disabled type="button"  id="start_clock" value="Start Clock"/>
													</div>
												</div>
											</div>
								<!--<a href="<?php echo site_url('module/users/add'); ?>">Add Users</a>-->
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Stop User Clock Entries')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#user_clock_entry_accordion" href="#stop_user_clock_entry_panel">Stop</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="stop_user_clock_entry_panel">
										<div class="panel-body">
											<h3>Stop a User Clock Entries</h3>
											<p>Here you can stop user clock entries.</p>
											<div class="col-md-6">
												<div class="form-group row">
													<label class="col-md-3" >Started Tasks:</label>
													<div class="input-group col-md-9">
														<span class="input-group-addon"><i class="fa-flag-o fa"></i></span>
														<?php
														echo form_dropdown('stop_task', $started_task_options, set_value('stop_task'), "id='started_tasks' class='form-control'");
														?>
													</div>
												</div>
												<div class="form-group row">
													<label class="col-md-3" >Stop the Clock:</label>
													<div class="input-group col-md-9">
														<input class="btn btn-primary" disabled type="button"  id="stop_clock" value="Stop Clock"/>
													</div>
												</div>
											</div>
								<!--<a href="<?php echo site_url('module/users/add'); ?>">Add Users</a>-->
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}

	if($this->flexi_auth->is_privileged('Users')){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Users
					</div>
					<div class="panel-body">
						<div class="panel-group" id="user_accordion"><?php
							if($this->flexi_auth->is_privileged('View Users')){
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#user_accordion" href="#view_user_panel" >View</a>
										</h4>
									</div>
									<div class="panel-collapse collapse in" id="view_user_panel">
										<div class="panel-body">
											<h3>View Users</h3>
											<p>Here you can view users.</p>
											<a href="<?php echo site_url('module/users/view'); ?>">View Users</a>
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Add Users')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#user_accordion" href="#add_user_panel">Add</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="add_user_panel">
										<div class="panel-body">
											<h3>Add Users</h3>
											<p>Here you can add users.</p>
											<a href="<?php echo site_url('module/users/add'); ?>">Add Users</a>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}
	if($this->flexi_auth->is_privileged('Groups')){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Groups
					</div>
					<div class="panel-body">
						<div class="panel-group" id="group_accordion"><?php
							if($this->flexi_auth->is_privileged('View Groups')){
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#group_accordion" href="#view_group_panel" >View</a>
										</h4>
									</div>
									<div class="panel-collapse collapse in" id="view_group_panel">
										<div class="panel-body">
											<h3>View Groups</h3>
											<p>Here you can view groups.</p>
											<a href="<?php echo site_url('module/groups/view'); ?>">View groups</a>
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Add Groups')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#group_accordion" href="#add_group_panel">Add</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="add_group_panel">
										<div class="panel-body">
											<h3>Add Groups</h3>
											<p>Here you can add groups.</p>
											<a href="<?php echo site_url('module/groups/add'); ?>">Add groups</a>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}
	if($this->flexi_auth->is_privileged('Privileges')){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Privileges
					</div>
					<div class="panel-body">
						<div class="panel-group" id="privileges_accordion"><?php
							if($this->flexi_auth->is_privileged('View Privileges')){
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#privileges_accordion" href="#view_privileges_panel" >View</a>
										</h4>
									</div>
									<div class="panel-collapse collapse in" id="view_privileges_panel">
										<div class="panel-body">
											<h3>View Privileges</h3>
											<p>Here you can view privileges.</p>
											<a href="<?php echo site_url('module/privileges/view'); ?>">View Privileges</a>
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Add Privileges')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#privileges_accordion" href="#add_privileges_panel">Add</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="add_privileges_panel">
										<div class="panel-body">
											<h3>Add Privileges</h3>
											<p>Here you can add privileges.</p>
											<a href="<?php echo site_url('module/privileges/add'); ?>">Add Privileges</a>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}
	if($this->flexi_auth->is_privileged('Projects')){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Projects
					</div>
					<div class="panel-body">
						<div class="panel-group" id="projects_accordion"><?php
							if($this->flexi_auth->is_privileged('View Projects')){
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#projects_accordion" href="#view_projects_panel" >View</a>
										</h4>
									</div>
									<div class="panel-collapse collapse in" id="view_projects_panel">
										<div class="panel-body">
											<h3>View Projects</h3>
											<p>Here you can view projects.</p>
											<a href="<?php echo site_url('module/projects/view'); ?>">View Projects</a>
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Add Projects')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#projects_accordion" href="#add_projects_panel">Add</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="add_projects_panel">
										<div class="panel-body">
											<h3>Add Projects</h3>
											<p>Here you can add projects.</p>
											<a href="<?php echo site_url('module/projects/add'); ?>">Add Projects</a>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}

	if($this->flexi_auth->is_privileged('Tasks')){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Tasks
					</div>
					<div class="panel-body">
						<div class="panel-group" id="tasks_accordion"><?php
							if($this->flexi_auth->is_privileged('View Tasks')){
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#tasks_accordion" href="#view_tasks_panel" >View</a>
										</h4>
									</div>
									<div class="panel-collapse collapse in" id="view_tasks_panel">
										<div class="panel-body">
											<h3>View Tasks</h3>
											<p>Here you can view tasks.</p>
											<a href="<?php echo site_url('module/tasks/view'); ?>">View Tasks</a>
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Add Tasks')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#tasks_accordion" href="#add_tasks_panel">Add</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="add_tasks_panel">
										<div class="panel-body">
											<h3>Add Tasks</h3>
											<p>Here you can add tasks.</p>
											<a href="<?php echo site_url('module/tasks/add'); ?>">Add Tasks</a>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}


	if($this->flexi_auth->is_privileged('Clock Entries')){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Clock Entries
					</div>
					<div class="panel-body">
						<div class="panel-group" id="clock_entries_accordion"><?php
							if($this->flexi_auth->is_privileged('View Clock Entries')){
								?>
								<div class="panel panel-default">
									<div class="panel-heading ">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#clock_entries_accordion" href="#view_clock_entries_panel" >View</a>
										</h4>
									</div>
									<div class="panel-collapse collapse in" id="view_clock_entries_panel">
										<div class="panel-body">
											<h3>View Clock Entries</h3>
											<p>Here you can view clock_entries.</p>
											<a href="<?php echo site_url('module/clock_entries/view'); ?>">View Clock Entries</a>
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Add Clock Entries')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#clock_entries_accordion" href="#add_clock_entries_panel">Add</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="add_clock_entries_panel">
										<div class="panel-body">
											<h3>Add Clock Entries</h3>
											<p>Here you can add clock entries.</p>
											<a href="<?php echo site_url('module/clock_entries/add'); ?>">Add Clock Entries</a>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}
	if($this->flexi_auth->is_privileged('Customers')){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Customers
					</div>
					<div class="panel-body">
						<div class="panel-group" id="customers_accordion"><?php
							if($this->flexi_auth->is_privileged('View Customers')){
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#customers_accordion" href="#view_customers_panel" >View</a>
										</h4>
									</div>
									<div class="panel-collapse collapse in" id="view_customers_panel">
										<div class="panel-body">
											<h3>View Customers</h3>
											<p>Here you can view customers.</p>
											<a href="<?php echo site_url('module/customers/view'); ?>">View Customers</a>
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Add Customers')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#customers_accordion" href="#add_customers_panel">Add</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="add_customers_panel">
										<div class="panel-body">
											<h3>Add Customers</h3>
											<p>Here you can add customers.</p>
											<a href="<?php echo site_url('module/customers/add'); ?>">Add Customers</a>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}
	if($this->flexi_auth->is_privileged('External Users')){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						External Users
					</div>
					<div class="panel-body">
						<div class="panel-group" id="external_users_accordion"><?php
							if($this->flexi_auth->is_privileged('View External Users')){
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#external_users_accordion" href="#view_external_users_panel" >View</a>
										</h4>
									</div>
									<div class="panel-collapse collapse in" id="view_external_users_panel">
										<div class="panel-body">
											<h3>View External Users</h3>
											<p>Here you can view external users.</p>
											<a href="<?php echo site_url('module/external_users/view'); ?>">View External Users</a>
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Add External Users')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#external_users_accordion" href="#add_external_users_panel">Add</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="add_external_users_panel">
										<div class="panel-body">
											<h3>Add External Users</h3>
											<p>Here you can add external users.</p>
											<a href="<?php echo site_url('module/external_users/add'); ?>">Add External Users</a>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}
	if($this->flexi_auth->is_privileged('External User Types')){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						External User Types
					</div>
					<div class="panel-body">
						<div class="panel-group" id="external_user_types_accordion"><?php
							if($this->flexi_auth->is_privileged('View External User Types')){
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#external_user_types_accordion" href="#view_external_user_types_panel" >View</a>
										</h4>
									</div>
									<div class="panel-collapse collapse in" id="view_external_user_types_panel">
										<div class="panel-body">
											<h3>View External User Types</h3>
											<p>Here you can view external user types.</p>
											<a href="<?php echo site_url('module/external_user_types/view'); ?>">View External User Types</a>
										</div>
									</div>
								</div>
								<?php
							}
							if($this->flexi_auth->is_privileged('Add External User Types')){
								?>
								<div class="panel panel-default">

									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#external_user_types_accordion" href="#add_external_user_types_panel">Add</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="add_external_user_types_panel">
										<div class="panel-body">
											<h3>Add External User Types</h3>
											<p>Here you can add external user types.</p>
											<a href="<?php echo site_url('module/external_user_types/add'); ?>">Add External User Types</a>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}
	?><script type="text/javascript">

			var tmp_svr_date='<?php echo date('Y-m-d H:i:s') ?>'.split(/[- :]/);
			var svr_date=new Date(tmp_svr_date[0], tmp_svr_date[1]-1, tmp_svr_date[2], tmp_svr_date[3], tmp_svr_date[4], tmp_svr_date[5]);
			var get_project_task_url='<?php echo site_url('api/get_project_tasks') ?>';
			var start_task_url='<?php echo site_url('api/start_task') ?>';
			var stop_task_url='<?php echo site_url('api/stop_task') ?>';
			var get_my_entries_url='<?php echo site_url('api/get_user_tasks'); ?>';
			$(function (){
				function update_svr_time(){
					svr_date.setSeconds(svr_date.getSeconds()+1);
					setTimeout(update_svr_time, 1000);
				}
				update_svr_time();
				$('#started_tasks').change(function (){
					var this_has_val=$(this).val()!='';
					if(this_has_val){
						$('#stop_clock').attr('disabled', false);
					}else{
						$('#stop_clock').attr('disabled', true);
					}
				});
				$('#stop_clock').click(function (){
					var clock_entry_id=$('#started_tasks').val();
					var params={
						clock_entry_id: clock_entry_id
					};
					$.post(stop_task_url, params, function (data){
						if(data){
							$('#started_tasks option[value="'+clock_entry_id+'"]').remove();
							$('#stop_clock').attr('disabled', true);
							get_finished();
							bootbox.alert('<h2>Success!</h2> The time has stopped.');
						}else{
							bootbox.alert('<h2>Uh oh...</h2>Something went wrong...');
						}
					});
				});
				var $project_tasks=$('#project_task_ids');
				var blank_option=$("<option />").val('').html('Please Select');
				$('#projects').change(function (){
					var params={
						project_id: $(this).val()
					};
					$.post(get_project_task_url, params, function (data){
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
				$('#project_task_ids').change(function (){
					var this_has_val=$(this).val()!='';
					var project_has_val=$('#projects').val()!='';
					if(this_has_val&&project_has_val){
						$('#start_clock').attr('disabled', false);
					}else{
						$('#start_clock').attr('disabled', true);
					}
				});
				$('#start_clock').click(function (){
					var params={
						project_task_id: $('#project_task_ids').val()
					};
					$.post(start_task_url, params, function (data){
						if(!data.error){
							bootbox.alert('<h2>Success!</h2> The time has started.');
							get_unfinished();
							$('#start_clock').attr('disabled', true);
						}else{
							var boot_string="<h2>Uh oh...</h2>";
							for(var k in data.error){
								boot_string+=(data.error[k]+"<br />");
							}
							bootbox.alert(boot_string);
						}
					});
				});
				function get_finished(){
					$.post(get_my_entries_url, function (data){
						$('#finshed_tasks').html(data);
					});
				}
				function get_unfinished(){
					var params={
						status: 'unfinished'
					};
					$.post(get_my_entries_url, params, function (data){
						$('#unfinished_tasks').html(data);
					});
				}
				get_finished();
				get_unfinished();


				function update_times(){
					$('.start-time').each(function (){
						var key=$(this).data('key');
						// Split timestamp into [ Y, M, D, h, m, s ]
						var t=$(this).html().split(/[- :]/);
						// Apply each element to the Date function
						var startTime=new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
	//						var endTime=new Date();
						var endTime=svr_date;
						console.log(svr_date, startTime);
						var timeDiff=endTime-startTime;
						var timeDiff=timeDiff/1000;
						var seconds=Math.round(timeDiff%60);
						timeDiff=Math.floor(timeDiff/60);
						var minutes=Math.round(timeDiff%60);
						timeDiff=Math.floor(timeDiff/60);
						var hours=Math.round(timeDiff%24);
						timeDiff=Math.floor(timeDiff/24);
						var days=timeDiff;
						var time_string="";
						var plural="";
						if(days>0){
							if(days>1){
								plural="s";
							}
							time_string+=(days+" day"+plural+", ");
						}
						if(hours>0){
							plural="";
							if(hours>1){
								plural="s";
							}
							time_string+=(hours+" hour"+plural+", ");
						}
						plural="";
						if(minutes>1){
							plural="s";
						}
						time_string+=(minutes+" minute"+plural+", ");
						plural="";
						if(seconds>1){
							plural="s";
						}
						time_string+=(seconds+" second"+plural);

						//=days+" days,"+hours+" hours,"+minutes+" minutes,"+seconds+" seconds";
						$('#elapsed-time-'+key).html(time_string);
					});
					setTimeout(update_times, 1000);
				}
				update_times();
				$('body').on('click', '.stop-task', function (){
					var remove=$(this).closest('.remove-me');
					var clock_entry_id=$(this).attr('id');
					var params={
						clock_entry_id: clock_entry_id
					};
					$.post(stop_task_url, params, function (data){
						if(data){
							remove.remove();
							get_finished();
							bootbox.alert('<h2>Success!</h2> The time has stopped.');
						}else{
							bootbox.alert('<h2>Uh oh...</h2>Something went wrong...');
						}
					});
				});
			});
	</script>
	<?php
}