<?php
/**
 * Created by PhpStorm.
 * User: terainfor
 * Date: 08/10/16
 * Time: 14:42
 */

namespace CodeOrders\V1\Rest\Orders;


use Zend\Hydrator\ObjectProperty;

class OrdersService
{

    /**
     * @var OrdersRepository
     */
    private $repository;

    public function __construct(OrdersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function insert($data){
        $hydrator = new ObjectProperty();
        $data = $hydrator->extract($data);
        $object = $data;
        unset($object['item']);
        $items = $data['item'];
        $tablegateway = $this->repository->getTableGateway();
        try{
            $tablegateway->getAdapter()->getDriver()->getConnection()->beginTransaction();

            $orderid = $this->repository->insert($object);
            foreach ($items as $item){
                $item['order_id']=$orderid;
                $this->repository->inssertitem($item);
            }
            $tablegateway->getAdapter()->getDriver()->getConnection()->commit();
            return ['order_id'=>$orderid];
        } catch (\Exception $e ){
            $tablegateway->getAdapter()->getDriver()->getConnection()->rollback();
            return 'error';
        }

    }

}