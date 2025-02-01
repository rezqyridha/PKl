<?php
require_once '../models/SalesModel.php';

class EmployeeController {
    private $db;
    private $salesModel;

    public function __construct($db) {
        $this->db = $db;
        $this->salesModel = new SalesModel($db);
    }

    public function getEmployeeDashboardData($employeeId) {
        return [
            'total_sales' => $this->salesModel->getSalesByEmployee($employeeId), // Penjualan oleh karyawan
            'tasks' => $this->getTasksForEmployee($employeeId) // Tugas yang harus diselesaikan
        ];
    }

    private function getTasksForEmployee($employeeId) {
        // Contoh tugas dummy (dapat dihubungkan ke tabel task jika ada)
        return [
            ['task' => 'Update stock for Product A', 'due_date' => '2024-12-20'],
            ['task' => 'Record sales for today', 'due_date' => '2024-12-20']
        ];
    }
}
?>
