<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function list()
    {
        $products = $this->product->all();
        $total = 0;
        $totalProduct = 0;
        foreach($products as $product)
        {
            $total += $product['Qty'] * $product['Price'];
            $totalProduct += $product['Qty'];
        }
        $total = number_format(strval($total));
        $totalProduct = number_format(strval($totalProduct));
        return view('product.list', compact('total', 'totalProduct'));
    }
    public function apiList()
    {

//        dd($_GET);

        $total = $this->product->count();
        $draw = (isset($_GET['draw'])) ? $_GET['draw'] : 1;
        $start = (isset($_GET['start'])) ? $_GET['start'] : 1;
        $leng = (isset($_GET['length'])) ? $_GET['length'] : 10;

        $keywork = (isset($_GET['search'])) ? $_GET['search'] : '';

        $columns = $_GET['columns'];
        $code = $columns[0]['search']['value'];
        $name = $columns[1]['search']['value'];
        $qty = $columns[2]['search']['value'];
        $price = $columns[3]['search']['value'];
        $date = $columns[4]['search']['value'];

        if($keywork['value'] != '')
        {
            $keywork = $keywork['value'];
            $products = $this->product
                ->where('ProductCode', 'like', '%'.$keywork.'%')
                ->orWhere('ProductName', 'like', '%'.$keywork.'%')
                ->orWhere('Qty', 'like', '%'.$keywork.'%')
                ->orWhere('Price', 'like', '%'.$keywork.'%')
                ->offset($start)->limit($leng)->get();
        }
        else if($code != '' || $name != '' || $qty != '' || $price != '' || $date != '')
        {
            // xử lý date
            $arrDate = explode('/', $date);
            if(count($arrDate) == 1 && $arrDate[0] == '')
            {
                $products = $this->product
                    ->where('ProductCode', 'like', '%'.$code.'%')
                    ->where('ProductName', 'like', '%'.$name.'%')
                    ->where('Qty', 'like', '%'.$qty.'%')
                    ->where('Price', 'like', '%'.$price.'%')
                    ->offset($start)->limit($leng)->get();
            }
            else if(count($arrDate) == 1)
            {
                $products = $this->product
                    ->where('ProductCode', 'like', '%'.$code.'%')
                    ->where('ProductName', 'like', '%'.$name.'%')
                    ->where('Qty', 'like', '%'.$qty.'%')
                    ->where('Price', 'like', '%'.$price.'%')
                    ->whereDay('CreateDate', '=', $arrDate[0])
                    ->offset($start)->limit($leng)->get();
            }
            else if(count($arrDate) == 2)
            {
                $products = $this->product
                    ->where('ProductCode', 'like', '%'.$code.'%')
                    ->where('ProductName', 'like', '%'.$name.'%')
                    ->where('Qty', 'like', '%'.$qty.'%')
                    ->where('Price', 'like', '%'.$price.'%')
                    ->whereDay('CreateDate', '=', $arrDate[0])
                    ->whereMonth('CreateDate', '=', $arrDate[1])
                    ->offset($start)->limit($leng)->get();
            }
            else if(count($arrDate) == 3)
            {
                $products = $this->product
                    ->where('ProductCode', 'like', '%'.$code.'%')
                    ->where('ProductName', 'like', '%'.$name.'%')
                    ->where('Qty', 'like', '%'.$qty.'%')
                    ->where('Price', 'like', '%'.$price.'%')
                    ->whereDay('CreateDate', '=', $arrDate[0])
                    ->whereMonth('CreateDate', '=', $arrDate[1])
                    ->whereYear('CreateDate', '=', $arrDate[2])
                    ->offset($start)->limit($leng)->get();
            }
        }
        else
        {
            $products = $this->product->offset($start)->limit($leng)->get();
        }
        return response([
            'draw' => $draw,
            'recordsTotal' => count($products),
            'recordsFiltered' => $total,
            'data' => $products
        ])->header('Access-Control-Allow-Origin', '*');
    }
}
