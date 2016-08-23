<?php
// application/models/UserModel.class.php

class UserModel extends Model{


    public function getUsers(){

        $sql = "select * from $this->table";

        $users = $this->db->getAll($sql);

        return $users;

    }

    public function insertData($sql){
    	$result=$this->db->insertOne($sql);
    	return $result;

    }

    public function selectData($sql){
    	$result=$this->db->selectOne($sql);
    	return $result;
    }

}