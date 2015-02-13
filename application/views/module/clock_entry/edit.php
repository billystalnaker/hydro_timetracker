<div class="row">
    <div class="col-lg-12">
		<h1 class="page-header">Edit Clock Entry <?php echo $clock_entry['id'] ?></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<?php
if(!empty($message)){
	?>
		<div id="message">
		<?php echo $message; ?>
	</div>
<?php } ?>

<?php echo validation_errors("<div class='row'> <div class='alert alert-danger col-md-6'>", "</div><div class='col-md-6'></div></div>"); ?>

<form action="<?php echo site_url('/module/clock_entries/edit/'.$clock_entry['id']); ?>" method="POST">
    <div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-md-3" >User:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-user fa"></i></span>
						<?php
						echo form_dropdown('update_clock_entry_user_id', $user_options, set_value('update_clock_entry_user_id', USER_ID), "class='form-control'");
						?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Start Time:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-clock-o fa"></i></span>
						<input class="form-control date-time-picker" type="text" value="<?php echo set_value('update_clock_entry_start', $clock_entry['start']) ?>" name="update_clock_entry_start">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Stop Time:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-clock-o fa"></i></span>
						<input class="form-control date-time-picker" type="text" value="<?php echo set_value('update_clock_entry_stop', $clock_entry['stop']) ?>" name="update_clock_entry_stop">
					</div>
				</div>
				<div class="form-group">
					<input class="form-control btn btn-primary" type="submit" id="" name="update_clock_entry_submit">
				</div>

			</div>
		</div>
</form>