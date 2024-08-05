<?php

namespace App\Controllers;

use App\Libraries\QRGenerator;
use App\Models\MemberModel;
use App\Models\UserModel;

class Auth extends BaseController
{

    protected MemberModel $memberModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel;
        $this->userModel = new UserModel;
    }
    
    public function logins()
    {
        if ($this->member || $this->user) {
            return redirect()->to('dashboard');
        }

        return view('auth/login', [
            'validation' => \Config\Services::validation()
        ]);
    }
    
    public function login()
    {
        if ($this->member || $this->user) {
            return redirect()->to('dashboard');
        }

        if (!$this->validate([
            'email'    => 'required|valid_email|max_length[255]',
            'password' => 'required|min_length[4]'
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];
            
            return view('auth/login', $data);
        }

        $recaptchaResponse = $this->request->getVar('g-recaptcha-response');
        $secretKey = '6LeMKB4qAAAAABX4q_QoAP82_DXP0DuLqwsMkgLg';
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';

        $response = file_get_contents($recaptchaUrl . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
        $responseKeys = json_decode($response, true);

        if (!$responseKeys['success']) {
            session()->setFlashdata(['msg' => 'reCAPTCHA verification failed. Please try again.']);
            return redirect()->to('login')->withInput();
        }

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $member = $this->memberModel->where('email', $email)->first();

        if (!empty($member) && password_verify($password, $member['password'])) {
            session()->set('member', $member['email']);
            session()->setFlashdata(['msg' => 'Login successful']);
            return redirect()->to('dashboard');
        }

        $user = $this->userModel->where('email', $email)->first();
        
        if (!empty($user) && password_verify($password, $user['password'])) {
            session()->set('user', $user['email']);
            session()->setFlashdata(['msg' => 'Login successful']);
            return redirect()->to('dashboard');
        }

        session()->setFlashdata(['msg' => 'Incorrect email or password']);
        return redirect()->to('login');
    }
    
    public function registers()
    {
        if ($this->member || $this->user) {
            return redirect()->to('dashboard');
        }

        return view('auth/register', [
            'validation' => \Config\Services::validation()
        ]);
    }
    
    public function register()
    {
        if ($this->member || $this->user) {
            return redirect()->to('dashboard');
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
            ];

            return view('auth/register', $data);
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
            ];

            session()->setFlashdata('errors', $errors);
            return view('auth/register', $data);
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
            ];

            session()->setFlashdata(['msg' => 'Register failed']);
            return view('auth/register', $data);
        }

        session()->setFlashdata(['msg' => 'Register successful']);
        return redirect()->to('login');
    }
    
    public function logout()
    {
        $this->session->remove('member');
        $this->session->remove('user');
        session()->setFlashdata(['msg' => 'Logout successful']);
        return redirect()->to('login');
    }
}