<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    use Utils;
    public function index()
    {
        $policy = Policy::first();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Company Policies'), Auth::user()->id)) {
			return view('backend.settings.policy.policy',compact('policy'));
		} else {
			return view('401');
		}  
    }

    public function store(Request $request)
    {
        $policycount = Policy::get()->count();
        if($policycount > 0) {
            Policy::findOrFail(1)->update([
                'terms_condition' => $request->terms_condition,
                'privacy_policy'  => $request->privacy_policy,
                'return_policy'   => $request->return_policy,
                'support_policy'  => $request->support_policy
            ]);
        }
        else {
            Policy::create([
                'terms_condition' => $request->terms_condition,
                'privacy_policy'  => $request->privacy_policy,
                'return_policy'   => $request->return_policy,
                'support_policy'  => $request->support_policy
            ]);
        }

        $notification = array(
			'message' => 'Policy '.(($policycount > 0) ? 'Updated' : 'Created').' Successfully',
			'alert-type' => 'success'
		);
		
		return redirect()->back()->with($notification);
    }
}