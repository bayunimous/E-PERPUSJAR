<?php

namespace App\Controllers\Books;

use App\Models\BookModel;
use App\Models\RackModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;

class RacksController extends BaseController
{
    protected RackModel $rackModel;
    protected BookModel $bookModel;

    public function __construct()
    {
        $this->rackModel = new RackModel;
        $this->bookModel = new BookModel;
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

        $racks = $this->rackModel->paginate($itemPerPage, 'racks');

        $bookCountInRacks = [];

        foreach ($racks as $rack) {
            array_push($bookCountInRacks, $this->bookModel
                ->where('rack_id', $rack['id'])
                ->countAllResults());
        }

        $data = [
            'racks'             => $racks,
            'bookCountInRacks'  => $bookCountInRacks,
            'pager'             => $this->rackModel->pager,
            'currentPage'       => $this->request->getVar('page_racks') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'user'              => $this->base_data['user']
        ];

        return view('racks/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        if ($this->user == false) {
            session()->setFlashdata(['msg' => 'Please login first']);
            return redirect()->to('login');
        }

        $rack = $this->rackModel->where('id', $id)->first();

        if (empty($rack)) {
            throw new PageNotFoundException('Rack not found');
        }

        $itemPerPage = 20;

        $books = $this->bookModel
            ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->where('rack_id', $id)
            ->paginate($itemPerPage, 'books');

        $data = [
            'books'         => $books,
            'pager'         => $this->bookModel->pager,
            'currentPage'   => $this->request->getVar('page_books') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'rack'          => $this->rackModel
                ->select('racks.name')
                ->where('id', $id)->first()['name'],
            'user'          => $this->base_data['user']
        ];

        return view('books/index', $data);
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

        return view('racks/create', [
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
            'rack'  => 'required|alpha_numeric_punct|max_length[8]',
            'floor' => 'permit_empty|if_exist|alpha_numeric_punct|max_length[16]',
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            return view('racks/create', $data);
        }

        if (!$this->rackModel->save([
            'name' => $this->request->getVar('rack'),
            'floor' => !empty($this->request->getVar('floor')) ? $this->request->getVar('floor') : 1,
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('racks/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new rack successful']);
        return redirect()->to('racks');
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

        $rack = $this->rackModel->where('id', $id)->first();

        if (empty($rack)) {
            throw new PageNotFoundException('Rack not found');
        }

        $data = [
            'rack'          => $rack,
            'validation'    => \Config\Services::validation(),
            'user'          => $this->base_data['user']
        ];

        return view('racks/edit', $data);
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

        $rack = $this->rackModel->where('id', $id)->first();

        if (empty($rack)) {
            throw new PageNotFoundException('Category not found');
        }

        if (!$this->validate([
            'rack'  => 'required|alpha_numeric_punct|max_length[8]',
            'floor' => 'permit_empty|if_exist|alpha_numeric_punct|max_length[16]',
        ])) {
            $data = [
                'rack'       => $rack,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            return view('racks/edit', $data);
        }

        if (!$this->rackModel->save([
            'id'   => $id,
            'name' => $this->request->getVar('rack'),
            'floor' => !empty($this->request->getVar('floor')) ? $this->request->getVar('floor') : 1,
        ])) {
            $data = [
                'rack'   => $rack,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('racks/create', $data);
        }

        session()->setFlashdata(['msg' => 'Update rack successful']);
        return redirect()->to('racks');
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

        $rack = $this->rackModel->where('id', $id)->first();

        if (empty($rack)) {
            throw new PageNotFoundException('Rack not found');
        }

        if (!$this->rackModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete rack', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'Rack deleted successfully']);
        return redirect()->to('racks');
    }
}
