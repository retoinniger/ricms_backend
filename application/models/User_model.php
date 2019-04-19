<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    /* public function get_users() {

        $this->db->select('user.*');
        $this->db->from('users user');

        $query = $this->db->get();
        return $query->result();
    } */

    public function get_admin_users()
    {
        $this->db->select('user.*');
        $this->db->from('users user');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_admin_user($id)
    {
        $this->db->select('user.*');
        $this->db->from('users user');
        $this->db->where('user.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function insertUser($userData)
    {
        $this->db->insert('users', $userData);
        return $this->db->insert_id();
    }

    public function updateUser($id, $userData)
    {
        $this->db->where('id', $id);
        $this->db->update('users', $userData);
    }

    public function deleteUser($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('users');
    }
}