<?php

class AdminController extends Controller{
    
	private $_db;
	
    public function __construct($dependencies, $action, $params) {
        
        global $config;

		// get the connection to the database from the dependencies container
        $this->_db = $dependencies->get_db();
		
        // check if is admin and if the required action is defined
        if($this->check_role('admin') && method_exists($this, $action)){ 
            $this->$action($params);
        }else{
            header('Location: '.$config['wwwroot'].'/error/notfound');
        }
    }
    
    public function activity($params){

		// set maxrecords to 10 if is not set
		$max_records = isset($params['activity_maxrecords']) ? $params['activity_maxrecords'] : 10 ;
	
        $sql = "SELECT paa.id, paa.userid, paa.action, paa.timestamp, pu.firstname, pu.lastname
				FROM presence_admin_activity paa
				JOIN presence_users pu ON paa.userid = pu.email
				ORDER BY id DESC LIMIT ".$max_records;
		
		$st = $this->_db->prepare($sql);
        $st->execute();
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $activity_entries = $st->fetchAll();

        $this->_view = new AdminActivityView($activity_entries);
    }
	
	public function users(){
		
		$sql = "SELECT *
				FROM presence_users
				ORDER BY lastname ASC";
		$st = $this->_db->prepare($sql);
		$st->execute();
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $presence_users = $st->fetchAll();
		
		$this->_view = new AdminUsersView($presence_users);
	}

}