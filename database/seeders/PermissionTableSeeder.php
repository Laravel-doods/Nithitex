<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      $permissions = [
         [
            'name' => 'Products',
            'permission_group_id' => permissionGroup::where('name', 'Products')->first()->id
         ],
         [
            'name' => 'Categories',
            'permission_group_id' => permissionGroup::where('name', 'Products')->first()->id
         ],
         [
            'name' => 'Colors',
            'permission_group_id' => permissionGroup::where('name', 'Products')->first()->id
         ],
         [
            'name' => 'Add Products',
            'permission_group_id' => permissionGroup::where('name', 'Products')->first()->id
         ],
         [
            'name' => 'All Products',
            'permission_group_id' => permissionGroup::where('name', 'Products')->first()->id
         ],
         [
            'name' => 'Stock Maintenance',
            'permission_group_id' => permissionGroup::where('name', 'Products')->first()->id
         ],
         [
            'name' => 'Customer',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Customers',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'All Customers',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Customer Orders',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Customer All Orders',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Customer Pending Orders',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Customer Confirmed Orders',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Customer Processing Orders',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Customer Picked Orders',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Customer Shipped Orders',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Customer Delivered Orders',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Customer Cancelled Orders',
            'permission_group_id' => permissionGroup::where('name', 'Customers')->first()->id
         ],
         [
            'name' => 'Reseller',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Resellers',
            'permission_group_id' => permissionGroup::where('name', 'Resellers')->first()->id
         ],
         [
            'name' => 'All Resellers',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Reseller Requests',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Reseller Orders',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Reseller All Orders',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Reseller Pending Orders',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Reseller Confirmed Orders',
            'permission_group_id' => permissionGroup::where('name', 'Resellers')->first()->id
         ],
         [
            'name' => 'Reseller Processing Orders',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Reseller Picked Orders',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Reseller Shipped Orders',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Reseller Delivered Orders',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Reseller Cancelled Orders',
            'permission_group_id' => permissionGroup::where('name', 'Reseller')->first()->id
         ],
         [
            'name' => 'Return_Cancel',
            'permission_group_id' => permissionGroup::where('name', 'Return_Cancel')->first()->id
         ],
         [
            'name' => 'Return Request',
            'permission_group_id' => permissionGroup::where('name', 'Return_Cancel')->first()->id
         ],
         [
            'name' => 'Return Orders List',
            'permission_group_id' => permissionGroup::where('name', 'Return_Cancel')->first()->id
         ],
         [
            'name' => 'Customer Return Request',
            'permission_group_id' => permissionGroup::where('name', 'Return_Cancel')->first()->id
         ],
         [
            'name' => 'Reseller Return Request',
            'permission_group_id' => permissionGroup::where('name', 'Return_Cancel')->first()->id
         ],
         [
            'name' => 'Cancel Request',
            'permission_group_id' => permissionGroup::where('name', 'Return_Cancel')->first()->id
         ],
         [
            'name' => 'Cancel Orders List',
            'permission_group_id' => permissionGroup::where('name', 'Return_Cancel')->first()->id
         ],
         [
            'name' => 'Customer Cancel Request',
            'permission_group_id' => permissionGroup::where('name', 'Return_Cancel')->first()->id
         ],
         [
            'name' => 'Reseller Cancel Request',
            'permission_group_id' => permissionGroup::where('name', 'Return_Cancel')->first()->id
         ],
         [
            'name' => 'Reports',
            'permission_group_id' => permissionGroup::where('name', 'Reports')->first()->id
         ],
         [
            'name' => 'Out Of Stock',
            'permission_group_id' => permissionGroup::where('name', 'Reports')->first()->id
         ],
         [
            'name' => 'Stock',
            'permission_group_id' => permissionGroup::where('name', 'Reports')->first()->id
         ],
         [
            'name' => 'Shop Settings',
            'permission_group_id' => permissionGroup::where('name', 'Shop_Settings')->first()->id
         ],
         [
            'name' => 'Settings',
            'permission_group_id' => permissionGroup::where('name', 'Shop_Settings')->first()->id
         ],
         [
            'name' => 'Company About',
            'permission_group_id' => permissionGroup::where('name', 'Shop_Settings')->first()->id
         ],
         [
            'name' => 'Company Policies',
            'permission_group_id' => permissionGroup::where('name', 'Shop_Settings')->first()->id
         ],
         [
            'name' => 'Home Slider Setup',
            'permission_group_id' => permissionGroup::where('name', 'Shop_Settings')->first()->id
         ],
         [
            'name' => 'Shop Information',
            'permission_group_id' => permissionGroup::where('name', 'Shop_Settings')->first()->id
         ],
         [
            'name' => 'State Master',
            'permission_group_id' => permissionGroup::where('name', 'Shop_Settings')->first()->id
         ],
         [
            'name' => 'Manage Staffs',
            'permission_group_id' => permissionGroup::where('name', 'Manage_Staff')->first()->id
         ],
         [
            'name' => 'Staffs',
            'permission_group_id' => permissionGroup::where('name', 'Manage_Staff')->first()->id
         ],
         [
            'name' => 'Roles',
            'permission_group_id' => permissionGroup::where('name', 'Manage_Staff')->first()->id
         ],
         [
            'name' => 'Assign Roles',
            'permission_group_id' => permissionGroup::where('name', 'Manage_Staff')->first()->id
         ],
         [
            'name' => 'All Staffs',
            'permission_group_id' => permissionGroup::where('name', 'Manage_Staff')->first()->id
         ],
         [
            'name' => 'Manage Referral',
            'permission_group_id' => permissionGroup::where('name', 'Manage_Referral')->first()->id
         ],
         [
            'name' => 'Referrals',
            'permission_group_id' => permissionGroup::where('name', 'Manage_Referral')->first()->id
         ],
         [
            'name' => 'Referral Settings',
            'permission_group_id' => permissionGroup::where('name', 'Manage_Referral')->first()->id
         ],
         [
            'name' => 'Referral History',
            'permission_group_id' => permissionGroup::where('name', 'Manage_Referral')->first()->id
         ],

      ];
      echo '-------------------------------------------' . "\n";
      echo '----------Permission Seeding---------' . "\n";
      foreach ($permissions as $key => $value) {
         $permission = new Permission;
         $permission->name = $value['name'];
         $permission->permission_group_id = $value['permission_group_id'];
         $permission->save();
         echo "---------Permission Name=> $permission->name------------" . "\n";
      }
      echo "-----------------Permission Seeding Completed-----------------" . "\n";
   }
}
