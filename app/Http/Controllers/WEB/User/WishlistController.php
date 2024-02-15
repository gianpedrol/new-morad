<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\NotificationText;
use App\Models\ManageText;
use App\Models\Navigation;
use App\Models\Day;
use Auth;
use Illuminate\Pagination\Paginator;
class WishlistController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function wishlist(){
        Paginator::useBootstrap();
        $user = Auth::guard('web')->user();
        $wishlists = Wishlist::with('property')->where('user_id',$user->id)->paginate(10);

        return view('user.wishlist')->with([
            'wishlists' => $wishlists,
        ]);
    }

    public function create($id){

        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array(
                'messege'=>env('NOTIFY_TEXT'),
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification);
        }
        // end

        $user=Auth::guard('web')->user();
        $isExist=Wishlist::where(['property_id'=>$id, 'user_id'=>$user->id])->first();
        if(!$isExist){
            $wishlist=new Wishlist();
            $wishlist->user_id=$user->id;
            $wishlist->property_id=$id;
            $wishlist->save();

            $notification = trans('user_validation.Wishlist added successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);
        }else{
            $notification = trans('user_validation.Item already exist');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }

    public function delete($id){

        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array(
                'messege'=>env('NOTIFY_TEXT'),
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification);
        }
        // end

        $wishlist=Wishlist::find($id);
        $wishlist->delete();

        $notification = trans('user_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}
