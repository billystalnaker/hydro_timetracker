<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

class LF_Loader extends CI_Loader{
	public function __construct(){
		parent::__construct();

		$CI			 = & get_instance();
		$CI->load	 = $this;
	}
	public function library($library = '', $params = NULL, $object_name = NULL){
		if(is_array($library)){
			foreach($library as $class){
				$this->library($class, $params);
			}

			return;
		}

		if($library == '' OR isset($this->_base_classes[$library])){
			return FALSE;
		}

		if(!is_null($params) && !is_array($params)){
			$params = NULL;
		}
		return $this->_ci_load_class($library, $params, $object_name);
	}
	public function model($model, $name = '', $db_conn = FALSE){
		if(is_array($model)){
			foreach($model as $babe){
				$this->model($babe);
			}
			return;
		}

		if($model == ''){
			return;
		}

		$path = '';

		// Is the model in a sub-folder? If so, parse out the filename and path.
		if(($last_slash = strrpos($model, '/')) !== FALSE){
			// The path is in front of the last slash
			$path = substr($model, 0, $last_slash + 1);

			// And the model name behind it
			$model = substr($model, $last_slash + 1);
		}

		if($name == ''){
			$name = $model;
		}


		$CI = & get_instance();
		if(in_array($name, $this->_ci_models, TRUE)){
			return $CI->$name;
		}
		if(isset($CI->$name)){
			show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
		}

		$model = strtolower($model);
		foreach($this->_ci_model_paths as $mod_path){
			if(!file_exists($mod_path.'models/'.$path.$model.'.php')){
				continue;
			}

			if($db_conn !== FALSE AND ! class_exists('CI_DB')){
				if($db_conn === TRUE){
					$db_conn = '';
				}

				$CI->load->database($db_conn, FALSE, TRUE);
			}

			if(!class_exists('CI_Model')){
				load_class('Model', 'core');
			}

			require_once($mod_path.'models/'.$path.$model.'.php');

			$model = ucfirst($model);

			$CI->$name = new $model();

			$this->_ci_models[] = $name;
			return $CI->$name;
		}

		// couldn't find the model
		show_error('Unable to locate the model you have specified: '.$model);
	}
	protected function _ci_init_class($class, $prefix = '', $config = FALSE, $object_name = NULL){
		// Is there an associated config file for this class?  Note: these should always be lowercase

		if($config === NULL){
			// Fetch the config paths containing any package paths
			$config_component = $this->_ci_get_component('config');

			if(is_array($config_component->_config_paths)){
				// Break on the first found file, thus package files
				// are not overridden by default paths
				foreach($config_component->_config_paths as $path){
					// We test for both uppercase and lowercase, for servers that
					// are case-sensitive with regard to file names. Check for environment
					// first, global next
					if(defined('ENVIRONMENT') AND file_exists($path.'config/'.ENVIRONMENT.'/'.strtolower($class).'.php')){
						include($path.'config/'.ENVIRONMENT.'/'.strtolower($class).'.php');
						break;
					}elseif(defined('ENVIRONMENT') AND file_exists($path.'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).'.php')){
						include($path.'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).'.php');
						break;
					}elseif(file_exists($path.'config/'.strtolower($class).'.php')){
						include($path.'config/'.strtolower($class).'.php');
						break;
					}elseif(file_exists($path.'config/'.ucfirst(strtolower($class)).'.php')){
						include($path.'config/'.ucfirst(strtolower($class)).'.php');
						break;
					}
				}
			}
		}
		if($prefix == ''){
			if(class_exists('CI_'.$class)){
				$name = 'CI_'.$class;
			}elseif(class_exists(config_item('subclass_prefix').$class)){
				$name = config_item('subclass_prefix').$class;
			}else{
				$name = $class;
			}
		}else{
			$name = $prefix.$class;
		}

		// Is the class name valid?
		if(!class_exists($name)){
			log_message('error', "Non-existent class: ".$name);
			show_error("Non-existent class: ".$class);
		}

		// Set the variable name we will assign the class to
		// Was a custom class name supplied?  If so we'll use it
		$class = strtolower($class);

		if(is_null($object_name)){
			$classvar = (!isset($this->_ci_varmap[$class]))?$class:$this->_ci_varmap[$class];
		}else{
			$classvar = $object_name;
		}

		// Save the class name and object name
		$this->_ci_classes[$class] = $classvar;

		// Instantiate the class
		$CI = & get_instance();
		if($config !== NULL){
			return $CI->$classvar = new $name($config);
		}else{
			return $CI->$classvar = new $name;
		}
	}
	protected function _ci_load($_ci_data){
		// Set the default data variables
		foreach(array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val){
			$$_ci_val = (!isset($_ci_data[$_ci_val]))?FALSE:$_ci_data[$_ci_val];
		}

		$file_exists = FALSE;

		// Set the path to the requested file
		if($_ci_path != ''){
			$_ci_x		 = explode('/', $_ci_path);
			$_ci_file	 = end($_ci_x);
		}else{
			$_ci_ext	 = pathinfo($_ci_view, PATHINFO_EXTENSION);
			$_ci_file	 = ($_ci_ext == '')?$_ci_view.'.php':$_ci_view;
			foreach($this->_ci_view_paths as $view_file=> $cascade){
				if(file_exists($view_file.$_ci_file)){
					$_ci_path	 = $view_file.$_ci_file;
					$file_exists = TRUE;
					break;
				}

				if(!$cascade){
					break;
				}
			}
		}
		if(!$file_exists && !file_exists($_ci_path)){
			show_error('Unable to load the requested file: '.$_ci_file);
		}

		// This allows anything loaded using $this->load (views, files, etc.)
		// to become accessible from within the Controller and Model functions.

		$_ci_CI = & get_instance();
		foreach(get_object_vars($_ci_CI) as $_ci_key=> $_ci_var){
			if(!isset($this->$_ci_key)){
				$this->$_ci_key = & $_ci_CI->$_ci_key;
			}
		}

		/*
		 * Extract and cache variables
		 *
		 * You can either set variables using the dedicated $this->load_vars()
		 * function or via the second parameter of this function. We'll merge
		 * the two types and cache them so that views that are embedded within
		 * other views can have access to these variables.
		 */
		if(is_array($_ci_vars)){
			$this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
		}
		extract($this->_ci_cached_vars);

		/*
		 * Buffer the output
		 *
		 * We buffer the output for two reasons:
		 * 1. Speed. You get a significant speed boost.
		 * 2. So that the final rendered template can be
		 * post-processed by the output class.  Why do we
		 * need post processing?  For one thing, in order to
		 * show the elapsed page load time.  Unless we
		 * can intercept the content right before it's sent to
		 * the browser and then stop the timer it won't be accurate.
		 */
		ob_start();

		// If the PHP installation does not support short tags we'll
		// do a little string replacement, changing the short tags
		// to standard PHP echo statements.

		if((bool)@ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE){
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
		}else{
			include($_ci_path); // include() vs include_once() allows for multiple views with the same name
		}

		log_message('debug', 'File loaded: '.$_ci_path);

		// Return the file data if requested
		if($_ci_return === TRUE){
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		/*
		 * Flush the buffer... or buff the flusher?
		 *
		 * In order to permit views to be nested within
		 * other views, we need to flush the content back out whenever
		 * we are beyond the first level of output buffering so that
		 * it can be seen and included properly by the first included
		 * template and any subsequent ones. Oy!
		 *
		 */
		if(ob_get_level() > $this->_ci_ob_level + 1){
			ob_end_flush();
		}else{
			$_ci_CI->output->append_output(ob_get_contents());
			@ob_end_clean();
		}
	}
}
