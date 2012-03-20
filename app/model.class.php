<?php

class Model{

    protected $_db;
    protected $_table;

    public function __construct($dependencies){
        $this->_db = $dependencies->get_db();
    }

    public static function find($id){

        $sql = "SELECT *
                FROM ".self::table()."
                WHERE `id` = ?";

        return DB::getRecord($sql, array($id));
    }

    public static function find_all(){
        $sql = "SELECT *
                FROM ".self::table();

        return DB::getAllRecords($sql);
    }

	public function create($record){
		return DB::putRecord($this->_table, $record);
	}

    /**
     * Returns the name of the table corresponding to the model
     *
     * @return string
     */
    private static function table(){
        $prefix = 'presence_';
        $caller = get_called_class();
        $class = lcfirst(substr($caller, 0, -5));

        return $prefix.self::pluralise($class);
    }

    /**
     * Returns a pluralised version of a word (very basic)
     *
     * @param type $word string
     * @return string
     */
    private static function pluralise($word){
        $exceptions = array();
        $exceptions['activity'] = 'activity';

        empty($exceptions[$word])
            ? $plural = $word.'s'
            : $plural = $exceptions[$word];

        return $plural;
    }
}