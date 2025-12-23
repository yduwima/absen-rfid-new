<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Controller
 * Handles authentication (login, logout)
 */
class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Guru_model');
    }
    
    /**
     * Login page
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->session->userdata('user_id')) {
            $this->_redirect_by_role();
            return;
        }
        
        $this->load->view('auth/login');
    }
    
    /**
     * Process login
     */
    public function do_login() {
        // Validation
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/login');
            return;
        }
        
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');
        
        // Verify login
        $user = $this->User_model->verify_login($username, $password);
        
        if ($user) {
            // Get guru data if exists
            $guru_data = null;
            if ($user->guru_id) {
                $guru_data = $this->Guru_model->get_by_id($user->guru_id);
            }
            
            // Set session
            $session_data = array(
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'guru_id' => $user->guru_id,
                'nama' => $guru_data ? $guru_data->nama : 'Admin',
                'email' => $user->email,
                'foto' => $guru_data ? $guru_data->foto : null,
                'logged_in' => TRUE
            );
            
            $this->session->set_userdata($session_data);
            
            // Log activity
            $this->_log_activity('Login', 'User '.$username.' logged in');
            
            // Redirect based on role
            $this->_redirect_by_role();
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah!');
            redirect('auth/login');
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        // Log activity
        $this->_log_activity('Logout', 'User logged out');
        
        // Destroy session
        $this->session->sess_destroy();
        
        $this->session->set_flashdata('success', 'Anda telah berhasil logout');
        redirect('auth/login');
    }
    
    /**
     * Redirect based on role
     */
    private function _redirect_by_role() {
        $role = $this->session->userdata('role');
        
        switch ($role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'guru':
                redirect('guru/dashboard');
                break;
            case 'walikelas':
                redirect('guru/dashboard');
                break;
            case 'piket':
                redirect('guru/dashboard');
                break;
            case 'bk':
                redirect('bk/dashboard');
                break;
            default:
                redirect('dashboard');
        }
    }
    
    /**
     * Log activity (simple logging)
     */
    private function _log_activity($action, $description) {
        // You can implement activity logging here
        // For now, we'll just skip it or use CodeIgniter's log
        log_message('info', $action . ': ' . $description);
    }
}
