<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Colors;
use App\Models\Product;
use App\Models\ProductMultipleImage;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\MainCategory;
use App\Traits\Utils;
use App\Models\OrderItem;
use App\Models\ProductVariants;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Image;

class ProductController extends Controller
{
    use Utils;
    public function ProductView()
    {
        $colors = Colors::latest()->get();
        $main_category = MainCategory::whereNull('deleted_at')->latest()->get();
        $category = Category::whereNull('deleted_at')->latest()->get();

        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Add Products'), Auth::user()->id)) {
            return view('backend.product.product_view', compact('colors', 'main_category', 'category'));
        } else {
            return view('401');
        }
    }


    public function fetchCategories(Request $request)
    {
        $mainCategoryId = $request->mainCategoryId;
        $categories = Category::where('main_category_id', $mainCategoryId)->whereNull('deleted_at')->get();

        return response()->json(['categories' => $categories]);
    }

    //Get Category Code
    public function getProductSKU(Request $request)
    {
        try {
            if ($request->category_id > 0) {
                $category_code = Category::where("id", $request->category_id)->value('category_code');
                $product_sku = $this->generateUniqueProductSKU($category_code);

                return response()->json([
                    'product_sku' => $product_sku
                ]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function generateUniqueProductSKU($category_code)
    {
        do {
            // Generate a new SKU
            $new_product_sku = $category_code . $this->generateRandom(6);

            // Check if the SKU already exists
            $sku_exists = Product::where('product_sku', $new_product_sku)->exists();
        } while ($sku_exists); // Repeat if the SKU exists

        return $new_product_sku;
    }


    private function generateProductSKU($category_code)
    {
        do {
            $new_product_sku = $category_code . $this->generateRandom(6);

            $sku_exists = Product::where('product_sku', $new_product_sku)->exists();
        } while ($sku_exists);
        return $new_product_sku;
    }

    private function checkProductSKUExist($product_sku, $category_code)
    {
        $check_old_sku = Product::where('product_sku', $product_sku)->get();

        if ($check_old_sku) {
            $new_product_sku = $this->generateProductSKU($category_code);
        } else {
            $new_product_sku = $product_sku;
        }

        return $new_product_sku;
    }

    private function generateRandom($digit)
    {
        $min = pow(10, $digit - 1);
        $max = pow(10, $digit) - 1;
        return rand($min, $max);
    }

    public function getColorValue($color_id)
    {
        $colors = explode(',', $color_id);

        $productcolor = Colors::whereIn('id', $colors)->get();

        return response()->json(array(
            'color' => $productcolor
        ));
    }


    public function ProductStore(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_name' => 'required',
            'ddlMainCategoryType' => 'required',
            'ddlCategoryType' => 'required',
            'stock' => 'required',
            'product_sku'  => 'required|unique:products',
            // 'multi_img' => 'required',
            'price' => 'required|numeric',
            'discountprice' => 'required|numeric',
            'sellerdiscount' => 'required|numeric',
            'shortdescription' => 'required',
            'longdescription' => 'required',
        ]);

        // $images = $request->file('multi_img');
        // $first = true;
        // foreach ($images as $img) {
        //     $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
        //     if ($first) {
        //         $image = Image::make($img);

        //         $image->text($request->product_sku, 60, 70, function ($font) {
        //             $font->size(45);
        //             $font->color('#fff');
        //             $font->align('left');
        //             $font->valign('bottom');
        //             $font->file(public_path('frontend/assets/fonts/Roboto-Bold.ttf'));
        //         });
        //         $image->save('upload/products/thambnail/' . $make_name);

        //         $save_url = 'upload/products/thambnail/' . $make_name;
        //         $first = false;
        //     }
        // }
        $oldGroupID = Product::orderBy('group_id', 'desc')->first();

        if ($oldGroupID) {
            $group_id = $oldGroupID->group_id + 1;
        } else {
            $group_id = 1;
        }
        if ($request->has('colors')) {
            $category_code = Category::where('id', $request->ddlCategoryType)->value('category_code');

            $isFirstIteration = true;
            foreach ($request->colors as $color_id) {
                $color_name = Colors::where('id', $color_id)->value('color_name');
                if ($isFirstIteration) {
                    $productSKU = $request->product_sku;
                    $isFirstIteration = false;
                } else {
                    $product_sku = $this->generateProductSKU($category_code);
                    $productSKU = $this->checkProductSKUExist($product_sku, $category_code);
                }

                $product_id = Product::insertGetId([
                    'group_id' => $group_id,
                    'product_name' => $request->product_name,
                    'product_slug' => strtolower(str_replace(' ', '-', $request->product_name)) . '-' . $color_name,

                    'main_category_id' => $request->ddlMainCategoryType,
                    'category_id' => $request->ddlCategoryType,
                    'tags' => $request->tags,
                    'current_stock' =>  $request->stock,
                    'product_sku' => $productSKU,
                    'color_id' => $color_id,

                    // 'product_image' => $save_url,
                    // 'product_video_url' => $request->video_link,

                    'product_price' => $request->price,
                    'product_discount' => $request->discountprice,

                    'seller_price' => $request->price,
                    'seller_discount' => $request->sellerdiscount,

                    'short_description' => $request->shortdescription,
                    'long_description' => $request->longdescription,


                    'meta_title' => $request->metaname,
                    'meta_description' => $request->metadescription,
                    'meta_keywords' => $request->metakeywords,

                    'is_featured' => ($request->is_featured == 1 ? True : false),
                    'is_newArrival' => ($request->is_newArrival == 1 ? True : false),
                    'is_offers' => ($request->is_offers == 1 ? True : false),
                    'is_bestSelling' => ($request->is_bestSelling == 1 ? True : false),
                    'status' => 1
                ]);
                $this->addProductVariant($request, $product_id);
            }
        } else {
            $product_id = Product::insertGetId([
                'group_id' => $group_id,
                'product_name' => $request->product_name,
                'product_slug' => strtolower(str_replace(' ', '-', $request->product_name)),

                'main_category_id' => $request->ddlMainCategoryType,
                'category_id' => $request->ddlCategoryType,
                'tags' => $request->tags,
                'current_stock' =>  $request->stock,
                'product_sku' => $request->product_sku,
                'color_id' => $request->color,

                // 'product_image' => $save_url,
                // 'product_video_url' => $request->video_link,

                'product_price' => $request->price,
                'product_discount' => $request->discountprice,

                'seller_price' => $request->price,
                'seller_discount' => $request->sellerdiscount,

                'short_description' => $request->shortdescription,
                'long_description' => $request->longdescription,


                'meta_title' => $request->metaname,
                'meta_description' => $request->metadescription,
                'meta_keywords' => $request->metakeywords,

                'is_featured' => ($request->is_featured == 1 ? True : false),
                'is_newArrival' => ($request->is_newArrival == 1 ? True : false),
                'is_offers' => ($request->is_offers == 1 ? True : false),
                'is_bestSelling' => ($request->is_bestSelling == 1 ? True : false),
                'status' => 1
            ]);
            $this->addProductVariant($request, $product_id);
        }

        // foreach ($images as $img) {
        //     $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
        //     $image = Image::make($img);
        //     $image->text($request->product_sku, 60, 70, function ($font) {
        //         $font->size(45);
        //         $font->color('#fff');
        //         $font->align('left');
        //         $font->valign('bottom');
        //         $font->file(public_path('frontend/assets/fonts/Roboto-Bold.ttf'));
        //     });
        //     $image->save('upload/products/multi-image/' . $make_name);
        //     $uploadPath = 'upload/products/multi-image/' . $make_name;

        //     ProductMultipleImage::insert([

        //         'product_id' => $product_id,
        //         'product_mult_image' => $uploadPath

        //     ]);
        // }

        $notification = array(
            'message' => 'Product Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('product.list')->with($notification);
    }

    private function addProductVariant(Request $request, $product_id)
    {
        //Create Product Variants

        if ($request->tabVariantSize) {


            Product::where('id', $product_id)->update([
                'is_product_variant' => 1,
            ]);

            $variant_size = $request->tabVariantSize;
            $variant_stock = $request->tabVariantStock;
            $variant_mrp_price = $request->tabVariantMRPPrice;
            $variant_customer_price = $request->tabVariantCSPrice;
            $variant_reseller_price = $request->tabVariantRSPrice;

            if (
                is_array($variant_size) && is_array($variant_stock) && is_array($variant_mrp_price) && is_array($variant_customer_price) && is_array($variant_reseller_price) &&
                count($variant_size) === count($variant_stock) &&
                count($variant_stock) === count($variant_mrp_price) &&
                count($variant_mrp_price) === count($variant_customer_price) &&
                count($variant_customer_price) === count($variant_reseller_price)
            ) {
                $variantData = [];

                for ($i = 0, $count = count($variant_size); $i < $count; $i++) {
                    $variantData[] = [
                        "product_id" => $product_id,
                        "type_id" => $request->hdVariantType,
                        "size" => $variant_size[$i],
                        "stock" => $variant_stock[$i] ?? 0,
                        "price" => $variant_mrp_price[$i],
                        "customer_price" => $variant_customer_price[$i],
                        "seller_price" => $variant_reseller_price[$i],
                        "created_at" => Carbon::now(),
                    ];
                }
                ProductVariants::insert($variantData);

                $product = Product::where('id', $product_id)->first();
                if ($product) {
                    $variants = ProductVariants::where('product_id', $product_id)->get();
                    foreach ($variants as $variant) {
                        $variant->product_sku = $product->product_sku . '-' . $variant->size;
                        $variant->update();
                    }
                }
            }
        }
    }

    public function addProductImage(Request $request)
    {
        $request->validate([
            'multi_img' => 'required'
        ]);
        $images = $request->file('multi_img');
        $first = true;
        foreach ($images as $img) {
            $make_name = 'upload/products/thambnail/' . hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
            if ($first) {
                $image = Image::make($img);

                $image->text($request->product_sku, 60, 70, function ($font) {
                    $font->size(45);
                    $font->color('#fff');
                    $font->align('left');
                    $font->valign('bottom');
                    $font->file(public_path('frontend/assets/fonts/Roboto-Bold.ttf'));
                });
                $save_url = $this->fileUploadS3Bucket($make_name, (string)$image->encode());
                $first = false;
            }
        }
        Product::where('id', $request->hdProductId)->update([
            'product_image' => $save_url,
            'product_video_url' => $request->video_link,
        ]);

        foreach ($images as $img) {
            $make_name = 'upload/products/multi-image/' . hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
            $image = Image::make($img);
            $image->text($request->product_sku, 60, 70, function ($font) {
                $font->size(45);
                $font->color('#fff');
                $font->align('left');
                $font->valign('bottom');
                $font->file(public_path('frontend/assets/fonts/Roboto-Bold.ttf'));
            });

            // Save to S3 and get URL
            $uploadPath = $this->fileUploadS3Bucket($make_name, (string)$image->encode());

            ProductMultipleImage::insert([

                'product_id' => $request->hdProductId,
                'product_mult_image' => $uploadPath

            ]);
        }
        $notification = array(
            'message' => 'Product Image Added Successfully',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }

    public function ProductList()
    {
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('All Products'), Auth::user()->id)) {
            return view('backend.product.product_list');
        } else {
            return view('401');
        }
    }

    public function ProductEdit($id)
    {
        $multiImgs = ProductMultipleImage::where('product_id', $id)->get();
        $main_category = MainCategory::latest()->get();
        // $colors = Colors::get();
        $products = Product::findOrFail($id);
        $variant_type = ProductVariants::where('product_id', $id)->value('type_id');
        $product_variants = ProductVariants::where('product_id', $id)->get();
        $isEdit = 1;
        $isSize = 0;
        $isOther = 0;

        foreach ($product_variants as $variant) {
            if ($variant->type_id == 0) {
                $isSize = 1;
            }
            if ($variant->type_id == 1) {
                $isOther = 1;
            }
        }

        $categories = Category::where('main_category_id', $products->main_category_id)->latest()->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('All Products'), Auth::user()->id)) {
            return view('backend.product.product_edit', compact('main_category', 'categories', 'products', 'multiImgs', 'variant_type', 'product_variants', 'isSize', 'isOther', 'isEdit'));
        } else {
            return view('401');
        }
    }

    public function ProductMultiImageDelete($id)
    {
        $oldimg = ProductMultipleImage::findOrFail($id);

        $filePath = parse_url($oldimg->product_mult_image, PHP_URL_PATH);
        if ($this->checkFileExistsOnS3($filePath)) {
            $this->deleteFromS3Bucket($filePath);
        } else {
            @unlink(public_path($filePath));
        }

        ProductMultipleImage::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Product Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return response($notification);
    }

    private function isProductImgExists($product_id)
    {
        $product = ProductMultipleImage::where('product_id', $product_id)->first();
        if (!$product) {
            return false;
        } else {
            return true;
        }
    }

    public function ProductUpdate(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_name' => 'required',
            'ddlMainCategoryType' => 'required',
            'ddlCategoryTypeUp' => 'required',
            'stock' => 'required',
            'price' => 'required|numeric',
            'product_sku'  => 'required|unique:products,product_sku,' . $request->id,
            'discountprice' => 'required|numeric',
            'sellerdiscount' => 'required|numeric',
            'shortdescription' => 'required',
            'longdescription' => 'required',
        ]);

        $totalVariantStock = 0;
        if ($request->has('tabVariantStock')) {
            foreach ($request->tabVariantStock as $variantStock) {
                $totalVariantStock += $variantStock;
            }
        }

        if ($request->stock < $totalVariantStock) {
            $notification = array(
                'message' => 'Plese enter valid stock!',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }

        $old_product_discount = Product::where('id', $request->id)->pluck('product_discount')->first();
        $old_seller_discount = Product::where('id', $request->id)->pluck('seller_discount')->first();

        $product = Product::find($request->id);
        $product->product_name = $request->product_name;
        $product->product_slug = strtolower(str_replace(' ', '-', $request->product_name));
        $product->main_category_id = $request->ddlMainCategoryType;
        $product->category_id = $request->ddlCategoryTypeUp;
        $product->color_id = $request->color;
        $product->tags = $request->tags;
        $product->current_stock = $request->stock;
        $product->product_sku = $request->product_sku;
        $product->product_video_url = $request->video_link;
        $product->product_price = $request->price;
        $product->product_discount = $request->discountprice;
        $product->seller_price = $request->price;
        $product->seller_discount = $request->sellerdiscount;
        $product->short_description = $request->shortdescription;
        $product->long_description = $request->longdescription;
        $product->meta_title = $request->metaname;
        $product->meta_description = $request->metadescription;
        $product->meta_keywords = $request->metakeywords;
        $product->is_featured = ($request->is_featured == 1 ? True : false);
        $product->is_newArrival = ($request->is_newArrival == 1 ? True : false);
        $product->is_offers = ($request->is_offers == 1 ? True : false);
        $product->is_bestSelling = ($request->is_bestSelling == 1 ? True : false);
        $product->status = 1;

        $images = $request->file('multi_img');
        if ($images) {
            if (!$this->isProductImgExists($request->id)) {
                $first = true;
                foreach ($images as $img) {
                    $make_name = 'upload/products/thambnail/' . hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
                    if ($first) {

                        $filePath = parse_url($product->product_image, PHP_URL_PATH);
                        if ($this->checkFileExistsOnS3($filePath)) {
                            $this->deleteFromS3Bucket($filePath);
                        } else {
                            $localFilePath = public_path($filePath);
                            if (file_exists($localFilePath)) {
                                @unlink($localFilePath);
                            }
                        }
                
                        $image = Image::make($img);
                        $image->text($request->product_sku, 60, 70, function ($font) {
                            $font->size(45);
                            $font->color('#fff');
                            $font->align('left');
                            $font->valign('bottom');
                            $font->file(public_path('frontend/assets/fonts/Roboto-Bold.ttf'));
                        });
                        $save_url = $this->fileUploadS3Bucket($make_name, (string)$image->encode());

                        $product->product_image = $save_url;
                        $first = false;
                    }
                }
            }

            foreach ($images as $img) {
                $make_name = 'upload/products/multi-image/' . hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
                $image = Image::make($img);
                $image->text($request->product_sku, 60, 70, function ($font) {
                    $font->size(45);
                    $font->color('#fff');
                    $font->align('left');
                    $font->valign('bottom');
                    $font->file(public_path('frontend/assets/fonts/Roboto-Bold.ttf'));
                });
                $uploadPath = $this->fileUploadS3Bucket($make_name, (string)$image->encode());

                ProductMultipleImage::insert([
                    'product_id' => $request->id,
                    'product_mult_image' => $uploadPath

                ]);
            }
        }

        $product->save();

        //Update Product Variants

        if ($request->tabVariantSize) {
            Cart::where('product_id', $request->id)->delete();
            Wishlist::where('product_id', $request->id)->delete();
            ProductVariants::where('product_id', $request->id)->delete();

            Product::where('id', $request->id)->update([
                'is_product_variant' => 1,
            ]);

            $variant_size = $request->tabVariantSize;
            $variant_stock = $request->tabVariantStock;
            $variant_mrp_price = $request->tabVariantMRPPrice;
            $variant_customer_price = $request->tabVariantCSPrice;
            $variant_reseller_price = $request->tabVariantRSPrice;

            if (
                is_array($variant_size) && is_array($variant_stock) && is_array($variant_mrp_price) && is_array($variant_customer_price) && is_array($variant_reseller_price) &&
                count($variant_size) === count($variant_stock) &&
                count($variant_stock) === count($variant_mrp_price) &&
                count($variant_mrp_price) === count($variant_customer_price) &&
                count($variant_customer_price) === count($variant_reseller_price)
            ) {
                $variantData = [];

                for ($i = 0, $count = count($variant_size); $i < $count; $i++) {
                    $variantData[] = [
                        "product_id" => $request->id,
                        "type_id" => $request->hdVariantType,
                        "size" => $variant_size[$i],
                        "stock" => $variant_stock[$i] ?? 0,
                        "price" => $variant_mrp_price[$i],
                        "customer_price" => $variant_customer_price[$i],
                        "seller_price" => $variant_reseller_price[$i],
                        "created_at" => Carbon::now(),
                    ];
                }
                ProductVariants::insert($variantData);

                $product = Product::where('id', $request->id)->first();
                if ($product) {
                    $variants = ProductVariants::where('product_id', $request->id)->get();
                    foreach ($variants as $variant) {
                        $variant->product_sku = $product->product_sku . '-' . $variant->size;
                        $variant->update();
                    }
                }
            }
        } else {
            Product::where('id', $request->id)->update([
                'is_product_variant' => 0,
            ]);
            ProductVariants::where('product_id', $request->id)->delete();
            // Cart::where('product_id', $request->id)->delete();
            // Wishlist::where('product_id', $request->id)->delete();
        }

        //Delete cart if product price is changed
        if ($old_product_discount != $request->discountprice || $old_seller_discount != $request->sellerdiscount) {
            Cart::where('product_id', $request->id)->delete();
        }
        $notification = array(
            'message' => 'Product Updated  Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('product.list')->with($notification);
    }

    public function ProductDelete($id)
    {
        $orderitem = OrderItem::where('product_id', $id)->get()->count();
        if ($orderitem == 0) {
            $product = Product::findOrFail($id);
            @unlink($product->product_image);
            Product::findOrFail($id)->delete();

            $images = ProductMultipleImage::where('product_id', $id)->get();
            foreach ($images as $img) {
                @unlink($img->product_mult_image);
                ProductMultipleImage::where('product_id', $id)->delete();
            }
            $carts = Cart::where('product_id', $id)->get();
            foreach ($carts as $item) {
                $item->delete();
            }

            $notification = array(
                'message' => 'Product Deleted Successfully',
                'alert-type' => 'success'
            );
        } else {
            $notification = array(
                'message' => 'Delete Failed!. This product is reference with another instance.',
                'alert-type' => 'error'
            );
        }
        return redirect()->back()->with($notification);
    }

    public function ProductStock()
    {
        $categories = Category::latest()->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Stock Maintenance'), Auth::user()->id)) {
            return view('backend.product.product_stock', compact('categories'));
        } else {
            return view('401');
        }
    }

    public function stockupdate(Request $request)
    {
        $id = $request->product_id;
        Product::findOrFail($id)->update([
            'current_stock' => $request->current_qty
        ]);

        $notification = array(
            'message' => 'Product Quantity Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // end mehtod 


    public function ReportOutofStock()
    {
        $categories = Category::latest()->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Out Of Stock'), Auth::user()->id)) {
            return view('backend.report.out_of_stock', compact('categories'));
        } else {
            return view('401');
        }
    }
    public function getOutOfStockData(Request $request, $category_id)
    {
        if ($request->type == 2) {
            $products = ProductVariants::join('products', 'products.id', 'product_variants.product_id')
                ->select(
                    'products.product_name',
                    'products.created_at',
                    'products.product_sku',
                    'product_variants.size',
                    'products.product_image as product_image',
                    'product_variants.price as product_price',
                    'product_variants.customer_price as product_discount',
                    'product_variants.seller_price as seller_discount',
                    'product_variants.stock as current_stock',
                )
                ->where('stock', 0);
        } else {
            $products = Product::where('current_stock', 0)->where('is_product_variant', 0);
        }
        if ($category_id > 0) {
            $products = $products->where('products.category_id', $category_id)->latest()->get();
        } else {
            $products = $products->latest()->get();
        }
        return datatables()->of($products)->toJson();
    }


    public function Reportstock()
    {
        $categories = Category::latest()->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Stock'), Auth::user()->id)) {
            return view('backend.report.stock', compact('categories'));
        } else {
            return view('401');
        }
    }

    public function stockReport(Request $request, $category_id)
    {

        if ($request->type == 2) {
            $products = ProductVariants::join('products', 'products.id', 'product_variants.product_id')
                ->select(
                    'products.product_name',
                    'products.category_id',
                    'products.created_at',
                    'products.product_sku',
                    'products.product_image as product_image',
                    'product_variants.price as product_price',
                    'product_variants.size',
                    'product_variants.customer_price as product_discount',
                    'product_variants.seller_price as seller_discount',
                    'product_variants.stock as current_stock',
                )
                ->where('stock', '!=', 0);
        } else {
            $products = Product::select('products.*')->where('current_stock', '!=', 0)->where('is_product_variant', 0);
        }
        if ($category_id > 0) {
            $products = $products->where('products.category_id', $category_id)->latest()->get();
        } else {
            $products = $products->latest()->get();
        }
        return datatables()->of($products)->toJson();
    }

    public function productListData(Request $request)
    {
        $query = Product::with('main_category', 'category', 'color')->orderBy('id', 'DESC');

        // Handle search
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('product_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('product_sku', 'like', '%' . $searchValue . '%')
                    //   ->orWhereHas('main_category', function($q) use ($searchValue) {
                    //       $q->where('main_category_name', 'like', '%' . $searchValue . '%');
                    //   })
                    //   ->orWhereHas('category', function($q) use ($searchValue) {
                    //       $q->where('category_name', 'like', '%' . $searchValue . '%');
                    //   })
                    //   ->orWhereHas('color', function($q) use ($searchValue) {
                    //       $q->where('color_name', 'like', '%' . $searchValue . '%');
                    //   })
                ;
            });
        }

        $productsListData = $query->paginate($request->input('length', 10), ['*'], 'page', ($request->input('start') / $request->input('length')) + 1);

        $productsListData->getCollection()->transform(function ($product) {
            $product->action = '
                <a href="' . route('product.edit', $product->id) . '" class="btn btn-info btn-sm btn-flat" title="Edit Data">Edit</a>
                <a href="' . route('product.delete', $product->id) . '" class="btn btn-danger btn-sm btn-flat" title="Delete Data" id="delete">Delete</a>
            ';
            return $product;
        });

        return response()->json([
            'data' => $productsListData->items(),
            'recordsTotal' => $productsListData->total(),
            'recordsFiltered' => $productsListData->total(),
            'current_page' => $productsListData->currentPage(),
            'last_page' => $productsListData->lastPage(),
        ]);
    }


    public function getStockMaintenaceData($category_id)
    {
        if ($category_id > 0) {
            $product_stock_maintenance = Product::leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->select('products.*', 'product_variants.product_id as product_variant')
                ->groupBy('products.id')
                ->where('products.category_id', $category_id)
                ->orderBy('products.current_stock', 'ASC')
                ->get();
        } else {
            $product_stock_maintenance = Product::leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->select('products.*', 'product_variants.product_id as product_variant')
                ->groupBy('products.id')
                ->orderBy('products.current_stock', 'ASC')
                ->get();
        }

        return datatables()->of($product_stock_maintenance)
            ->addColumn('action', function ($row) {
                $html = '';
                if (!$row->product_variant) {
                    $html .= '<div class="d-flex"><input type="number" name="current_qty" id="current_qty_' . $row->id . '" class="form-control" min="0"
                            title="Please enter Quantity" value="' . $row->current_stock . '">';
                    $html .= '<button class="btn btn-info" data-id="' . $row->id . '" onclick="updateProductQuantity(' . $row->id . ');"><i class="fa fa-check"></i></button></div>';
                } else {
                    $html .= '<button class="btn btn-info" onclick="stockUpdatePopup(' . $row->id . ')">Update Stock</button>';
                }
                return $html;
            })->toJson();
    }

    public function getProductAndVariants(Request $request)
    {
        $productId = $request->input('productId');

        $product = Product::findOrFail($productId);
        $variants = ProductVariants::where('product_id', $productId)->get();

        return response()->json(['product' => $product, 'variants' => $variants]);
    }

    public function updateVariantStock(Request $request)
    {
        try {
            // Update product stock
            $product = Product::findOrFail($request->hdProductId);
            $product->current_stock = $request->stock;
            $product->save();

            if ($request->has('ddlVariantStock')) {
                $variantStocks = json_decode($request->ddlVariantStock, true); // Decode the JSON string to an array
                $variants = ProductVariants::where('product_id', $request->hdProductId)->get();

                foreach ($variants as $key => $variant) {
                    if (isset($variantStocks[$key])) {
                        $variant->stock = $variantStocks[$key];
                        $variant->save();
                    }
                }
            }

            return response()->json(['success' => 'Stock updated successfully']);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'An error occurred while updating stock.']);
        }
    }
    
    public function viewAnalytics()
    {
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('View Analytics'), Auth::user()->id)) {
            return view('backend.report.view_analytics');
        } else {
            return view('401');
        }
    }

    public function getViewAnalyticsData()
    {
        $products = Product::select('products.*')->groupBy('products.id')->orderBy('products.view_count', 'DESC')->get();
        return datatables()->of($products)->toJson();
    }
}
