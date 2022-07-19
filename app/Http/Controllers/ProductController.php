<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Company;
use App\Models\Sale;
class ProductController extends Controller
{
    /**
    * コンストラクタ
    * 継承したControllerクラスのmiddleware()を利用する
    */
    public function __construct() {
    // ログイン状態を判断するミドルウェア
    $this->middleware('auth');
    }
    
    /**
     * 商品一覧画面を表示
     * 
     * @return view
     */
    public function showDisplay(Request $request) {
        $product_instance = new Product;
        $company_instance = new Company;
        $keyword = $request->input('keyword');
        $select_name = $request->input('company_id');
        try {
            $product_list = $product_instance->productList();
            $company_data = $company_instance->companyList();
            if(!empty($keyword)) {
            $product_list = $product_instance->searchKeyword($keyword);
            }
            if(!empty($select_name)){
            $product_list = $product_instance->searchCompanyName($select_name);
            }
        } catch (\Throwaable $e) {
            throw new \Exception($e->getMessage());
        }
        return view('product.list_display', compact('product_list', 'company_data', 'keyword'));
    }

    /**
     * 商品詳細画面
     * @param $id
     * @return $view
     */
    public function showDetail($id){
        $product_instance = new Product;
            $product = $product_instance->productDetail($id);
            try{
            if(is_null($product)) {
                \Session::flash('err_msg','データがありません。');
                return redirect(route('product.list_display'));
            }
        }catch(\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
        return view('product.detail', compact('product'));
    }
    
    /**
     * 商品登録画面
     * 
     * @return view
     */
    public function showCreate(){
        $company_instance = new Company;
        try{
            $selectItems=$company_instance->companyList();
        }catch(\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
        return view('product.form', compact('selectItems'));
    }

    /**
     * 新規商品登録フォーム
     * @param ProductRequest $request 
     * @return view
     */
    public function exeStore(ProductRequest $request) {
        $product_instance = new Product;
        $img_path = $request->file('img_path');

        $path = null;
        if(!empty($img_path)) {
            $path = $img_path->store('\img', 'public');
            // $img_path_name = $img_path->getPathname();
            // $img_path->storeAs('',$img_path_name,'public');
        }
        
        $insert_data = [];
        $insert_data['company_id'] = $request->input('company_id');
        $insert_data['product_name'] = $request->input('product_name');
        $insert_data['price'] = $request->input('price');
        $insert_data['stock'] = $request->input('stock');
        $insert_data['comment'] = $request->input('comment');
        $insert_data['img_path'] = $path;

        \DB::beginTransaction();

        try{
            $product_instance->createProduct($insert_data);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
        }
        \Session::flash('err_msg','商品を登録しました。');
        return redirect(route('product.display'));
    }
    
    /**
     * 商品情報編集画面
     * @param $id
     * @return view
     */
    public function showEdit($id) {
        $product_instance = new Product;
        $company_instance = new Company;
        try{
            $product = $product_instance->productDetail($id);
            $company_display = $company_instance->companyList();
            if(is_null($product)) {
                \Session::flash('err_msg','該当するものがありません。');
                return redirect(route('product.list_display'));
            }
        }catch(\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
        return view('product.edit', compact('product', 'company_display'));
    }

    /**
     * 商品情報編集フォーム
     * @param ProductRequest $request 
     * @return view
     */
    public function exeUpdate(ProductRequest $request) {
        $product_instance = new Product;
        $img_path = $request->file('img_path');

        $path = null;
        if(!empty($img_path)) {
            $path = $img_path->store('\img', 'public');
        }
        $update_date = [];
        $update_date['id'] = $request->input('id');
        $update_date['company_id'] = $request->input('company_id');
        $update_date['product_name'] = $request->input('product_name');
        $update_date['price'] = $request->input('price');
        $update_date['stock'] = $request->input('stock');
        $update_date['comment'] = $request->input('comment');
        $update_date['img_path'] = $path;

        \DB::beginTransaction();
        try{
            $product_instance->updateProduct($update_date);
            \DB::commit();
        }catch(\Throwable $e){
            \DB::rollback();
            throw new \Excepetion($e-getMessage());
        }
        \Session::flash('err_msg','商品情報を更新しました。');
        return redirect(route('product.display'));
    }
    
    /**
     * 商品情報削除
     * @param $id
     */
    public function exeDelete($id) {
        $product_instance = new Product;
        if(empty($id)) {
            \Session::flash('err_msg','該当データはありません');
            return redirect(route('product_display'));
        }
        try{
            $product_instance->deleteProduct($id);
        }catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
        \Session::flash('err_msg', '削除しました');
        return redirect(route('product.display'));
    }
}
