<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\APITrait;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class CartController extends Controller
{
    use APITrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $request->user()->id;
        $request['user_id'] = $user_id;
        return $this->returnData(true, 'product added to Cart', Cart::create($request->all()), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user_id = $request->user()->id;
        $carts = User::find($user_id)->carts()->get();
        foreach ($carts as $cart){
            $cart->prodcut = Cart::find($cart->id)->product()->get();
        }
        return $this->returnData(true, 'user cart get', $carts, 200);
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
        $user_id = $request->user()->id;
        $cart = User::find($user_id)->carts()->where('id', $id)->first();
        if(isset($cart)){
//            $cart =$cart->first();
            $result = $cart->update($request->all());
            if($result){
                $cart->product = Cart::find($cart->id)->product()->get();
                return $this->returnData(true, 'Update cart success ', $cart, 200);
            }
        }
            return $this->returnData(false, 'Something is wrong', null, 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user_id = $request->user()->id;
        $result = User::find($user_id)->carts()->where('id' , $id)->delete();
        if ($result) {
            return $this->returnSuccessMessage('Delete Bookmark Success', 200);
        } else {
            return $this->returnError('Something is wong', 500);
        }
    }
}
