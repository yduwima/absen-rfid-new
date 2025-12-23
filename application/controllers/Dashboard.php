<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard Controller
 * Main dashboard after login
 */
class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        is_logged_in(); // Check if user is logged in
    }
    
    /**
     * Dashboard index
     * Redirect to role-specific dashboard
     */
    public function index() {
        $role = $this->session->userdata('role');
        
        switch ($role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'guru':
            case 'walikelas':
            case 'piket':
                redirect('guru/dashboard');
                break;
            case 'bk':
                redirect('bk/dashboard');
                break;
            default:
                // If role not recognized, logout
                redirect('auth/logout');
        }
    }
}
