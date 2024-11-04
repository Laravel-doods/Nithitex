<?php

namespace App\Http\Controllers;

use App\Models\FcmToken;
use App\Models\User;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;
use Intervention\Image\Facades\Image;

class NotificationController extends Controller
{
    use Utils;
    use HasPermissions;
    public function notificationView(Request $request)
    {
        try {

            if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Notification'), Auth::user()->id)) {
                $users = User::all();
                return view('backend.notification.notification', compact('users'));
            } else {
                return view('401');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function sendNotification(Request $request)
    {
        $title = $request->title;
        $body = $request->content;
        $notifyImage = null;

        if ($request->hasFile('notifyImage')) {
            $image = $request->file('notifyImage');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            if (!file_exists(public_path('upload/notification'))) {
                mkdir(public_path('upload/notification'), 0777, true);
            }
            Image::make($image)->save(public_path('upload/notification/' . $name_gen));
            $notifyImage = 'upload/notification/' . $name_gen;
        }

        $tokens = FcmToken::whereIn('user_id', $request->users)->get();
        $action = url('/');

        $response = false;
        if ($tokens) {
            $response = $this->pushNotification($tokens, $title, $body, $notifyImage, $action);
        }

        $notification = $response ? 
            ['message' => 'Notification sent successfully', 'alert-type' => 'success'] : 
            ['message' => 'Something went wrong!', 'alert-type' => 'error'];

        return redirect()->back()->with($notification);
    }
}
