<?php
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../config/database.php';

class CategoryController
{
    private $db;
    private $categoryModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function getAllCategories()
    {
        return $this->categoryModel->getAllCategories();
    }

    public function getCategoryById($id)
    {
        return $this->categoryModel->getCategoryById($id);
    }

    public function addCategory($data)
    {
        return $this->categoryModel->addCategory($data);
    }

    public function updateCategory($id, $data)
    {
        return $this->categoryModel->updateCategory($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->categoryModel->deleteCategory($id);
    }
}
