<?php

namespace App\Controllers;

use App\Libraries\QRGenerator;
use App\Models\BookModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use Dompdf\Dompdf;
use Dompdf\Options;

class Returns extends BaseController
{
    protected LoanModel $loanModel;
    protected FineModel $fineModel;
    protected MemberModel $memberModel;
    protected BookModel $bookModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->loanModel = new LoanModel;
        $this->fineModel = new FineModel;
        $this->memberModel = new MemberModel;
        $this->bookModel = new BookModel;
        $this->userModel = new UserModel;

        helper('upload');
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        if ($this->member == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $itemPerPage = 20;

        $uidMember = $this->base_data['member']['uid'];

        $builder = $this->loanModel
            ->select('members.*, members.uid as member_uid, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('fines', 'fines.loan_id = loans.id', 'LEFT')
            ->where('members.uid', $uidMember);

        if ($this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $builder->groupStart()
                ->like('first_name', $keyword, 'both', true)
                ->orLike('last_name', $keyword, 'both', true)
                ->orLike('email', $keyword, 'both', true)
                ->orLike('title', $keyword, 'both', true)
                ->orLike('slug', $keyword, 'both', true)
                ->groupEnd();
        }

        $loans = $builder->paginate($itemPerPage, 'returns');

        $loans = array_filter($loans, function ($loan) {
            return $loan['deleted_at'] == null && $loan['return_date'] != null && $loan['fine_deleted'] == null;
        });

        $data = [
            'loans'         => $loans,
            'pager'         => $this->loanModel->pager,
            'currentPage'   => $this->request->getVar('page_returns') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'member'        => $this->base_data['member']
        ];

        return view('membersreturn/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($uid = null)
    {
        if ($this->member == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $loan = $this->loanModel
            ->select('members.*, members.uid as member_uid, books.*, fines.*, fines.id as fine_id, loans.*, loans.qr_code as loan_qr_code, loans.status_return as loan_status_return, book_stock.quantity as book_stock, racks.name as rack, categories.name as category')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->join('fines', 'fines.loan_id = loans.id', 'LEFT')
            ->where('loans.uid', $uid)
            ->where("return_date IS NOT NULL")
            ->first();

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        }

        if ($this->request->getGet('update-qr-code')) {
            $qrGenerator = new QRGenerator();
            $qrCodeLabel = substr($loan['first_name'] . ($loan['last_name'] ? " {$loan['last_name']}" : ''), 0, 12) . '_' . substr($loan['title'], 0, 12);
            $qrCode = $qrGenerator->generateQRCode(
                $loan['uid'],
                labelText: $qrCodeLabel,
                dir: LOANS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );

            // delete former qr code
            deleteLoansQRCode($loan['qr_code']);

            $this->loanModel->update($loan['id'], ['qr_code' => $qrCode]);

            $loan = $this->loanModel
                ->select('members.*, members.uid as member_uid, books.*, loans.*, loans.qr_code as loan_qr_code, loans.status_return as loan_status_return, book_stock.quantity as book_stock, racks.name as rack, categories.name as category')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->where('loans.uid', $uid)
                ->first();

            return redirect()->to("returns/{$loan['uid']}");
        }

        $data = [
            'loan'         => $loan,
            'member'       => $this->base_data['member']
        ];

        return view('membersreturn/show', $data);
    }

    public function searchLoan()
    {
        if ($this->member == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');

            $uidMember = $this->base_data['member']['uid'];

            if (empty($param)) return;

            $builder = $this->loanModel
                ->select('members.*, books.*, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT');

            $builder->where('loans.status_loan', 'Setuju')
                    ->where('members.uid', $uidMember);

            if (!empty($param)) {
                $builder->groupStart()
                    ->like('title', $param, 'both', true)
                    ->orLike('author', $param, 'both', true)
                    ->orLike('publisher', $param, 'both', true)
                    ->orWhere('loans.uid', $param)
                    ->orWhere('members.uid', $param)
                    ->groupEnd();
            }

            $loans = $builder->findAll();

            $loans = array_filter($loans, function ($loan) {
                return $loan['deleted_at'] == null && $loan['return_date'] == null;
            });

            if (empty($loans)) {
                return view('membersreturn/loan', ['msg' => 'Loan not found']);
            }

            return view('membersreturn/loan', ['loans' => $loans, 'member' => $this->base_data['member']]);
        }

        return view('membersreturn/search_loan', ['member' => $this->base_data['member']]);
    }


    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        if ($this->member == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $loanUid = $this->request->getVar('loan-uid');

        if (empty($loanUid)) {
            session()->setFlashdata(['msg' => 'Select loan first', 'error' => true]);
            return redirect()->to('returns/new/search');
        }

        $loans = $this->loanModel
            ->select('members.*, books.*, categories.name as category, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->where('loans.uid', $loanUid)
            ->findAll();

        $loan = array_filter($loans, function ($l) {
            return $l['deleted_at'] == null && $l['return_date'] == null;
        });

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        }

        $data = [
            'loan'       => $loan[array_key_first($loan)],
            'validation' => $validation ?? \Config\Services::validation(),
            'member'     => $this->base_data['member']
        ];

        return view('membersreturn/create', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if ($this->member == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $date = Time::parse($this->request->getVar('date') ?? 'now', locale: 'id');
        $loanUid = $this->request->getVar('loan_uid');

        $loan = $this->loanModel->where('uid', $loanUid)->first();

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        }

        $loanDueDate = Time::parse($loan['due_date'], locale: 'id');

        $isLate = $date->isAfter($loanDueDate);

        if ($isLate) {
            if (!$this->loanModel->update($loan['id'], [
                'return_date' => $date->toDateTimeString(),
                'status_return' => 'Proses'
            ])) {
                session()->setFlashdata(['msg' => 'Update failed', 'error' => true]);
                return redirect()->to('returns/new?loan-uid=' . $loan['uid']);
            }

            $finePerDay = intval(getenv('amountFinesPerDay'));
            $daysLate = $date->today()->difference($loanDueDate)->getDays();
            $totalFine = abs($daysLate) * $loan['quantity'] * $finePerDay;

            if (!$this->fineModel->save([
                'loan_id' => $loan['id'],
                'fine_amount' => $totalFine,
            ])) {
                session()->setFlashdata(['msg' => 'Update failed', 'error' => true]);
                return redirect()->to('returns/new?loan-uid=' . $loan['uid']);
            }
        } else {
            deleteLoansQRCode($loan['qr_code']);
            if (!$this->loanModel->update($loan['id'], [
                'return_date' => $date->toDateTimeString(),
                'qr_code' => null,
                'status_return' => 'Proses'
            ])) {
                session()->setFlashdata(['msg' => 'Update failed', 'error' => true]);
                return redirect()->to('returns/new?loan-uid=' . $loan['uid']);
            }
        }

        $book = $this->bookModel->where('id', $loan['book_id'])->first();

        $petugas = $this->userModel->where('role', 'Petugas')->first();

        $data = [
            'api_key'           => 'FNHBA401J42948KEATX28CZH6GAC5KY9',
            'device_key'        => 'MYVMJB',
            'destination'       => preg_replace('/\D/', '', $petugas['phone']),
            'message'           => 'Hallo *'.$petugas['full_name'].'* ada pengembalian buku baru *'.$book['title'].'* dari *'.$this->base_data['member']['first_name'].' '.$this->base_data['member']['last_name'].'* pada tanggal *'.Time::now()->toDateTimeString().'*.',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://wapisender.id/api/v5/message/text');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: multipart/form-data'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        session()->setFlashdata(['msg' => 'Success', 'error' => false]);
        return redirect()->to('membersreturn');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    // public function edit($uid = null)
    // {
    //! Not implemented
    // }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    // public function update($uid = null)
    // {
    //! Not implemented
    // }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($uid = null)
    {
        if ($this->member == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $loans = $this->loanModel
            ->select('members.*, books.*, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('loans.uid', $uid)->findAll();

        $loans = array_filter($loans, function ($loan) {
            return $loan['deleted_at'] == null && $loan['return_date'] != null;
        });

        $loan = $loans[0];

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        }

        $qrGenerator = new QRGenerator();

        $qrCodeLabel = substr($loan['first_name'] . ($loan['last_name'] ? " {$loan['last_name']}" : ''), 0, 12) . '_' . substr($loan['title'], 0, 12);

        $qrCode = $qrGenerator->generateQRCode(
            data: $loan['uid'],
            labelText: $qrCodeLabel,
            dir: LOANS_QR_CODE_PATH,
            filename: $qrCodeLabel
        );

        if (!$this->loanModel->update($loan['id'], [
            'return_date' => null,
            'qr_code' => $qrCode
        ])) {
            deleteLoansQRCode($qrCode);

            session()->setFlashdata(['msg' => 'Update failed', 'error' => true]);
            return redirect()->to('membersreturn/' . $loan['uid']);
        }

        $isLate = Time::parse($loan['return_date'])->isAfter(Time::parse($loan['due_date']));

        if ($isLate) {
            $fine = $this->fineModel->where('loan_id', $loan['id'])->first();
            if (!empty($fine)) $this->fineModel->delete($fine['id']);
        }

        session()->setFlashdata(['msg' => 'Success', 'error' => false]);
        return redirect()->to('membersreturn');
    }

    public function reportReturns($format = 'pdf')
    {
        $loans = $this->loanModel
            ->select('members.*, members.uid as member_uid, books.*, fines.*, fines.id as fine_id, fines.deleted_at as fine_deleted, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('fines', 'fines.loan_id = loans.id', 'LEFT')
            ->findAll();

        $data = [
            'loans' => $loans,
        ];

        $html = view('reports/report_returns', $data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("Laporan Pengembalian Buku E-PERPUSJAR", array("Attachment" => false));
    }
}
