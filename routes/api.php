<?php

use ProcessMaker\Package\PackageCrud\Http\Controllers\Api\CrudController;

Route::group(['middleware' => ['auth:api', 'bindings']], function () {
    Route::group(['prefix' => 'package-crud', 'as' => 'api.package-crud.'], function () {
        Route::get('crud', [CrudController::class, 'index'])->name('crud.index');
        Route::post('crud', [CrudController::class, 'store'])->name('crud.store');
        Route::put('crud/{uuid}', [CrudController::class, 'update'])->name('crud.update');
        Route::delete('crud/{uuid}', [CrudController::class, 'destroy'])->name('crud.destroy');
    });
});
