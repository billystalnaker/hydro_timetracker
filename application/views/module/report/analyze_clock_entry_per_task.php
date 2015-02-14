<div class="row">
    <div class="col-lg-12">
		<h1 class="page-header">Analyze Clock Entries Per Task</h1>
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
					<label class="col-md-3" >Task:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-flag-o fa"></i></span>
						<?php
						echo form_dropdown('task_id', $task_options, set_value('task_id'), "id='task_id' class='form-control'");
						?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >User:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-user fa"></i></span>	
						<?php
						echo form_dropdown('user_id', $user_options, set_value('user_id'), "class='form-control'");
						?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Date From:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-clock-o fa"></i></span>
						<input class="form-control date-time-picker" type="text" value="<?php echo set_value('date_le') ?>" name="date_le">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Date To:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-clock-o fa"></i></span>
						<input class="form-control date-time-picker" type="text" value="<?php echo set_value('date_ge') ?>" name="date_ge">
					</div>
				</div>
				<div class="form-group">
					<input class="form-control btn btn-primary" type="submit" id="" name="analyze">
				</div>
			</div>
		</div>
    </div>
</form>
<?php if(count($results) > 0){ ?>
	<table class="table table-hover">
		<thead>
		<th>
			Task
		</th>
		<th>
			User
		</th>

		<th>
			Time
		</th>
	</thead>
	<tbody>
		<?php
		$totals	 = [];
		$old_tid = 0;
		foreach($results as $tid => $tasks){
			if(!isset($totals[$tid])){
				$totals[$tid] = 0;
			}
			if($old_tid != $tid){
				$task_info	 = $this->modules->get_task($tid, true);
				$task_name	 = $task_info['name'];
				if($old_tid > 0){
					?>
					<tr>
						<td align="center" colspan="2" style=" background-color: beige; ">
							Task Total
						</td>
						<td><?php echo $this->modules->format_seconds($totals[$old_tid]); ?></td>
					</tr>
					<?php
					unset($totals[$old_tid]);
				}
				$old_tid = $tid;
			}
			$old_uid = 0;
			foreach($tasks as $uid => $times){
				$user_name = "";
				if($old_uid != $uid){
					$user_info	 = $this->modules->get_user($uid, true);
					$user_name	 = $user_info['upro_first_name'].' '.$user_info['upro_last_name'];
					$old_uid	 = $uid;
				}
				$total_time = 0;
				foreach($times as $time){
					$total_time+= $time;
				}
				$totals[$tid] +=$total_time;
				?>
				<tr>
					<td><?php echo $task_name; ?></td>
					<td><?php echo $user_name; ?></td>
					<td><?php echo $this->modules->format_seconds($total_time); ?></td>
				</tr>
				<?php
			}
		}
		foreach($totals as $tid => $time){
			?>
			<tr>
				<td align="center" colspan="2" style=" background-color: beige; ">
					Task Total
				</td>
				<td><?php echo $this->modules->format_seconds($totals[$tid]); ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
	</table>
	<?php
}?>