<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Model
 * Parent class for all models
 */
class Base_Model extends CI_Model {
    
    protected $table;
    protected $primary_key = 'id';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all records
     */
    public function get_all($order_by = null) {
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Get record by ID
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, array($this->primary_key => $id))->row();
    }
    
    /**
     * Get records by field
     */
    public function get_by($field, $value) {
        return $this->db->get_where($this->table, array($field => $value))->result();
    }
    
    /**
     * Get single record by field
     */
    public function get_one_by($field, $value) {
        return $this->db->get_where($this->table, array($field => $value))->row();
    }
    
    /**
     * Insert record
     */
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    
    /**
     * Update record
     */
    public function update($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Delete record
     */
    public function delete($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
    }
    
    /**
     * Count all records
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }
    
    /**
     * Count records by condition
     */
    public function count_by($where) {
        return $this->db->where($where)->from($this->table)->count_all_results();
    }
    
    /**
     * Get paginated data
     */
    public function get_paginated($limit, $offset, $search = null, $order_by = null) {
        if ($search) {
            $this->db->like($search);
        }
        
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Batch insert
     */
    public function batch_insert($data) {
        return $this->db->insert_batch($this->table, $data);
    }
    
    /**
     * Batch update
     */
    public function batch_update($data, $key) {
        return $this->db->update_batch($this->table, $data, $key);
    }
    
    /**
     * Truncate table
     */
    public function truncate() {
        return $this->db->truncate($this->table);
    }
}
