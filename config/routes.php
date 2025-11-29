<?php

return [
   // Home routes
   '/' => ['HomeController', 'dashboard'],
   '/home/dashboard' => ['HomeController', 'dashboard'],
   '/home/menu' => ['HomeController', 'menu'],
   '/menu/item' => ['MenuController', 'item'],
   // CUSTOMER AUTH
   '/customer/login'             => ['CustomerController', 'loginForm'],
   '/customer/login/submit'      => ['CustomerController', 'login'],
   '/customer/signup'            => ['CustomerController', 'signupForm'],
   '/customer/signup/submit'     => ['CustomerController', 'signup'],
   '/customer/logout'            => ['CustomerController', 'logout'],
   // CUSTOMER DASHBOARD
   '/customer/dashboard'         => ['CustomerController', 'dashboard'],
   '/customer/deal'              => ['CustomerController', 'showDeal'],
   // CATEGORY + MENU
   '/customer/category'          => ['CustomerController', 'chooseCategory'],
   '/customer/menu'              => ['CustomerController', 'menuPage'],
   // CART
   '/customer/cart'              => ['CustomerController', 'cartPage'],
   '/customer/cart/add'          => ['CustomerController', 'addToCart'],
   '/customer/cart/update'       => ['CustomerController', 'updateCart'],
   '/customer/cart/remove'       => ['CustomerController', 'removeFromCart'],
   '/customer/cart/items' => ['CustomerController', 'loadCart'],
   'quick_serve/customer/cart/add_deal' => ['CustomerController', 'addDealToCart'],
   // CHECKOUT + ORDER
   '/customer/order_start'       => ['CustomerController', 'orderStart'],
   '/customer/order_confirm'     => ['CustomerController', 'orderConfirm'],
   '/customer/orders'            => ['CustomerController', 'orderHistory'],
   '/customer/receipt'           => ['CustomerController', 'receiptPage'],
   '/customer/receipt/download'  => ['CustomerController', 'downloadReceipt'],
   '/customer/orders/current'    => ['CustomerController', 'currentOrderStatus'],
   // Standalone track order page
   '/customer/orders/track' => ['CustomerController', 'trackOrderPage'],
   // FEEDBACK
   '/customer/feedback'          => ['CustomerController', 'feedbackForm'],
   '/customer/feedback/submit'   => ['CustomerController', 'submitFeedback'],
   // SETTINGS
   '/customer/settings'              => ['CustomerController', 'settingsPage'],
   '/customer/settings/profile'      => ['CustomerController', 'update_profile'],
   '/customer/settings/password'     => ['CustomerController', 'change_password'],
   '/customer/settings/avatar'       => ['CustomerController', 'updateAvatar'],
   // DELETE ACCOUNT
   '/customer/delete_account'         => ['CustomerController', 'deleteAccount'],
   '/customer/delete_account/confirm' => ['CustomerController', 'confirm_delete_account'],


   // Staff routes
   '/staff/login' => ['StaffController', 'login'],
   '/staff/logout' => ['StaffController', 'logout'],
   '/staff/dashboard' => ['StaffController', 'dashboard'],
   '/api/search_suggestions' => ['ApiController', 'searchSuggestions'],
   //staff profile management
   '/staff/edit-profile' => ['StaffController', 'editProfile'],
   '/staff/update-profile' => ['StaffController', 'updateProfile'],
   '/staff/view-profile' => ['StaffController', 'viewProfile'],
   '/staff/change-password' => ['StaffController', 'changePassword'],
   '/staff/delete-account' => ['StaffController', 'deleteAccount'],
   '/staff/cancel-deletion' => ['StaffController', 'cancelDeletion'],
   //staff menu management
   '/staff/menu' => ['StaffController', 'menu'],
   '/staff/menu/publish' => ['StaffController', 'publishMenu'],
   '/staff/menu/unpublish' => ['StaffController', 'unpublishMenu'],
   //staff kitchen management
   '/staff/kitchen' => ['KitchenController', 'kitchenView'],
   '/staff/update_status' => ['KitchenController', 'updateOrderStatus'],
   '/staff/kitchen_poll' => ['KitchenController', 'pollOrders'],
   '/staff/send_email' => ['KitchenController', 'sendEmail'],
   '/staff/order-history' => ['KitchenController', 'orderHistory'],
   '/staff/clear-order' => ['KitchenController', 'clearOrder'],
   '/staff/order-details' => ['KitchenController', 'orderDetails'],
   '/staff/restore-order' => ['KitchenController', 'restoreOrder'],
   '/staff/cancel-order'   => ['KitchenController', 'cancelOrderPage'],
   '/staff/cancel_order'   => ['KitchenController', 'cancelOrder'],
   //staff order creation
   '/staff/add-order'       => ['KitchenController', 'addOrderPage'],
   '/staff/place-order'     => ['KitchenController', 'placeOrder'],
   '/staff/find-customer'  => ['KitchenController', 'findCustomer'],
   '/staff/receipt' => ['KitchenController', 'receiptPage'],
   '/staff/order-success' => ['KitchenController', 'orderSuccessPage'],

   // Admin routes

   '/admin/login' => ['AdminController', 'login'],
   '/admin/logout' => ['AdminController', 'logout'],
   '/admin/dashboard' => ['AdminController', 'dashboard'],
   //admin profile management
   '/admin/profile' => ['AdminController', 'adminProfile'],
   '/admin/profile/edit' => ['AdminController', 'adminEditForm'],
   '/admin/profile/update' => ['AdminController', 'adminUpdate'],
   '/admin/profile/password' => ['AdminController', 'adminPasswordForm'],
   '/admin/profile/password/update' => ['AdminController', 'adminPasswordUpdate'],
   '/admin/create' => ['AdminController', 'adminCreate'],
   '/admin/profile/delete' => ['AdminController', 'adminDelete'],
   //admin staff management
   '/admin/staff/list' => ['AdminController', 'staffList'],
   '/admin/staff/add' => ['AdminController', 'staffAddForm'],
   '/admin/staff/create' => ['AdminController', 'createStaff'],
   '/admin/staff/edit' => ['AdminController', 'staffEditForm'],
   '/admin/staff/update' => ['AdminController', 'staffUpdate'],
   '/admin/staff/delete' => ['AdminController', 'staffDelete'],
   '/admin/staff/view' => ['AdminController', 'staffView'],
   //  Admin menu management
   '/admin/menu' => ['AdminController', 'menu'],
   '/admin/menu/add' => ['AdminController', 'menuAddForm'],
   '/admin/menu/create' => ['AdminController', 'menuCreate'],
   '/admin/menu/edit' => ['AdminController', 'menuEditForm'],
   '/admin/menu/update' => ['AdminController', 'menuUpdate'],
   '/admin/menu/delete' => ['AdminController', 'menuDelete'],
   '/admin/menu/publish' => ['AdminController', 'publishMenu'],
   '/admin/menu/unpublish' => ['AdminController', 'unpublishMenu'],
   // Admin order management
   '/admin/order/list' => ['AdminController', 'orderList'],
   '/admin/order/view' => ['AdminController', 'orderView'],
   '/admin/order/delete' => ['AdminController', 'orderDelete'],
   '/admin/order/update_status' => ['AdminController', 'orderUpdateStatus'],
   '/admin/order-analytics' => ['AdminController', 'orderAnalyticsDashboard'],
   // admin customer 
   '/admin/customer/list' => ['AdminController', 'customerList'],
   '/admin/customer/view' => ['AdminController', 'customerView'],
   //update here
   '/admin/customer/status' => ['AdminController', 'customerStatus'],
   '/admin/customer/delete' => ['AdminController', 'customerDelete'],
   '/admin/customer/detail' => ['AdminController', 'customerDetailOverview'],


];
