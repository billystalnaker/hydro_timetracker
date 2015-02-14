<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Download extends LF_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('dompdf', 'file', 'excel'));
	}
	public function per_project_pdf(){
		$this->load->model('modules');
		$this->modules->analyze_clock_entries_per_project_report();
		$html = $this->load->view('tpl/result/per_project_results', $this->data, true);
		pdf_create($html, 'Project Report');
		//if you want to write it to disk and/or send it as an attachment    
	}
	public function per_task_pdf(){
		$this->load->model('modules');
		$this->modules->analyze_clock_entries_per_task_report();
		$html = $this->load->view('tpl/result/per_task_results', $this->data, true);
		pdf_create($html, 'Task Report');
		//if you want to write it to disk and/or send it as an attachment    
	}
	public function clock_entries_pdf(){
		ini_set('display_errors', 1);
		$this->load->model('modules');
		$this->modules->analyze_clock_entries_report();
		$html = $this->load->view('tpl/result/clock_entry_results', $this->data, true);
		pdf_create($html, 'Clcok Entry Report');
		//if you want to write it to disk and/or send it as an attachment 
	}
	public function per_project_xls(){
		$this->load->model('modules');
		$this->modules->analyze_clock_entries_per_project_report();
		$results = $this->data['results'];
		$totals	 = [];
		$old_pid = 0;
		$data	 = [];
		$i		 = 0;
		foreach($results as $pid => $projects){
			$project_name = "";
			if(!isset($totals[$pid])){
				$totals[$pid] = 0;
			}
			if($old_pid != $pid){
				$project_info	 = $this->modules->get_project($pid, true);
				$project_name	 = $project_info['name'];
				if($old_pid > 0){
					$data[$i][]	 = 'Project Total';
					$data[$i][]	 = '';
					$data[$i][]	 = '';
					$data[$i][]	 = $this->modules->format_seconds($totals[$old_pid]);
					unset($totals[$old_pid]);
					$i++;
				}
				$old_pid = $pid;
			}
			$old_tid = 0;
			foreach($projects as $tid => $tasks){
				$task_name = "";
				if($old_tid != $tid){
					$project_task_info	 = $this->modules->get_project_task($tid, true);
					$task_info			 = $this->modules->get_task($project_task_info['task_id'], true);
					$task_name			 = $task_info['name'];
					$old_tid			 = $tid;
				}
				$old_uid = 0;
				foreach($tasks as $uid => $times){
					$user_name = "";
					if($old_uid != $uid){
						$user_info	 = $this->modules->get_user($uid, true);
						$user_name	 = $user_info['upro_first_name'].' '.$user_info['upro_last_name'];
						$old_uid	 = $uid;
					}
					$total_time = 0;
					foreach($times as $time){
						$total_time+= $time;
					}
					$totals[$pid] +=$total_time;
					$data[$i][]	 = $project_name;
					$data[$i][]	 = $task_name;
					$data[$i][]	 = $user_name;
					$data[$i][]	 = $this->modules->format_seconds($total_time);
					$i++;
				}
			}
		}
		foreach($totals as $pid => $time){
			$i++;
			$data[$i][]	 = 'Project Total';
			$data[$i][]	 = '';
			$data[$i][]	 = '';
			$data[$i][]	 = $this->modules->format_seconds($totals[$old_pid]);
		}
		excel_create($data, 'Project Report');
		//if you want to write it to disk and/or send it as an attachment    
	}
	public function per_task_xls(){
		$this->load->model('modules');
		$this->modules->analyze_clock_entries_per_task_report();
		$results = $this->data['results'];
		$totals	 = [];
		$old_tid = 0;
		$i		 = 0;
		$data	 = [];
		foreach($results as $tid => $tasks){
			if(!isset($totals[$tid])){
				$totals[$tid] = 0;
			}
			if($old_tid != $tid){
				$task_info	 = $this->modules->get_task($tid, true);
				$task_name	 = $task_info['name'];
				if($old_tid > 0){
					$data[$i][]	 = 'Task Total';
					$data[$i][]	 = '';
					$data[$i][]	 = $this->modules->format_seconds($totals[$old_tid]);
					unset($totals[$old_tid]);
					$i++;
				}
				$old_tid = $tid;
			}
			$old_uid = 0;
			foreach($tasks as $uid => $times){
				$user_name = "";
				if($old_uid != $uid){
					$user_info	 = $this->modules->get_user($uid, true);
					$user_name	 = $user_info['upro_first_name'].' '.$user_info['upro_last_name'];
					$old_uid	 = $uid;
				}
				$total_time = 0;
				foreach($times as $time){
					$total_time+= $time;
				}
				$totals[$tid] +=$total_time;
				$data[$i][]	 = $task_name;
				$data[$i][]	 = $user_name;
				$data[$i][]	 = $this->modules->format_seconds($total_time);
				$i++;
			}
		}
		foreach($totals as $tid => $time){
			$data[$i][]	 = 'Task Total';
			$data[$i][]	 = '';
			$data[$i][]	 = $this->modules->format_seconds($totals[$tid]);
			$i++;
		}
		excel_create($data, 'Task Report');
	}
	public function clock_entries_xls(){
		$this->load->model('modules');
		$this->modules->analyze_clock_entries_report();
		$results	 = $this->data['results'];
		$i			 = 0;
		$data		 = [];
		$data[$i][]	 = "User Name";
		$data[$i][]	 = "Project Name";
		$data[$i][]	 = "Task Name";
		$data[$i][]	 = "Start Date";
		$data[$i][]	 = "Stop Date";
		$i++;
		foreach($results as $result){
			$first_name		 = $result['first_name'];
			$last_name		 = $result['last_name'];
			$user_name		 = $first_name." ".$last_name;
			$project_name	 = $result['project_name'];
			$task_name		 = $result['task_name'];
			$start_date		 = $result['start'];
			$stop_date		 = $result['stop'];
			$data[$i][]		 = $project_name;
			$data[$i][]		 = $task_name;
			$data[$i][]		 = $user_name;
			$data[$i][]		 = $start_date;
			$data[$i][]		 = $stop_date;
			$i++;
		}
		excel_create($data, 'Clock Entry Report');
	}
}

?>