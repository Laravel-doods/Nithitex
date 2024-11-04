<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use App\Traits\Utils;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class MainCategoryController extends Controller
{
	use Utils;

	public function mainCategoryView()
	{
		$main_categories = MainCategory::orderBy('id', 'ASC')->whereNull('deleted_at')->get();
		if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Main Categories'), Auth::user()->id)) {
			return view('backend.main-category.main-category_view', compact('main_categories'));
		} else {
			return view('401');
		}
	}

	public function mainCategoryStore(Request $request)
	{
		$request->validate([
			'main_category_name' => 'required|min:4|max:100|unique:main_categories,main_category_name',
			'main_category_image' => 'image|mimes:jpeg,jpg,png,webp|min:1|max:2000'
		]);

		$image = $request->file('main_category_image');
		$name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

		// Resize the image
		$resizedImage = Image::make($image)->encode();

		$uploadPath = 'upload/products/main-category/';
		$filePath = $uploadPath . $name_gen;

		$save_url = $this->fileUploadS3Bucket($filePath, $resizedImage);

		MainCategory::create([
			'main_category_name' => $request->main_category_name,
			'main_category_image' => $save_url
		]);

		$notification = [
			'message' => 'Main Category Created Successfully',
			'alert-type' => 'success'
		];

		return redirect()->back()->with($notification);
	}

	public function mainCategoryEdit($id)
	{
		$main_categories = MainCategory::findOrFail($id);
		if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Main Categories'), Auth::user()->id)) {
			return view('backend.main-category.main-category_edit', compact('main_categories'));
		} else {
			return view('401');
		}
	}

	public function mainCategoryUpdate(Request $request)
	{
		$request->validate([
			'main_category_name' => [
				'required',
				'min:4',
				'max:100',
				Rule::unique('main_categories', 'main_category_name')->ignore($request->id)
			],
			'main_category_image' => 'image|mimes:jpeg,jpg,png,webp|min:1|max:2000'
		]);
		$category_id = $request->id;
		$oldImage = $request->old_image;

		$main_category = MainCategory::find($category_id);
		$main_category->main_category_name = $request->main_category_name;


		if ($request->file('main_category_image')) {
			if ($oldImage) {
				$filePath = parse_url($oldImage, PHP_URL_PATH);
				if ($this->checkFileExistsOnS3($filePath)) {
					$this->deleteFromS3Bucket($filePath);
				} else {
					@unlink(public_path($filePath));
				}
			}

			$image = $request->file('main_category_image');
			$name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
			$resizedImage = Image::make($image)->encode();

			$uploadPath = 'upload/products/main-category/';
			$filePath = $uploadPath . $name_gen;
			$save_url = $this->fileUploadS3Bucket($filePath, $resizedImage);

			$main_category->main_category_image = $save_url;
		}
		$main_category->save();

		$notification = array(
			'message' => 'Main Category Updated Successfully',
			'alert-type' => 'success'
		);

		return redirect()->route('main-category.all')->with($notification);
	}

	public function mainCategoryDelete($id)
	{
		$category = Category::where('main_category_id', $id)->get()->count();
		if ($category == 0) {
			$main_category = MainCategory::findOrFail($id);
			$main_category->delete();

			$notification = array(
				'message' => 'Main Category Deleted Successfully',
				'alert-type' => 'success'
			);
		} else {
			$notification = array(
				'message' => 'Delete Failed!. This main category is reference with another instance.',
				'alert-type' => 'error'
			);
		}
		return redirect()->back()->with($notification);
	}
}
