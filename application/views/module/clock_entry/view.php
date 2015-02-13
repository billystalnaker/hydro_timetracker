
<div class="row">
    <div class="col-lg-12">
		<h1 class="page-header">View Clock Entries</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<?php if(!empty($message)){ ?>
		<div id="message">
		<?php echo $message; ?>
	</div>
<?php } ?>

<div class="row">
    <div class="col-lg-12">
		<?php if($this->flexi_auth->is_privileged('Add Clock Entries')){ ?>
		<a href="<?php echo site_url('module/clock_entries/add') ?>" >Add a clock entry...</a>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				Clock Entries
			</div>
			<!-- /.panel-heading -->
			<form action="<?php echo current_url(); ?>" method="POST" >
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="data_table">
							<thead>
								<tr>
									<th>Project Task</th>
									<th>User</th>
									<th>Start Time</th>
									<th>Stop Time</th>
									<th>Delete</th>
								</tr>
							</thead>
							<?php if(!empty($clock_entries)){ ?>
							<tbody>
									<?php
									foreach($clock_entries as $clock_entry){
										$project_task_info	 = $this->modules->get_project_task($clock_entry['project_task_id'], true);
											$project_info		 = $this->modules->get_project($project_task_info['project_id'], true);
											$task_info			 = $this->modules->get_task($project_task_info['task_id'], true);
											$project_task_name	 = $project_info['name'].' - '.$task_info['name'];
											?>
									<tr>
												<td>
													<?php if($this->flexi_auth->is_privileged('Edit Clock Entries')){ ?>
														<a href="<?php echo site_url("module/clock_entries/edit/".$clock_entry['id']); ?>">
															<?php echo $project_task_name; ?>
														</a>
													<?php }else{ ?>
														<?php echo $project_task_name; ?>
													<?php } ?>
												</td>
												<td>
													<?php
													$user_info = $this->modules->get_user($clock_entry['user_id'], true);
													echo ($user_info['upro_first_name'].' '.$user_info['upro_last_name']);
													?>
												</td>
												<td>
													<?php echo $clock_entry['start']; ?>
												</td>
												<td>
													<?php echo $clock_entry['stop']; ?>
												</td>
												<td class="align_ctr">
													<?php if($this->flexi_auth->is_privileged('Delete Clock Entries')){ ?>
														<input type="checkbox" name="delete_clock_entry[<?php echo $clock_entry['id']; ?>]" value="1"/>
													<?php }else{ ?>
														<input type="checkbox" disabled="disabled"/>
														<small>Not Privileged</small>
														<input type="hidden" name="delete_clock_entry[<?php echo $clock_entry['id']; ?>]" value="0"/>
													<?php } ?>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								<?php } ?>
						</table>
					</div>
					<input class="btn btn-primary" type="submit" value="Update Clock Entries" name="update_clock_entries"/>
				</div>
			</form>
		</div>
    </div>
</div>