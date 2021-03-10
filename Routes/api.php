<?php

declare(strict_types=1);

use App\Http\Controllers\IlluminatorAgreementController;
use App\Http\Controllers\IlluminatorController;


    //Illuminators-Units
    Route::middleware('access.for:user')->group(function (): void {
        Route::get('illuminators/{illuminator}/agreement', [IlluminatorAgreementController::class, 'show'])
            ->name('illuminatorAgreement.show');
        Route::post('illuminators/{illuminator}/agreement/accept', [IlluminatorAgreementController::class, 'accept'])
            ->name('illuminatorAgreement.accept');
        Route::post('illuminators/{illuminator}/agreement/approve', [IlluminatorAgreementController::class, 'approve'])
            ->name('illuminatorAgreement.approve');
        Route::match(['PUT', 'PATCH'], 'illuminators/{illuminator}/agreement', [IlluminatorAgreementController::class, 'update'])
            ->name('illuminatorAgreement.update');
        Route::get('illuminators/{illuminator}/agreement/changes', [IlluminatorAgreementController::class, 'showChanges'])
            ->name('illuminatorAgreement.showChanges');
    });
    Route::middleware('access.for:user,illuminator')->group(function (): void {
        Route::apiResource('illuminators', IlluminatorController::class)
            ->only('store', 'show', 'index', 'update');
        Route::timeline('illuminators', [IlluminatorController::class, 'timeline']);
    });
});
