<?php if(count($results) > 0){ ?>
<!--	<style>
		.table {
			width: 100%;
			margin-bottom: 20px;
		}
		table {
			max-width: 100%;
			background-color: transparent;
		}
		table {
			border-spacing: 0;
			border-collapse: collapse;
		}
		* {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}
		user agent stylesheettable {
			display: table;
			border-collapse: separate;
			border-spacing: 2px;
			border-color: gray;
		}
		Pseudo ::before element
		*:before, *:after {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}
		Pseudo ::after element
		*:before, *:after {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}
		Inherited from body
		body {
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
			font-size: 14px;
			line-height: 1.42857143;
			color: #333;
			background-color: #73AAA3;
		}
		Inherited from html
		html {
			font-size: 62.5%;
			-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
		}
		html {
			font-family: sans-serif;
			-webkit-text-size-adjust: 100%;
			-ms-text-size-adjust: 100%;
		}
		.table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > td {
			border-top: 0;
		}
		.table > thead > tr > th {
			vertical-align: bottom;
			border-bottom: 2px solid #ddd;
		}
		.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
			padding: 8px;
			line-height: 1.42857143;
			vertical-align: top;
			border-top: 1px solid #ddd;
		}
		th {
			text-align: left;
		}
		td, th {
			padding: 0;
		}
		.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
			padding: 8px;
			line-height: 1.42857143;
			vertical-align: top;
			border-top: 1px solid #ddd;
		}
	</style>-->
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
					Time
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$totals	 = [];
			$old_pid = 0;
			foreach($results as $pid => $projects){
				$project_name = "";
				if(!isset($totals[$pid])){
					$totals[$pid] = 0;
				}
				if($old_pid != $pid){
					$project_info	 = $this->modules->get_project($pid, true);
					$project_name	 = $project_info['name'];
					if($old_pid > 0){
						?>
						<tr>
							<td align="center" colspan="3" style=" background-color: beige; ">
								Project Total
							</td>
							<td><?php echo $this->modules->format_seconds($totals[$old_pid]); ?></td>
						</tr>
						<?php
						unset($totals[$old_pid]);
					}
					$old_pid = $pid;
				}
				$old_tid = 0;
				foreach($projects as $tid => $tasks){
					$task_name = "";
					if($old_tid != $tid){
						$project_task_info	 = $this->modules->get_project_task($tid, true);
						$task_info			 = $this->modules->get_task($project_task_info['task_id'], true);
						$task_name			 = $task_info['name'];
						$old_tid			 = $tid;
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
						$totals[$pid] +=$total_time;
						?>
						<tr>
							<td><?php echo $project_name; ?></td>
							<td><?php echo $task_name; ?></td>
							<td><?php echo $user_name; ?></td>
							<td><?php echo $this->modules->format_seconds($total_time); ?></td>
						</tr>
						<?php
					}
				}
			}
			foreach($totals as $pid => $time){
				?>
				<tr>
					<td align="center" colspan="3" style=" background-color: beige; ">
						Project Total
					</td>
					<td><?php echo $this->modules->format_seconds($totals[$pid]); ?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
}elseif($this->input->post()){
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					No Results..
				</div>
				<div class="panel-body">
					<div class="panel-group">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="panel-collapse collapse in">
									<div class="panel-body">
										<p>There were no results for the query parameters you entered.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
}
?>