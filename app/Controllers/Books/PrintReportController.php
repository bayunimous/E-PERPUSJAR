<?php

namespace App\Controllers\Books;

use App\Models\ReportModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;

class PrintReportController extends BaseController
{
    protected ReportModel $reportModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->reportModel = new ReportModel;
        $this->userModel = new UserModel;
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $itemPerPage = 20;

        if ($this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $reports = $this->reportModel
                ->select('users.*, users.id as user_id, reports.*')
                ->join('users', 'reports.user_id = users.id', 'LEFT')
                ->like('full_name', $keyword, insensitiveSearch: true)
                ->orLike('role', $keyword, insensitiveSearch: true)
                ->orLike('description', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'reports');
        } else {
            $reports = $this->reportModel
                ->select('users.*, users.id as user_id, reports.*')
                ->join('users', 'reports.user_id = users.id', 'LEFT')
                ->paginate($itemPerPage, 'reports');
        }

        $data = [
            'reports'       => $reports,
            'pager'         => $this->reportModel->pager,
            'currentPage'   => $this->request->getVar('page_reports') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'search'        => $this->request->getGet('search'),
            'user'          => $this->base_data['user']
        ];

        return view('printreport/index', $data);
    }

    public function report()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $itemPerPage = 20;

        $printDateFrom = $this->request->getVar('print_date_from');
        $printDateTo = $this->request->getVar('print_date_to');

        $reportsQuery = $this->reportModel
                ->select('users.*, users.id as user_id, reports.*')
                ->join('users', 'reports.user_id = users.id', 'LEFT');

        if ($keyword = $this->request->getGet('search')) {
            $reportsQuery->groupStart()
                ->like('full_name', $keyword, 'both', true)
                ->orLike('role', $keyword, 'both', true)
                ->orLike('description', $keyword, 'both', true)
                ->groupEnd();
        }

        if ($printDateFrom && $printDateTo) {
            $reportsQuery->where('reports.created_at >=', date('Y-m-d 00:00:00', strtotime($printDateFrom)))
                ->where('reports.created_at <=', date('Y-m-d 23:59:59', strtotime($printDateTo)));
        }

        $reports = $reportsQuery->paginate($itemPerPage, 'reports');

        $data = [
            'reports'       => $reports,
            'pager'         => $this->reportModel->pager,
            'currentPage'   => $this->request->getVar('page_reports') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'search'        => $this->request->getGet('search'),
            'user'          => $this->base_data['user'],
            'printDateFrom' => $printDateFrom,
            'printDateTo'   => $printDateTo,
        ];

        return view('reports/report', $data);
    }

    public function printReport($format = 'pdf')
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $itemPerPage = 20;

        $printDateFrom = $this->request->getVar('print_date_from');
        $printDateTo = $this->request->getVar('print_date_to');

        $reportsQuery = $this->reportModel
                ->select('users.*, users.id as user_id, reports.*')
                ->join('users', 'reports.user_id = users.id', 'LEFT');

        if ($keyword = $this->request->getGet('search')) {
            $reportsQuery->groupStart()
                ->like('full_name', $keyword, 'both', true)
                ->orLike('role', $keyword, 'both', true)
                ->orLike('description', $keyword, 'both', true)
                ->groupEnd();
        }

        if ($printDateFrom && $printDateTo) {
            $reportsQuery->where('reports.created_at >=', date('Y-m-d 00:00:00', strtotime($printDateFrom)))
                ->where('reports.created_at <=', date('Y-m-d 23:59:59', strtotime($printDateTo)));
        }

        $reports = $reportsQuery->findAll();

        $kepdin = $this->userModel->where('role', 'Kepala Dinas')->first();

        $this->reportModel->save([
            'user_id'       => $this->base_data['user']['id'],
            'description'   => 'Cetak Peminjaman'
        ]);

        if ($format === 'html') {
            return view('reports/print_report', ['reports' => $reports, 'kepdin' => $kepdin]);
        } elseif ($format === 'pdf') {
            $pdfView = view('reports/print_report', ['reports' => $reports, 'kepdin' => $kepdin]);

            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream("Laporan Data Cetak Laporan Dicetak E-PERPUSJAR");
        }

        return view('reports/print_report', ['reports' => $reports, 'kepdin' => $kepdin]);

        return view('reports/report', $data);
    }
}