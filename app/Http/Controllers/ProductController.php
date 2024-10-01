<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $products = Product::where('product_name', '!=', "");
            $products = $products->paginate(2);
            return response()->json(compact('products'), 200);
        } else {
            return response()->json(["msg" => "failed"]);
        }
    }
    public function store(Request $request)
    {
        //we will have to de validation first
        $validateproduct = Validator::make($request->all(), [
            'product_name' => 'required|unique:products|max:255',
            "quantity" => 'required|numeric',
            "price" => 'required|decimal:0,3',
            "description" => 'nullable'
        ]);

        if ($validateproduct->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validateproduct->errors()], 201);
        };
        // Save the product to the database
        // $product = Product::create($validateproduct->validated());
        $product = Product::create($validateproduct->safe()->only(["product_name", "quantity", "price", 'description']));
        // Return a success response
        return response()->json(['status' => 'success', 'data' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $validateproduct = Validator::make($request->all(), [
            'product_name' => 'required|max:255',
            "quantity" => 'required|numeric',
            "price" => 'required|decimal:0,3',
            "description" => 'nullable'
        ]);

        if ($validateproduct->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validateproduct->errors()], 201);
        };

        $existingProduct = Product::findOrFail($id);
        $validProduct = $validateproduct->validated();

        $existingProduct->product_name = $validProduct['product_name'];
        $existingProduct->quantity = $validProduct["quantity"];
        $existingProduct->price = $validProduct["price"];
        $existingProduct->description = $validProduct["description"];
        $existingProduct->save();
        return response()->json(compact('existingProduct'), 201);
    }
    public function destroy(Request $request, $id)
    {
        $existingProduct = Product::findOrFail($id);
        $existingProduct->delete();
        return response()->json(['status' => 'success'], 301);
    }
    public function single(Request $request, $id)
    {
        $existingProduct = Product::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $existingProduct], 301);
    }
}
