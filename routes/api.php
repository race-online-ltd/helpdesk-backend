<?php

use App\Http\Controllers\v1\Dashboard\DashboardController;
use App\Http\Controllers\v1\Report\ReportsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\v1\Settings\CompanyController;
use App\Http\Controllers\v1\Settings\DepartmentController;
use App\Http\Controllers\v1\Settings\TeamController;
use App\Http\Controllers\v1\Settings\CategoryController;
use App\Http\Controllers\v1\Settings\SubCategoryController;
use App\Http\Controllers\v1\Settings\EmailController;
use App\Http\Controllers\v1\Settings\NotificationController;
use App\Http\Controllers\v1\Settings\DivisionController;
use App\Http\Controllers\v1\Settings\ServiceLevelAgreementController;
use App\Http\Controllers\v1\Settings\ViewItemsController;
use App\Http\Controllers\v1\Settings\UserLoginController;
use App\Http\Controllers\v1\Settings\UserRegisterController;
use App\Http\Controllers\v1\Ticket\TicketController;
use App\Http\Controllers\v1\UtilityController;
use App\Http\Controllers\v1\BackboneController;
use App\Http\Controllers\v1\Settings\BranchController;
use App\Http\Controllers\v1\Settings\NetworkBackboneController;
use App\Http\Controllers\SendSmsController;
use App\Http\Controllers\AggregatorController;
use App\Http\Controllers\ClientAggregatorMappingController;
use App\Http\Controllers\TeamMappingController;
use App\Http\Controllers\SlaSubcatConfigController;
use App\Http\Controllers\SlaClientConfigController;
use App\Http\Controllers\OpenTicketController;
use App\Http\Controllers\SmsTemplateController;
use App\Http\Controllers\SmsAttributeController;
use App\Http\Controllers\v1\Superapp\SuperappController;
use App\Http\Controllers\SlaDetailsViewController;



/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (No Authentication)
|--------------------------------------------------------------------------
*/

Route::get('v1/ticket/{ticketNumber}/sla-report', [SlaDetailsViewController::class, 'getTicketSlaReport']);

Route::post('v1/user/userauthentication', [UserLoginController::class, 'Login']);
Route::get('v1/public-ticket/{token}', [TicketController::class, 'getPublicTicket']);


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
Route::prefix('v1/sms-attributes')->group(function () {
    Route::get('/',        [SmsAttributeController::class, 'index']);
    Route::post('/',       [SmsAttributeController::class, 'store']);
    Route::put('/{id}',    [SmsAttributeController::class, 'update']);
    Route::delete('/{id}', [SmsAttributeController::class, 'destroy']);
});





// routes/api.php
Route::get('v1/settings/sms/show',        [SmsTemplateController::class, 'index']);
Route::get('v1/settings/sms/show/{id}',   [SmsTemplateController::class, 'show']);
Route::post('v1/settings/sms/store',      [SmsTemplateController::class, 'store']);
Route::put('v1/settings/sms/update/{id}', [SmsTemplateController::class, 'update']);
Route::post('v1/sms-templates/check-exclude-notify', [SmsTemplateController::class, 'checkExcludeNotify']);






Route::prefix('v1/sla-client-configs')->group(function () {
    Route::get('/', [SlaClientConfigController::class, 'index']);
    Route::get('{id}', [SlaClientConfigController::class, 'show']);
    Route::post('/', [SlaClientConfigController::class, 'store']);
    Route::put('{id}', [SlaClientConfigController::class, 'update']);
    Route::delete('{id}', [SlaClientConfigController::class, 'destroy']);
});


Route::post('v1/ticket/merge', [OpenTicketController::class, 'mergeTickets']);





Route::prefix('v1/sla-subcat-configs')->group(function () {
    Route::get('/', [SlaSubcatConfigController::class, 'index']);
    Route::get('{id}', [SlaSubcatConfigController::class, 'show']);
    Route::post('/', [SlaSubcatConfigController::class, 'store']);
    Route::put('{id}', [SlaSubcatConfigController::class, 'update']);
    Route::delete('{id}', [SlaSubcatConfigController::class, 'destroy']);
});




Route::get('v1/client-aggregators/{clientId}',[ClientAggregatorMappingController::class, 'getAggregatorsByClient']);


Route::get('v1/ticket/open/by/team/{teamId}',[OpenTicketController::class, 'getOpenTicketsByTeam']);


Route::get('/send-sms-test', [SendSmsController::class, 'sendSMSStatic']);

Route::post('/send-sms', [SendSmsController::class, 'sendSMS']);
Route::post('v1/send-sms-by-sid', [SendSmsController::class, 'checkAndSendSMS']);


Route::post('/send-sms-partner', [SendSmsController::class, 'sendSMSForPartner']);
Route::post('v1/send-sms-by-partner-number', [SendSmsController::class, 'checkAndSendSMSForPartner']);


Route::post('v1/sms/send-client', [SendSmsController::class, 'sendSMSForClient']);


    Route::prefix('v1')->group(function () {
        Route::prefix('settings/company')->group(function () {
            Route::post('show', [CompanyController::class, 'index']);
            Route::post('cliententityshow', [CompanyController::class, 'ClientEntityShow']);
            Route::post('store', [CompanyController::class, 'store']);
            Route::get('show/{id}', [CompanyController::class, 'show']);
            Route::put('update/{id}', [CompanyController::class, 'update']);
            Route::delete('destroy/{id}', [CompanyController::class, 'destroy']);
        });
        Route::prefix('settings/department')->group(function () {
            Route::get('show', [DepartmentController::class, 'index']);
            Route::post('store', [DepartmentController::class, 'store']);
            Route::get('show/{id}', [DepartmentController::class, 'show']);
            Route::put('update/{id}', [DepartmentController::class, 'update']);
            Route::delete('destroy/{id}', [DepartmentController::class, 'destroy']);
        });


        Route::prefix('team-mapping')->group(function () {
            
            Route::get('/', [TeamMappingController::class, 'index']);
            Route::post('/store', [TeamMappingController::class, 'store']);
            Route::get('/show/{id}', [TeamMappingController::class, 'show']);
            Route::put('/update/{id}', [TeamMappingController::class, 'update']);
            Route::delete('/{id}', [TeamMappingController::class, 'destroy']);

            Route::get('/subcategory/by-category/{categoryId}', [TeamMappingController::class, 'getSubCategoriesByCategory']);
    
            // Alternative: Fetch subcategories by category ID (direct SQL)
            Route::get('/category/{categoryId}', [TeamMappingController::class, 'getSubCategoriesByCategoryDirect']);

            // Filter by relationships
            Route::get('/company/{companyId}', [TeamMappingController::class, 'getByCompanyId']);
            Route::get('/category/{categoryId}', [TeamMappingController::class, 'getByCategoryId']);
            Route::get('/subcategory/{subcategoryId}', [TeamMappingController::class, 'getBySubcategoryId']);
        });

        Route::prefix('settings/team')->group(function () {
            Route::get('show', [TeamController::class, 'index']);
            Route::get('all', [TeamController::class, 'all']);
            Route::get('show/bysubcategory/{id}', [TeamController::class, 'getTeamBySubcategory']);
            Route::post('store', [TeamController::class, 'store']);

            Route::get('show/by/default/business/entity/{id}', [TeamController::class, 'getTeamByDefaultEntity']);
            Route::get('show/{id}', [TeamController::class, 'show']);
            Route::put('update/{id}', [TeamController::class, 'update']);
            Route::delete('destroy/{id}', [TeamController::class, 'destroy']);

            Route::post('additional-config', [TeamController::class, 'storeOrUpdateTeamConfiguration']);
            Route::get('config-show/{id}', [TeamController::class, 'getTeamConfig']);
            
        });

        Route::prefix('settings/division')->group(function () {
            Route::get('show', [DivisionController::class, 'index']);
            Route::post('store', [DivisionController::class, 'store']);
            Route::get('show/{id}', [DivisionController::class, 'show']);
            Route::put('update/{id}', [DivisionController::class, 'update']);
            Route::delete('destroy/{id}', [DivisionController::class, 'destroy']);
        });

        Route::prefix('settings/category')->group(function () {
            Route::get('show', [CategoryController::class, 'index']);
            Route::get('default-business-entity/{id}', [CategoryController::class, 'fetchDefaultBusinessEntity']);
            Route::get('unique-category/{id}', [CategoryController::class, 'uniqueCategory']);


            Route::post('store', [CategoryController::class, 'store']);
            Route::get('show/{id}', [CategoryController::class, 'show']);
            Route::put('update/{id}', [CategoryController::class, 'update']);
            Route::delete('destroy/{id}', [CategoryController::class, 'destroy']);

            Route::get('showall', [CategoryController::class, 'fetchCategoryAll']);

            Route::get('showall-partner/{id}', [CategoryController::class, 'fetchCategoryForPartner']);
            Route::post('visibility', [CategoryController::class, 'updateCategoryVisibility']);
        });

        Route::prefix('settings/subcategory')->group(function () {

            Route::get('show/bycategory/{companyId}/{categoryId}', [SubCategoryController::class, 'fetchSubcategoryByCategoryId']);
            Route::get('show', [SubCategoryController::class, 'index']);
            Route::post('store', [SubCategoryController::class, 'store']);
            Route::get('show/{id}', [SubCategoryController::class, 'show']);
            Route::put('update/{id}', [SubCategoryController::class, 'update']);
            Route::delete('destroy/{id}', [SubCategoryController::class, 'destroy']);

            Route::get('showall', [SubCategoryController::class, 'fetchSubcategoryAll']);

            Route::get('showall-partner/{categoryId}/{entityId}', [SubCategoryController::class, 'fetchSubcategoryAllForPartner']);
            Route::post('visibility', [SubCategoryController::class, 'updateSubCategoryVisibility']);
        });

        Route::prefix('settings/email')->group(function () {
            Route::get('attribute', [EmailController::class, 'getAttributes']);
            Route::get('show', [EmailController::class, 'index']);
            Route::post('store', [EmailController::class, 'store']);
            Route::get('show/{id}', [EmailController::class, 'show']);
            Route::put('update/{id}', [EmailController::class, 'update']);
            Route::delete('destroy/{id}', [EmailController::class, 'destroy']);
        });

        Route::prefix('settings/email/notification')->group(function () {
            Route::get('show', [NotificationController::class, 'index']);
            Route::post('store', [NotificationController::class, 'store']);
            Route::get('show/{id}', [NotificationController::class, 'show']);
            Route::put('update/{id}', [NotificationController::class, 'update']);
            Route::delete('destroy/{id}', [NotificationController::class, 'destroy']);
        });

        Route::prefix('settings/sla')->group(function () {
            Route::get('show', [ServiceLevelAgreementController::class, 'index']);
            // Route::get('subcategorybyteam/{id}', [ServiceLevelAgreementController::class, 'getSubcategoryByTeam']);
            Route::get('subcategorybyteam/{teamId}/{businessEntity}', [ServiceLevelAgreementController::class, 'getSubcategoryByTeam']);
            Route::get('subcategorybyteamnew/{teamId}/{businessEntity}', [ServiceLevelAgreementController::class, 'getSubcategoryByTeamNew']);
            Route::get('subcategorybybusinessentity/{id}', [ServiceLevelAgreementController::class, 'getSubcategoryByBusinessEntity']);
            Route::get('show/by/subcategoryid/{id}', [ServiceLevelAgreementController::class, 'getSLAbySubcategoryId']);

            Route::get('show/by/team/{teamId}', [ServiceLevelAgreementController::class, 'showEscalation']);
            Route::get('/escalations/{teamId}/{levelId}/edit', [ServiceLevelAgreementController::class, 'editEscalation']);
            Route::put('/escalations/{teamId}/{levelId}/update', [ServiceLevelAgreementController::class, 'updateEscalation']);
            Route::delete('/team/{teamId}/escalation/{levelId}', [ServiceLevelAgreementController::class, 'deleteEscalation']);

            // Route::get('/teams/{team_id}/sub-categories',[ServiceLevelAgreementController::class, 'getSubCategoriesByTeam']);
            Route::get('/teams/{team_id}/business-entity/{business_entity_id}/sub-categories',[ServiceLevelAgreementController::class, 'getSubCategoriesByTeam']);
            Route::get('/clients/show/by/business-entity/{business_entity_id}',[ServiceLevelAgreementController::class, 'getClientsByBusinessEntity']);





            Route::post('store', [ServiceLevelAgreementController::class, 'store']);
            Route::get('show/{id}', [ServiceLevelAgreementController::class, 'edit']);
            Route::put('update/{id}', [ServiceLevelAgreementController::class, 'update']);
            Route::delete('destroy/{id}', [ServiceLevelAgreementController::class, 'destroy']);
        });


        Route::prefix('settings/role')->group(function () {
            Route::get('show', [ViewItemsController::class, 'index']);
            Route::get('default-client', [ViewItemsController::class, 'defaultClientRole']);
            Route::get('default-agent', [ViewItemsController::class, 'defaultAgentRole']);
            Route::get('showsidebaritems', [ViewItemsController::class, 'fetchSidebarItems']);
            Route::get('showdashboarditems', [ViewItemsController::class, 'fetchDashboardItems']);
            Route::get('showsettingitems', [ViewItemsController::class, 'fetchSettingItems']);
            Route::get('showpageitems/{id}', [ViewItemsController::class, 'fetchPageDetails']);

            Route::post('store', [ViewItemsController::class, 'store']);
        });


        Route::prefix('/user')->group(function () {
            Route::get('userfetch', [UserLoginController::class, 'FetchUser']);
            // Route::post('userauthentication', [UserLoginController::class, 'Login']);
            Route::post('userlogout', [UserLoginController::class, 'Logout']);
            Route::post('userregister', [UserRegisterController::class, 'Register']);
            Route::post('change-password', [UserLoginController::class, 'changePassword']);
        });

        Route::prefix('settings/client')->group(function () {
            Route::get('user-serial/{id}', [UserRegisterController::class, 'getUserSerial']);
            Route::post('by-default-entity', [UserRegisterController::class, 'getClientsByDefaultEntity']);
            Route::get('show', [UserRegisterController::class, 'index']);
            Route::post('store', [UserRegisterController::class, 'store']);
            Route::post('entity/store', [UserRegisterController::class, 'insertClientNewEntity']);

            // Route::get('show/{id}', [UserRegisterController::class, 'clientShow']);
            Route::get('show/{id}/entityId/{businessId}', [UserRegisterController::class, 'editClient']);
            // Route::put('update/{id}', [UserRegisterController::class, 'update']);
            // Route::put('update/{id}', [UserRegisterController::class, 'updateClient']);
            Route::put('/update/{id}', [UserRegisterController::class, 'updateClient']);

            Route::delete('destroy/{id}', [UserRegisterController::class, 'destroy']);

            // Route::get('clients/{id}/edit', [UserRegisterController::class, 'editClient']);
            // Route::put('clients/{id}', [UserRegisterController::class, 'updateClient']);
        });

        Route::prefix('settings/agent')->group(function () {

            Route::get('show', [UserRegisterController::class, 'getAgent']);
            Route::get('options', [UserRegisterController::class, 'getAgentOptions']);
            Route::get('show/{id}', [UserRegisterController::class, 'agentShow']);
            Route::get('show/agents/by/team-of-default-business-entity/{id}', [UserRegisterController::class, 'getAgentByDefaultBusinessEntityTeam']);

            Route::get('show/byteam/{id}', [UserRegisterController::class, 'getAgentByTeam']);
            Route::put('update/{id}', [UserRegisterController::class, 'update']);

            Route::get('show-all', [UserRegisterController::class, 'getAgentAll']);
        });

        Route::prefix('settings/network-backbone')->group(function () {

            Route::get('element/show', [NetworkBackboneController::class, 'showElements']);
            Route::get('elements', [NetworkBackboneController::class, 'getNetworkBackboneElements']);
            Route::post('store/element', [NetworkBackboneController::class, 'storeElement']);
            Route::post('store/element-list', [NetworkBackboneController::class, 'storeElementList']);
        });

        Route::prefix('settings/branch')->group(function () {
            Route::get('show', [BranchController::class, 'show']);
            Route::post('store', [BranchController::class, 'store']);
            Route::get('edit/{id}', [BranchController::class, 'edit']); // Fetch branch for edit
            Route::put('update/{id}', [BranchController::class, 'update']); // Update branch
            Route::get('show/{id}', [BranchController::class, 'getBranch']);
        });



        Route::prefix('utility')->group(function () {
            Route::get('source/show', [UtilityController::class, 'getSource']);
            Route::get('status/show', [UtilityController::class, 'getStatus']);
            Route::get('cc-email/show', [UtilityController::class, 'getCcEmail']);
            Route::get('priority/show', [UtilityController::class, 'getPriority']);
            Route::post('reset-password', [UtilityController::class, 'resetPassword']);
            Route::post('client-reset-password', [UtilityController::class, 'clientResetPassword']);

            Route::get('sid/show', [UtilityController::class, 'getSID']);
            Route::get('/fetch-and-insert-users', [UtilityController::class, 'fetchAndInsert']);
        });

        Route::prefix('ticket')->group(function () {
          Route::get('procedure', [TicketController::class, 'getTicketFromProcedures']);
           Route::get('client-list/{businessEntityId}', [TicketController::class, 'getClientList']);
            Route::get('show', [TicketController::class, 'index']);
            Route::post('show/count', [TicketController::class, 'ticketCount']);

            // Route::get('show/by/status/entity/{status}/{entity}', [TicketController::class, 'getTicketByStatusAndDefaultEntity']);
            Route::post('show/by/status/entity', [TicketController::class, 'getTicketByStatusAndDefaultEntity']);
            Route::post('store', [TicketController::class, 'store']);
            Route::get('show/{id}', [TicketController::class, 'show']);
            Route::get('is-ticket-real/{id}', [TicketController::class, 'isTicketReal']);
            Route::get('details/show/{id}', [TicketController::class, 'ticketDetails']);

            Route::put('update/{id}', [TicketController::class, 'update']);
            Route::delete('destroy/{id}', [TicketController::class, 'destroy']);


            Route::post('store/self/ticket', [TicketController::class, 'storeSelfTicket']);
            Route::post('forward-to-hq', [TicketController::class, 'forwardToHQ']);
            Route::post('self/show', [TicketController::class, 'selfTicketShow']);
            Route::post('self-ticket-to-ticket', [TicketController::class, 'insertSelfTicketToTicket']);

            Route::get('/ticket-details-by-ticket/{id}', [TicketController::class, 'getTicketDetailsByTicket']);
            Route::get('branch-id/{id}', [TicketController::class, 'getBranchDetailsById']);


            //comment
            Route::post('comment/store', [TicketController::class, 'commentStore']);
            Route::get('comment/show/by/ticket/{id}', [TicketController::class, 'getCommentsByTicketNumber']);

            // chnaged status
            Route::get('status/change/by/status/ticket/{status}/{ticketNo}/{userId}', [TicketController::class, 'statusChanged']);
            Route::post('assign/team/store', [TicketController::class, 'assignTeamAndStore']);


            Route::get('status/changetoopen/by/status/ticket/{status}/{ticketNo}/{userId}', [TicketController::class, 'ticketReopened']);

            // 

            Route::get('assing/team/show/by/subcategory/{id}', [TicketController::class, 'getTeamBySubcategoryId']);

            Route::post('notification/comment/show', [TicketController::class, 'commentByUserTeam']);
            Route::get('violated/first/response/time/sla/{id}', [TicketController::class, 'violatedFirstResponseTime']);
            Route::get('violated/service/response/time/sla/{id}', [TicketController::class, 'violatedServiceResponseTime']);

            //backbone

            Route::get('backbone-elements', [BackboneController::class, 'getAllBackboneElements']);
            Route::get('backbone/element/list/by/{id}', [BackboneController::class, 'getBackboneElementListsByElementId']);
            Route::post('get-selected-client-id', [TicketController::class, 'getClientId']);
            Route::post('get-open-ticket-for-sid', [TicketController::class, 'getOpenTicketForSID']);
            Route::post('recently-open-and-closed-by', [TicketController::class, 'getRecentlyOpenAndClosedTicketForSID']);



            Route::post('get-ticket-list-for-merge', [TicketController::class, 'mergeTicketShowList']);
            Route::post('/merge-tickets', [TicketController::class, 'mergrTicketStore']);


            Route::get('/districts', [TicketController::class, 'getDistricts']);
            Route::get('/divisions', [TicketController::class, 'getDivisions']);
            Route::get('/aggregators', [TicketController::class, 'getAggregators']);
            Route::get('/branches', [TicketController::class, 'getBranches']);
            Route::get('/agents', [TicketController::class, 'getAgents']);
            Route::get('/events', [TicketController::class, 'fetchEvents']);
        });

        Route::prefix('reports')->group(function () {
            Route::post('statistics/show', [ReportsController::class, 'statisticsWithGraph']);
            Route::get('life/cycle/show', [ReportsController::class, 'ticketLifeCycle']);
            Route::get('top/complaint/show', [ReportsController::class, 'topComplaint']);
            Route::get('agent/performance/show', [ReportsController::class, 'agentPerformance']);
            Route::get('sla/violation/show', [ReportsController::class, 'slaViolation']);
            Route::post('/ticket-details', [ReportsController::class, 'getTicketDetails']);
            // Route::get('/ticket-details', [ReportsController::class, 'getTicketDetails']);
            Route::get('/get-local-clients-by-business-entity/{id}', [ReportsController::class, 'getLocalClientsByBusinessEntityId']);

            Route::post('new-details', [ReportsController::class, 'getNewTicketReports']);
            Route::post('agent-performance', [ReportsController::class, 'getAgentPerformance']);

            Route::post('agent-performance-details', [ReportsController::class, 'getAgentPerformanceDetails']);
        });

        Route::prefix('dashboard')->group(function () {
            Route::post('summary/show', [DashboardController::class, 'summary']);
            Route::post('last/thirty/days', [DashboardController::class, 'dashboardLast30Days']);
            Route::post('statistics/show/by/department', [DashboardController::class, 'departmentReportForDashboard']);
            Route::post('statistics/show/by/division', [DashboardController::class, 'divisionReportForDashboard']);
            Route::post('statistics/show/by/team', [DashboardController::class, 'teamReportForDashboard']);
            Route::post('statistics/subcategory-vs-created/show', [DashboardController::class, 'subcategoryVsCreated']);

            Route::post('statistics/graph/by/team', [DashboardController::class, 'teamTicketDetailsGraph']);
            Route::get('all-business-entity-open-ticket', [DashboardController::class, 'getOpenTicketCountByBusinessEntity']);
            Route::post('business-entity-ticket-summary', [DashboardController::class, 'getTicketSummaryByBusinessEntity']);
            Route::post('ticket-count-details', [DashboardController::class, 'getTeamTicketDetails']);
            Route::post('ticket-customer-client-wise', [DashboardController::class, 'getTicketCountByClientCustomer']);
            Route::get('ticket-count-breakdown/team-wise', [DashboardController::class, 'getTicketCountAndAvgTimeByTeam']);
            Route::get('ticket-count-breakdown/team-wise-own', [DashboardController::class, 'getTicketCountAndAvgTimeByTeamOwnEntity']);
            Route::get('ticket-count/entity-wise-own', [DashboardController::class, 'getTicketCountByOwnEntity']);
            Route::post('team-vs-business-entity-ticket-count-details', [DashboardController::class, 'getTeamVsBusinessEntityTicketCountDetails']);
            Route::post('team-vs-business-entity-ticket-count-details-by-business-entity-id', [DashboardController::class, 'getTeamVsBusinessEntityTicketCountDetailsByBusinessEntityId']);
            Route::post('team-vs-own-business-entity-ticket-count-details', [DashboardController::class, 'getTeamVsOwnBusinessEntityTicketCountDetails']);
            Route::get('sla-statistics-team-wise', [DashboardController::class, 'getTeamWiseSLAstatistics']);
        });

        Route::prefix('prismerp')->group(function () {
            Route::get('dhakacolo/customers', [UserLoginController::class, 'dhakaColoCustomers']);
            Route::get('dhakacolo/customers/{id}', [UserLoginController::class, 'dhakaColoCustomerDetails']);

            Route::get('earth/customers', [UserLoginController::class, 'earthCustomers']);
            Route::get('earth/customers/{id}', [UserLoginController::class, 'earthCustomerDetails']);

            Route::get('race/customers', [UserLoginController::class, 'raceCustomers']);
            Route::get('race/customers/{id}', [UserLoginController::class, 'raceCustomerDetails']);
        });


        Route::prefix('settings/aggregator')->group(function () {
            Route::post('store', [AggregatorController::class, 'store']);
            Route::get('show', [AggregatorController::class, 'index']);
            Route::get('edit/{id}', [AggregatorController::class, 'edit']);
            Route::put('update/{id}', [AggregatorController::class, 'update']);
            Route::delete('delete/{id}', [AggregatorController::class, 'destroy']);
        });



        

        Route::prefix('settings/client-aggregator-mapping')->group(function () {
            Route::post('store', [ClientAggregatorMappingController::class, 'store']);
            Route::get('show', [ClientAggregatorMappingController::class, 'index']);
            Route::get('edit/{id}', [ClientAggregatorMappingController::class, 'edit']);
            Route::put('update/{id}', [ClientAggregatorMappingController::class, 'update']);
            Route::delete('delete/{id}', [ClientAggregatorMappingController::class, 'destroy']);
            Route::get('list', [ClientAggregatorMappingController::class, 'fetchAggregatorClientMapping']);
        });

        Route::prefix('super-app')->group(function () {
            Route::get('companies/{company}/business-divisions/{division}/categories', [SuperappController::class, 'categories']);
            Route::get(
            'companies/{company}/business-divisions/{division}/categories/{category}/subcategories',
            [SuperappController::class, 'subcategories']);

            // priorities
            Route::get('priorities', [SuperappController::class, 'priorities']);

            // tickets
            Route::post('tickets', [SuperappController::class, 'storeTicket']);
            Route::get('tickets/{sid}', [SuperappController::class, 'showTicket']);
            Route::get('filter-tickets', [SuperappController::class, 'filterTickets']);


        });


       



        Route::post('test-sms', [UtilityController::class, 'sendSmsTest']);
    });



});



        












Route::get('/clear-cache', function () {
    // dd('check');
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    return redirect()->back();
});


Route::get('/test-redis', function () {
    $key = 'test_key';
    $value = Cache::remember($key, 60, function () {
        return 'This is a test value from Redis cache.';
    });
    return response()->json(['data' => $value]);
});
