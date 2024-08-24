<?php

namespace App\Controllers\Loans;

use App\Models\BookModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use App\Models\UserModel;
use App\Models\ReportModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;

class FinesController extends BaseController
{
    protected LoanModel $loanModel;
    protected FineModel $fineModel;
    protected MemberModel $memberModel;
    protected UserModel $userModel;
    protected BookModel $bookModel;
    protected ReportModel $reportModel;

    public function __construct()
    {
        $this->loanModel = new LoanModel;
        $this->fineModel = new FineModel;
        $this->memberModel = new MemberModel;
        $this->userModel = new UserModel;
        $this->bookModel = new BookModel;
        $this->reportModel = new ReportModel;

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
            $fines = $this->loanModel
                ->select('members.*, members.uid as member_uid, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('fines', 'fines.loan_id = loans.id', 'INNER')
                ->like('first_name', $keyword, insensitiveSearch: true)
                ->orLike('last_name', $keyword, insensitiveSearch: true)
                ->orLike('email', $keyword, insensitiveSearch: true)
                ->orLike('title', $keyword, insensitiveSearch: true)
                ->orLike('slug', $keyword, insensitiveSearch: true)
                ->orderBy('fines.created_at', 'DESC')
                ->paginate($itemPerPage, 'fines');
        } else {
            $fines = $this->loanModel
                ->select('members.*, members.uid as member_uid, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('fines', 'fines.loan_id = loans.id', 'INNER')
                ->orderBy('fines.created_at', 'DESC')
                ->paginate($itemPerPage, 'fines');
        }

        $paidOffFilter = ($this->request->getVar('paid-off') ?? 'false') === 'true';

        if ($paidOffFilter) {
            $fines = array_filter($fines, function ($fine) {
                return $fine['paid_at'] != null || ($fine['amount_paid'] ?? 0) >= $fine['fine_amount'];
            });
        } else {
            $fines = array_filter($fines, function ($fine) {
                return $fine['paid_at'] == null || $fine['fine_amount'] > ($fine['amount_paid'] ?? 0);
            });
        }

        $fines = array_filter($fines, function ($fine) {
            return $fine['deleted_at'] == null && $fine['return_date'] != null && $fine['fine_deleted'] == null;
        });

        $data = [
            'paidOffFilter' => $paidOffFilter,
            'fines'         => $fines,
            'pager'         => $this->loanModel->pager,
            'currentPage'   => $this->request->getVar('page_fines') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'search'        => $this->request->getGet('search'),
            'user'          => $this->base_data['user']
        ];

        return view('fines/index', $data);
    }

    public function searchReturn()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');

            if (empty($param)) return;

            $returns = $this->loanModel
                ->select('members.*, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('fines', 'fines.loan_id = loans.id', 'INNER')
                ->like('first_name', $param, insensitiveSearch: true)
                ->orLike('last_name', $param, insensitiveSearch: true)
                ->orLike('email', $param, insensitiveSearch: true)
                ->orLike('title', $param, insensitiveSearch: true)
                ->orLike('author', $param, insensitiveSearch: true)
                ->orLike('publisher', $param, insensitiveSearch: true)
                ->orWhere('loans.uid', $param)
                ->orWhere('members.uid', $param)
                ->findAll();

            $returns = array_filter($returns, function ($return) {
                return $return['deleted_at'] == null && $return['return_date'] != null && $return['fine_deleted'] == null;
            });

            if (empty($returns)) {
                return view('fines/return', ['msg' => 'Loan not found', 'user' => $this->base_data['user']]);
            }

            return view('fines/return', ['returns' => $returns, 'user' => $this->base_data['user']]);
        }

        return view('fines/search_return', ['user' => $this->base_data['user']]);
    }

    public function pay($uid = null, $validation = null, $oldInput = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $returns = $this->loanModel
            ->select('members.*, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, racks.name as rack, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->join('fines', 'fines.loan_id = loans.id', 'INNER')
            ->where('loans.uid', $uid)
            ->findAll();

        $return = array_filter($returns, function ($r) {
            return $r['deleted_at'] == null && $r['fine_id'] != null && $r['return_date'] != null && $r['fine_deleted'] == null && $r['paid_at'] == null;
        });

        if (empty($return)) {
            throw new PageNotFoundException('Return not found');
        }

        return view('fines/pay', [
            'validation' => $validation ?? \Config\Services::validation(),
            'oldInput'   => $oldInput,
            'return'     => $return[array_key_first($return)],
            'user'       => $this->base_data['user']
        ]);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    // public function new()
    // {
    //! Not implemented
    // }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    // public function create()
    // {
    //! Not implemented
    // }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    // public function edit($id = null)
    // {
    //! Not implemented
    // }

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

        if (!$this->validate([
            'nominal'  => 'required|numeric|greater_than_equal_to[1000]'
        ])) {
            return $this->pay($uid, \Config\Services::validation(), $this->request->getVar());
        }

        $returns = $this->loanModel
            ->select('fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
            ->join('fines', 'fines.loan_id = loans.id', 'INNER')
            ->where('loans.uid', $uid)
            ->findAll();

        $return = array_filter($returns, function ($r) {
            return $r['deleted_at'] == null && $r['fine_id'] != null && $r['return_date'] != null && $r['fine_deleted'] == null && $r['paid_at'] == null;
        });

        if (empty($return)) {
            throw new PageNotFoundException('Return not found');
        }

        $return = $return[array_key_first($return)];

        $nominal = $this->request->getVar('nominal');
        $newAmountPaid = intval($return['amount_paid'] ?? 0) + intval($nominal);

        if (!$this->fineModel->update(
            $return['fine_id'],
            [
                'amount_paid' => $newAmountPaid,
                'paid_at'     => $newAmountPaid >= $return['fine_amount'] ? Time::now()->toDateTimeString() : null
            ]
        )) {
            session()->setFlashdata(['msg' => 'Update failed']);
            return $this->pay($uid, \Config\Services::validation(), $this->request->getVar());
        }

        if ($newAmountPaid >= $return['fine_amount']) {
            deleteLoansQRCode($return['qr_code']);
            $this->loanModel->update($return['id'], ['qr_code' => null]);
        }

        session()->setFlashdata(['msg' => 'Update fine successful']);
        return redirect()->to('fines');
    }

    public function reportFines()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $itemPerPage = 20;

        if ($keyword = $this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $fines = $this->loanModel
                ->select('members.*, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('fines', 'fines.loan_id = loans.id', 'INNER')
                ->like('first_name', $keyword, insensitiveSearch: true)
                ->orLike('last_name', $keyword, insensitiveSearch: true)
                ->orLike('title', $keyword, insensitiveSearch: true)
                ->orLike('amount_paid', $keyword, insensitiveSearch: true)
                ->orLike('fine_amount', $keyword, insensitiveSearch: true)
                ->paginate($itemPerPage, 'fines');
        } else {
            $fines = $this->loanModel
                ->select('members.*, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('fines', 'fines.loan_id = loans.id', 'INNER')
                ->paginate($itemPerPage, 'fines');
        }

        $data = [
            'fines'             => $fines,
            'pager'             => $this->loanModel->pager,
            'currentPage'       => $this->request->getVar('page_returns') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'user'              => $this->base_data['user'],
            'keyword'           => $this->request->getGet('search')
        ];

        return view('reports/fines', $data);
    }

    public function printReportFines($format = 'pdf')
    {
        $keyword = $this->request->getGet('search');

        $finesQuery = $this->loanModel
            ->select('members.*, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('fines', 'fines.loan_id = loans.id', 'INNER')
            ->like('first_name', $keyword, insensitiveSearch: true)
            ->orLike('last_name', $keyword, insensitiveSearch: true)
            ->orLike('title', $keyword, insensitiveSearch: true)
            ->orLike('amount_paid', $keyword, insensitiveSearch: true)
            ->orLike('fine_amount', $keyword, insensitiveSearch: true);

        $fines = $finesQuery->findAll();

        $kepdin = $this->userModel->where('role', 'Kepala Dinas')->first();

        $this->reportModel->save([
            'user_id'       => $this->base_data['user']['id'],
            'description'   => 'Cetak Denda'
        ]);

        if ($format === 'html') {
            return view('reports/report_fines', ['fines' => $fines, 'kepdin' => $kepdin]);
        } elseif ($format === 'pdf') {
            $pdfView = view('reports/report_fines', ['fines' => $fines, 'kepdin' => $kepdin]);
    
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
    
            $dompdf->stream("Laporan Denda Anggota E-PERPUSJAR.pdf");
        }

        return view('reports/report_fines', ['fines' => $fines, 'kepdin' => $kepdin]);
    }

    // ... (Kode yang sudah ada sebelumnya)

    protected function getFinesData($itemPerPage)
    {
        // Modify this to retrieve fines data based on your requirements
        // For example, you can use the same logic as in the index method
        // or customize it as needed.
        return $this->loanModel
            ->select('members.*, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('fines', 'fines.loan_id = loans.id', 'INNER')
            ->paginate($itemPerPage, 'fines');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    // public function delete($id = null)
    // {
    //! Not implemented
    // }
}
