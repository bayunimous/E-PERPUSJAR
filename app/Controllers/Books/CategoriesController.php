<?php

namespace App\Controllers\Books;

use App\Models\BookModel;
use App\Models\CategoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;

class CategoriesController extends BaseController
{
    protected CategoryModel $categoryModel;
    protected BookModel $bookModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel;
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

        $categories = $this->categoryModel->paginate($itemPerPage, 'categories');

        $bookCountInCategories = [];

        foreach ($categories as $category) {
            array_push($bookCountInCategories, $this->bookModel
                ->where('category_id', $category['id'])
                ->countAllResults());
        }

        $data = [
            'categories'        => $categories,
            'bookCountInCategories' => $bookCountInCategories,
            'pager'             => $this->categoryModel->pager,
            'currentPage'       => $this->request->getVar('page_categories') ?? 1,
            'itemPerPage'       => $itemPerPage,
            'user'              => $this->base_data['user']
        ];

        return view('categories/index', $data);
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

        $category = $this->categoryModel->where('id', $id)->first();

        if (empty($category)) {
            throw new PageNotFoundException('Category not found');
        }

        $itemPerPage = 20;

        $books = $this->bookModel
            ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->where('category_id', $id)
            ->paginate($itemPerPage, 'books');

        $data = [
            'books'         => $books,
            'pager'         => $this->bookModel->pager,
            'currentPage'   => $this->request->getVar('page_books') ?? 1,
            'itemPerPage'   => $itemPerPage,
            'category'      => $this->categoryModel
                ->select('categories.name')
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

        return view('categories/create', [
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
            'category'  => 'required|string|min_length[2]',
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            return view('categories/create', $data);
        }

        if (!$this->categoryModel->save([
            'name' => $this->request->getVar('category'),
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('categories/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new category successful']);
        return redirect()->to('categories');
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

        $category = $this->categoryModel->where('id', $id)->first();

        if (empty($category)) {
            throw new PageNotFoundException('Category not found');
        }

        $data = [
            'category'       => $category,
            'validation'     => \Config\Services::validation(),
            'user'           => $this->base_data['user']
        ];

        return view('categories/edit', $data);
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

        $category = $this->categoryModel->where('id', $id)->first();

        if (empty($category)) {
            throw new PageNotFoundException('Category not found');
        }

        if (!$this->validate([
            'category'  => 'required|string|min_length[2]',
        ])) {
            $data = [
                'category'   => $category,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            return view('categories/edit', $data);
        }

        if (!$this->categoryModel->save([
            'id'   => $id,
            'name' => $this->request->getVar('category'),
        ])) {
            $data = [
                'category'   => $category,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
                'user'       => $this->base_data['user']
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('categories/create', $data);
        }

        session()->setFlashdata(['msg' => 'Update category successful']);
        return redirect()->to('categories');
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

        $category = $this->categoryModel->where('id', $id)->first();

        if (empty($category)) {
            throw new PageNotFoundException('Category not found');
        }

        if (!$this->categoryModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete category', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'Category deleted successfully']);
        return redirect()->to('categories');
    }
}
