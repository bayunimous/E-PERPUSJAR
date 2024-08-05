<?php

namespace App\Controllers\Users;

use App\Models\PerformanceModel;
use App\Models\ReportModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;

class PerformancesController extends BaseController
{
    protected PerformanceModel $performanceModel;
    protected UserModel $userModel;
    protected ReportModel $reportModel;

    public function __construct()
    {
        $this->performanceModel = new PerformanceModel;
        $this->userModel = new UserModel;
        $this->reportModel = new ReportModel;
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

        $addDateFrom = $this->request->getVar('add_date_from');
        $addDateTo = $this->request->getVar('add_date_to');

        $performancesQuery = $this->performanceModel
                ->select('users.*, users.id as user_id, performances.*')
                ->join('users', 'performances.user_id = users.id', 'LEFT');

        if ($keyword = $this->request->getGet('search')) {
            $performancesQuery->like('full_name', $keyword, insensitiveSearch: true)
                ->orLike('rating', $keyword, insensitiveSearch: true)
                ->orLike('description', $keyword, insensitiveSearch: true);
        }

        if ($addDateFrom && $addDateTo) {
            $performancesQuery->where('performances.created_at >=', date('Y-m-d', strtotime($addDateFrom)))
                ->where('performances.created_at <=', date('Y-m-d', strtotime($addDateTo)));
        }

        $performances = $performancesQuery->paginate($itemPerPage, 'performances');

        $data = [
            'performances'      => $performances,
            'pager'             => $this->performanceModel->pager,
            'currentPage'       => $this->request->getVar('page_categories') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'search'            => $this->request->getGet('search'),
            'user'              => $this->base_data['user'],
            'user'              => $this->base_data['user'],
            'addDateFrom'       => $addDateFrom,
            'addDateTo'         => $addDateTo
        ];

        return view('performances/index', $data);
    }

    public function printReportPerformances($format = 'pdf')
    {
        $addDateFrom = $this->request->getVar('add_date_from');
        $addDateTo = $this->request->getVar('add_date_to');

        $performancesQuery = $this->performanceModel
                ->select('users.*, users.id as user_id, performances.*')
                ->join('users', 'performances.user_id = users.id', 'LEFT');

        if ($addDateFrom && $addDateTo) {
            $performancesQuery->where('performances.created_at >=', date('Y-m-d', strtotime($addDateFrom)))
                ->where('performances.created_at <=', date('Y-m-d', strtotime($addDateTo)));
        }

        $performances = $performancesQuery->findAll();

        $kepdin = $this->userModel->where('role', 'Kepala Dinas')->first();

        $this->reportModel->save([
            'user_id'       => $this->base_data['user']['id'],
            'description'   => 'Cetak Pelayanan Petugas'
        ]);

        if ($format === 'html') {
            return view('performances/report_performances', ['performances' => $performances, 'kepdin' => $kepdin]);
        } elseif ($format === 'pdf') {
            $pdfView = view('performances/report_performances', ['performances' => $performances, 'kepdin' => $kepdin]);

            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream("Laporan Data Pelayanan Petugas E-PERPUSJAR");
        }

        return view('performances/report_performances', ['performances' => $performances, 'kepdin' => $kepdin]);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $staffs = $this->userModel->where('role', 'Petugas')->findAll();

        return view('performances/create', [
            'validation' => \Config\Services::validation(),
            'staffs'     => $staffs,
            'user'       => $this->base_data['user']
        ]);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $staffs = $this->userModel->where('role', 'Petugas')->findAll();

        if (!$this->validate([
            'user_id'       => 'required|numeric',
            'rating'        => 'required|numeric',
            'description'   => 'required|max_length[100]'
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'staffs'     => $staffs,
                'user'       => $this->base_data['user']
            ];

            return view('performances/create', $data);
        }

        if (!$this->performanceModel->save([
            'user_id'       => $this->request->getVar('user_id'),
            'rating'        => $this->request->getVar('rating'),
            'description'   => $this->request->getVar('description')
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'staffs'     => $staffs,
                'user'       => $this->base_data['user']
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('performances/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new performance successful']);
        return redirect()->to('performances');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $performances = $this->performanceModel->where('id', $id)->first();
        
        $staffs = $this->userModel->where('role', 'Petugas')->findAll();

        if (empty($performances)) {
            throw new PageNotFoundException('Performance not found');
        }

        $data = [
            'performances'   => $performances,
            'staffs'         => $staffs,
            'validation'     => \Config\Services::validation(),
            'user'           => $this->base_data['user']
        ];

        return view('performances/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $performances = $this->performanceModel->where('id', $id)->first();

        $staffs = $this->userModel->where('role', 'Petugas')->findAll();

        if (empty($performances)) {
            throw new PageNotFoundException('Performance not found');
        }

        $rules = [
            'user_id'       => 'required|numeric',
            'rating'        => 'required|numeric',
            'description'   => 'required|max_length[100]'
        ];

        if ($this->request->getVar('password')) {
            $rules['password'] = 'required|min_length[6]|max_length[50]';
        }

        if (!$this->validate($rules)) {
            $data = [
                'performances'  => $performances,
                'staffs'        => $staffs,
                'validation'    => \Config\Services::validation(),
                'oldInput'      => $this->request->getVar(),
                'user'          => $this->base_data['user']
            ];
    
            return view('performances/edit', $data);
        }

        $data = [
            'id'            => $id,
            'user_id'       => $this->request->getVar('user_id'),
            'rating'        => $this->request->getVar('rating'),
            'description'   => $this->request->getVar('description')
        ];

        if ($this->request->getVar('password')) {
            $data['password'] = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
        }

        if (!$this->performanceModel->save($data)) {
            $data = [
                'performances'  => $performances,
                'staffs'        => $staffs,
                'validation'    => \Config\Services::validation(),
                'oldInput'      => $this->request->getVar(),
                'user'          => $this->base_data['user']
            ];
    
            session()->setFlashdata(['msg' => 'Update failed']);
            return view('performances/edit', $data);
        }

        session()->setFlashdata(['msg' => 'Update performance successful']);
        return redirect()->to('performances');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $performance = $this->performanceModel->where('id', $id)->first();

        if (empty($performance)) {
            throw new PageNotFoundException('Performance not found');
        }

        if (!$this->performanceModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete performance', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'Performance deleted successfully']);
        return redirect()->to('performances');
    }
}
