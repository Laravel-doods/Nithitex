<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;

class MainCategoryController extends Controller
{
    use ResponseAPI;

    public function mainCategory()
    {
        try {
            $responseData = [];

            $responseData['main_category'] = [];
            $main_category = MainCategory::get();
            foreach ($main_category as $item) {
                $main_categoryDetails['id'] = $item->id;
                $main_categoryDetails['main_category_name'] = $item->main_category_name;
                $main_categoryDetails['main_category_image'] = url($item->main_category_image);
                array_push($responseData['main_category'], $main_categoryDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
