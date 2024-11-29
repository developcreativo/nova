<?php

use Illuminate\Http\Middleware\CheckResponseForModifications;
use Illuminate\Support\Facades\Route;
use Laravel\Components\Http\Controllers\ActionController;
use Laravel\Components\Http\Controllers\AssociatableController;
use Laravel\Components\Http\Controllers\AttachableController;
use Laravel\Components\Http\Controllers\AttachedResourceUpdateController;
use Laravel\Components\Http\Controllers\CardController;
use Laravel\Components\Http\Controllers\CreationFieldController;
use Laravel\Components\Http\Controllers\CreationFieldSyncController;
use Laravel\Components\Http\Controllers\CreationPivotFieldController;
use Laravel\Components\Http\Controllers\DashboardCardController;
use Laravel\Components\Http\Controllers\DashboardController;
use Laravel\Components\Http\Controllers\DashboardMetricController;
use Laravel\Components\Http\Controllers\DetailMetricController;
use Laravel\Components\Http\Controllers\FieldAttachmentController;
use Laravel\Components\Http\Controllers\FieldController;
use Laravel\Components\Http\Controllers\FieldDestroyController;
use Laravel\Components\Http\Controllers\FieldDownloadController;
use Laravel\Components\Http\Controllers\FieldPreviewController;
use Laravel\Components\Http\Controllers\FilterController;
use Laravel\Components\Http\Controllers\ImpersonateController;
use Laravel\Components\Http\Controllers\LensActionController;
use Laravel\Components\Http\Controllers\LensCardController;
use Laravel\Components\Http\Controllers\LensController;
use Laravel\Components\Http\Controllers\LensFilterController;
use Laravel\Components\Http\Controllers\LensMetricController;
use Laravel\Components\Http\Controllers\LensResourceCountController;
use Laravel\Components\Http\Controllers\LensResourceDestroyController;
use Laravel\Components\Http\Controllers\LensResourceForceDeleteController;
use Laravel\Components\Http\Controllers\LensResourceRestoreController;
use Laravel\Components\Http\Controllers\MetricController;
use Laravel\Components\Http\Controllers\MorphableController;
use Laravel\Components\Http\Controllers\MorphedResourceAttachController;
use Laravel\Components\Http\Controllers\NotificationDeleteAllController;
use Laravel\Components\Http\Controllers\NotificationDeleteController;
use Laravel\Components\Http\Controllers\NotificationIndexController;
use Laravel\Components\Http\Controllers\NotificationReadAllController;
use Laravel\Components\Http\Controllers\NotificationReadController;
use Laravel\Components\Http\Controllers\NotificationUnreadController;
use Laravel\Components\Http\Controllers\PivotFieldDestroyController;
use Laravel\Components\Http\Controllers\RelatableAuthorizationController;
use Laravel\Components\Http\Controllers\ResourceAttachController;
use Laravel\Components\Http\Controllers\ResourceCountController;
use Laravel\Components\Http\Controllers\ResourceDestroyController;
use Laravel\Components\Http\Controllers\ResourceDetachController;
use Laravel\Components\Http\Controllers\ResourceForceDeleteController;
use Laravel\Components\Http\Controllers\ResourceIndexController;
use Laravel\Components\Http\Controllers\ResourcePeekController;
use Laravel\Components\Http\Controllers\ResourcePreviewController;
use Laravel\Components\Http\Controllers\ResourceRestoreController;
use Laravel\Components\Http\Controllers\ResourceSearchController;
use Laravel\Components\Http\Controllers\ResourceShowController;
use Laravel\Components\Http\Controllers\ResourceStoreController;
use Laravel\Components\Http\Controllers\ResourceUpdateController;
use Laravel\Components\Http\Controllers\ScriptController;
use Laravel\Components\Http\Controllers\SearchController;
use Laravel\Components\Http\Controllers\SoftDeleteStatusController;
use Laravel\Components\Http\Controllers\StyleController;
use Laravel\Components\Http\Controllers\UpdateFieldController;
use Laravel\Components\Http\Controllers\UpdatePivotFieldController;

// Scripts & Styles...
Route::get('/scripts/{script}', ScriptController::class)->middleware(CheckResponseForModifications::class);
Route::get('/styles/{style}', StyleController::class)->middleware(CheckResponseForModifications::class);

// Global Search...
Route::get('/search', SearchController::class);

// Impersonation...
Route::post('impersonate', [ImpersonateController::class, 'startImpersonating']);
Route::delete('impersonate', [ImpersonateController::class, 'stopImpersonating']);

// Fields...
Route::get('/{resource}/field/{field}', FieldController::class);
Route::post('/{resource}/field/{field}/preview', FieldPreviewController::class);
Route::post('/{resource}/field-attachment/{field}', [FieldAttachmentController::class, 'store']);
Route::delete('/{resource}/field-attachment/{field}', [FieldAttachmentController::class, 'destroyAttachment']);
Route::get('/{resource}/field-attachment/{field}/draftId', [FieldAttachmentController::class, 'draftId']);
Route::delete('/{resource}/field-attachment/{field}/{draftId}', [FieldAttachmentController::class, 'destroyPending']);
Route::get('/{resource}/creation-fields', CreationFieldController::class);
Route::get('/{resource}/{resourceId}/update-fields', UpdateFieldController::class);
Route::get('/{resource}/{resourceId}/creation-pivot-fields/{relatedResource}', CreationPivotFieldController::class);
Route::get('/{resource}/{resourceId}/update-pivot-fields/{relatedResource}/{relatedResourceId}', UpdatePivotFieldController::class);
Route::patch('/{resource}/creation-fields', CreationFieldSyncController::class);
Route::patch('/{resource}/{resourceId}/update-fields', [UpdateFieldController::class, 'sync']);
Route::patch('/{resource}/{resourceId}/creation-pivot-fields/{relatedResource}', [CreationPivotFieldController::class, 'sync']);
Route::patch('/{resource}/{resourceId}/update-pivot-fields/{relatedResource}/{relatedResourceId}', [UpdatePivotFieldController::class, 'sync']);
Route::get('/{resource}/{resourceId}/download/{field}', FieldDownloadController::class);
Route::delete('/{resource}/{resourceId}/field/{field}', FieldDestroyController::class);
Route::delete('/{resource}/{resourceId}/{relatedResource}/{relatedResourceId}/field/{field}', PivotFieldDestroyController::class);

// Dashboards...
Route::get('/dashboards/{dashboard}', DashboardController::class);
Route::get('/dashboards/cards/{dashboard}', DashboardCardController::class);

// Notifications...
Route::get('/nova-notifications', NotificationIndexController::class);
Route::post('/nova-notifications/read-all', NotificationReadAllController::class);
Route::post('/nova-notifications/{notification}/read', NotificationReadController::class);
Route::post('/nova-notifications/{notification}/unread', NotificationUnreadController::class);
Route::delete('/nova-notifications/', NotificationDeleteAllController::class);
Route::delete('/nova-notifications/{notification}', NotificationDeleteController::class);

// Actions...
Route::get('/{resource}/actions', [ActionController::class, 'index']);
Route::post('/{resource}/action', [ActionController::class, 'store']);
Route::patch('/{resource}/action', [ActionController::class, 'sync']);

// Filters...
Route::get('/{resource}/filters', FilterController::class);

// Lenses...
Route::get('/{resource}/lenses', [LensController::class, 'index']);
Route::get('/{resource}/lens/{lens}', [LensController::class, 'show']);
Route::get('/{resource}/lens/{lens}/count', LensResourceCountController::class);
Route::delete('/{resource}/lens/{lens}', LensResourceDestroyController::class);
Route::delete('/{resource}/lens/{lens}/force', LensResourceForceDeleteController::class);
Route::put('/{resource}/lens/{lens}/restore', LensResourceRestoreController::class);
Route::get('/{resource}/lens/{lens}/actions', [LensActionController::class, 'index']);
Route::post('/{resource}/lens/{lens}/action', [LensActionController::class, 'store']);
Route::patch('/{resource}/lens/{lens}/action', [LensActionController::class, 'sync']);
Route::get('/{resource}/lens/{lens}/filters', [LensFilterController::class, 'index']);

// Cards / Metrics...
Route::get('/metrics/{metric}', DashboardMetricController::class);
Route::get('/{resource}/metrics', [MetricController::class, 'index']);
Route::get('/{resource}/metrics/{metric}', [MetricController::class, 'show']);
Route::get('/{resource}/{resourceId}/metrics/{metric}', DetailMetricController::class);

Route::get('/{resource}/lens/{lens}/metrics', [LensMetricController::class, 'index']);
Route::get('/{resource}/lens/{lens}/metrics/{metric}', [LensMetricController::class, 'show']);

Route::get('/{resource}/cards', CardController::class);
Route::get('/{resource}/lens/{lens}/cards', LensCardController::class);

// Authorization Information...
Route::get('/{resource}/relate-authorization', RelatableAuthorizationController::class);

// Soft Delete Information...
Route::get('/{resource}/soft-deletes', SoftDeleteStatusController::class);

// Resource Management...
Route::get('/{resource}', ResourceIndexController::class);
Route::get('/{resource}/search', ResourceSearchController::class);
Route::get('/{resource}/count', ResourceCountController::class);
Route::delete('/{resource}/detach', ResourceDetachController::class);
Route::put('/{resource}/restore', ResourceRestoreController::class);
Route::delete('/{resource}/force', ResourceForceDeleteController::class);
Route::get('/{resource}/{resourceId}', ResourceShowController::class);
Route::get('/{resource}/{resourceId}/preview', ResourcePreviewController::class);
Route::get('/{resource}/{resourceId}/peek', ResourcePeekController::class);
Route::post('/{resource}', ResourceStoreController::class);
Route::put('/{resource}/{resourceId}', ResourceUpdateController::class);
Route::delete('/{resource}', ResourceDestroyController::class);

// Associatable Resources...
Route::get('/{resource}/associatable/{field}', AssociatableController::class);
Route::get('/{resource}/{resourceId}/attachable/{field}', AttachableController::class);
Route::get('/{resource}/morphable/{field}', MorphableController::class);

// Resource Attachment...
Route::post('/{resource}/{resourceId}/attach/{relatedResource}', ResourceAttachController::class);
Route::post('/{resource}/{resourceId}/update-attached/{relatedResource}/{relatedResourceId}', AttachedResourceUpdateController::class);
Route::post('/{resource}/{resourceId}/attach-morphed/{relatedResource}', MorphedResourceAttachController::class);
