<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
    * @var Product
    */
    private $product;
    public function __construct(Product $product){
        $this->product = $product;
    }
    
    public function index(){
        
        return response()->json($this->product->paginate(5));
    }
    
    public function show(int $id){
        
        $product = $this->product->find($id);
        if (!$product){
            return response()->json(ApiError::errorMessage("Produto não encontrado!", 4040), 404);
        }
        $data = ['data' => $product];
        return response()->json($data);
    }
    
    public function store(Request $request){
        try{
            $product = $request->all();
            $this->product->create($product);

            return response()->json(['msg'=>"Produto criado com sucesso!"], 201);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage("Houve um erro ao realizar a operação de inserção",1010), 500);
        }
    } 
    
    public function update(Request $request, int $id){
        try{
            $productRequest = $request->all();
            
            $product = $this->product->find($id);
            $product->update($productRequest);

            return response()->json(['msg'=>"Produto atualizado com sucesso!"], 201);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011), 500);
            }
            return response()->json(ApiError::errorMessage("Houve um erro ao realizar a operação de alteração",1011), 500);
        }
    } 
    
    public function delete(Product $id){
        try{
            $id->delete();

            return response()->json(['msg'=>"Produto removido com sucesso!"], 200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012), 500);
            }
            return response()->json(ApiError::errorMessage("Houve um erro ao realizar a operação de remoção",1012), 500);
        }
    }
}
