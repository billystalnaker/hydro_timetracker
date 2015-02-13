<div class="row">
    <div class="col-lg-12">
		<h1 class="page-header">Edit the <?php echo $customer['company'] ?> Customer </h1>
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

<form action="<?php echo site_url('/module/customers/edit/'.$customer['id']); ?>" method="POST">
    <div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-md-3" >Company Name:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa fa-group"></i></span>
						<input class="form-control" type="text" value="<?php echo set_value('update_customer_company', $customer['company']) ?>" name="update_customer_company">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Customer First Name:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-list fa"></i></span>
						<input class="form-control" type="text" value="<?php echo set_value('update_customer_first_name', $customer['first_name']) ?>" name="update_customer_first_name">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3" >Customer Last Name:</label>
					<div class="input-group col-md-9">
						<span class="input-group-addon"><i class="fa-list fa"></i></span>
						<input class="form-control" type="text" value="<?php echo set_value('update_customer_last_name', $customer['last_name']) ?>" name="update_customer_last_name">
					</div>
				</div>
				<div class="form-group">
					<input class="form-control btn btn-primary" type="submit" id="" name="update_customer_submit">
				</div>

			</div>
		</div>
</form>