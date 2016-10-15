<?php
/**
 * Created by PhpStorm.
 * User: terainfor
 * Date: 05/10/16
 * Time: 14:28
 */

namespace CodeOrders\V1\Rest\Users;


use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbTableGateway;
use ZF\MvcAuth\Identity\AuthenticatedIdentity;

class UsersRepository
{
    /**
     * @var TableGatewayInterface
     */
    private $tablegateway;
    /**
     * @var AuthenticatedIdentity
     */
    private $auth;

    public function __construct(TableGatewayInterface $tablegateway, AuthenticatedIdentity $auth)
    {
        $this->tablegateway = $tablegateway;
        $this->auth = $auth;
    }

    public function findAll(){
        $tablegateway = $this->tablegateway;
        $paginatorAdapter = new DbTableGateway($tablegateway);
        return new UsersCollection($paginatorAdapter);
    }

    public function find($id){
        $resultset = $this->tablegateway->select(['id'=>(int) $id]);

        return $resultset->current();
    }

    public function findByUserName($username){
        return $this->tablegateway->select(['username'=>$username])->current();
    }

    public function getAuthenticated(){
        $username = $this->auth->getAuthenticationIdentity()['user_id'];
        return $this->findByUserName($username);
    }
}