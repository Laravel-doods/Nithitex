<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'door_no',
        'street_address',
        'city_name',
        'state_name',
        'name',
        'email',
        'pin_code',
        'payment_type',
        'payment_status',
        'transaction_id',
        'currency',
        'amount',
        'sub_total',
        'shipping_charge',
        'order_number',
        'invoice_no',
        'invoice',
        'track_number',
        'order_date',
        'order_month',
        'order_year',
        'confirmed_date',
        'processing_date',
        'picked_date',
        'shipped_date',
        'delivered_date',
        'cancel_request',
        'cancel_date',
        'return_order',
        'return_date',
        'return_reason',
        'status',
        'phone',
        'alternative_number',
        'r_payment_id',
        'r_order_id',
        'tot_Qty',
        'note'
    ];
}
