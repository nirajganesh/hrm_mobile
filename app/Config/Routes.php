<?php

namespace Config;

use App\Controllers\Api\ApiController;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) 
{
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
//$routes->put('showInvoice/(:num)', 'Home::showinvoice/$1');
//$route['showInvoice/(:any)'] = 'Home/showinvoice/$1';


$myroutes=[];
$myroutes['showInvoice/(:any)/(:any)']='Home::showinvoice/$1/$1';
$routes->map($myroutes);


$invoiceroutes=[];
$routes->get('invoice/(:any)/(:any)', 'Home::payslip_invoice/$1/$1');
$routes->map($invoiceroutes);


$quotation_routes=[];
$routes->get('quotation_invoice/(:any)/(:any)', 'Home::quotation_invoice/$1/$1');
$routes->map($quotation_routes);


$payment_routes=[];
$routes->get('invoice_payment/(:any)','Home::invoice_payment/$1');
$routes->map($payment_routes);


$routes->group("api",["namespace" =>"App\Controllers\Api"],function($routes){

    // ---------------------- Login ----------------------
    
    $routes->post('login','ApiController::loginUser');
    $routes->post('register','ApiController::registerUser');

    // ---------------Dashboard --------------------------

    $routes->get('dashboard','ApiController::dashboardList');

    // ---------------Employee ----------------------------
   
    $routes->get('employee-list','ApiController::listEmployee');
    $routes->get('addition-list','ApiController::listaddition');
    $routes->post('add-employee','ApiController::addEmployee');
    $routes->put('edit-address/(:num)','ApiController::editAddress/$1');
    $routes->put('edit-personalinfo/(:num)','ApiController::editPersonalinfo/$1');
    $routes->put('edit-bankinfo/(:num)','ApiController::editBankinfo/$1');
    $routes->put('edit-document/(:num)','ApiController::editDocument/$1');
    $routes->put('edit-socialmedia/(:num)','ApiController::editSocialmedia/$1');
    $routes->put('edit-salary/(:any)','ApiController::editSalary/$1');
    $routes->put('edit-password/(:num)','ApiController::editPassword/$1');
    $routes->put('update-employee/(:num)','ApiController::updateEmployee/$1');
    $routes->delete('delete-employee/(:num)','ApiController::deleteEmployee/$1');

   // ----------------Holiday ------------------------------------------------
    $routes->get('holiday-list','ApiController::listHoliday');
    $routes->post('add-holiday','ApiController::addHoliday');
    $routes->delete('delete-holiday/(:num)','ApiController::deleteHoliday/$1');
 
    //-------------------Todo --------------------------------------------------

    $routes->get('todo-list','ApiController::listTodo');
    $routes->post('add-todo','ApiController::addTodo');
    $routes->delete('delete-todo/(:num)','ApiController::deleteTodo/$1');

    // --------------------Department -----------------------

    $routes->get('department-list','ApiController::listDepartment');
    $routes->post('add-department','ApiController::addDepartment');
    $routes->put('update-department/(:num)','ApiController::updateDepartment/$1');
    $routes->delete('delete-department/(:num)','ApiController::deleteDepartment/$1');

    //--------------------Designation ----------------------------

    $routes->get('designation-list','ApiController::listDesignation');
    $routes->post('add-designation','ApiController::addDesignation');
    $routes->put('update-designation/(:num)','ApiController::updateDesignation/$1');
    $routes->delete('delete-designation/(:num)','ApiController::deleteDesignation/$1');

    //--------------------Client ----------------------------

    $routes->get('client-list','ApiController::listClient');
    $routes->post('add-client','ApiController::addClient');
    $routes->put('update-client/(:num)','ApiController::updateClient/$1');
    $routes->delete('delete-client/(:num)','ApiController::deleteClient/$1');

    //-----------------------Services_category------------------------

    $routes->get('service-category-list','ApiController::listServices_category_list');
    $routes->post('add-service-category','ApiController::addServices_category');
    $routes->put('update-service-category/(:num)','ApiController::updateServices_category/$1');
    $routes->delete('delete-service-category/(:num)','ApiController::deleteServices_category/$1');

    //----------------------- Settings ---------------------------------------
    $routes->get('settings-list','ApiController::listSettings');
    $routes->put('update-settings/(:num)','ApiController::updateSettings/$1');

    //------------------------ Services -------------------------------------
    $routes->get('services-list','ApiController::listServices');
    $routes->post('add-services','ApiController::addServices');
    $routes->put('update-services/(:num)','ApiController::updateServices/$1');
    $routes->delete('delete-services/(:num)','ApiController::deleteServices/$1');

    //------------------------- Proposals -------------------------------------

    $routes->get('proposals-list','ApiController::listProposals');
    $routes->post('add-proposals','ApiController::addProposals');
    $routes->put('update-proposals/(:num)','ApiController::updateProposals/$1');
    $routes->delete('delete-proposals/(:num)','ApiController::deleteProposals/$1');
 

    //---------------------------- Summary ----------------------------------------
    
    $routes->get('summary-list','ApiController::listSummary');
    $routes->delete('delete-summary/(:num)','ApiController::deleteSummary/$1');
    $routes->put('service_summary-list/(:num)','ApiController::listService_Summary/$1');
    $routes->post('add-service_summary','ApiController::addService_summary');
    $routes->put('update-service_summary/(:num)','ApiController::updateSummary/$1');
    $routes->get('listQuatation_last','ApiController::listQuatation_last');
    $routes->put('listQuotation_item_service/(:num)','ApiController::listQuotation_item_service/$1');
    
    //---------------------------- Salary_type ----------------------------------------
     $routes->get('salary_type-list','ApiController::listSalary_type');


    //----------------------------- Expenses --------------------------------------------
    $routes->get('expenses-list','ApiController::listExpenses');
    $routes->post('add-expenses','ApiController::addExpenses');
    $routes->put('update-expenses/(:num)','ApiController::updateExpenses/$1');
    $routes->delete('delete-expenses/(:num)','ApiController::deleteExpenses/$1');


    //----------------------------- Payroll type ----------------------------------------
    $routes->get('payroll_type-list','ApiController::listPayroll_type');
    $routes->post('add-payroll_type','ApiController::addPayroll_type');
    $routes->put('update-payroll_type/(:num)','ApiController::updatePayroll_type/$1');
    $routes->delete('delete-payroll_type/(:num)','ApiController::deletePayroll_type/$1');


    //----------------------------- Payroll List-------------------------------------------
    $routes->get('payroll-list','ApiController::listPayroll_list');
    $routes->post('add-payroll','ApiController::addPayroll_list');
    $routes->put('generate-payslip/(:num)','ApiController::generate_payslip/$1');
    $routes->put('update-payroll/(:num)','ApiController::updatePayroll_list/$1');
    $routes->delete('delete-payroll/(:num)','ApiController::deletePayroll_list/$1');


    //---------------------------Quatation ----------------------------------------------
    $routes->get('quatation-list','ApiController::listQuatation');
    $routes->post('add-quatation','ApiController::addQuatation');
    $routes->put('update-quatation/(:any)','ApiController::updateQuatation/$1');
    $routes->delete('delete-quatation/(:num)','ApiController::deleteQuatation/$1');
    $routes->put('quotation_item-list/(:num)','ApiController::listQuotation_item/$1');


     //---------------------------Payment ----------------------------------------------
     $routes->get('payment-list','ApiController::listPayment');
     $routes->post('add-payment','ApiController::addPayment');
     $routes->put('update-payment/(:num)','ApiController::updatePayment/$1');
     $routes->delete('delete-payment/(:num)','ApiController::deletePayment/$1');
     $routes->get('listpayment_last','ApiController::listpayment_last');
     $routes->put('invoice_client/(:num)','ApiController::invoice_client/$1');


     //---------------------------Sales Report ----------------------------------------------
     $routes->get('sales-list','ApiController::listSales');
    

    //--------------------------- Invoice ----------------------------------------------
     $routes->get('invoice-list','ApiController::listInvoice');
     $routes->post('add-invoice','ApiController::addInvoice');
     $routes->put('update-invoice/(:num)','ApiController::updateInvoice/$1');
     $routes->delete('delete-invoice/(:num)','ApiController::deleteInvoice/$1');
     $routes->put('invoice_item_list/(:num)','ApiController::listInvoice_item/$1');
     $routes->get('listInvoice_last','ApiController::listInvoice_last');
     $routes->get('listfinal_Invoice','ApiController::listfinal_Invoice');
    
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
