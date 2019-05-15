<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\Order;
use App\User;

class PruebasController extends Controller
{
    //
    public function index(){
        $categories = Category::all();
        $products = Product::all();
        $users = User::all();
        $orders = Order::all();
        
        /*
        foreach($products as $product){
            echo "{$product->name} - {$product->category->name} <br>";
            
            
        } echo "<hr>";*/

        foreach ($orders as $order) {
            echo "El usuario : {$order->user->name} {$order->user->surname} pago con S/.{$order->money} y el total era de : S/.{$order->total}
            recibiendo como vuelto: S/.{$order->money_change}  y lo que compro fue: ";
            foreach ($order->products as $product ) {
                echo "{$product->name} que es un {$product->category->name} <br>";
            } 
            
        }
        
    }
}
