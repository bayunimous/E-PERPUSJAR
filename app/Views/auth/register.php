<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Register</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container d-flex justify-content-center p-5">
  <div class="card col-12 col-md-5 shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-5">Register</h5>

        <?php if (session()->getFlashdata('msg')) : ?>
          <div class="pb-2">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?= session()->getFlashdata('msg') ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
          <div class="pb-2">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                  <li>- <?= esc($error) ?></li>
                <?php endforeach; ?>
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
        <?php endif; ?>

      <form action="<?= url_to('register') ?>" method="post">
        <?= csrf_field() ?>

          <div class="row mt-3">
            <div class="col-12 col-md-6 mb-3">
              <label for="first_name" class="form-label">Nama depan</label>
              <input type="text" class="form-control <?php if ($validation->hasError('first_name')) : ?>is-invalid<?php endif ?>" id="first_name" name="first_name" value="<?= $oldInput['first_name'] ?? ''; ?>" placeholder="John Doe">
              <div class="invalid-feedback">
                <?= $validation->getError('first_name'); ?>
              </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="last_name" class="form-label">Nama belakang</label>
              <input type="text" class="form-control <?php if ($validation->hasError('last_name')) : ?>is-invalid<?php endif ?>" id="last_name" name="last_name" value="<?= $oldInput['last_name'] ?? ''; ?>">
              <div class="invalid-feedback">
                <?= $validation->getError('last_name'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control <?php if ($validation->hasError('email')) : ?>is-invalid<?php endif ?>" id="email" name="email" value="<?= $oldInput['email'] ?? ''; ?>" placeholder="johndoe@gmail.com">
              <div class="invalid-feedback">
                <?= $validation->getError('email'); ?>
              </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="phone" class="form-label">Nomor telepon</label>
              <input type="tel" class="form-control <?php if ($validation->hasError('phone')) : ?>is-invalid<?php endif ?>" id="phone" name="phone" value="<?= $oldInput['phone'] ?? ''; ?>" placeholder="+628912345">
              <div class="invalid-feedback">
                <?= $validation->getError('phone'); ?>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control <?php if ($validation->hasError('username')) : ?>is-invalid<?php endif ?>" id="username" name="username" value="<?= $oldInput['username'] ?? ''; ?>" placeholder="johndoe123">
            <div class="invalid-feedback">
              <?= $validation->getError('username'); ?>
            </div>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control <?php if ($validation->hasError('password')) : ?>is-invalid<?php endif ?>" id="password" name="password" value="<?= $oldInput['password'] ?? ''; ?>">
            <div class="invalid-feedback">
              <?= $validation->getError('password'); ?>
            </div>
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Alamat</label>
            <textarea class="form-control <?php if ($validation->hasError('address')) : ?>is-invalid<?php endif ?>" id="address" name="address"><?= $oldInput['address'] ?? ''; ?></textarea>
            <div class="invalid-feedback">
              <?= $validation->getError('address'); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 mb-3">
              <label for="date_of_birth" class="form-label">Tanggal lahir</label>
              <input type="date" class="form-control <?php if ($validation->hasError('date_of_birth')) : ?>is-invalid<?php endif ?>" id="date_of_birth" name="date_of_birth" value="<?= $oldInput['date_of_birth'] ?? ''; ?>">
              <div class="invalid-feedback">
                <?= $validation->getError('date_of_birth'); ?>
              </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label class="form-label">Jenis kelamin</label>
              <div class="my-2 <?php if ($validation->hasError('gender')) : ?>is-invalid<?php endif ?>">
                <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="male" name="gender" value="1" <?= $oldInput['gender'] ?? '' == '1' ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="male">
                    Laki-laki
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" id="female" name="gender" value="2" <?= $oldInput['gender'] ?? '' == '2' ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="female">
                    Perempuan
                  </label>
                </div>
              </div>
              <div class="invalid-feedback">
                <?= $validation->getError('gender'); ?>
              </div>
            </div>
          </div>
        
        <div class="d-grid col-12 mx-auto m-3">
          <button type="submit" class="btn btn-primary btn-block">Register</button>
        </div>
        
        <p class="text-center">Have account? <a href="<?= url_to('login') ?>">Login</a></p>

      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>