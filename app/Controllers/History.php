<?php

namespace App\Controllers;

use App\Models\LoanModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;

class History extends BaseController
{
    protected LoanModel $loanModel;

    public function __construct()
    {
        $this->loanModel = new LoanModel;
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

        return view('membershistory/index', $data);
    }
}