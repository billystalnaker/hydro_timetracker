<div class="row">
    <div class="col-lg-12">
	<h2>Forgotten Password</h2>
    </div>
</div>
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
	    <label class="col-md-3" for="identity">Username:</label>
	    <div class="input-group col-md-9">
		<span class="input-group-addon"><i class="fa fa-user"></i></span>
		<input type="text" id="identity" name="forgot_password_identity" value="<?php echo set_value('forgot_password_identity') ?>" class="form-control tooltip_trigger"
		       title="Please enter either your email address or username defined during registration."
		       />
	    </div>
	</div>
	<div class="form-group row">
	    <label class="col-md-3" for="submit">Send Email:</label>
	    <div class="input-group col-md-9">
		<input type="submit" name="send_forgotten_password" id="submit" value="Submit" class="btn btn-primary form-control"/>
	    </div>
	</div>
    </div>
</div>
<?php echo form_close(); ?>