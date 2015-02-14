
<table class="table table-hover">
	<thead>
		<tr>
			<th>
				Project
			</th>

			<th>
				Task
			</th>
			<th>
				User
			</th>

			<th>
				Start Date
			</th>
			<th>
				Stop Date
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($results as $result){
			$first_name		 = $result['first_name'];
			$last_name		 = $result['last_name'];
			$user_name		 = $first_name." ".$last_name;
			$project_name	 = $result['project_name'];
			$task_name		 = $result['task_name'];
			$start_date		 = $result['start'];
			$stop_date		 = $result['stop'];
			?>
			<tr>
				<td><?php echo $project_name; ?></td>
				<td><?php echo $task_name; ?></td>
				<td><?php echo $user_name; ?></td>
				<td><?php echo $start_date; ?></td>
				<td><?php echo $stop_date; ?></td>
			</tr>
		<?php }
		?>
	</tbody>
</table>