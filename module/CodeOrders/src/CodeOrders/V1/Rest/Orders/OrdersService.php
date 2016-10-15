<?php
/**
 * Created by PhpStorm.
 * User: terainfor
 * Date: 08/10/16
 * Time: 14:42
 */

namespace CodeOrders\V1\Rest\Orders;


use CodeOrders\V1\Rest\Products\ProductsRepository;
use CodeOrders\V1\Rest\Users\UsersRepository;
use Zend\Hydrator\ObjectProperty;

class OrdersService
{

    /**
     * @var OrdersRepository
     */
    private $repository;
    /**
     * @var UsersRepository
     */
    private $usersRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(
        OrdersRepository $repository,
        UsersRepository $usersRepository,
        ProductsRepository $productRepository
    )
    {
        $this->repository = $repository;
        $this->usersRepository = $usersRepository;
        $this->productRepository = $productRepository;
    }

    public function insert($data){
        $hydrator = new ObjectProperty();
        $data->user_id = $this->usersRepository->getAuthenticated()->getId();
        $data->created_at = (new \DateTime())->format('Y-m-d');
        $data->total=0;
        $items = $data->item;
        unset($data->item);
        $orderData = $hydrator->extract($data);
        $tablegateway = $this->repository->getTableGateway();
        try{
            $tablegateway->getAdapter()->getDriver()->getConnection()->beginTransaction();
            $orderid = $this->repository->insert($orderData);
            $total = 0;
            foreach ($items as $key=>$item){
                $product = $this->productRepository->find($item['product_id']);
                $item['order_id']=$orderid;
                $item['price'] = $product['price'];
                $item['total'] = $items[$key]['total']=$item['quantity']*$item['price'];
                $total += $item['total'];
                $this->repository->inssertitem($item);
            }
            $this->repository->update(['total'=>$total],$orderid);
            $tablegateway->getAdapter()->getDriver()->getConnection()->commit();
            return ['order_id'=>$orderid];
        } catch (\Exception $e ){
            $tablegateway->getAdapter()->getDriver()->getConnection()->rollback();
            return 'error';
        }

    }

}