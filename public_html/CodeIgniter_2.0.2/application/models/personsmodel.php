<?php

class Personsmodel extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function get_records()
	{
		$query = $this->db->get('persons');
		return $query->result();
	}
	
	function add_record($data) 
	{
		$this->db->insert('persons', $data);
		return;
	}
	
	function update_record($data) 
	{
		$this->db->where('id', 12);
		$this->db->update('persons', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id', $this->uri->segment(3));
		$this->db->delete('persons');
	}
	
	function retrieve_by_email($email)
	{
		$query = $this->db->get_where('persons',array('email'=>$email,'active'=>'yes'));
		return $query->result_array();
	}

	function retrieve_by_uid($uid)
	{
		$query = $this->db->get_where('persons',array('uid'=>$uid));
		$this->db->limit(1);
		return $query->result_array();
	}
	

	function set_user_activation_key($uid)
	{
		$sql = 'update ' . $this->db->protect_identifiers('persons', TRUE) . '
		set user_activation_key = md5(now())	WHERE uid = "' . (int)($uid) .'"';
		$query = $this->db->query($sql);
		$query = $this->db->get_where('persons',array('uid'=>$uid));
		return $query->result_array();
	}

	function send_new_membermail($uid)
	{
		$data = $this->retrieve_by_uid($uid);
		$keydata = $this->set_user_activation_key($uid);
		$subject = "Adgang til din personlige side på KBHFF ";
		$mailcontent = 	'Kære ' . utf8_decode($data['0']['firstname'] . ' ' . $data['0']['middlename'] . ' ' . $data['0']['lastname']) . "\n\n"
					.	"Den nye medlemsside er nu klar.\n\n"
					.	"Som medlem af KBHFF får du hermed din personlige login-information.\n\n"
					.	"Adresse: https://medlem.kbhff.dk/\n"
					.	'Dit medlemsnummer: ' . $data['0']['uid'] . "\n\n"
					.	"Første gang du logger på, skal du vælge dit kodeord. Gå ind på følgende adresse:\n\n"
					.	'<a href="https://medlem.kbhff.dk/login/resetpassword/' . $data['0']['uid'] . '/' . $keydata['0']['user_activation_key'] . '">'
					.	'https://medlem.kbhff.dk/login/resetpassword/' . $data['0']['uid'] . '/' . $keydata['0']['user_activation_key'] . "</a>\n\n"
					.	"Vælg dit personlige kodeord - og husk det.\n\n"
					.	"Derefter kan du logge ind, checke din kontaktinformation, bestille og betale poser m.m.\n\n"
					.	"I starten kan der være uforudsete problemer - send en mail til torsten@arendrup.dk for hjælp.\n\n"
					.	"Med venlig hilsen\n"
					.	"KBHFF web-robotten";
		sendenkeltmail ($subject, $mailcontent,$data['0']['email'], 'robot@medlem.kbhff.dk', $data['0']['firstname'] . ' ' . $data['0']['middlename'] . ' ' . $data['0']['lastname']);
	}
	
}