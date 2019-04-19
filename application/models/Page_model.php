<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page_model extends CI_Model
{

    public function get_page($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('pages');
        return $query->row();
    }

    public function get_pages()
    {
        $this->db->select('page.*');
        $this->db->from('pages page');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_admin_pages()
    {
        $this->db->select('page.*');
        $this->db->from('pages page');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_admin_page($id)
    {
        $this->db->select('page.*');
        $this->db->from('pages page');
        $this->db->where('page.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function insertPage($pageData)
    {
        $this->db->insert('pages', $pageData);
        return $this->db->insert_id();
    }

    public function updatePage($id, $pageData)
    {
        $this->db->where('id', $id);
        $this->db->update('pages', $pageData);
    }

    public function deletePage($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('pages');
    }
}