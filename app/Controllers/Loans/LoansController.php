<?php

namespace App\Controllers\Loans;

use App\Libraries\QRGenerator;
use App\Models\BookModel;
use App\Models\RackModel;
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

class LoansController extends BaseController
{
    protected LoanModel $loanModel;
    protected MemberModel $memberModel;
    protected UserModel $userModel;
    protected BookModel $bookModel;
    protected RackModel $rackModel;
    protected ReportModel $reportModel;

    public function __construct()
    {
        $this->loanModel = new LoanModel;
        $this->memberModel = new MemberModel;
        $this->userModel = new UserModel;
        $this->bookModel = new BookModel;
        $this->rackModel = new RackModel;
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
            $loans = $this->loanModel
                ->select('members.*, members.uid as member_uid, books.*, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->like('first_name', $keyword, insensitiveSearch: true)
                ->orLike('last_name', $keyword, insensitiveSearch: true)
                ->orLike('email', $keyword, insensitiveSearch: true)
                ->orLike('title', $keyword, insensitiveSearch: true)
                ->orLike('slug', $keyword, insensitiveSearch: true)
                ->orderBy('loans.created_at', 'DESC')
                ->paginate($itemPerPage, 'loans');
        } else {
            $loans = $this->loanModel
                ->select('members.*, members.uid as member_uid, books.*, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->orderBy('loans.created_at', 'DESC')
                ->paginate($itemPerPage, 'loans');
        }

        $loans = array_filter($loans, function ($loan) {
            return $loan['deleted_at'] == null && $loan['return_date'] == null;
        });

        $data = [
            'loans'         => $loans,
            'pager'         => $this->loanModel->pager,
            'currentPage'   => $this->request->getVar('page_loans') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'search'        => $this->request->getGet('search'),
            'user'          => $this->base_data['user']
        ];

        return view('loans/index', $data);
    }

    public function notice()
    {
        $currentDate = new \DateTime();

        $loans = $this->loanModel
            ->where('status_loan', 'Setuju')
            ->findAll();

        foreach ($loans as $loan) {
            $dueDate = new \DateTime($loan['due_date']);

            $threeDaysBefore = (clone $dueDate)->sub(new \DateInterval('P3D'));
            $oneDayBefore = (clone $dueDate)->sub(new \DateInterval('P1D'));

            if ($currentDate->format('Y-m-d') == $threeDaysBefore->format('Y-m-d')) {
                $book = $this->bookModel->where('id', $loan['book_id'])->first();
                $members = $this->memberModel->where('id', $loan['member_id'])->first();

                $message = 'Hallo *'.$members['first_name'].' '.$members['last_name'].'*, peminjaman buku kamu dengan judul buku *'.$book['title'].'* akan berakhir dalam 3 hari lagi pada tanggal *'.$loan['due_date'].'*. Terimakasih telah mengunjungi E-PERPUSJAR.';

                $data = [
                    'api_key'           => 'FNHBA401J42948KEATX28CZH6GAC5KY9',
                    'device_key'        => 'MYVMJB',
                    'destination'       => preg_replace('/\D/', '', $members['phone']),
                    'message'           => $message,
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

                echo 'Notifikasi Pengembalian H-3 berhasil dikirim ke '.$members['first_name'].' '.$members['last_name'].'';
            }

            if ($currentDate->format('Y-m-d') == $oneDayBefore->format('Y-m-d')) {
                $book = $this->bookModel->where('id', $loan['book_id'])->first();
                $members = $this->memberModel->where('id', $loan['member_id'])->first();

                $message = 'Hallo *'.$members['first_name'].' '.$members['last_name'].'*, peminjaman buku kamu dengan judul buku *'.$book['title'].'* akan berakhir dalam 1 hari lagi pada tanggal *'.$loan['due_date'].'*. Harap segera mengembalikan buku tersebut. Terimakasih telah mengunjungi E-PERPUSJAR.';

                $data = [
                    'api_key'           => 'FNHBA401J42948KEATX28CZH6GAC5KY9',
                    'device_key'        => 'MYVMJB',
                    'destination'       => preg_replace('/\D/', '', $members['phone']),
                    'message'           => $message,
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

                echo 'Notifikasi Pengembalian H-1 berhasil dikirim ke '.$members['first_name'].' '.$members['last_name'].'';
            }
        }
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

        $loan = $this->loanModel
            ->select('members.*, members.uid as member_uid, books.*, loans.*, loans.qr_code as loan_qr_code, book_stock.quantity as book_stock, racks.name as rack, categories.name as category')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->where('loans.uid', $uid)
            ->first();

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        }

        if ($this->request->getGet('update-qr-code') && $loan['return_date'] == null) {
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
                ->select('members.*, members.uid as member_uid, books.*, loans.*, loans.qr_code as loan_qr_code, book_stock.quantity as book_stock, racks.name as rack, categories.name as category')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->where('loans.uid', $uid)
                ->first();

            return redirect()->to("loans/{$loan['uid']}");
        }

        $data = [
            'loan'         => $loan,
            'user'         => $this->base_data['user']
        ];

        return view('loans/show', $data);
    }
    
    public function approve($uid = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $loan = $this->loanModel->where('uid', $uid)->first();
        
        if (!$this->loanModel->update($loan['id'], [
            'status_loan' => 'Setuju'
        ])) {
            session()->setFlashdata(['msg' => 'Update failed', 'error' => true]);
            return redirect()->to('loans/' . $loan['uid']);
        }

        $book = $this->bookModel->where('id', $loan['book_id'])->first();

        $members = $this->memberModel->where('id', $loan['member_id'])->first();

        $data = [
            'api_key'           => 'FNHBA401J42948KEATX28CZH6GAC5KY9',
            'device_key'        => 'MYVMJB',
            'destination'       => preg_replace('/\D/', '', $members['phone']),
            'message'           => 'Hallo *'.$members['first_name'].' '.$members['last_name'].'* peminjaman buku kamu dengan judul buku *'.$book['title'].'* telah disetujui oleh *'.$this->base_data['user']['full_name'].'* dengan jadwal pengembalian pada tanggal *'.$loan['due_date'].'*. Terimakasih telah mengunjungin E-PERPUSJAR.',
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
        return redirect()->to('loans');
    }
    
    public function reject($uid = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $loan = $this->loanModel->where('uid', $uid)->first();
        
        if (!$this->loanModel->update($loan['id'], [
            'status_loan' => 'Tolak'
        ])) {
            session()->setFlashdata(['msg' => 'Update failed', 'error' => true]);
            return redirect()->to('loans/' . $loan['uid']);
        }

        $book = $this->bookModel->where('id', $loan['book_id'])->first();

        $members = $this->memberModel->where('id', $loan['member_id'])->first();

        $data = [
            'api_key'           => 'FNHBA401J42948KEATX28CZH6GAC5KY9',
            'device_key'        => 'MYVMJB',
            'destination'       => preg_replace('/\D/', '', $members['phone']),
            'message'           => 'Hallo *'.$members['first_name'].' '.$members['last_name'].'* peminjaman buku kamu dengan judul buku *'.$book['title'].'* ditolak oleh *'.$this->base_data['user']['full_name'].'* dengan jadwal pengembalian pada tanggal *'.$loan['due_date'].'*. Terimakasih telah mengunjungin E-PERPUSJAR.',
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
        return redirect()->to('loans');
    }

    public function searchMember()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');

            if (empty($param)) return;

            $members = $this->memberModel
                ->like('first_name', $param, insensitiveSearch: true)
                ->orLike('last_name', $param, insensitiveSearch: true)
                ->orLike('email', $param, insensitiveSearch: true)
                ->orWhere('uid', $param)
                ->findAll();

            $members = array_filter($members, function ($member) {
                return $member['deleted_at'] == null;
            });

            if (empty($members)) {
                return view('loans/member', ['msg' => 'Member not found', 'user' => $this->base_data['user']]);
            }

            return view('loans/member', ['members' => $members, 'user' => $this->base_data['user']]);
        }

        return view('loans/search_member', ['user' => $this->base_data['user']]);
    }

    public function searchBook()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');
            $memberUid = $this->request->getVar('memberUid');

            if (empty($param)) return;

            if (empty($memberUid)) {
                return view('loans/book', ['msg' => 'Member UID is empty', 'user' => $this->base_data['user']]);
            }

            $books = $this->bookModel
                ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->join('racks', 'books.rack_id = racks.id', 'LEFT')
                ->like('title', $param, insensitiveSearch: true)
                ->orLike('slug', $param, insensitiveSearch: true)
                ->orLike('author', $param, insensitiveSearch: true)
                ->orLike('publisher', $param, insensitiveSearch: true)
                ->orWhere('isbn', $param)
                ->findAll();

            $books = array_filter($books, function ($book) {
                return $book['deleted_at'] == null;
            });

            if (empty($books)) {
                return view('loans/book', ['msg' => 'Book not found', 'user' => $this->base_data['user']]);
            }

            $books = array_map(function ($book) {
                $newBook = $book;
                $newBook['stock'] = $this->getRemainingBookStocks($book);
                return $newBook;
            }, $books);

            return view('loans/book', ['books' => $books, 'memberUid' => $memberUid, 'user' => $this->base_data['user']]);
        }

        $memberUid = $this->request->getVar('member-uid');

        if (empty($memberUid)) {
            session()->setFlashdata(['msg' => 'Select member first', 'error' => true]);
            return redirect()->to('loans/new/members/search');
        }

        $members = $this->memberModel->where('uid', $memberUid)->first();

        if (empty($members)) {
            session()->setFlashdata(['msg' => 'Member not found', 'error' => true]);
            return redirect()->to('loans/new/members/search');
        }

        return view('loans/search_book', ['members' => $members, 'user' => $this->base_data['user']]);
    }

    protected function getRemainingBookStocks($book)
    {
        $loans = $this->loanModel->where([
            'book_id' => $book['id'],
            'return_date' => null
        ])->findAll();

        $loanCount = array_reduce(
            array_map(function ($loan) {
                return $loan['quantity'];
            }, $loans),
            function ($carry, $item) {
                return ($carry + $item);
            }
        );

        return $book['quantity'] - $loanCount;
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new($validation = null, $oldInput = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('loans/new/members/search');
        }

        $members = $this->memberModel
            ->where('uid', $this->request->getVar('member_uid'))
            ->first();

        $books = [];

        $bookSlugs = $this->request->getVar('slugs');

        if (empty($bookSlugs)) {
            return redirect()->back();
        }

        foreach ($bookSlugs as $slug) {
            $book = $this->bookModel
                ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
                ->where('books.slug', $slug)->first();

            if (!empty($book)) {
                $book['stock'] = $this->getRemainingBookStocks($book);
                array_push($books, $book);
            }
        }

        $data = [
            'books'      => $books,
            'members'    => $members,
            'validation' => $validation ?? \Config\Services::validation(),
            'oldInput'   => $oldInput,
            'user'       => $this->base_data['user']
        ];

        return view('loans/create', $data);
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

        $validation = [
            'member_uid' => 'required|string|max_length[64]',
        ];

        $bookSlugs = $this->request->getVar('slugs') or die();

        foreach ($bookSlugs as $slug) {
            $validation['quantity-' . $slug] = 'required|numeric|integer|greater_than[0]|less_than_equal_to[10]';
            $validation['duration-' . $slug] = 'required|numeric|integer|greater_than[0]|less_than_equal_to[30]';
        }

        if (!$this->validate($validation)) {
            return $this->new(\Config\Services::validation(), $this->request->getVar());
        }

        $memberUid = $this->request->getVar('member_uid') or die();

        $member = $this->memberModel->where('uid', $memberUid)->first();

        if (empty($member)) {
            session()->setFlashdata(['msg' => 'Member not found']);
            return redirect()->to('loans/new/members/search');
        }

        $newLoanIds = [];

        foreach ($bookSlugs as $slug) {
            $duration = $this->request->getVar('duration-' . $slug);
            $quantity = $this->request->getVar('quantity-' . $slug);

            $book = $this->bookModel->where('slug', $slug)->first();

            if (empty($duration) || empty($quantity) || empty($book)) {
                continue;
            }

            $loanUid = sha1($book['slug'] . $member['uid'] . time());

            $qrGenerator = new QRGenerator();

            $qrCodeLabel = substr($member['first_name'] . ($member['last_name'] ? " {$member['last_name']}" : ''), 0, 12) . '_' . substr($book['title'], 0, 12);

            $qrCode = $qrGenerator->generateQRCode(
                data: $loanUid,
                labelText: $qrCodeLabel,
                dir: LOANS_QR_CODE_PATH,
                filename: $qrCodeLabel
            );

            $newLoan = [
                'book_id' => $book['id'],
                'quantity' => $quantity,
                'member_id' => $member['id'],
                'uid' => $loanUid,
                'loan_date' => Time::now()->toDateTimeString(),
                'due_date' => Time::now()->addDays(intval($duration))->toDateTimeString(),
                'qr_code' => $qrCode,
                'status_loan' => 'Setuju'
            ];

            $this->loanModel->insert($newLoan);

            array_push($newLoanIds, $this->loanModel->getInsertID());
        }

        $newLoans = array_map(function ($id) {
            return $this->loanModel->select('members.*, members.uid as member_uid, books.*, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->where('loans.id', $id)->first();
        }, $newLoanIds);

        return view('loans/result', [
            'newLoans'  => $newLoans,
            'user'      => $this->base_data['user']
        ]);
    }

    public function reportLoans()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $itemPerPage = 20;

        $loanDateFrom = $this->request->getVar('loan_date_from');
        $loanDateTo = $this->request->getVar('loan_date_to');
        $dueDateFrom = $this->request->getVar('due_date_from');
        $dueDateTo = $this->request->getVar('due_date_to');
        $returnDateFrom = $this->request->getVar('return_date_from');
        $returnDateTo = $this->request->getVar('return_date_to');

        $loansQuery = $this->loanModel
            ->select('members.*, members.uid as member_uid, books.*, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('loans.status_loan', 'Setuju');

        if ($keyword = $this->request->getGet('search')) {
            $loansQuery->groupStart()
                ->like('first_name', $keyword, 'both', true)
                ->orLike('last_name', $keyword, 'both', true)
                ->orLike('title', $keyword, 'both', true)
                ->groupEnd();
        }

        if ($loanDateFrom && $loanDateTo) {
            $loansQuery->where('loans.loan_date >=', date('Y-m-d', strtotime($loanDateFrom)))
                ->where('loans.loan_date <=', date('Y-m-d', strtotime($loanDateTo)));
        }
        if ($dueDateFrom && $dueDateTo) {
            $loansQuery->where('loans.due_date >=', date('Y-m-d', strtotime($dueDateFrom)))
                ->where('loans.due_date <=', date('Y-m-d', strtotime($dueDateTo)));
        }
        if ($returnDateFrom && $returnDateTo) {
            $loansQuery->where('loans.return_date >=', date('Y-m-d', strtotime($returnDateFrom)))
                ->where('loans.return_date <=', date('Y-m-d', strtotime($returnDateTo)));
        }

        $loans = $loansQuery->paginate($itemPerPage, 'loans');

        foreach ($loans as &$loan) {
            $loan['loan_date'] = Time::parse($loan['loan_date'], 'UTC')->toLocalizedString('yyyy-MM-dd HH:mm:ss');
            $loan['due_date'] = Time::parse($loan['due_date'], 'UTC')->toLocalizedString('yyyy-MM-dd HH:mm:ss');
            $loan['return_date'] = $loan['return_date'] ? Time::parse($loan['return_date'], 'UTC')->toLocalizedString('yyyy-MM-dd HH:mm:ss') : null;
        }

        $data = [
            'loans'             => $loans,
            'pager'             => $this->loanModel->pager,
            'currentPage'       => $this->request->getVar('page_returns') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'user'              => $this->base_data['user'],
            'loanDateFrom'      => $loanDateFrom,
            'loanDateTo'        => $loanDateTo,
            'dueDateFrom'       => $dueDateFrom,
            'dueDateTo'         => $dueDateTo,
            'returnDateFrom'    => $returnDateFrom,
            'returnDateTo'      => $returnDateTo,
        ];

        return view('reports/loans', $data);
    }

    public function printReportLoans($format = 'pdf')
    {
        $loanDateFrom = $this->request->getGet('loan_date_from');
        $loanDateTo = $this->request->getGet('loan_date_to');
        $dueDateFrom = $this->request->getGet('due_date_from');
        $dueDateTo = $this->request->getGet('due_date_to');
        $returnDateFrom = $this->request->getGet('return_date_from');
        $returnDateTo = $this->request->getGet('return_date_to');

        $loansQuery = $this->loanModel
            ->select('members.*, members.uid as member_uid, books.*, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->where('loans.status_loan', 'Setuju');
    
        if ($loanDateFrom && $loanDateTo) {
            $loansQuery->where('loans.loan_date >=', date('Y-m-d', strtotime($loanDateFrom)))
                ->where('loans.loan_date <=', date('Y-m-d', strtotime($loanDateTo)));
        }
        if ($dueDateFrom && $dueDateTo) {
            $loansQuery->where('loans.due_date >=', date('Y-m-d', strtotime($dueDateFrom)))
                ->where('loans.due_date <=', date('Y-m-d', strtotime($dueDateTo)));
        }
        if ($returnDateFrom && $returnDateTo) {
            $loansQuery->where('loans.return_date >=', date('Y-m-d', strtotime($returnDateFrom)))
                ->where('loans.return_date <=', date('Y-m-d', strtotime($returnDateTo)));
        }

        $loans = $loansQuery->findAll();

        foreach ($loans as &$loan) {
            $loan['loan_date'] = Time::parse($loan['loan_date'], 'UTC')->toLocalizedString('yyyy-MM-dd HH:mm:ss');
            $loan['due_date'] = Time::parse($loan['due_date'], 'UTC')->toLocalizedString('yyyy-MM-dd HH:mm:ss');
            $loan['return_date'] = $loan['return_date'] ? Time::parse($loan['return_date'], 'UTC')->toLocalizedString('yyyy-MM-dd HH:mm:ss') : null;
        }
    
        $kepdin = $this->userModel->where('role', 'Kepala Dinas')->first();

        $this->reportModel->save([
            'user_id'       => $this->base_data['user']['id'],
            'description'   => 'Cetak Peminjaman'
        ]);

        if ($format === 'html') {
            return view('reports/report_loans', ['loans' => $loans, 'kepdin' => $kepdin]);
        } elseif ($format === 'pdf') {
            $pdfView = view('reports/report_loans', ['loans' => $loans, 'kepdin' => $kepdin]);

            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream("Laporan Data Peminjaman Buku E-PERPUSJAR");
        }

        return view('reports/report_loans', ['loans' => $loans, 'kepdin' => $kepdin]);
    }

    public function statistics()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        // Mengambil data peminjam dengan jumlah peminjaman terbanyak
        $topBorrowers = $this->loanModel
            ->select('members.*, COUNT(loans.id) as total_loans')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->where('loans.status_loan', 'Setuju')
            ->groupBy('members.id')
            ->orderBy('total_loans', 'DESC')
            ->limit(5) // Ubah sesuai kebutuhan
            ->findAll();

        $data = [
            'topBorrowers' => $topBorrowers,
            'user'         => $this->base_data['user']
        ];

        return view('statisticsloan/statistics', $data);
    }

    public function statisticsMembers($format = 'pdf')
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $itemPerPage = 20;

        if ($keyword = $this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            $topBorrowers = $this->loanModel
                ->select('members.*, COUNT(loans.id) as total_loans')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->where('loans.status_loan', 'Setuju')
                ->groupBy('members.id')
                ->orderBy('total_loans', 'DESC')
                ->limit(5)
                ->having('first_name LIKE', "%{$keyword}%")
                ->orHaving('last_name LIKE', "%{$keyword}%")
                ->orHaving('total_loans =', $keyword)
                ->paginate($itemPerPage, 'loans');
        } else {
            $topBorrowers = $this->loanModel
                ->select('members.*, COUNT(loans.id) as total_loans')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->where('loans.status_loan', 'Setuju')
                ->groupBy('members.id')
                ->orderBy('total_loans', 'DESC')
                ->limit(5)
                ->paginate($itemPerPage, 'loans');
        }

        foreach ($topBorrowers as &$borrower) {
            // Format date if needed
        }

        $data = [
            'topBorrowers'      => $topBorrowers,
            'pager'             => $this->loanModel->pager,
            'currentPage'       => $this->request->getVar('page_returns') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'user'              => $this->base_data['user'],
            'keyword'           => $this->request->getGet('search')
        ];

        return view('reports/statistics', $data);
    }

    public function printStatistics($format = 'pdf')
    {
        $keyword = $this->request->getGet('search');

        $topBorrowersQuery = $this->loanModel
            ->select('members.*, COUNT(loans.id) as total_loans')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->where('loans.status_loan', 'Setuju')
            ->groupBy('members.id')
            ->orderBy('total_loans', 'DESC')
            ->limit(5)
            ->having('first_name LIKE', "%{$keyword}%")
            ->orHaving('last_name LIKE', "%{$keyword}%")
            ->orHaving('total_loans =', $keyword);

        $topBorrowers = $topBorrowersQuery->findAll();

        $kepdin = $this->userModel->where('role', 'Kepala Dinas')->first();

        $this->reportModel->save([
            'user_id'       => $this->base_data['user']['id'],
            'description'   => 'Cetak Anggota Teraktif'
        ]);

        if ($format === 'html') {
            return view('reports/print_statistics', ['topBorrowers' => $topBorrowers, 'kepdin' => $kepdin]);
        } elseif ($format === 'pdf') {
            $pdfView = view('reports/print_statistics', ['topBorrowers' => $topBorrowers, 'kepdin' => $kepdin]);
    
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
    
            $dompdf->stream("Laporan Anggota Teraktif E-PERPUSJAR.pdf");
        }

        return view('reports/print_statistics', ['topBorrowers' => $topBorrowers, 'kepdin' => $kepdin]);
    }

    public function bookCategoryStatistics()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $statistics = $this->loanModel
            ->select('categories.name as category, COUNT(loans.id) as total_loans')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->groupBy('categories.name')
            ->findAll();

        $data = [
            'statistics' => $statistics,
            'user'       => $this->base_data['user']
        ];

        return view('filtersrack/book_category', $data);
    }

    public function printBookCategoryStatistics()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $itemPerPage = 20;

        if ($keyword = $this->request->getGet('search')) {
            $keyword = $this->request->getGet('search');
            // Get the statistics data
            $statistics = $this->loanModel
                ->select('categories.name as category, COUNT(loans.id) as total_loans')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->groupBy('categories.name')
                ->having('category LIKE', "%{$keyword}%")
                ->orHaving('total_loans =', $keyword)
                ->paginate($itemPerPage, 'loans');
        } else {
            $statistics = $this->loanModel
                ->select('categories.name as category, COUNT(loans.id) as total_loans')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->join('categories', 'books.category_id = categories.id', 'LEFT')
                ->groupBy('categories.name')
                ->paginate($itemPerPage, 'loans');
        }

        $data = [
            'statistics'        => $statistics,
            'pager'             => $this->loanModel->pager,
            'currentPage'       => $this->request->getVar('page_returns') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'user'              => $this->base_data['user'],
            'keyword'           => $this->request->getGet('search')
        ];

        return view('reports/book_category', $data);
    }

    public function printBookCategory($format = 'pdf')
    {
        $keyword = $this->request->getGet('search');

        $statisticsQuery = $this->loanModel
            ->select('categories.name as category, COUNT(loans.id) as total_loans')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->groupBy('categories.name')
            ->having('category LIKE', "%{$keyword}%")
            ->orHaving('total_loans =', $keyword);

        $statistics = $statisticsQuery->findAll();

        $kepdin = $this->userModel->where('role', 'Kepala Dinas')->first();

        $this->reportModel->save([
            'user_id'       => $this->base_data['user']['id'],
            'description'   => 'Cetak Kategori (KBT)'
        ]);

        if ($format === 'html') {
            return view('reports/print_book_category', ['statistics' => $statistics, 'kepdin' => $kepdin]);
        } elseif ($format === 'pdf') {
            $pdfView = view('reports/print_book_category', ['statistics' => $statistics, 'kepdin' => $kepdin]);
    
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
    
            $dompdf->stream("Laporan Data Kategori Buku Terlaris E-PERPUSJAR.pdf");
        }

        return view('reports/print_book_category', ['statistics' => $statistics, 'kepdin' => $kepdin]);
    }

    public function bookRackStatistics()
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $itemPerPage = 20;
        $keyword = $this->request->getGet('search');

        $racks = $this->loanModel
            ->select('racks.id, racks.name, racks.floor, COUNT(loans.id) as total_loans')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->groupBy('racks.id, racks.name, racks.floor')
            ->orderBy('total_loans', 'DESC');
    
        if ($keyword) {
            $racks->having('racks.name LIKE', "%{$keyword}%")
                  ->orHaving('racks.floor LIKE', "%{$keyword}%");
        }

        $racks = $racks->paginate($itemPerPage, 'racks');

        $bookCountInRacks = [];
        foreach ($racks as $rack) {
            $bookCountInRacks[$rack['id']] = $this->bookModel
                ->where('rack_id', $rack['id'])
                ->countAllResults();
        }

        $data = [
            'racks'             => $racks,
            'bookCountInRacks'  => $bookCountInRacks,
            'pager'             => $this->loanModel->pager,
            'currentPage'       => $this->request->getVar('page_racks') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'user'              => $this->base_data['user'],
            'keyword'           => $this->request->getGet('search')
        ];

        return view('reports/book_rack', $data);
    }

    public function printBookRack($format = 'pdf')
    {
        $keyword = $this->request->getGet('search');

        $racksQuery = $this->loanModel
            ->select('racks.id, racks.name, racks.floor, COUNT(loans.id) as total_loans')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->groupBy('racks.id, racks.name, racks.floor')
            ->orderBy('total_loans', 'DESC')
            ->having('racks.name LIKE', "%{$keyword}%")
            ->orHaving('racks.floor LIKE', "%{$keyword}%");

        $racks = $racksQuery->findAll();

        $bookCountInRacks = [];
        foreach ($racks as $rack) {
            $bookCountInRacks[$rack['id']] = $this->bookModel
                ->where('rack_id', $rack['id'])
                ->countAllResults();
        }

        $kepdin = $this->userModel->where('role', 'Kepala Dinas')->first();

        $this->reportModel->save([
            'user_id'       => $this->base_data['user']['id'],
            'description'   => 'Cetak Rak Buku Terlaris'
        ]);

        if ($format === 'html') {
            return view('reports/print_book_rack', ['racks' => $racks, 'bookCountInRacks' => $bookCountInRacks, 'kepdin' => $kepdin]);
        } elseif ($format === 'pdf') {
            $pdfView = view('reports/print_book_rack', ['racks' => $racks, 'bookCountInRacks' => $bookCountInRacks, 'kepdin' => $kepdin]);
    
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
    
            $dompdf->stream("Laporan Data Rak Buku Terlaris E-PERPUSJAR.pdf");
        }

        return view('reports/print_book_rack', ['racks' => $racks, 'bookCountInRacks' => $bookCountInRacks, 'kepdin' => $kepdin]);
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
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $loan = $this->loanModel->where('uid', $uid)->first();

        if (empty($loan)) {
            throw new PageNotFoundException('Loan not found');
        };

        if (!$this->loanModel->delete($loan['id'])) {
            session()->setFlashdata(['msg' => 'Failed to delete loan', 'error' => true]);
            return redirect()->back();
        }

        deleteLoansQRCode($loan['qr_code']);

        session()->setFlashdata(['msg' => 'Loan deleted successfully']);
        return redirect()->to('loans');
    }
}
