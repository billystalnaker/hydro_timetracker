
<div class="row">
    <div class="col-lg-12">
		<h1 class="page-header">View Customers</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<?php if(!empty($message)){ ?>
		<div id="message">
		<?php echo $message; ?>
	</div>
<?php } ?>

<div class="row">
    <div class="col-lg-12">
		<?php if($this->flexi_auth->is_privileged('Add Customers')){ ?>
			<a href="<?php echo site_url('module/customers/add') ?>" >Add a customer...</a>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				Customers
			</div>
			<!-- /.panel-heading -->
			<form action="<?php echo current_url(); ?>" method="POST" >
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="data_table">
							<thead>
								<tr>
									<th>Name</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Delete</th>
								</tr>
							</thead>
							<?php if(!empty($customers)){ ?>
								<tbody>
									<?php foreach($customers as $customer){
										?>
									<tr>
												<td>
													<?php if($this->flexi_auth->is_privileged('Delete Customers')){ ?>
													<a href="<?php echo site_url("module/customers/edit/".$customer['id']); ?>">
																	<?php echo $customer['company']; ?>
														</a>
													<?php }else{ ?>
														<?php echo $customer['company']; ?>
													<?php } ?>
												</td>
												<td>
													<?php echo $customer['first_name']; ?>
												</td>

												<td>
													<?php echo $customer['last_name']; ?>
												</td>
												<td class="align_ctr">
													<?php if($this->flexi_auth->is_privileged('Delete Customers')){ ?>
														<input type="checkbox" name="delete_customer[<?php echo $customer['id']; ?>]" value="1"/>
													<?php }else{ ?>
														<input type="checkbox" disabled="disabled"/>
														<small>Not Privileged</small>
														<input type="hidden" name="delete_customer[<?php echo $customer['id']; ?>]" value="0"/>
													<?php } ?>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								<?php } ?>
						</table>
					</div>
					<input class="btn btn-primary" type="submit" value="Update Customers" name="update_customers"/>
				</div>
			</form>
		</div>
    </div>
</div>