<?php

namespace App\Controllers;

use App\Libraries\QRGenerator;
use App\Models\BookModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use Dompdf\Dompdf;
use Dompdf\Options;

class Loans extends BaseController
{
    protected LoanModel $loanModel;
    protected MemberModel $memberModel;
    protected BookModel $bookModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->loanModel = new LoanModel;
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
            ->select('members.*, members.uid as member_uid, books.*, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
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

        $data = [
            'loans'         => $builder->paginate($itemPerPage, 'loans'),
            'pager'         => $builder->pager,
            'currentPage'   => $this->request->getVar('page_loans') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'search'        => $this->request->getGet('search'),
            'member'        => $this->base_data['member']
        ];

        $data['loans'] = array_filter($data['loans'], function ($loan) {
            return $loan['deleted_at'] == null && $loan['return_date'] == null;
        });

        return view('membersloan/index', $data);
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
            ->select('members.*, members.uid as member_uid, books.*, loans.*, loans.qr_code as loan_qr_code, loans.status_loan as loan_status_loan, book_stock.quantity as book_stock, racks.name as rack, categories.name as category')
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

            return redirect()->to("membersloan/{$loan['uid']}");
        }

        $data = [
            'loan'         => $loan,
            'member'       => $this->base_data['member']
        ];

        return view('membersloan/show', $data);
    }

    public function searchMember()
    {
        if ($this->member == false) {
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
                return view('membersloan/member', ['msg' => 'Member not found']);
            }

            return view('membersloan/member', ['members' => $members, 'member' => $this->base_data['member']]);
        }

        return view('membersloan/search_member', ['member' => $this->base_data['member']]);
    }

    public function searchBook()
    {
        if ($this->member == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        if ($this->request->isAJAX()) {
            $param = $this->request->getVar('param');
            $memberUid = $this->request->getVar('memberUid');

            if (empty($param)) return;

            if (empty($memberUid)) {
                return view('membersloan/book', ['msg' => 'Member UID is empty']);
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
                return view('membersloan/book', ['msg' => 'Book not found']);
            }

            $books = array_map(function ($book) {
                $newBook = $book;
                $newBook['stock'] = $this->getRemainingBookStocks($book);
                return $newBook;
            }, $books);

            return view('membersloan/book', ['books' => $books, 'memberUid' => $memberUid, 'member' => $this->base_data['member']]);
        }

        $memberUid = $this->request->getVar('member-uid');

        if (empty($memberUid)) {
            session()->setFlashdata(['msg' => 'Select member first', 'error' => true]);
            return redirect()->to('membersloan/new/members/search');
        }

        $members = $this->memberModel->where('uid', $memberUid)->first();

        if (empty($members)) {
            session()->setFlashdata(['msg' => 'Member not found', 'error' => true]);
            return redirect()->to('membersloan/new/members/search');
        }

        return view('membersloan/search_book', ['members' => $members, 'member' => $this->base_data['member']]);
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
        if ($this->member == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('membersloan/new/members/search');
        }

        $member = $this->memberModel
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
            'member'     => $member,
            'validation' => $validation ?? \Config\Services::validation(),
            'oldInput'   => $oldInput,
            'member'     => $this->base_data['member']
        ];

        return view('membersloan/create', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
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
            return redirect()->to('membersloan/new/members/search');
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
                'status_loan' => 'Proses',
            ];

            $this->loanModel->insert($newLoan);

            array_push($newLoanIds, $this->loanModel->getInsertID());
        }

        $petugas = $this->userModel->where('role', 'Petugas')->first();

        $data = [
            'api_key'           => 'FNHBA401J42948KEATX28CZH6GAC5KY9',
            'device_key'        => 'LKAWLH',
            'destination'       => preg_replace('/\D/', '', $petugas['phone']),
            'message'           => 'Hallo *'.$petugas['full_name'].'* ada peminjaman buku baru *'.$book['title'].'* dari *'.$member['first_name'].' '.$member['last_name'].'* pada tanggal *'.Time::now()->toDateTimeString().'*.',
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

        $newLoans = array_map(function ($id) {
            return $this->loanModel->select('members.*, members.uid as member_uid, books.*, loans.*')
                ->join('members', 'loans.member_id = members.id', 'LEFT')
                ->join('books', 'loans.book_id = books.id', 'LEFT')
                ->where('loans.id', $id)->first();
        }, $newLoanIds);

        return view('membersloan/result', [
            'newLoans'  => $newLoans,
            'member'    => $this->base_data['member']
        ]);
    }

    public function reportLoans($format = 'pdf')
    {
        $loans = $this->loanModel
            ->select('members.*, members.uid as member_uid, books.*, loans.*')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->findAll();

        foreach ($loans as &$loan) {
            $loan['loan_date'] = Time::parse($loan['loan_date'], 'UTC')->toLocalizedString('yyyy-MM-dd HH:mm:ss');
            $loan['due_date'] = Time::parse($loan['due_date'], 'UTC')->toLocalizedString('yyyy-MM-dd HH:mm:ss');
            $loan['return_date'] = $loan['return_date'] ? Time::parse($loan['return_date'], 'UTC')->toLocalizedString('yyyy-MM-dd HH:mm:ss') : null;
        }

        if ($format === 'html') {
            return view('reports/report_loans', ['loans' => $loans]);
        } elseif ($format === 'pdf') {
            $pdfView = view('reports/report_loans', ['loans' => $loans]);

            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream("Laporan Data Peminjaman Buku E-PERPUSJAR", array("Attachment" => false));
        }

        return view('reports/report_loans', ['loans' => $loans]);
    }


    public function statistics()
    {
        // Mengambil data peminjam dengan jumlah peminjaman terbanyak
        $topBorrowers = $this->loanModel
            ->select('members.*, COUNT(loans.id) as total_loans')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->groupBy('members.id')
            ->orderBy('total_loans', 'DESC')
            ->limit(5) // Ubah sesuai kebutuhan
            ->findAll();

        $data = [
            'topBorrowers' => $topBorrowers,
            'member'       => $this->base_data['member']
        ];

        return view('statisticsloan/statistics', $data);
    }

    public function printStatistics($format = 'pdf')
    {
        $topBorrowers = $this->loanModel
            ->select('members.*, COUNT(loans.id) as total_loans')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->groupBy('members.id')
            ->orderBy('total_loans', 'DESC')
            ->limit(5)
            ->findAll();

        foreach ($topBorrowers as &$borrower) {
            // Format date if needed
        }

        $data = [
            'topBorrowers' => $topBorrowers,
        ];

        if ($format === 'html') {
            return view('reports/print_statistics', $data); // Gantilah file view jika diperlukan
        } elseif ($format === 'pdf') {
            $pdfView = view('reports/print_statistics', $data); // Gantilah file view jika diperlukan

            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($pdfView);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream("Laporan Anggota Teraktif E-PERPUSJAR", array("Attachment" => false));
        }

        return view('reports/print_statistics', $data);
    }

    public function bookCategoryStatistics()
    {
        $statistics = $this->loanModel
            ->select('categories.name as category, COUNT(loans.id) as total_loans')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->groupBy('categories.name')
            ->findAll();

        $data = [
            'statistics' => $statistics,
        ];

        return view('filtersrack/book_category', $data);
    }

    public function printBookCategoryStatistics()
    {
        // Get the statistics data
        $statistics = $this->loanModel
            ->select('categories.name as category, COUNT(loans.id) as total_loans')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->groupBy('categories.name')
            ->findAll();

        // Pass the data to the view
        $data = [
            'statistics' => $statistics,
        ];

        // Load the PDF view
        $pdf = view('reports/print_book_category', $data);

        // Set up the PDF options
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // Create the Dompdf instance
        $dompdf = new \Dompdf\Dompdf($options);

        // Load the HTML content into Dompdf
        $dompdf->loadHtml($pdf);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF (output)
        $dompdf->render();

        // Stream the PDF to the browser
        $dompdf->stream("Laporan Data Kategori Buku Terlaris E-PERPUSJAR.pdf");
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
