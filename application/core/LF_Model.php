<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * CodeIgniter Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class LF_Model extends CI_Model{

    /**
     * Constructor
     *
     * @access public
     */
    public $table;

    function __construct($table = ''){
	parent::__construct();
	$this->table = strtolower($table);
	log_message('debug', "Model Class Initialized: $this->table");
    }
    /**
     * __get
     *
     * Allows models to access CI's loaded classes using the same
     * syntax as controllers.
     *
     * @param	string
     * @access private
     */
    function get_info($table = '', $params = array()){
	if(is_array($params) && count($params) > 0){
	    foreach($params as $k=> $v){
		$this->db->where($k, $v);
	    }
	}
	if($table === ''){
	    if($this->table !== ''){
		return $this->db->get($this->table)->result_array();
	    }
	}else{
	    return $this->db->get($table)->result_array();
	}
    }
    function set_table($table){
	$this->table = $table;
	return $this;
    }
    function describe(){
	return $this->db->query("DESCRIBE `$this->table`")->result_array();
    }
    function table_exists(){
	return $this->db->table_exists($this->table);
    }
}

// END Model Class

/* End of file Model.php */
/* Location: ./system/core/Model.php */