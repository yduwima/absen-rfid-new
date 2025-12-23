<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Model
 * Model for users table
 */
class User_model extends Base_Model {
    
    protected $table = 'users';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get user by username
     */
    public function get_by_username($username) {
        return $this->db->get_where($this->table, array('username' => $username))->row();
    }
    
    /**
     * Verify login
     */
    public function verify_login($username, $password) {
        $user = $this->get_by_username($username);
        
        if ($user && password_verify($password, $user->password)) {
            if ($user->is_active == 1) {
                return $user;
            }
        }
        
        return FALSE;
    }
    
    /**
     * Get user with guru data
     */
    public function get_user_with_guru($user_id) {
        $this->db->select('users.*, guru.nama as nama_guru, guru.nip, guru.foto');
        $this->db->from($this->table);
        $this->db->join('guru', 'guru.id = users.guru_id', 'left');
        $this->db->where('users.id', $user_id);
        return $this->db->get()->row();
    }
    
    /**
     * Create new user
     */
    public function create_user($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->insert($data);
    }
    
    /**
     * Update password
     */
    public function update_password($user_id, $new_password) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        return $this->update($user_id, array('password' => $hashed));
    }
    
    /**
     * Check if username exists
     */
    public function username_exists($username, $exclude_id = null) {
        $this->db->where('username', $username);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
    
    /**
     * Get users by role
     */
    public function get_by_role($role) {
        return $this->db->get_where($this->table, array('role' => $role, 'is_active' => 1))->result();
    }
}
