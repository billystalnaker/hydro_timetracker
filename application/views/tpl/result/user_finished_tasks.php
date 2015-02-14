<?php if(count($results) > 0){ ?>
	<table class="table table-hover">
		<thead>
		<th>
			Project - Task
		</th>
		<th>
			Start
		</th>
		<th>
			Stop
		</th>
		<th>
			Total
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
				$total_time	 = $entry['total'];
				$start_time	 = $entry['start'];
				$stop_time	 = $entry['stop'];
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
				?>	<tr>
					<td><?php echo $project_task_name; ?></td>
					<td><?php echo $start_time; ?></td>
					<td><?php echo $stop_time; ?></td>
					<td><?php echo $this->modules->format_seconds($total_time); ?></td>
				</tr>
				<?php
			}
		}
	}else{
		?>
	<p><b>You currently Do not have any closed tasks.</b></p>
	<?php
}
?>