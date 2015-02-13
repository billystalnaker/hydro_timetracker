<?php
$config = array(
    'accounts/index'=>array(
	array(
	    'field'	=>'username',
	    'label'	=>'Username',
	    'rules'	=>'required'
	),
	array(
	    'field'	=>'password',
	    'label'	=>'Password',
	    'rules'	=>'required'
	),
	array(
	    'field'	=>'submit_login',
	    'label'	=>'Form',
	    'rules'	=>'callback_check_login'
	)
    ),
    'objects/add'	=>array(
	array(
	    'field'	=>'module_id',
	    'label'	=>'ID',
	    'rules'	=>'required'
	),
	array(
	    'field'	=>'module_description',
	    'label'	=>'Description',
	    'rules'	=>'max_length[255]'
	),
	array(
	    'field'	=>'module_active',
	    'label'	=>'Active',
	    'rules'	=>'required'
	)
    ),
    'onjects/view'	=>array(
	array(
	    'field'	=>'module_id',
	    'label'	=>'ID',
	    'rules'	=>'required'
	),
	array(
	    'field'	=>'module_description',
	    'label'	=>'Description',
	    'rules'	=>'max_length[255]'
	),
	array(
	    'field'	=>'module_active',
	    'label'	=>'Active',
	    'rules'	=>'required'
	)
    )
);
