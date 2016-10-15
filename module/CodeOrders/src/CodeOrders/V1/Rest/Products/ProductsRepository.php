<?php
/**
 * Created by PhpStorm.
 * User: terainfor
 * Date: 14/10/16
 * Time: 12:59
 */

namespace CodeOrders\V1\Rest\Products;


use Zend\Db\TableGateway\AbstractTableGateway;

class ProductsRepository
{

    /**
     * @var AbstractTableGateway
     */
    private $tableGateway;

    public function __construct(AbstractTableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function find($id){
        $result = $this->tableGateway->select(['id'=>(int) $id]);
        if($result->count() == 1){
            return $result->current();
        }
            return false;
    }
}