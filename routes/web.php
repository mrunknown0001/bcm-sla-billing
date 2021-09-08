<?php

use Illuminate\Support\Facades\Route;

// User Login
Route::get('/', 'LoginController@login')->name('login');
Route::post('/', 'LoginController@postLogin')->name('post.login');

Route::get('/forgot-password', 'LoginController@forgotPassword')->name('forgot.password');
Route::post('/forgot-password', 'LoginController@postForgotPassword')->name('post.forgot.password');

Route::get('/enter/verification/code/{id}', 'LoginController@validateVerificationCode')->name('validate.verification.code');

Route::get('/enter/verification/code', 'LoginController@postValidateVerificationCode')->name('post.validate.verification.code');

Route::post('/reset-password', 'LoginController@postPasswordReset')->name('post.password.reset');

Route::group(['middleware' => 'auth'], function() {

	Route::get('/wro/download/attachement/{id}', 'WorkOrderController@downloadWro')->name('download.wro.attachement');

	// Accounting & Purchasing
	Route::get('/reports', 'ReportController@index')->name('reports');
	Route::get('/reports/all/wro/{from?}/{to?}/{status?}', 'ReportController@allWro')->name('reports.all.wro');


	Route::get('/reports/view/wro/{id}', 'ReportController@viewWro')->name('reports.view.wro');

	Route::get('/reports/work-order/{status?}', 'ReportController@wroStatus')->name('reports.wro..status');

	Route::post('/reports/generate/download', 'ReportController@generateDownload')->name('reports.generate.download');


	Route::post('/password/change', 'GeneralController@postChangePassword')->name('post.change.password');
});


/**
 * Common User URL 
 * User Type == 6
 */
Route::group(['prefix' => 'u', 'middleware' => ['user', 'preventBackHistory']], function () {
	// User Dashboard
	Route::get('/dashboard', 'UserController@dashboard')->name('user.dashboard');

	Route::get('/account', 'UserController@account')->name('user.account');
	
	Route::get('/all/work-order', 'UserController@allworkOrder')->name('user.all.wro');

	Route::get('/work-order', 'UserController@workOrder')->name('user.work.order');

	Route::post('/work-order', 'UserController@postworkOrder')->name('user.post.work.order');

	Route::get('/wro/number/preview/{farm_id}', 'GeneralController@previewWroNo')->name('user.preview.wrono');

	Route::get('/work-order/view/{id}', 'UserController@viewWorkOrder')->name('user.view.work.order');

	Route::get('/work-order/cancel/{id}/{comment}', 'UserController@cancelWorkOrder')->name('user.cancel.work.order');

	Route::get('/wro-download/{id}', 'GeneralController@downloadWro')->name('user.wro.pdf.download');

	Route::get('/wro/archived', 'UserController@archivedWRO')->name('user.archived.wro');
	Route::get('/wro/archived/all', 'UserController@allArchivedWRO')->name('user.all.archived.wro');
});



// Manager route group
// User Type 4
Route::group(['prefix' => 'manager', 'middleware' => ['manager', 'preventBackHistory']], function () {
	Route::get('/dashboard', 'ManagerController@dashboard')->name('manager.dashboard');

	Route::get('/account', 'ManagerController@account')->name('manager.account');

	Route::get('/all/work-order', 'ManagerController@allWorkOrder')->name('manager.all.wro');

	Route::get('/work-order/view/{id}', 'ManagerController@viewWorkOrder')->name('manager.view.work.order');

	Route::get('/wro/approval/{id}', 'ManagerController@wroApproval')->name('manager.wro.approval');

	Route::get('/wro/disapproval/{id}/{comment}', 'ManagerController@wroDisapproval')->name('manager.wro.disapproval');

	Route::get('/wro/archive/{id}', 'ManagerController@wroArchive')->name('manager.wro.archive');

	Route::get('/wro/archived', 'ManagerController@archivedWRO')->name('manager.archived.wro');
	Route::get('/wro/archived/all', 'ManagerController@allArchivedWRO')->name('manager.all.archived.wro');

	Route::get('/wro-download/{id}', 'GeneralController@downloadWro')->name('manager.wro.pdf.download');


	// BCM Manager Approval and Disapproval
	Route::get('/wro/bcm/manager/approval/{id}', 'ManagerController@wroBCMManagerApproval')->name('manager.bcm.approve.wro');

	Route::get('/wro/bcm/manager/disapproval/{id}/{comment}', 'ManagerController@wroBCMManagerDisapproval')->name('manager.bcm.approve.wro');

	// Treasury Manager Approval and Disapproval
	Route::get('/wro/trsry/manager/approval/{id}', 'ManagerController@wroTrsryManagerApproval')->name('manager.trsry.approve.wro');

	Route::get('/wro/trsry/manager/disapproval/{id}/{comment}', 'ManagerController@wroTrsryManagerDisapproval')->name('manager.trsry.approve.wro');

});


// Division Head route group
// User Type 3
Route::group(['prefix' => 'div-head', 'middleware' => ['divhead', 'preventBackHistory']], function () {
	Route::get('/dashboard', 'DivHeadController@dashboard')->name('divhead.dashboard');

	Route::get('/account', 'DivHeadController@account')->name('divhead.account');

	Route::get('/work-order/all', 'DivHeadController@allWorkOrder')->name('divhead.all.work.order');

	Route::get('/work-order/view/{id}', 'DivHeadController@viewWorkOrder')->name('divhead.view.work.order');

	Route::get('/wro/approval/{id}', 'DivHeadController@wroApproval')->name('divhead.wro.approval');

	Route::get('/wro/disapproval/{id}/{comment}', 'DivHeadController@wroDisapproval')->name('divhead.wro.disapproval');

	Route::get('/wro-download/{id}', 'GeneralController@downloadWro')->name('divhead.wro.pdf.download');

	Route::get('/wro/archived', 'DivHeadController@archivedWRO')->name('divhead.archived.wro');
	Route::get('/wro/archived/all', 'DivHeadController@allArchivedWRO')->name('divhead.all.archived.wro');	

	Route::get('/gs/wro/approval/{id}', 'DivHeadController@wroGsDivHeadApproval')->name('divhead.gs.wro.approval');

	Route::get('/gs/wro/disapproval/{id}/{comment}', 'DivHeadController@wroGsDivHeadDisapproval')->name('divhead.gs.wro.disapproval');
});


// vp route group
// user type 2
Route::group(['prefix' => 'vp', 'middleware' => ['vp', 'preventBackHistory']], function () {
	Route::get('/dashboard', 'VpController@dashboard')->name('vp.dashboard');

	Route::get('/account', 'VpController@account')->name('vp.account');

	Route::get('/work-order/view/{id}', 'VpController@viewWorkOrder')->name('vp.view.work.order');

	Route::get('/work-order/all', 'VpController@allWorkOrder')->name('vp.all.work.order');

	Route::get('/wro/approval/{id}', 'VpController@wroGsVPApproval')->name('vp.wro.approval');

	Route::get('/wro/disapproval/{id}/{comment}', 'VpController@wroGsVpDisapproval')->name('vp.wro.disapproval');

	Route::get('/wro/archived', 'VpController@archivedWro')->name('vp.archived.wro');

	Route::get('/wro/archived/all', 'VpController@allArchivedWro')->name('vp.all.archived.wro');

});



//  Admin Login
Route::get('/admin/login', 'AdminController@adminLogin')->name('admin.login');
Route::post('/admin/login', 'AdminController@postAdminLogin')->name('post.admin.login');

Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'preventBackHistory']], function () {
	// Admin Dashboard
	Route::get('/dashboard', 'AdminController@dashboard')->name('admin.dashboard');

	// Settings for Admin
	Route::get('/settings', 'AdminController@settings')->name('admin.settings');


	Route::get('/users', 'AdminController@users')->name('admin.users');

	Route::get('/user/add', 'AdminController@addUser')->name('admin.add.user');

	Route::post('/user/add', 'AdminController@postAddUser')->name('admin.post.add.user');

	Route::get('/user/update/{id}', 'AdminController@updateUser')->name('admin.update.user');

	Route::post('/user/update', 'AdminController@postUpdateUser')->name('admin.post.upate.user');

	Route::get('/users/all', 'AdminController@allUsers')->name('admin.all.users');

	Route::get('/user/setup/{id}', 'AdminController@setupUser')->name('admin.setup.user');

	Route::post('/user/setup', 'AdminController@postSetupUser')->name('admin.post.setup.user');
	Route::get('/user/setup', function() {
		return redirect()->route('admin.users');
	});

	Route::get('/work-request-order-setup', 'AdminController@wroSetup')->name('admin.wro.setup');
	// Route::get('/work-request-order-setup', function () {
	// 	return 'hey';
	// });

	Route::get('/wro/setup/{code}', 'WorkOrderController@wroSetupApprover')->name('admin.wro.setup.form');
	Route::post('/wro/setup', 'WorkOrderController@postWroSetupApprover')->name('admin.post.wro.setup');

	Route::get('/get/managers', 'GeneralController@getManagers')->name('admin.get.managers');

	// Farm
	Route::get('/farm', 'FarmController@farms')->name('admin.farms');

	Route::get('/farm/all', 'FarmController@all')->name('admin.all.farms');

	Route::get('/farm/add', 'FarmController@add')->name('admin.add.farm');
	Route::post('/farm/add', 'FarmController@postAdd')->name('admin.post.add.farm');

	Route::get('/farm/update/{id}', 'FarmController@update')->name('admin.update.farm');
	Route::post('/farm/update', 'FarmController@postUpdate')->name('admin.post.update.farm');

	// Department
	Route::get('/departments', 'DepartmentController@departments')->name('admin.departments');

	Route::get('/departments/all', 'DepartmentController@all')->name('admin.all.departments');

	Route::get('/department/add', 'DepartmentController@add')->name('admin.add.department');
	Route::post('/department/add', 'DepartmentController@postAdd')->name('admin.post.add.department');


	Route::get('/department/update/{id}', 'DepartmentController@update')->name('admin.update.department');
	Route::post('/department/update', 'DepartmentController@postUpdate')->name('admin.post.update.department');

	// Password retention
	Route::get('/password/retention', 'AdminController@passwordRetention')->name('admin.password.retention');
	Route::post('/password/retention', 'AdminController@postPasswordRetention')->name('admin.post.password.retention');


	// Truncate WRO and WRO Number
	Route::get('/truncate/wro', 'AdminController@truncateWro')->name('admin.truncate.wro');



	// Database Backup for Admin
	Route::get('/databbase-backup', 'AdminController@backup')->name('admin.db.backup');

	Route::get('/database-restore', 'AdminController@restore')->name('admin.db.restore');

	// Module Management for Admin
	Route::get('/module-management', 'AdminController@moduleManagement')->name('admin.module');

	// Mail Setting
	Route::post('/mail-setup', 'MailController@setup')->name('mail.setup');
});


Route::get('/logout/{param?}', 'LoginController@logout')->name('logout');













