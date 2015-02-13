
<h2>Change Forgotten Password</h2>

<?php
echo validation_errors("<div class='row'> <div class='alert alert-danger col-md-6'>", "</div><div class='col-md-6'></div></div>");
if(!empty($message)){
    ?>
    <div id="message">
    <?php echo $message; ?>
    </div>
<?php } ?>

<?php echo form_open(current_url()); ?>
<div class="row">
    <div class="col-md-6">
	<div class="form-group row">
	    <label class="col-md-3" for="new_password">New Password:</label>
	    <div class="input-group col-md-9">
		<span class="input-group-addon"><i class="fa fa-key"></i></span>
		<input class="form-control" type="password" id="new_password" name="new_password" value="<?php echo set_value('new_password') ?>"/>
	    </div>
	</div>
	<div class="form-group row">
	    <label class="col-md-3" for="confirm_new_password">Confirm New Password:</label>
	    <div class="input-group col-md-9">
		<span class="input-group-addon"><i class="fa fa-key"></i></span>
		<input class="form-control" type="password" id="confirm_new_password" name="confirm_new_password" value="<?php echo set_value('confirm_new_password') ?>"/>
	    </div>
	</div>
	<div class="form-group row">
	    <label class="col-md-3" for="submit">Change Password:</label>
	    <div class="input-group col-md-9">
		<input class="form-control btn-btn-primary" type="submit" name="change_forgotten_password" id="submit" value="Submit"/>
	    </div>
	</div>
    </div>
</div>
<?php echo form_close(); ?>