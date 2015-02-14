
<?php if(count($results) > 0){ ?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>
					Task
				</th>
				<th>
					User
				</th>

				<th>
					Time
				</th>
			</tr>
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