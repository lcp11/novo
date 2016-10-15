<?php
/**
 * Created by PhpStorm.
 * User: terainfor
 * Date: 14/10/16
 * Time: 15:34
 */

namespace CodeOrders\V1\Rest\Products;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\ArraySerializable;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class ProductsRepositoryFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbadapter = $serviceLocator->get('dbadpter');
        $hydrator = new HydratingResultSet(new ArraySerializable(), new ProductsEntity());
        $tablegateway = new TableGateway('products', $dbadapter, null, $hydrator);
        return new ProductsRepository($tablegateway);
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // TODO: Implement __invoke() method.
    }
}