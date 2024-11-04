<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ResponseAPI;
use Exception;

class CategoryController extends Controller
{
    use ResponseAPI;

    public function category()
    {
        try {
            $responseData = [];

            $responseData['category'] = [];
            $category = Category::get();
            foreach ($category as $item) {
                $categoryDetails['id'] = $item->id;
                $categoryDetails['main_category_id'] = $item->main_category_id;
                $categoryDetails['category_name'] = $item->category_name;
                $categoryDetails['category_image'] = url($item->category_image);
                array_push($responseData['category'], $categoryDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
