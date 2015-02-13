<?php if(!empty($message)){ ?>
    <div id="message">
	<?php echo $message; ?>
    </div>
<?php } ?>

<?php
$attributes = array(
    'method'=>'POST',
    'role'	=>'form',
    'class'	=>'form-center'
);
echo form_open(current_url(), $attributes);
?>
<h2 class="form-signin-heading">Please sign in</h2>
<input name="login_identity" type="username" class="form-control" placeholder="Username" autofocus="" value="<?php echo set_value('username'); ?>">
<input name="login_password" type="password" class="form-control" placeholder="Password" value="<?php echo set_value('password'); ?>">
<label class="checkbox">
    <input name="remember_me" type="checkbox" value="1"  <?php echo set_checkbox('remember_me', 1); ?>> Remember me
</label>
<a href="<?php echo site_url('account/forgotten_password') ?>">Forgot your password?</a>
<input type="submit" name="login_user" class="btn btn-lg btn-primary btn-block" value="Sign In"/>
<?php
echo form_close();
?>