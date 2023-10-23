<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\UserLog;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);

        $product = Product::create($request->all());
        $log_entry = Auth::user()->name . " added a product ". $product->name;
        event(new UserLog($log_entry));
        return redirect()->route('products.index')->with('success', 'product created successfully');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
    ]);

    $data = $request->only(['name', 'description', 'price']);
    $product->update($data);

    $log_entry = Auth::user()->name . " updated a product " . $product->name;
    event(new UserLog($log_entry));

    return redirect()->route('products.index')
        ->with('success', 'product updated successfully');
}


    public function destroy(Product $product)
    {
        $product->delete();
        $log_entry = Auth::user()->name . " deleted an product ". $product->name;
        event(new UserLog($log_entry));

        return redirect()->route('products.index')
            ->with('success', 'product deleted successfully');
    }
}
