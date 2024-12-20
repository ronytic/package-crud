<?php

use ProcessMaker\Package\PackageCrud\Http\Controllers\PackageCrudController;

Route::group(['middleware' => ['auth:api', 'bindings']], function () {
    Route::get('admin/package-crud/fetch', [PackageCrudController::class, 'fetch'])->name('package.skeleton.fetch');
    Route::apiResource('admin/package-crud', PackageCrudController::class);
});
