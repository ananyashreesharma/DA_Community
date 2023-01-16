<?php

namespace classes;



class DB {
 
    private static $_instance = null;
    private $_pdo,
            $_error = false,
            $_query,
            $_results,
            $_count = 0;

    private function __construct() {
        $this->_pdo = new \PDO("mysql:host=" . Config::get('mysql/host') . ";dbname=" . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
    }

    public static function getInstance() {


        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }

        return self::$_instance;
    }
    

    public function query($sql, $params = array()) {
       
        $this->error = false;


        if($this->_query = $this->_pdo->prepare($sql)) {
           
            if(count($params)) {
                $count = 1;
                foreach($params as $param) {

                    $this->_query->bindValue($count, $param);
                    $count++;
                }
            }

            if($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(\PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }

        return $this;
    }

    public function pdo() {
        return $this->_pdo;
    }

    public function error() {
        return $this->_error;
    }

    public function results() {
        return $this->_results;
    }

    public function count() {
        return $this->_count;
    }
}


