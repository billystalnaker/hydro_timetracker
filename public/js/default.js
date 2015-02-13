$(window).on('resize', function(){
	if(window.innerwidth>767){
//no button
	}else{
//button
	}
});
$(function(){
	//sign-out link click
	$('.sign-out').click(function(){
		$anchor=$(this);
		bootbox.confirm('<h2>Leaving so soon?</h2> Are you sure you want to sign out?', function(result){
			if(result===true){
				var uri=$anchor.data('alt');
				redirect(uri);
			}
		});
	});
	//search choice click
	$('#search_choices > li').on('click', function(){
		$(this).siblings('li').removeClass('selected');
		$(this).addClass('selected');
	});
	//search submit click
	$('#search_submit').on('click', function(){
		selected=false;
		$('#search_choices > li').each(function(){
			if($(this).hasClass('selected')){
				selected=$(this).data('choice');
			}
		})
		if(selected==false){
			selected='st_light';
		}
		search_params=$('#search_params').val();
		redirect('/search/'+selected+'/'+search_params);
	});
	//all data-tables used
	if(typeof ($('#data_table'))!=='undefined'){
		$('#data_table').dataTable();
	}
	$('.reset-password').on('click', function(e){
		e.preventDefault();
		$(this).attr('disabled', true);
		if(typeof $(this).data('identifier')!=='undefined'){
			var identifier=$(this).data('identifier');
			var main=bootbox.dialog({
				title: "Reset a password.",
				message: "<div class='row'>"+
						"<div class='col-md-12'>"+
						"<form class='form-horizontal'>"+
						"<div class='form-group'>"+
						"<label class='col-md-4 control-label' for='new_password'>New Password</label> "+
						"<div class='col-md-4'>"+
						"<input id='new_password' name='new_password' type='password' class='form-control input-md'>"+
						"</div>"+
						"</div>"+
						"<div class='form-group'>"+
						"<label class='col-md-4 control-label' for='confirm_password'>Re-Type Password</label>"+
						"<div class='col-md-4'>"+
						"<input type='password' name='confirm_password' id='confirm_password'  class='form-control input-md'>"+
						"</div>"+
						"</div>"+
						"</form>"+
						"</div>"+
						"</div>"+
						"<div class='row'>"+
						"<div class='col-md-12 alert alert-danger' style='display:none' id='api_errors'>"+
						"</div></div>",
				buttons: {
					success: {
						label: "Save",
						className: "btn-success",
						callback: function(){
							var params={
								identifier: identifier,
								new_password: $('#new_password').val(),
								confirm_password: $('#confirm_password').val()
							};

							$.post('/api/reset_password', params, function(data){
								if(!data.errors){
									main.modal('hide');
									bootbox.alert('<h2>Yay!!</h2> Password reset!.');
								}else{
									$('#api_errors').html('');
									var error_string="";
									for(var k in data.errors){
										error_string+=(data.errors[k]);
									}
									$('#api_errors').html(error_string).slideDown();
								}
							});
							return false;
						}
					}
				}
			});
			$(this).attr('disabled', false);
		}
	});
//	var switch_options={
//		onText: 'Yes',
//		offText: 'No'
//	};
//	$(":checkbox").bootstrapSwitch(switch_options);
	$('.date-time-picker').datetimepicker();
});
function redirect(uri){
	window.location=uri;
}