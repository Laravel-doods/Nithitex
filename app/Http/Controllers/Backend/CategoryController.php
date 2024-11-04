<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\OfferHistory;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Models\User;
use App\Traits\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
	use Utils;

	public function CategoryView()
	{
		$main_category = MainCategory::whereNull('deleted_at')->latest()->get();
		$categories = Category::with('main_category')->orderBy('id', 'ASC')->whereNull('deleted_at')->get();
		if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Categories'), Auth::user()->id)) {
			return view('backend.category.category_view', compact('main_category', 'categories'));
		} else {
			return view('401');
		}
	}

	public function CategoryStore(Request $request)
	{
		$request->validate([
			'ddlMainCategory' => 'required',
			'category' => 'required|min:4|max:100|unique:categories,category_name',
			'category_image' => 'image|mimes:jpeg,jpg,png,webp|min:1|max:2000'
		]);

		$image = $request->file('category_image');
		$name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

		// Resize the image
		$resizedImage = Image::make($image)->encode();

		$uploadPath = 'upload/products/category/';
		$filePath = $uploadPath . $name_gen;

		$save_url = $this->fileUploadS3Bucket($filePath, $resizedImage);

		Category::create([
			'main_category_id' => $request->ddlMainCategory,
			'category_name' => $request->category,
			'category_slug' => strtolower(str_replace(' ', '-', $request->category)),
			'category_code' => $request->txtCategoryCode,
			'category_description' => $request->category_description,
			'category_image' => $save_url
		]);

		$notification = array(
			'message' => 'Category Created Successfully',
			'alert-type' => 'success'
		);

		return redirect()->back()->with($notification);
	}

	public function CategoryEdit($id)
	{
		$main_category = MainCategory::whereNull('deleted_at')->latest()->get();
		$categories = Category::findOrFail($id);
		if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Categories'), Auth::user()->id)) {
			return view('backend.category.category_edit', compact('main_category', 'categories'));
		} else {
			return view('401');
		}
	}

	public function CategoryUpdate(Request $request)
	{
		$request->validate([
			'ddlMainCategory' => 'required',
			'category' => [
				'required',
				Rule::unique('categories', 'category_name')->ignore($request->id)
			],
			'category_image' => 'image|mimes:jpeg,jpg,png,webp|min:1|max:2000'
		]);
		$category_id = $request->id;
		$oldImage = $request->old_image;

		$category = Category::find($category_id);
		$category->main_category_id = $request->ddlMainCategory;
		$category->category_name = $request->category;
		$category->category_code = $request->txtCategoryCode;
		$category->category_slug = strtolower(str_replace(' ', '-', $request->category));
		$category->category_description = $request->category_description;


		if ($request->file('category_image')) {

			if ($oldImage) {
				$filePath = parse_url($oldImage, PHP_URL_PATH);
				if ($this->checkFileExistsOnS3($filePath)) {
					$this->deleteFromS3Bucket($filePath);
				} else {
					@unlink(public_path($filePath));
				}
			}
			$image = $request->file('category_image');
			$name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
			$resizedImage = Image::make($image)->encode();

			$uploadPath = 'upload/products/category/';
			$filePath = $uploadPath . $name_gen;
			$save_url = $this->fileUploadS3Bucket($filePath, $resizedImage);

			$category->category_image = $save_url;
		}
		$category->save();

		$notification = array(
			'message' => 'Category Updated Successfully',
			'alert-type' => 'success'
		);

		return redirect()->route('category.all')->with($notification);
	}

	public function CategoryDelete($id)
	{
		$product = Product::where('category_id', $id)->get()->count();
		if ($product == 0) {
			$category = Category::findOrFail($id);
			$category->delete();

			$notification = array(
				'message' => 'Category Deleted Successfully',
				'alert-type' => 'success'
			);
		} else {
			$notification = array(
				'message' => 'Delete Failed!. This category is reference with another instance.',
				'alert-type' => 'error'
			);
		}
		return redirect()->back()->with($notification);
	}

	public function createTodayOffer(Request $request)
	{
		$request->validate([
			'offer' => 'required|numeric|max:100|min:1',
		]);
		try {
			$exist = Category::where('id', $request->id)->where('is_today_offer', 1)->first();

			if ($exist == null) {
				Category::where('id', $request->id)->update([
					'is_today_offer' => 1,
					'offer' => $request->offer
				]);
				$this->createOfferHistory($request->id, $request->offer, 1);
				$response = [
					'success' => true,
					'message' => 'Offer Created Successfully'
				];
			} else {
				$response = [
					'success' => false,
					'message' => 'Offer Already Exist'
				];
			}
		} catch (\Throwable $th) {

			$response = [
				'success' => false,
				'message' => 'Somthing Went wrong!'
			];
		}
		return response()->json($response);
	}
	// public function createTodayOffer(Request $request)
	// {
	// 	$request->validate([
	// 		'ddlCategory' => 'required',
	// 		'offer' => 'required|numeric|max:100|min:1',
	// 	]);
	// 	try {
	// 		$this->removeTodayOffer();
	// 		Category::where('id', $request->ddlCategory)->update([
	// 			'is_today_offer' => 1,
	// 			'offer' => $request->offer
	// 		]);
	// 		$notification = array(
	// 			'message' => 'Offer Created Successfully',
	// 			'alert-type' => 'success'
	// 		);

	// 	} catch (\Throwable $th) {
	// 		$notification = array(
	// 			'message' => 'Somthing Went wrong!',
	// 			'alert-type' => 'error'
	// 		);
	// 	}
	// 	return redirect()->back()->with($notification);

	// }

	// public function removeTodayOffer()
	// {
	// 	Category::where('is_today_offer', 1)->update([
	// 		'is_today_offer' => 0,
	// 		'offer' => 0
	// 	]);
	// 	return response()->json(['success' => 'Today Offer removed successfully']);
	// }
	public function removeTodayOffer(Request $request)
	{
		try {
			$category =	Category::where('id', $request->id)->first();
			//Update cart prices, while removing an today offer!!
			$product_ids = Product::where('category_id', $request->id)->pluck('id');
			$cart = Cart::whereIn('product_id', $product_ids)->where('is_offer_product', 1)->get();
			foreach ($cart as $item) {
				$product = Product::where('id', $item->product_id)->first();
				$user_role_id = User::where('id', $item->user_id)->value('userrole_id');
				$item->is_offer_product = 0;
				if ($product->is_product_variant == 1) {
					$variant_product = ProductVariants::where('id', $item->variant_id)->first();
					$item->price = ($user_role_id == 1 ? $variant_product->customer_price : $variant_product->seller_price);
					$item->total = ($user_role_id == 1 ? $variant_product->customer_price : $variant_product->seller_price) * $item->qty;
				} else {
					$item->price = ($user_role_id == 1 ? $product->product_discount : $product->seller_discount);
					$item->total = ($user_role_id == 1 ? $product->product_discount : $product->seller_discount) * $item->qty;
				}
				$item->save();
			}

			$this->createOfferHistory($request->id, $category->offer, 0);

			$category->is_today_offer = 0;
			$category->offer = 0;
			$category->update();

			$response = [
				'success' => true,
				'message' => 'Offer Removed Successfully'
			];
		} catch (\Throwable $th) {
			$response = [
				'success' => false,
				'message' => $th->getMessage()
			];
		}
		return response()->json($response);
	}

	public static function createOfferHistory($cate_id, $offer, $status)
	{
		OfferHistory::create([
			'category_id' => $cate_id,
			'offer_price' => $offer,
			'status' => $status,
			'created_by' => Auth::user()->id,
			'created_at' => Carbon::now()
		]);
	}

	public function updateWeight(Request $request)
    {
        $categoryId = $request->category_id;
        $weight = $request->weight;

        $request->validate([
            'weight' => 'required|numeric|min:0',
        ]);

        $category = Category::find($categoryId);
        if ($category) {
            $category->weight = $weight;
            $category->save();

            return response()->json(['status' => 'success', 'message' => 'Weight updated successfully!']);
        }

        return response()->json(['status' => 'error', 'message' => 'Category not found.'], 404);
    }
}
