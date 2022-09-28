<?php

namespace App\Models;

use CodeIgniter\Model;

class PaySalaryModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pay_salary';
    protected $primaryKey       = 'pay_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [

        "pay_id",
        "emp_id",
        "type_id",
        "month",
        "year",
        "paid_date",
        "total_days",
        "basic",
        "medical",
        "house_rent",
        "bonus",
        "bima",
        "tax",
        "provident_fund",
        "loan",
        "total_pay",
        "addition",
        "diduction",
        "status",
        "paid_type",
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
