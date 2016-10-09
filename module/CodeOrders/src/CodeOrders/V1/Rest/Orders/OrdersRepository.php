<?php
/**
 * Created by PhpStorm.
 * User: terainfor
 * Date: 06/10/16
 * Time: 15:17
 */

namespace CodeOrders\V1\Rest\Orders;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Hydrator\ObjectProperty;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Stdlib\Hydrator\ClassMethods;

class OrdersRepository
{

    /**
     * @var AbstractTableGateway
     */
    private $abstracttablegateway;
    /**
     * @var AbstractTableGateway
     */
    private $ordersitemtablegateway;

    public function __construct(AbstractTableGateway $abstracttablegateway, AbstractTableGateway $ordersitemtablegateway)
    {

        $this->abstracttablegateway = $abstracttablegateway;
        $this->ordersitemtablegateway = $ordersitemtablegateway;
    }

    public function findAll(){

        $hydrator = new ClassMethods();
        $hydrator->addStrategy('items', new OrdersItemHydratorStrategy(new ClassMethods()));
        $orders = $this->abstracttablegateway->select();
        $res = [];
        foreach ($orders as $order){
            $items = $this->ordersitemtablegateway->select(['order_id'=>$order->getId()]);
            foreach ($items as $item){
                $order->addItem($item);
            }
            $data = $hydrator->extract($order);
            $res[]=$data;
        }
        $arrayadapter = new ArrayAdapter($res);
        $paginator = new OrdersCollection($arrayadapter);
        return $paginator;
    }

    public function insert(array $data){
        $this->abstracttablegateway->insert($data);
        $id = $this->abstracttablegateway->getLastInsertValue();

        return $id;
    }


    public function inssertitem(array $data){
        $this->ordersitemtablegateway->insert($data);
        return $this->ordersitemtablegateway->getLastInsertValue();
    }

    public function getTableGateway(){
        return $this->abstracttablegateway;
    }
}