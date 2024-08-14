<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

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
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/book', 'Home::book');

$routes->get('/login', 'Auth::logins');
$routes->post('/login', 'Auth::login');

$routes->get('/register', 'Auth::registers');
$routes->post('/register', 'Auth::register');

$routes->match(['get', 'post'], '/logout', 'Auth::logout');

$routes->get('membersloan/new/members/search', 'Loans::searchMember');
$routes->get('membersloan/new/books/search', 'Loans::searchBook');
$routes->post('membersloan/new', 'Loans::new');
$routes->resource('membersloan', ['controller' => 'Loans']);

$routes->get('membersreturn/new/search', 'Returns::searchLoan');
$routes->resource('membersreturn', ['controller' => 'Returns']);

$routes->resource('membersbook', ['controller' => 'Books']);

$routes->resource('membershistory', ['controller' => 'History']);

$routes->get('/', 'Dashboard\DashboardController');
$routes->get('dashboard', 'Dashboard\DashboardController::dashboard');

$routes->get('reports/report', 'Books\PrintReportController::report');
$routes->get('reports/print_report', 'Books\PrintReportController::printReport');
$routes->resource('printreport', ['controller' => 'Books\PrintReportController']);

$routes->get('reports/loans', 'Loans\LoansController::reportLoans');
$routes->get('reports/report_loans', 'Loans\LoansController::printReportLoans');

$routes->get('reports/returns', 'Loans\ReturnsController::reportReturns');
$routes->get('reports/report_returns', 'Loans\ReturnsController::printReportReturns');

$routes->get('reports/fines', 'Loans\FinesController::reportFines');
$routes->get('reports/report_fines', 'Loans\FinesController::printReportFines');

$routes->get('reports/book_category', 'Loans\LoansController::printBookCategoryStatistics');
$routes->get('reports/print_book_category', 'Loans\LoansController::printBookCategory');
    
$routes->get('reports/statistics', 'Loans\LoansController::statisticsMembers');
$routes->get('reports/print_statistics', 'Loans\LoansController::printStatistics');
    
$routes->get('statisticsloan/statistics', 'Loans\LoansController::statistics');

$routes->get('reports/book_rack', 'Loans\LoansController::bookRackStatistics');
$routes->get('reports/print_book_rack', 'Loans\LoansController::printBookRack');

$routes->get('reports/users', 'Users\UsersController::reportUsers');
$routes->get('reports/print_users', 'Users\UsersController::printReportUsers');

$routes->put('facilitys/(:num)', 'Users\FacilitysController::update/$1');
$routes->post('facilitys/new', 'Users\FacilitysController::new');
$routes->get('facilitys/report_facilitys', 'Users\FacilitysController::printReportFacilitys');
$routes->resource('facilitys', ['controller' => 'Users\FacilitysController']);

$routes->put('performances/(:num)', 'Users\PerformancesController::update/$1');
$routes->post('performances/new', 'Users\PerformancesController::new');
$routes->get('performances/report_performances', 'Users\PerformancesController::printReportPerformances');
$routes->resource('performances', ['controller' => 'Users\PerformancesController']);

$routes->get('filtersrack/book_category', 'Loans\LoansController::bookCategoryStatistics');

$routes->get('loans/approve/(:any)', 'Loans\LoansController::approve/$1');
$routes->get('loans/reject/(:any)', 'Loans\LoansController::reject/$1');

$routes->get('returns/approve/(:any)', 'Loans\ReturnsController::approve/$1');
$routes->get('returns/reject/(:any)', 'Loans\ReturnsController::reject/$1');

$routes->get('loans/notice', 'Loans\LoansController::notice');


$routes->resource('members', ['controller' => 'Members\MembersController']);
$routes->resource('books', ['controller' => 'Books\BooksController']);
$routes->resource('categories', ['controller' => 'Books\CategoriesController']);
$routes->resource('racks', ['controller' => 'Books\RacksController']);

$routes->get('loans/new/members/search', 'Loans\LoansController::searchMember');
$routes->get('loans/new/books/search', 'Loans\LoansController::searchBook');
$routes->post('loans/new', 'Loans\LoansController::new');
$routes->resource('loans', ['controller' => 'Loans\LoansController']);

$routes->get('returns/new/search', 'Loans\ReturnsController::searchLoan');
$routes->resource('returns', ['controller' => 'Loans\ReturnsController']);

$routes->get('fines/returns/search', 'Loans\FinesController::searchReturn');
$routes->get('fines/pay/(:any)', 'Loans\FinesController::pay/$1');
$routes->resource('fines', ['controller' => 'Loans\FinesController']);

$routes->put('users/(:num)', 'Users\UsersController::update/$1');
$routes->post('users/new', 'Users\UsersController::new');
$routes->resource('users', ['controller' => 'Users\UsersController']);

// $routes->put('mobil/(:num)', 'Mobil::update/$1');
// $routes->post('mobil/new', 'Mobil::new');
// $routes->resource('mobil', ['controller' => 'Mobil']);

// $routes->get('reports/mobil', 'Mobil::report_mobil');
// $routes->get('reports/report_mobil', 'Mobil::printReportMobil');

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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
