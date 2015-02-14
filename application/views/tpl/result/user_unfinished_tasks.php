<?php if(count($results) > 0){ ?>
	<table class="table table-hover">
		<thead>
		<th>
			Project - Task
		</th>
		<th>
			Started
		</th>
		<th>
			Timed Elapsed
		</th>
		<th>
			Stop Time
		</th>
	</thead>
	<tbody>
		<?php
		$totals				 = [];
		$project_task_info	 = [];
		$project_info		 = [];
		$task_info			 = [];
		foreach($results as $ptid => $entries){
			foreach($entries as $entry){
				$total_time		 = $entry['total'];
				$start_time		 = $entry['start'];
				$stop_time		 = $entry['stop'];
				$clock_entry_id	 = $entry['clock_entry_id'];
				if(!isset($project_task_info[$ptid])){
					$project_task_info[$ptid] = $this->modules->get_project_task($ptid, true);
				}
				$project_id	 = $project_task_info[$ptid]['project_id'];
				$task_id	 = $project_task_info[$ptid]['task_id'];
				if(!isset($project_info[$project_id])){
					$project_info[$project_id] = $this->modules->get_project($project_id, true);
				}
				if(!isset($task_info[$task_id])){
					$task_info[$task_id] = $this->modules->get_task($task_id, true);
				}
				$project_task_name = $project_info[$project_id]['name']." - ".$task_info[$task_id]['name'];
				?>
				<tr class="remove-me">
					<td><?php echo $project_task_name; ?></td>
					<td class="start-time" data-key="<?php echo $clock_entry_id ?>"><?php echo $start_time; ?></td>
					<td id="elapsed-time-<?php echo $clock_entry_id ?>"></td>
					<td><button class="stop-task btn btn-primary" id="<?php echo $clock_entry_id ?>">Stop Task</button></td>
				</tr>
				<?php
			}
		}
}else{
	?>
	<p><b>You are not currently working on any tasks.</b></p>
	<?php
}
?>