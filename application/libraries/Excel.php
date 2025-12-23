<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Excel Library
 * Wrapper for PhpOffice/PhpSpreadsheet
 * 
 * Requires: composer require phpoffice/phpspreadsheet
 * Or manually download to application/third_party/phpspreadsheet/
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Excel {
    
    protected $CI;
    protected $spreadsheet;
    protected $activeSheet;
    
    public function __construct() {
        $this->CI =& get_instance();
        
        // Load PhpSpreadsheet autoloader
        require_once APPPATH . 'third_party/phpspreadsheet/vendor/autoload.php';
    }
    
    /**
     * Create new spreadsheet
     */
    public function create() {
        $this->spreadsheet = new Spreadsheet();
        $this->activeSheet = $this->spreadsheet->getActiveSheet();
        return $this;
    }
    
    /**
     * Load existing spreadsheet
     */
    public function load($file_path) {
        $this->spreadsheet = IOFactory::load($file_path);
        $this->activeSheet = $this->spreadsheet->getActiveSheet();
        return $this;
    }
    
    /**
     * Set header row with styling
     */
    public function setHeader($headers, $row = 1) {
        $col = 'A';
        foreach ($headers as $header) {
            $cell = $col . $row;
            $this->activeSheet->setCellValue($cell, $header);
            
            // Style header
            $this->activeSheet->getStyle($cell)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            
            $col++;
        }
        
        return $this;
    }
    
    /**
     * Add data rows
     */
    public function addData($data, $start_row = 2) {
        $row = $start_row;
        foreach ($data as $item) {
            $col = 'A';
            foreach ($item as $value) {
                $this->activeSheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }
        
        return $this;
    }
    
    /**
     * Auto size columns
     */
    public function autoSizeColumns() {
        $highestColumn = $this->activeSheet->getHighestColumn();
        foreach (range('A', $highestColumn) as $col) {
            $this->activeSheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        return $this;
    }
    
    /**
     * Download as Excel file
     */
    public function download($filename = 'export.xlsx') {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Save to file
     */
    public function save($file_path) {
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($file_path);
        return $this;
    }
    
    /**
     * Read data from spreadsheet
     */
    public function getData($start_row = 2) {
        $data = [];
        $highestRow = $this->activeSheet->getHighestRow();
        $highestColumn = $this->activeSheet->getHighestColumn();
        
        for ($row = $start_row; $row <= $highestRow; $row++) {
            $rowData = [];
            foreach (range('A', $highestColumn) as $col) {
                $rowData[] = $this->activeSheet->getCell($col . $row)->getValue();
            }
            
            // Skip empty rows
            if (!empty(array_filter($rowData))) {
                $data[] = $rowData;
            }
        }
        
        return $data;
    }
    
    /**
     * Get spreadsheet object
     */
    public function getSpreadsheet() {
        return $this->spreadsheet;
    }
    
    /**
     * Get active sheet
     */
    public function getActiveSheet() {
        return $this->activeSheet;
    }
}
