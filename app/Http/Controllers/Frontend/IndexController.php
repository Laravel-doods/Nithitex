<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Category;
use App\Models\Colors;
use App\Models\MainCategory;
use App\Models\Policy;
use App\Models\Product;
use App\Models\ProductMultipleImage;
use App\Models\ProductVariants;
use App\Models\ShopInformation;
use App\Models\Slider;
use App\Models\User;
use App\Models\Wishlist;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class IndexController extends Controller
{
	use Utils;
	private $sel_color = 0;
	private $search = "";
	private $sort_by = "";
	private $pagecount = 6;

	public function dashboard()
	{
		return view('dashboard');
	}
	
	public function index()
	{
		$main_category = MainCategory::get();
		$category = Category::get();
		$user_id = (Auth::check() ? array(Auth::user()->id) : 0);
		$query = $this->getProducts($user_id);

		$is_featured = $query->inRandomOrder()->limit(8)->get();
		$newarrivals = $query->inRandomOrder()->limit(8)->get();
		$best_selling_products = $query->inRandomOrder()->limit(8)->get();
		$about = About::limit(1)->get();
		$slider = Slider::where('userrole_id', '!=', 2)->get();
		$sellerSlider = Slider::where('userrole_id', '!=', 1)->get();
		$shopInformation = ShopInformation::find(1);
		if (Auth::user()) {
			return view((Auth::user()->userrole_id == 1 ? 'frontend.index' : 'seller.index'), compact('category', 'main_category', 'is_featured', 'newarrivals', 'best_selling_products', 'about', 'slider', 'sellerSlider', 'shopInformation'));
		} else {
			return view('frontend.index', compact('category', 'main_category', 'is_featured', 'newarrivals', 'best_selling_products', 'about', 'slider', 'shopInformation'));
		}
	}
	public function UserProfile()
	{
		$id = Auth::user()->id;
		$user = User::find($id);
		return view('frontend.profile.user_profile', compact('user'));
	}

	public function UserProfileStore(Request $request)
	{

		$data = User::find(Auth::user()->id);
		$data->name = $request->name;
		$data->email = $request->email;
		$data->phone = $request->phone;
		$data->door_no = $request->door_no;
		$data->street_address = $request->street;
		$data->city_name = $request->city;
		$data->state_name = $request->state_name;
		$data->pin_code = $request->pincode;

		if ($request->file('profile_photo_path')) {
			$file = $request->file('profile_photo_path');
			@unlink(public_path('upload/user_images/' . $data->profile_photo_path));
			$filename = date('YmdHi') . $file->getClientOriginalName();
			$file->move(public_path('upload/user_images'), $filename);
			$data['profile_photo_path'] = $filename;
		}
		$data->save();

		$notification = array(
			'message' => 'User Profile Updated Successfully',
			'alert-type' => 'success'
		);

		return redirect()->route('user.dashboard')->with($notification);
	} //end method


	public function UserChangePassword()
	{
		$id = Auth::user()->id;
		$user = User::find($id);
		return view('frontend.profile.change_password', compact('user'));
	}


	public function UserPasswordUpdate(Request $request)
	{

		$validateData = $request->validate([
			'oldpassword' => 'required',
			'password' => 'required|string|min:8|confirmed',
		]);
		$hashedPassword = Auth::user()->password;
		if (Hash::check($request->oldpassword, $hashedPassword)) {
			$user = User::find(Auth::id());
			$user->password = Hash::make($request->password);
			$user->save();
			Auth::logout();
			return redirect()->route('user.logout');
		} else {
			return redirect()->back();
		}
	} // end method


	//About Page
	public function about()
	{
		$about = About::limit(1)->get();
		return view('frontend.about', compact('about'));
	}
	//Contact Page
	public function contact()
	{
		$contact = ShopInformation::limit(1)->get();
		return view('frontend.contact', compact('contact'));
	}

	//Terms & Conditions Page
	public function terms()
	{
		$policy = Policy::limit(1)->get();
		return view('frontend.terms', compact('policy'));
	}

	//Privacy Policy Page
	public function privacy()
	{
		$privacyPolicy = Policy::limit(1)->get();
		return view('frontend.privacy', compact('privacyPolicy'));
	}

	//Return Policy
	public function return()
	{
		$returnPolicy = Policy::limit(1)->get();
		return view('frontend.return', compact('returnPolicy'));
	}

	//Support Policy
	public function support()
	{
		$supportPolicy = Policy::limit(1)->get();
		return view('frontend.support', compact('supportPolicy'));
	}

	//Track Order
	public function track_order()
	{

		return view('frontend.traking.track_order_design');
	}


	//Shop Page
	public function ProductShop(Request $request)
	{
		$sel_color = $this->sel_color;
		$sort_by = $this->sort_by;
		$user_id = (Auth::check() ? array(Auth::user()->id) : 0);
		$query = $this->getProducts($user_id);
		$shop_all_products = $query->inRandomOrder()->orderBy('id', 'DESC')->paginate($this->pagecount);
		$color = Colors::get();
		//  Load More Product with Ajax
		if ($request->ajax()) {
			$grid_view = view('frontend.product.shop_grid_view', compact('shop_all_products'))->render();
			$list_view = view('frontend.product.shop_list_view', compact('shop_all_products'))->render();
			return response()->json(['grid_view' => $grid_view, 'list_view' => $list_view]);
		}
		//  End Load More Product with Ajax

		return view('frontend.product.shop_details', compact('shop_all_products', 'color', 'sel_color', 'sort_by'));
	}


	//Offers Page
	public function ProductOffers(Request $request)
	{
		$sel_color = $this->sel_color;
		$sort_by = $this->sort_by;
		$user_id = (Auth::check() ? array(Auth::user()->id) : 0);
		$query = $this->getOfferProducts($user_id);
		$shop_all_products = $query->inRandomOrder()->orderBy('id', 'DESC')->paginate($this->pagecount);
		$color = Colors::get();
		//  Load More Product with Ajax
		if ($request->ajax()) {
			$grid_view = view('frontend.product.shop_grid_view', compact('shop_all_products'))->render();
			$list_view = view('frontend.product.shop_list_view', compact('shop_all_products'))->render();
			return response()->json(['grid_view' => $grid_view, 'list_view' => $list_view]);
		}
		//  End Load More Product with Ajax

		return view('frontend.product.shop_details', compact('shop_all_products', 'color', 'sel_color', 'sort_by'));
	}

	//Offers Page
	public function TodayOffer(Request $request)
	{
		$offer = 1;
		$sel_color = $this->sel_color;
		$sort_by = $this->sort_by;
		$user_id = (Auth::check() ? array(Auth::user()->id) : 0);
		$cat_ids = Category::where('is_today_offer', 1)->pluck('id');
		$query = $this->getProducts($user_id)->with('category')->whereIn('category_id', $cat_ids);

		$shop_all_products = $query->inRandomOrder()->orderBy('id', 'DESC')->paginate($this->pagecount);

		//offer price calculate
		foreach ($shop_all_products as $product) {
			$discount_amount = (int)$product->product_price - ((int)$product->product_price * $product->category->offer / 100);
			$product->product_discount = (string)$discount_amount;
			$product->seller_discount = (string)$discount_amount;
		}
		$color = Colors::get();
		//  Load More Product with Ajax
		if ($request->ajax()) {
			$grid_view = view('frontend.product.shop_grid_view', compact('shop_all_products', 'offer'))->render();
			$list_view = view('frontend.product.shop_list_view', compact('shop_all_products', 'offer'))->render();
			return response()->json(['grid_view' => $grid_view, 'list_view' => $list_view]);
		}
		//  End Load More Product with Ajax

		return view('frontend.product.shop_details', compact('shop_all_products', 'color', 'sel_color', 'sort_by', 'offer'));
	}


	//Products by Tags Page
	public function ProductsbyTags(Request $request, $tags)
	{
		$sel_color = $this->sel_color;
		$sort_by = $this->sort_by;
		$user_id = (Auth::check() ? array(Auth::user()->id) : 0);
		$query = $this->getProducts($user_id)->where('tags', $tags);

		$shop_all_products =  $query->inRandomOrder()->orderBy('id', 'DESC')->paginate($this->pagecount);
		$color = Colors::get();
		//  Load More Product with Ajax
		if ($request->ajax()) {
			$grid_view = view('frontend.product.shop_grid_view', compact('shop_all_products'))->render();
			$list_view = view('frontend.product.shop_list_view', compact('shop_all_products'))->render();
			return response()->json(['grid_view' => $grid_view, 'list_view' => $list_view]);
		}
		//  End Load More Product with Ajax

		return view('frontend.product.shop_details', compact('shop_all_products', 'color', 'sel_color', 'sort_by'));
	}



	public function ProductDetails(Request $request, $id, $slug)
	{
		$offer = $request->query('offer', 0);
		$user_id = (Auth::check() ? array(Auth::user()->id) : 0);
		$query = $this->getProductDetails($id, $user_id);

		$product_details = $query->first();
		if ($offer == 1) {
			$discount_amount = (int)$product_details->product_price - ((int)$product_details->product_price * $product_details->category->offer / 100);
			$product_details->product_discount = (string)$discount_amount;
			$product_details->seller_discount = (string)$discount_amount;
		}

		$userrole_id = (Auth::check() ? Auth::user()->userrole_id : 0);
		if ($userrole_id == 2) {
			$share = "https://api.whatsapp.com/send/?text=https://www.availablesarees.com";
		} else {
			$share = "https://api.whatsapp.com/send/?text=" . url('/product/details/' . $id . '/' . $slug);
		}
		$productVariants = $this->getProductVariant($id);
		$productGroup = $this->getProductGroup($product_details->group_id);

		$multiImage = ProductMultipleImage::where('product_id', $id)->get();
		$related_products = $this->getRelatedProducts($product_details->category_id, $id, $user_id)->get();

		//offer price calculate
		// foreach ($related_products as $product) {
		// 	$discount_amount = (int)$product->product_price - ((int)$product->product_price * $product->category->offer / 100);
		// 	$product->product_discount = (string)$discount_amount;
		// 	$product->seller_discount = (string)$discount_amount;
		// }

		return view('frontend.product.product_details', compact('product_details', 'multiImage', 'related_products', 'share', 'productVariants', 'productGroup', 'offer'));
	}

	public function MainCategoryDetails(Request $request, $id)
	{
		$sel_color = $this->sel_color;
		$sort_by = $this->sort_by;
		$user_id = (Auth::check() ? array(Auth::user()->id) : 0);
		$query = $this->getProducts($user_id);

		$shop_all_products = $query->where('main_category_id', $id)
			->inRandomOrder()
			->orderBy('id', 'DESC')
			->paginate($this->pagecount);
		$color = Colors::get();
		//  Load More Product with Ajax
		if ($request->ajax()) {
			$grid_view = view('frontend.product.shop_grid_view', compact('shop_all_products'))->render();
			$list_view = view('frontend.product.shop_list_view', compact('shop_all_products'))->render();
			return response()->json(['grid_view' => $grid_view, 'list_view' => $list_view]);
		}
		//  End Load More Product with Ajax

		return view('frontend.product.shop_details', compact('shop_all_products', 'color', 'sel_color', 'sort_by'));
	}

	public function CategoryDetails(Request $request, $id)
	{
		$sel_color = $this->sel_color;
		$sort_by = $this->sort_by;
		$user_id = (Auth::check() ? array(Auth::user()->id) : 0);
		$query = $this->getProducts($user_id);

		$shop_all_products = $query->where('category_id', $id)
			->inRandomOrder()
			->orderBy('id', 'DESC')
			->paginate($this->pagecount);
		$color = Colors::get();
		//  Load More Product with Ajax
		if ($request->ajax()) {
			$grid_view = view('frontend.product.shop_grid_view', compact('shop_all_products'))->render();
			$list_view = view('frontend.product.shop_list_view', compact('shop_all_products'))->render();
			return response()->json(['grid_view' => $grid_view, 'list_view' => $list_view]);
		}
		//  End Load More Product with Ajax

		return view('frontend.product.shop_details', compact('shop_all_products', 'color', 'sel_color', 'sort_by'));
	}

	public function userLogout()
	{
		Auth::logout();
		return redirect()->route('user.login');
	}


	/// Product View With Ajax
	public function ProductViewAjax($id)
	{
		$product = Product::with('category', 'color')->findOrFail($id);
		$multiImage = ProductMultipleImage::where('product_id', $id)->get();

		return response()->json(array(
			'product' => $product,
			'multiImage' => $multiImage

		));
	} // end method 


	public function productSearch(Request $request)
	{
		try {
			// dd($request->search);
			// $request->validate([
			// 	"search" => "required"
			// ], [
			// 	"search.required" => "Please Enter Some Values"
			// ]);
			$sel_color = $this->sel_color;
			$sort_by = $this->sort_by;
			$this->search = $request->search;
			$item = preg_replace('/\s+/', ' ', $this->search);
			$user_id = (Auth::check() ? array(Auth::user()->id) : 0);
			$query = $this->getAllProducts($user_id);
			$shop_all_products = $query->where(function ($query) use ($item) {
				$query->where('product_name', 'like', '%' . $item . '%')
					->orWhere('product_sku', 'like', '%' . $item . '%');
			})
				->paginate($this->pagecount);
	
			$color = Colors::get();
			//  Load More Product with Ajax
			if ($request->ajax()) {
				$grid_view = view('frontend.product.shop_grid_view', compact('shop_all_products'))->render();
				$list_view = view('frontend.product.shop_list_view', compact('shop_all_products'))->render();
				return response()->json(['grid_view' => $grid_view, 'list_view' => $list_view]);
			}
			//  End Load More Product with Ajax
	
			return view('frontend.product.shop_details', compact('shop_all_products', 'color', 'sel_color', 'sort_by'));
		} catch (\Throwable $th) {
			 dd($th);
		}
		
	}

	public function productSort(Request $request)
	{
		$sort_by = $request->sort;
		$sel_color = $this->sel_color;
		$color = Colors::get();
		$user_id = (Auth::check() ? array(Auth::user()->id) : 0);

		$query = $this->getProductsBySort($user_id, $sort_by);
		$shop_all_products = $query->get();


		return view('frontend.product.shop_details', compact('shop_all_products', 'color', 'sel_color', 'sort_by'));
	}

	public function productColor(Request $request)
	{
		$sort_by = $request->sort;
		$sel_color = $request->color_sort;
		$user_id = (Auth::check() ? array(Auth::user()->id) : 0);
		$query = $this->getProductsByColor($user_id, $sel_color);
		$shop_all_products = $query->inRandomOrder()->get();
		$color = Colors::get();

		return view('frontend.product.shop_details', compact('shop_all_products', 'color', 'sel_color', 'sort_by'));
	}

	public function getVariantData(Request $request)
	{
		$productVariant = ProductVariants::where('product_id', $request->id)->where('id', $request->variant_id)->first();
		$user_id = (Auth::check() ? Auth::user()->id : 0);
		$wishlist = Wishlist::where('product_id', $request->id)->where('variant_id', $request->variant_id)->where('user_id', $user_id)->first();

		if ($request->offer == 1) {
			$product = Product::find($request->id);
			$price = $productVariant->price - ($productVariant->price * $product->category->offer / 100);
			$productVariant->customer_price = $price;
			$productVariant->seller_price = $price;
		}

		return response()->json([
			'variant' => $productVariant,
			'wishlist' => $wishlist,
		]);
	}
}
