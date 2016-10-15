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
    /**
     * @var AbstractTableGateway
     */
    private $clientabstracttablegateway;

    public function __construct(
        AbstractTableGateway $abstracttablegateway,
        AbstractTableGateway $ordersitemtablegateway,
        AbstractTableGateway $clientabstracttablegateway
    )
    {

        $this->abstracttablegateway = $abstracttablegateway;
        $this->ordersitemtablegateway = $ordersitemtablegateway;
        $this->clientabstracttablegateway = $clientabstracttablegateway;
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

    public function update(array $data,$id){
        $this->abstracttablegateway->update($data,['id'=>$id]);
        return $id;
    }
    public function find($id){
        $result = $this->abstracttablegateway->select(['id'=>(int) $id]);
        if($result->count() == 1){
            $hydrator = new ClassMethods();
            $hydrator->addStrategy('items', new OrdersItemHydratorStrategy(new ClassMethods()));
            $order = $result->current();
            $client = $this->clientabstracttablegateway
                ->select(['id'=>$order->getClientId()])
                ->current();
            $sql = $this->ordersitemtablegateway->getSql();
                $select = $sql->select()
                    ->join('products','order_items.product_id=products.id',['name'=>'name'])
                    ->where(['order_id'=>$order->getId()]);
            $items = $this->ordersitemtablegateway->selectWith($select);
            $order->setClient($client);
            foreach ($items as $item) {
                $order->addItem($item);
            }
            $data = $hydrator->extract($order);
            return $data;
        }
        return false; 
    }
}