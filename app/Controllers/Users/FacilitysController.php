<?php

namespace App\Controllers\Users;

use App\Models\FacilityModel;
use App\Models\ReportModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;

class FacilitysController extends BaseController
{
    protected FacilityModel $facilityModel;
    protected ReportModel $reportModel;

    public function __construct()
    {
        $this->facilityModel = new FacilityModel;
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

        $facilitysQuery = $this->facilityModel;

        if ($keyword = $this->request->getGet('search')) {
            $facilitysQuery->like('title', $keyword, insensitiveSearch: true)
                ->orLike('description', $keyword, insensitiveSearch: true);
        }

        if ($addDateFrom && $addDateTo) {
            $facilitysQuery->where('facilitys.created_at >=', date('Y-m-d', strtotime($addDateFrom)))
                ->where('facilitys.created_at <=', date('Y-m-d', strtotime($addDateTo)));
        }

        $facilitys = $facilitysQuery->paginate($itemPerPage, 'facilitys');

        $data = [
            'facilitys'         => $facilitys,
            'pager'             => $this->facilityModel->pager,
            'currentPage'       => $this->request->getVar('page_categories') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'search'            => $this->request->getGet('search'),
            'user'              => $this->base_data['user'],
            'user'              => $this->base_data['user'],
            'addDateFrom'       => $addDateFrom,
            'addDateTo'         => $addDateTo
        ];

        return view('facilitys/index', $data);
    }

    public function printReportFacilitys($format = 'pdf')
    {
        $addDateFrom = $this->request->getVar('add_date_from');
        $addDateTo = $this->request->getVar('add_date_to');

        $facilitysQuery = $this->facilityModel;

        if ($addDateFrom && $addDateTo) {
            $facilitysQuery->where('facilitys.created_at >=', date('Y-m-d', strtotime($addDateFrom)))
                ->where('facilitys.created_at <=', date('Y-m-d', strtotime($addDateTo)));
        }

        $facilitys = $facilitysQuery->findAll();

        $kepdin = $this->userModel->where('role', 'Kepala Dinas')->first();

        $this->reportModel->save([
            'user_id'       => $this->base_data['user']['id'],
            'description'   => 'Cetak Penggunaan Fasilitas'
        ]);

        if ($format === 'html') {
            return view('facilitys/report_facilitys', ['facilitys' => $facilitys, 'kepdin' => $kepdin]);
        } elseif ($format === 'pdf') {
            $pdfView = view('facilitys/report_facilitys', ['facilitys' => $facilitys, 'kepdin' => $kepdin]);

            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream("Laporan Data Penggunaan Fasilitas E-PERPUSJAR");
        }

        return view('facilitys/report_facilitys', ['facilitys' => $facilitys, 'kepdin' => $kepdin]);
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

        return view('facilitys/create', [
            'validation' => \Config\Services::validation(),
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

        if (!$this->validate([
            'title'         => 'required|max_length[100]',
            'description'   => 'required|max_length[100]'
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            return view('facilitys/create', $data);
        }

        if (!$this->facilityModel->save([
            'title'         => $this->request->getVar('title'),
            'description'   => $this->request->getVar('description')
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('facilitys/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new facility successful']);
        return redirect()->to('facilitys');
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

        $facilitys = $this->facilityModel->where('id', $id)->first();

        if (empty($facilitys)) {
            throw new PageNotFoundException('Facility not found');
        }

        $data = [
            'facilitys'      => $facilitys,
            'validation'     => \Config\Services::validation(),
            'user'           => $this->base_data['user']
        ];

        return view('facilitys/edit', $data);
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

        $facilitys = $this->facilityModel->where('id', $id)->first();

        if (empty($facilitys)) {
            throw new PageNotFoundException('Facility not found');
        }

        $rules = [
            'title'         => 'required|max_length[100]',
            'description'   => 'required|max_length[100]'
        ];

        if ($this->request->getVar('password')) {
            $rules['password'] = 'required|min_length[6]|max_length[50]';
        }

        if (!$this->validate($rules)) {
            $data = [
                'facilitys'  => $facilitys,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];
    
            return view('facilitys/edit', $data);
        }

        $data = [
            'id'            => $id,
            'title'         => $this->request->getVar('title'),
            'description'   => $this->request->getVar('description')
        ];

        if ($this->request->getVar('password')) {
            $data['password'] = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
        }

        if (!$this->facilityModel->save($data)) {
            $data = [
                'facilitys'  => $facilitys,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];
    
            session()->setFlashdata(['msg' => 'Update failed']);
            return view('facilitys/edit', $data);
        }

        session()->setFlashdata(['msg' => 'Update facility successful']);
        return redirect()->to('facilitys');
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

        $facility = $this->facilityModel->where('id', $id)->first();

        if (empty($facility)) {
            throw new PageNotFoundException('Facility not found');
        }

        if (!$this->facilityModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete facility', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'Facility deleted successfully']);
        return redirect()->to('facilitys');
    }
}
