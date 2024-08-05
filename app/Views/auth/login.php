<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Login</title>
<?= $this->endSection() ?>

<?= $this->section('back'); ?>
<a href="<?= base_url(); ?>" class="btn btn-outline-primary m-3 position-absolute">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="container d-flex justify-content-center p-5">
  <div class="card col-12 col-md-5 shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-5">Login</h5>

    <?php if (session()->getFlashdata('msg')) : ?>
      <div class="pb-2">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= session()->getFlashdata('msg') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    <?php endif; ?>

      <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Email -->
        <div class="mb-2">
          <input type="email" class="form-control <?php if ($validation->hasError('email')) : ?>is-invalid<?php endif ?>" name="email" value="<?= $oldInput['email'] ?? ''; ?>" placeholder="Email Address" />
          <div class="invalid-feedback">
            <?= $validation->getError('email'); ?>
          </div>
        </div>

        <!-- Password -->
        <div class="mb-2">
          <input type="password" class="form-control <?php if ($validation->hasError('password')) : ?>is-invalid<?php endif ?>" name="password" value="<?= $oldInput['password'] ?? ''; ?>" placeholder="Password" />
          <div class="invalid-feedback">
            <?= $validation->getError('password'); ?>
          </div>
        </div>

        <!-- reCAPTCHA -->
        <div class="mb-2">
          <div class="g-recaptcha" data-sitekey="6LeMKB4qAAAAADA3PI656vbu6JSuSUBQs_OF8Tik"></div>
        </div>

        <!-- Remember me -->
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')) : ?> checked<?php endif ?>>
              Remember me?
            </label>
          </div>

        <div class="d-grid col-12 mx-auto m-3">
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </div>

        <p class="text-center">Need an account? <a href="<?= url_to('register') ?>">Register</a></p>

      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>