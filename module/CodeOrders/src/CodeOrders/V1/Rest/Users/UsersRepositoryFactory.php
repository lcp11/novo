<?php
/**
 * Created by PhpStorm.
 * User: terainfor
 * Date: 05/10/16
 * Time: 14:32
 */

namespace CodeOrders\V1\Rest\Users;


use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UsersRepositoryFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('dbadpter');
        $usersmapper = new UsersMapper();
        $hydrator = new HydratingResultSet($usersmapper, new UsersEntity());
        $tablegateway = new TableGateway('oauth_users', $dbAdapter, null, $hydrator);
        $usersrepository = new UsersRepository($tablegateway);
        return $usersrepository;
    }

}