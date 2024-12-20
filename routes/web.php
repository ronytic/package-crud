<?php

use ProcessMaker\Package\PackageCrud\Http\Controllers\PackageCrudController;

Route::group(['middleware' => ['auth']], function () {
    Route::get('admin/package-crud', [PackageCrudController::class, 'index'])->name('package.crud.index');
    // Route::get('package-crud', [PackageCrudController::class, 'index'])->name('package.crud.tab.index');
});
