<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{

    public function get_categories()
    {
        $this->db->select('category.*');
        $this->db->from('categories category');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_category($id)
    {
        $this->db->select('category.*');
        $this->db->from('categories category');
        $query = $this->db->get();
        return $query->row();
    }

    public function insert_contact($contactData)
    {
        $this->db->insert('contacts', $contactData);
        return $this->db->insert_id();
    }

    public function login($username, $password)
    {
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function get_admin_category($id)
    {
        $this->db->select('category.*');
        $this->db->from('categories category');
        $this->db->where('category.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_admin_categories()
    {
        $this->db->select('category.*');
        $this->db->from('categories category');
        $query = $this->db->get();
        return $query->result();
    }

    public function checkToken($token)
    {
        $this->db->where('token', $token);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            return true;
        }
        return false;
    }

    /************* Category functions *******************/
    public function insertCategory($categoryData)
    {
        $this->db->insert('categories', $categoryData);
        return $this->db->insert_id();
    }

    public function updateCategory($id, $categoryData)
    {
        $this->db->where('id', $id);
        $this->db->update('categories', $categoryData);
    }

    public function deleteCategory($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('categories');
    }
}
