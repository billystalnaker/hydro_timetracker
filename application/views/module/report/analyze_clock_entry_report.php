<div class="row">
    <div class="col-lg-12">
		<h1 class="page-header">Analyze Clock Entries</h1>
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
<form class="download_form" action="<?php echo site_url('download/clock_entries_pdf'); ?>" method="POST">
	<input class="btn btn-primary" type="submit" name="download_pdf_submit" value="Get PDF"/>
</form>
<form class="download_form" action="<?php echo site_url('download/clock_entries_xls'); ?>" method="POST">
	<input class="btn btn-primary" type="submit" name="download_xls_submit" value="Get XLS"/>
</form>
<table class="table table-hover">
	<thead>
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