<?php

namespace App\Controllers\Users;

use App\Models\UserModel;
use App\Models\ReportModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;

class UsersController extends BaseController
{
    protected UserModel $userModel;
    protected ReportModel $reportModel;

    public function __construct()
    {
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

        if ($this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $users = $this->userModel
                ->like('full_name', $keyword, insensitiveSearch: true)
                ->orLike('email', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'users');

            $users = array_filter($users, function ($user) {
                return $user['deleted_at'] == null;
            });
        } else {
            $users = $this->userModel->paginate($itemPerPage, 'users');
        }

        $data = [
            'users'             => $users,
            'pager'             => $this->userModel->pager,
            'currentPage'       => $this->request->getVar('page_categories') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'search'            => $this->request->getGet('search'),
            'user'              => $this->base_data['user']
        ];

        return view('users/index', $data);
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

        return view('users/create', [
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
            'nip'           => 'required',
            'full_name'     => 'required|max_length[100]',
            'email'         => 'required|valid_email|max_length[255]',
            'phone'         => 'required',
            'username'      => 'required|min_length[6]|max_length[50]',
            'password'      => 'required|min_length[6]|max_length[50]',
            'role'          => 'required'
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            return view('users/create', $data);
        }

        $nip = $this->request->getVar('nip');
        $email = $this->request->getVar('email');
        $phone = $this->request->getVar('phone');
        $username = $this->request->getVar('username');

        $errors = [];

        if ($this->userModel->where('nip', $nip)->first()) {
            $errors[] = 'NIP is already in use.';
        }

        if ($this->userModel->where('email', $email)->first()) {
            $errors[] = 'Email is already in use.';
        }

        if ($this->userModel->where('phone', $phone)->first()) {
            $errors[] = 'Phone is already in use.';
        }

        if ($this->userModel->where('username', $username)->first()) {
            $errors[] = 'Username is already in use.';
        }

        if (!empty($errors)) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            session()->setFlashdata('errors', $errors);
            return view('users/create', $data);
        }

        if (!$this->userModel->save([
            'nip'       => $this->request->getVar('nip'),
            'full_name' => $this->request->getVar('full_name'),
            'email'     => $this->request->getVar('email'),
            'phone'     => $this->request->getVar('phone'),
            'username'  => $this->request->getVar('username'),
            'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'      => $this->request->getVar('role')
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('users/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new user successful']);
        return redirect()->to('users');
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

        $users = $this->userModel->where('id', $id)->first();

        if (empty($users)) {
            throw new PageNotFoundException('User not found');
        }

        $data = [
            'users'          => $users,
            'validation'     => \Config\Services::validation(),
            'user'           => $this->base_data['user']
        ];

        return view('users/edit', $data);
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

        $users = $this->userModel->where('id', $id)->first();

        if (empty($users)) {
            throw new PageNotFoundException('User not found');
        }

        $rules = [
            'nip'       => 'required',
            'full_name' => 'required|max_length[100]',
            'email'     => 'required|valid_email|max_length[255]',
            'phone'     => 'required',
            'username'  => 'required|min_length[6]|max_length[50]',
            'role'      => 'required'
        ];

        if ($this->request->getVar('password')) {
            $rules['password'] = 'required|min_length[6]|max_length[50]';
        }

        if (!$this->validate($rules)) {
            $data = [
                'users'      => $users,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];
    
            return view('users/edit', $data);
        }

        $data = [
            'id'        => $id,
            'nip'       => $this->request->getVar('nip'),
            'full_name' => $this->request->getVar('full_name'),
            'email'     => $this->request->getVar('email'),
            'phone'     => $this->request->getVar('phone'),
            'username'  => $this->request->getVar('username'),
            'role'      => $this->request->getVar('role')
        ];

        if ($this->request->getVar('password')) {
            $data['password'] = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
        }

        if (!$this->userModel->save($data)) {
            $data = [
                'users'       => $users,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];
    
            session()->setFlashdata(['msg' => 'Update failed']);
            return view('users/edit', $data);
        }

        session()->setFlashdata(['msg' => 'Update user successful']);
        return redirect()->to('users');
    }

    public function reportUsers()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $itemPerPage = 20;

        $addDateFrom = $this->request->getVar('add_date_from');
        $addDateTo = $this->request->getVar('add_date_to');

        $usersQuery = $this->userModel;

        if ($keyword = $this->request->getGet('search')) {
            $usersQuery->groupStart()
                ->like('nip', $keyword, 'both', true)
                ->orLike('full_name', $keyword, 'both', true)
                ->orLike('email', $keyword, 'both', true)
                ->orLike('phone', $keyword, 'both', true)
                ->orLike('username', $keyword, 'both', true)
                ->orLike('role', $keyword, 'both', true)
                ->groupEnd();
        }

        if ($addDateFrom && $addDateTo) {
            $usersQuery->where('users.created_at >=', date('Y-m-d', strtotime($addDateFrom)))
                ->where('users.created_at <=', date('Y-m-d', strtotime($addDateTo)));
        }

        $users = $usersQuery->paginate($itemPerPage, 'users');

        $data = [
            'users'             => $users,
            'pager'             => $this->userModel->pager,
            'currentPage'       => $this->request->getVar('page_categories') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'search'            => $this->request->getGet('search'),
            'user'              => $this->base_data['user'],
            'addDateFrom'       => $addDateFrom,
            'addDateTo'         => $addDateTo,
        ];

        return view('reports/users', $data);
    }

    public function printReportUsers($format = 'pdf')
    {
        $addDateFrom = $this->request->getVar('add_date_from');
        $addDateTo = $this->request->getVar('add_date_to');

        $usersQuery = $this->userModel;

        if ($addDateFrom && $addDateTo) {
            $usersQuery->where('users.created_at >=', date('Y-m-d', strtotime($addDateFrom)))
                ->where('users.created_at <=', date('Y-m-d', strtotime($addDateTo)));
        }
        
        $users = $usersQuery->findAll();

        $kepdin = $this->userModel->where('role', 'Kepala Dinas')->first();

        $this->reportModel->save([
            'user_id'       => $this->base_data['user']['id'],
            'description'   => 'Cetak Pengguna'
        ]);

        if ($format === 'html') {
            return view('reports/print_users', ['users' => $users, 'kepdin' => $kepdin]);
        } elseif ($format === 'pdf') {
            $pdfView = view('reports/print_users', ['users' => $users, 'kepdin' => $kepdin]);

            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            $dompdf->stream("Laporan Data Pengguna E-PERPUSJAR");
        }

        return view('reports/print_users', ['users' => $users, 'kepdin' => $kepdin]);
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

        $user = $this->userModel->where('id', $id)->first();

        if (empty($user)) {
            throw new PageNotFoundException('User not found');
        }

        if (!$this->userModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete user', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'User deleted successfully']);
        return redirect()->to('users');
    }
}
