<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blog_model extends CI_Model
{
    public function get_blogs($featured, $recentpost)
    {
        $this->db->select('blog.*, cat.category_name, u.first_name, u.last_name');
        $this->db->from('blogs blog');
        $this->db->join('users u', 'u.id=blog.user_id');
        $this->db->join('categories cat', 'cat.id=blog.category_id', 'left');
        $this->db->where('blog.is_active', 1);

        if ($featured) {
            $this->db->where('blog.is_featured', 1);
        }
        if ($recentpost) {
            $this->db->order_by('blog.created_at', 'desc');
            $this->db->limit($recentpost);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_blog($id)
    {
        $this->db->select('blog.*, cat.category_name, u.first_name, u.last_name');
        $this->db->from('blogs blog');
        $this->db->join('users u', 'u.id=blog.user_id');
        $this->db->join('categories cat', 'cat.id=blog.category_id', 'left');
        $this->db->where('blog.is_active', 1);
        $this->db->where('blog.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_admin_blogs()
    {
        $this->db->select('blog.*, u.first_name, u.last_name');
        $this->db->from('blogs blog');
        $this->db->join('users u', 'u.id=blog.user_id');
        $this->db->order_by('blog.created_at', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_admin_blog($id)
    {
        $this->db->select('blog.*, u.first_name, u.last_name');
        $this->db->from('blogs blog');
        $this->db->join('users u', 'u.id=blog.user_id');
        $this->db->where('blog.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function insertBlog($blogData)
    {
        $this->db->insert('blogs', $blogData);
        return $this->db->insert_id();
    }

    public function updateBlog($id, $blogData)
    {
        $this->db->where('id', $id);
        $this->db->update('blogs', $blogData);
    }

    public function deleteBlog($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('blogs');
    }
}

