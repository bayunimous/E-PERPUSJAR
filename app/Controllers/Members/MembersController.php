<?php

namespace App\Controllers\Members;

use App\Libraries\QRGenerator;
use App\Models\BookModel;
use App\Models\BookStockModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;

class MembersController extends BaseController
{
    protected MemberModel $memberModel;
    protected BookModel $bookModel;
    protected BookStockModel $bookStockModel;
    protected LoanModel $loanModel;
    protected FineModel $fineModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel;
        $this->bookModel = new BookModel;
        $this->bookStockModel = new BookStockModel;
        $this->loanModel = new LoanModel;
        $this->fineModel = new FineModel;

        helper('upload');
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
            $members = $this->memberModel
                ->like('first_name', $keyword, insensitiveSearch: true)
                ->orLike('last_name', $keyword, insensitiveSearch: true)
                ->orLike('email', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'members');

            $members = array_filter($members, function ($member) {
                return $member['deleted_at'] == null;
            });
        } else {
            $members = $this->memberModel->paginate($itemPerPage, 'members');
        }

        $data = [
            'members'           => $members,
            'pager'             => $this->memberModel->pager,
            'currentPage'       => $this->request->getVar('page_categories') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'search'            => $this->request->getGet('search'),
            'user'              => $this->base_data['user']
        ];

        return view('members/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($uid = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $members = $this->memberModel->where('uid', $uid)->first();

        if (empty($members)) {
            throw new PageNotFoundException('Member not found');
        }

        $loans = $this->loanModel->where([
            'member_id' => $members['id'],
            'return_date' => null
        ])->findAll();

        $fines = $this->loanModel
            ->select('loans.id, fines.amount_paid, fines.fine_amount, fines.paid_at')
            ->join('fines', 'loans.id=fines.loan_id', 'LEFT')
            ->where('member_id', $members['id'])->findAll();

        $totakBooksLent = empty($loans) ? 0 : array_reduce(
            array_map(function ($loan) {
                return $loan['quantity'];
            }, $loans),
            function ($carry, $item) {
                return ($carry + $item);
            }
        );

        $return = array_filter($loans, function ($loan) {
            return $loan['return_date'] != null;
        });

        $lateLoans = array_filter($loans, function ($loan) {
            return $loan['return_date'] == null && Time::now()->isAfter(Time::parse($loan['due_date']));
        });

        $totalFines = array_reduce(
            array_map(function ($fine) {
                return $fine['fine_amount'];
            }, $fines),
            function ($carry, $item) {
                return ($carry + $item);
            }
        );

        $paidFines = array_reduce(
            array_map(function ($fine) {
                return $fine['amount_paid'];
            }, $fines),
            function ($carry, $item) {
                return ($carry + $item);
            }
        );

        $unpaidFines = $totalFines - $paidFines;

        // Create qr code if not exist
        if (!file_exists(MEMBERS_QR_CODE_PATH . $members['qr_code']) || empty($members['qr_code'])) {
            $qrGenerator = new QRGenerator();
            $qrCodeLabel = $members['first_name'] . ($members['last_name'] ? ' ' . $members['last_name'] : '');
            $qrCode = $qrGenerator->generateQRCode(
                $members['uid'],
                labelText: $qrCodeLabel,
                dir: MEMBERS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );

            $this->memberModel->update($members['id'], ['qr_code' => $qrCode]);
            $members = $this->memberModel->where('uid', $uid)->first();
        }

        $data = [
            'members'           => $members,
            'totalBooksLent'    => $totakBooksLent,
            'loanCount'         => count($loans),
            'returnCount'       => count($return),
            'lateCount'         => count($lateLoans),
            'unpaidFines'       => $unpaidFines,
            'paidFines'         => $paidFines,
            'user'              => $this->base_data['user']
        ];

        return view('members/show', $data);
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

        return view('members/create', [
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
            'first_name'    => 'required|alpha_numeric_punct|max_length[100]',
            'last_name'     => 'permit_empty|alpha_numeric_punct|max_length[100]',
            'email'         => 'required|valid_email|max_length[255]',
            'phone'         => 'required|alpha_numeric_punct|min_length[4]|max_length[20]',
            'username'      => 'required|max_length[20]',
            'password'      => 'required|max_length[20]',
            'address'       => 'required|string|min_length[5]|max_length[511]',
            'date_of_birth' => 'required|valid_date',
            'gender'        => 'required|alpha_numeric_punct',
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            return view('members/create', $data);
        }

        $email = $this->request->getVar('email');
        $phone = $this->request->getVar('phone');
        $username = $this->request->getVar('username');

        $errors = [];

        if ($this->memberModel->where('email', $email)->first()) {
            $errors[] = 'Email is already in use.';
        }

        if ($this->memberModel->where('phone', $phone)->first()) {
            $errors[] = 'Phone is already in use.';
        }

        if ($this->memberModel->where('username', $username)->first()) {
            $errors[] = 'Username is already in use.';
        }

        if (!empty($errors)) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            session()->setFlashdata('errors', $errors);
            return view('members/create', $data);
        }

        $uid = sha1(
            $this->request->getVar('first_name')
                . $this->request->getVar('email')
                . $this->request->getVar('phone')
                . rand(0, 1000)
                . md5($this->request->getVar('gender'))
        );

        $qrGenerator = new QRGenerator();
        $qrCodeLabel = $this->request->getVar('first_name')
            . ($this->request->getVar('last_name')
                ? ' ' . $this->request->getVar('last_name') : '');
        $qrCode = $qrGenerator->generateQRCode(
            data: $uid,
            labelText: $qrCodeLabel,
            dir: MEMBERS_QR_CODE_PATH,
            filename: $qrCodeLabel
        );

        if (!$this->memberModel->save([
            'uid'           => $uid,
            'first_name'    => $this->request->getVar('first_name'),
            'last_name'     => $this->request->getVar('last_name'),
            'email'         => $this->request->getVar('email'),
            'phone'         => $this->request->getVar('phone'),
            'username'      => $this->request->getVar('username'),
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'address'       => $this->request->getVar('address'),
            'date_of_birth' => $this->request->getVar('date_of_birth'),
            'gender'        => $this->request->getVar('gender'),
            'qr_code'       => $qrCode
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('members/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new member successful']);
        return redirect()->to('members');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($uid = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $members = $this->memberModel->where('uid', $uid)->first();

        if (empty($members)) {
            throw new PageNotFoundException('Member not found');
        }

        $data = [
            'members'    => $members,
            'validation' => \Config\Services::validation(),
            'user'       => $this->base_data['user']
        ];

        return view('members/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($uid = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $members = $this->memberModel->where('uid', $uid)->first();

        if (empty($members)) {
            throw new PageNotFoundException('Member not found');
        }

        $rules = [
            'first_name'    => 'required|alpha_numeric_punct|max_length[100]',
            'last_name'     => 'permit_empty|alpha_numeric_punct|max_length[100]',
            'email'         => 'required|valid_email|max_length[255]',
            'phone'         => 'required|alpha_numeric_punct|min_length[4]|max_length[20]',
            'username'      => 'required|max_length[20]',
            'address'       => 'required|string|min_length[5]|max_length[511]',
            'date_of_birth' => 'required|valid_date',
            'gender'        => 'required|alpha_numeric_punct',
        ];

        if ($this->request->getVar('password')) {
            $rules['password'] = 'required|min_length[6]|max_length[50]';
        }

        if (!$this->validate($rules)) {
            $data = [
                'members'    => $members,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];
    
            return view('members/edit', $data);
        }

        $firstName = $this->request->getVar('first_name');
        $email = $this->request->getVar('email');
        $phone = $this->request->getVar('phone');
        $gender = $this->request->getVar('gender');

        $isChanged = ($firstName != $members['first_name']
            || $email != $members['email']
            || $phone != $members['phone']);

        $uid = $isChanged
            ? sha1($firstName . $email . $phone . rand(0, 1000) . md5($gender))
            : $members['uid'];

        if ($isChanged) {
            $qrGenerator = new QRGenerator();
            $qrCodeLabel = $this->request->getVar('first_name')
                . ($this->request->getVar('last_name')
                    ? ' ' . $this->request->getVar('last_name') : '');
            $qrCode = $qrGenerator->generateQRCode(
                $uid,
                labelText: $qrCodeLabel,
                dir: MEMBERS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );
            deleteMembersQRCode($members['qr_code']);
        } else {
            $qrCode = $members['qr_code'];
        }

        $data = [
            'id'            => $members['id'],
            'uid'           => $uid,
            'first_name'    => $this->request->getVar('first_name'),
            'last_name'     => $this->request->getVar('last_name'),
            'email'         => $this->request->getVar('email'),
            'phone'         => $this->request->getVar('phone'),
            'username'      => $this->request->getVar('username'),
            'address'       => $this->request->getVar('address'),
            'date_of_birth' => $this->request->getVar('date_of_birth'),
            'gender'        => $this->request->getVar('gender'),
            'qr_code'       => $qrCode
        ];

        if ($this->request->getVar('password')) {
            $data['password'] = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
        }

        if (!$this->memberModel->save($data)) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];
    
            session()->setFlashdata(['msg' => 'Update failed']);
            return view('members/edit', $data);
        }

        session()->setFlashdata(['msg' => 'Update member successful']);
        return redirect()->to('members');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($uid = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $members = $this->memberModel->where('uid', $uid)->first();

        if (empty($members)) {
            throw new PageNotFoundException('Member not found');
        }

        if (!$this->memberModel->delete($members['id'])) {
            session()->setFlashdata(['msg' => 'Failed to delete member', 'error' => true]);
            return redirect()->back();
        }

        deleteMembersQRCode($members['qr_code']);

        session()->setFlashdata(['msg' => 'Member deleted successfully']);
        return redirect()->to('members');
    }
}
