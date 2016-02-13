<?php

class Admin_model extends CI_Model
{
	
	function __construct()
	{
		# code...
	}

	public function save_user($email, $first_name, $last_name, $password)
	{
	    $this->load->library('bcrypt');
		$hash = $this->bcrypt->hash_password($password);
	 
	    // store username and hashed password
	    $sql = "INSERT INTO users (email_address, first_name, last_name, password) VALUES (?, ?, ?, ?)";
	    $q = $this->db->query($sql, array($email, $first_name, $last_name, $hash));
	}

	public function verify_user($email, $password){
		$this->load->library('bcrypt');
		$hash = $this->bcrypt->hash_password($password);

		$sql = "SELECT * FROM users WHERE email_address = ?";
		$q = $this->db->query($sql, array($email));

		$row = $q->row();
		$stored_hash = $row->password;

		if ($this->bcrypt->check_password($password, $stored_hash))
		{
		    // Password does match stored password.
		    return $q->row();
		}
		else
		{
		    // Password does not match stored password.
		    return false;
		}

	}
}