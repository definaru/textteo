<?php

namespace App\Models;

use CodeIgniter\Model;

class JoinModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'joins';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $validationRules      = [];
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $beforeInsert   = [];
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $afterInsert    = [];
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $beforeUpdate   = [];
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $afterUpdate    = [];
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $beforeFind     = [];
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $afterFind      = [];
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $beforeDelete   = [];
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $afterDelete    = [];
}
