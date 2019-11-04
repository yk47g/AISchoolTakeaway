<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class orders extends NotORM {

    public function getOnesAllOrders($userID,$offset,$limit) {
        return $this->getORM()
            ->where('id >= ?', $offset)
            ->limit($limit)
            ->where('userID = ?', $userID)
            ->order('createTime DESC')
            ->fetchAll();
    }

    public function getOnesOneOrder($ID) {
        return $this->getORM()
            ->where('id = ?', $ID)
            ->fetchOne();
    }

    public function getOnesOneOrderByWechatNo($ID) {
        return $this->getORM()
            ->where('orderNo = ?', $ID)
            ->fetchOne();
    }

    public function getAllOrdersOnNeedDelive($offset,$limit) {
        return $this->getORM()
            ->where('id >= ?', $offset)
            ->where('status = 1')
            ->limit($limit)
            ->fetchAll();
    }

    public function insertOneOrder($userID,$foodArrID,$remark,$restID,$totalPrice,$payPrice,$addrID,$discountID,$createTime,$shouldDeliveTime,$deliveFee,$upstairs) {
        $data = array('userID' => $userID,'foods' => $foodArrID,'remark' => $remark,'discountID' => $discountID,'restID' => $restID,'totalPrice' => $totalPrice,'payPrice' => $payPrice,'addressID' => $addrID,'createTime' => $createTime, 'status' => 0, 'shouldDeliveTime' => $shouldDeliveTime, 'deliveFee' => $deliveFee, 'upstairs' => $upstairs);
        $orm = $this->getORM();
        $orm->insert($data);

        // 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
        return $orm->insert_id();
    }

    public function updateOrderNo($id,$orderNo) {
        $data = array('orderNo' => $orderNo);
        return $this->getORM()
        ->where('id', $id)
        ->update($data);
    }

    public function updateOrderPay($orderNo,$payPrice,$payTime) {
        $data = array('payTime' => $payTime,'payPrice' => $payPrice,'status' => 1);
        return $this->getORM()
        ->where('status = 0')
        ->where('orderNo', $orderNo)
        ->update($data);
    }

    public function updateStatus($id,$statusNum) {
        $data = array('status' => $statusNum);
        return $this->getORM()
        ->where('id', $id)
        ->update($data);
    }

    public function updateStatusOnJieDan($id,$statusNum) {
        $data = array('status' => $statusNum);
        return $this->getORM()
        ->where('status = 1')
        ->where('id', $id)
        ->update($data);
    }

    public function cancelOrder($id) {
        $data = array('status' => 8);
        return $this->getORM()
        ->where('status = 1')
        ->where('id', $id)
        ->update($data);
    }

    public function errorOrder($id) {
        $data = array('status' => -1);
        return $this->getORM()
        ->where('id', $id)
        ->update($data);
    }

    public function updateComplaint($id) {
        $data = array('hasComplaint' => 1);
        return $this->getORM()
        ->where('id', $id)
        ->update($data);
    }

    public function userCommentOrder($id,$commentID) {
        $data = array('status' => 5,'hasComment' => 1,'commentID' => $commentID);
        return $this->getORM()
        ->where('id', $id)
        ->update($data);
    }
}
