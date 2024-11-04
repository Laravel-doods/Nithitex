<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Colors;
use App\Models\Product;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColorController extends Controller
{
	use Utils;

	public function ColorView()
	{
		$colors = Colors::orderBy('id', 'ASC')->get();
		if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Colors'), Auth::user()->id)) {
			return view('backend.color.color_view', compact('colors'));
		} else {
			return view('401');
		}
	}

	public function ColorStore(Request $request)
	{
		$request->validate([
			'color' => 'required',
			'code' => 'required'
		]);

		Colors::create([
			'color_name' => $request->color,
			'color_code' => $request->code,
		]);

		$notification = array(
			'message' => 'Color Created Successfully',
			'alert-type' => 'success'
		);

		return redirect()->back()->with($notification);
	}

	public function ColorEdit($id)
	{
		$colors = Colors::findOrFail($id);
		if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Colors'), Auth::user()->id)) {
			return view('backend.color.color_edit', compact('colors'));
		} else {
			return view('401');
		}
	}

	public function ColorUpdate(Request $request)
	{
		$request->validate([
			'color' => 'required',
			'code' => 'required'
		]);
		$color_id = $request->id;
		Colors::findOrFail($color_id)->update([
			'color_name' => $request->color,
			'color_code' => $request->code
		]);

		$notification = array(
			'message' => 'Color Updated Successfully',
			'alert-type' => 'success'
		);

		return redirect()->route('color.all')->with($notification);
	}

	public function ColorDelete($id)
	{
		$product = Product::where('color_id', $id)->get()->count();
		if ($product == 0) {
			$color = Colors::findOrFail($id);
			$color->delete();

			$notification = array(
				'message' => 'Color Deleted Successfully',
				'alert-type' => 'success'
			);
		} else {
			$notification = array(
				'message' => 'Delete Failed!. This color is reference with another instance.',
				'alert-type' => 'error'
			);
		}
		return redirect()->back()->with($notification);
	}
}
