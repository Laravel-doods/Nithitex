<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function country()
    {
    	return $this->belongsTo(Country::class,'country_id','id');
    }

    public function state()
    {
    	return $this->belongsTo(State::class,'state_id','id');
    }

    public function city()
    {
    	return $this->belongsTo(City::class,'city_id','id');
    }

    public function user()
    {
    	return $this->belongsTo(User::class,'user_id','id');
    }

    public function userrole()
    {
    	return $this->belongsTo(UserRole::class,'userrole_id','id');
    }

    public function seller()
    {
      return $this->belongsTo(Seller::class,'seller_id','id');
    }
    
    public function qty()
    {
      return $this->belongsTo(OrderItem::class,'id','order_id');
    }

    public function couponCode()
    {
      return $this->belongsTo(Coupon::class,'coupon_id','id');
    }
}
