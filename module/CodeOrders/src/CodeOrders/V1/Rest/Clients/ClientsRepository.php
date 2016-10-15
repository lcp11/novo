<?php
/**
 * Created by PhpStorm.
 * User: terainfor
 * Date: 11/10/16
 * Time: 13:37
 */

namespace CodeOrders\V1\Rest\Clients;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Paginator\Adapter\DbTableGateway;

class ClientsRepository
{

    /**
     * @var AbstractTableGateway
     */
    private $tableGateway;

    public function __construct(AbstractTableGateway $tableGateway)
    {

        $this->tableGateway = $tableGateway;
    }

    public function findAll(){
        $paginator = new DbTableGateway($this->tableGateway);
        return new ClientsCollection($paginator);
    }
}