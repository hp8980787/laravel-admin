<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrderItemRequest;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as Controller;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderItemRequest $request)
    {
        $data = $request->only(['name', 'order_id', 'product_id', 'amount', 'price']);
        OrderItem::query()->create($data);
        return $this->message('添加成功!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'order_id', 'product_id', 'amount', 'price']);
        OrderItem::query()->where('id',$id)->update($data);
        return $this->message('修改成功!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OrderItem::query()->where('id',$id)->delete();
        return $this->message('删除成功!');
    }
}
