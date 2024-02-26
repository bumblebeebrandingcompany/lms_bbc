<?php
use App\Http\Controllers\Admin\StagesController;
use App\Http\Controllers\Admin\WebhookReceiverController;

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

//webhook receiver
Route::any('webhook/new-lead', 'Admin\WebhookReceiverController@storeNewLead')
    ->name('webhook.store.new.lead');
Route::any('webhook/servetel', 'Admin\WebhookReceiverController@handleServetelWebhook')->name('webhook.store.servetel');
Route::any('webhook/lead-activity', 'Admin\WebhookReceiverController@storeLeadActivity')
    ->name('webhook.store.lead.activity');
Route::any('webhook/{secret}', 'Admin\WebhookReceiverController@processor')->name('webhook.processor');
Route::any('webhook/call-record', 'Admin\CallRecordController@storeNewRecord')
    ->name('webhook.store.new.record');


Route::get('document/{id}/view', 'Admin\DocumentController@guestView')->name('document.guest.view');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {

    Route::post('store-global-client-filters', 'HomeController@storeGlobalClientFilters')
        ->name('store.global.client.filter');

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('incoming-webhook/list', 'WebhookReceiverController@incomingWebhookList')->name('webhook.incoming.list');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    //sitevist
    Route::resource('/sitevisit', 'SiteVisitController');
    Route::resource('/notes', 'NoteController');
    Route::resource('/notenotinterested', 'NoteNotInterestedController');

    Route::resource('/admitted', 'AdmittedController');
    Route::resource('/followups', 'FollowUpController');
    Route::delete('followups/destroy', 'FollowUpController@massDestroy')->name('followups.massDestroy');
    Route::get('leads/{lead}/initiate-call', 'LeadsController@initiateCall')
        ->name('leads.initiateCall');

    Route::put('/admin/sitevisits/{sitevisit}/cancel', 'SiteVisitController@cancelSiteVisit')
        ->name('sitevisits.cancel');
    Route::put('/admin/sitevisits/{sitevisit}/conducted', 'SiteVisitController@conducted')
        ->name('sitevisits.conducted');

    Route::put('/admin/sitevisits/{sitevisit}/notvisited', 'SiteVisitController@notVisited')
        ->name('sitevisits.notvisited');
    Route::put('/admin/sitevisits/{sitevisit}/applicationpurchased', 'SiteVisitController@applicationpurchased')
        ->name('sitevisits.applicationpurchased');
    Route::match(['post', 'put'], '/admin/sitevisits/{id}/reschedule', 'SiteVisitController@reschedule')->name('sitevisits.reschedule');
    Route::match(['post', 'put'], '/admin/sitevisit/reschedule', 'SiteVisitController@rescheduleSiteVisit')->name('sitevisit.reschedule');
    Route::match(['post', 'put'], '/admin/sitevisit/conducted', 'SiteVisitController@conductedstage')->name('sitevisit.conducted');
    Route::match(['post', 'put'], '/admin/sitevisit/notvisited', 'SiteVisitController@notvisitedstage')->name('sitevisit.notvisited');
    Route::match(['post', 'put'], '/admin/sitevisit/cancel', 'SiteVisitController@cancelstage')->name('sitevisit.cancel');



    // Route::put('sitevisit/{sitevisit}', 'SiteVisitController@update')->name('admin.sitevisit.update');

    //call record
    Route::resource('/callog', 'CallRecordController');
    Route::resource('/applications', 'ApplicationPurchasedController');

    Route::resource('/walkinform', 'WalkinController');
    Route::resource('/promo', 'PromoController');

    Route::resource('/admission', 'AdmissionFollowUpController');
    Route::resource('/application', 'ApplicationNotPurchasedController');
     Route::resource('/admission', 'AdmissionFollowUpController');
    // Users
    Route::get('users/{id}/edit-password', 'UsersController@editPassword')
        ->name('users.edit.password');
    Route::put('users/{id}/update-password', 'UsersController@updatePassword')
        ->name('users.update.password');
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Project
    Route::get('project-additional-fields', 'ProjectController@getAdditionalFieldsDropdown')->name('projects.additional.fields');
    Route::get('project-campaigns', 'ProjectController@getCampaignsDropdown')->name('projects.campaigns');
    Route::get('project-campaign-sources', 'ProjectController@getSourceDropdown')->name('projects.campaign.sources');
    Route::get('get-api-constant-row', 'ProjectController@getApiConstantRow')
        ->name('get.api.constant.row.html');
    Route::post('test-webhook', 'ProjectController@postTestWebhook')
        ->name('projects.test.webhook');
    Route::get('get-request-body-row', 'ProjectController@getRequestBodyRow')
        ->name('get.req.body.row.html');
    Route::get('project-webhook-html', 'ProjectController@getWebhookHtml')
        ->name('projects.webhook.html');
    Route::post('store-project-outgoing-webhook', 'ProjectController@saveOutgoingWebhookInfo')
        ->name('project.outgoing.webhook.store');
    Route::get('project/{id}/webhook', 'ProjectController@getWebhookDetails')
        ->name('projects.webhook');
    Route::delete('projects/destroy', 'ProjectController@massDestroy')->name('projects.massDestroy');
    Route::post('projects/media', 'ProjectController@storeMedia')->name('projects.storeMedia');
    Route::post('projects/ckmedia', 'ProjectController@storeCKEditorImages')->name('projects.storeCKEditorImages');
    Route::post('projects/parse-csv-import', 'ProjectController@parseCsvImport')->name('projects.parseCsvImport');
    Route::post('projects/process-csv-import', 'ProjectController@processCsvImport')->name('projects.processCsvImport');
    Route::resource('projects', 'ProjectController');

    // Campaign
    Route::get('get-campaigns', 'CampaignController@getCampaigns')->name('get.campaigns');
    Route::delete('campaigns/destroy', 'CampaignController@massDestroy')->name('campaigns.massDestroy');
    Route::resource('campaigns', 'CampaignController');
    Route::resource('tags', "TagController");

    //Stages
    Route::resource('/parent-stages', "ParentStageController");
    Route::resource('/stages', "StagesController");

    // Leads
    Route::get('share-lead/{lead_id}/doc/{doc_id}', 'LeadsController@shareDocument')->name('share.lead.doc');
    Route::get('leads/export', 'LeadsController@export')->name('leads.export');
    Route::post('send-mass-webhook', 'LeadsController@sendMassWebhook')->name('lead.send.mass.webhook');
    Route::get('lead-details-rows-html', 'LeadsController@getLeadDetailsRows')->name('lead.details.rows');
    Route::get('lead-detail-html', 'LeadsController@getLeadDetailHtml')->name('lead.detail.html');
    Route::delete('leads/destroy', 'LeadsController@massDestroy')->name('leads.massDestroy');
    Route::resource('leads', 'LeadsController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Client
    Route::delete('clients/destroy', 'ClientController@massDestroy')->name('clients.massDestroy');
    Route::post('clients/media', 'ClientController@storeMedia')->name('clients.storeMedia');
    Route::post('clients/ckmedia', 'ClientController@storeCKEditorImages')->name('clients.storeCKEditorImages');
    Route::resource('clients', 'ClientController');

    // Agency
    Route::delete('agencies/destroy', 'AgencyController@massDestroy')->name('agencies.massDestroy');
    Route::resource('agencies', 'AgencyController');
    Route::resource('lead-activity', 'LeadActivityController');


    // Source
    Route::get('source/{id}/webhook', 'SourceController@getWebhookDetails')
        ->name('sources.webhook');
    Route::post('update-email-and-phone-key', 'SourceController@updatePhoneAndEmailKey')->name('update.email.and.phone.key');
    Route::get('get-sources', 'SourceController@getSource')->name('get.sources');
    Route::delete('sources/destroy', 'SourceController@massDestroy')->name('sources.massDestroy');
    Route::resource('sources', 'SourceController');
    Route::resource('stage-notes', 'StageNotesController');


    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

    // Document
    Route::get('document-logs', 'DocumentController@getDocumentLogs')->name('get.documents.log');
    Route::get('get-filtered-documents', 'DocumentController@getFilteredDocuments')->name('documents.filtered');
    Route::delete('documents/{id}/file-remove', 'DocumentController@removeFile')->name('documents.remove.file');
    Route::post('documents/ckmedia', 'DocumentController@storeCKEditorImages')->name('documents.storeCKEditorImages');
    Route::delete('documents/destroy', 'DocumentController@massDestroy')->name('documents.massDestroy');
    Route::resource('documents', 'DocumentController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
