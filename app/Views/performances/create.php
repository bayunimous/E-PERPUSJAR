<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pelayanan Petugas Baru</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('performances'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold">Form Pelayanan Petugas Baru</h5>
    <form action="<?= base_url('performances'); ?>" method="post">
      <?= csrf_field(); ?>
      <div class="my-3">
        <label for="user" class="form-label">Nama lengkap petugas</label>
        <select class="form-control <?php if ($validation->hasError('user_id')) : ?>is-invalid<?php endif ?>" name="user_id" id="user" required>
            <option selected disabled>- Pilih Petugas -</option>
            <?php foreach ($staffs as $staff) : ?>
            <option value="<?= $staff['id']; ?>" <?= ($oldInput['user_id'] ?? '') == $staff['id'] ? 'selected' : ''; ?>><?= $staff['full_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">
          <?= $validation->getError('user_id'); ?>
        </div>
      </div>
      <div class="mb-3">
        <label for="rating" class="form-label">Rating</label>
        <div class="rating">
          <input type="hidden" id="rating" name="rating" value="<?= $oldInput['rating'] ?? ''; ?>" required>
          <div class="stars">
            <i class="fas fa-star" data-value="1"></i>
            <i class="fas fa-star" data-value="2"></i>
            <i class="fas fa-star" data-value="3"></i>
            <i class="fas fa-star" data-value="4"></i>
            <i class="fas fa-star" data-value="5"></i>
          </div>
        </div>
        <div class="invalid-feedback">
          <?= $validation->getError('rating'); ?>
        </div>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control <?php if ($validation->hasError('description')) : ?>is-invalid<?php endif ?>" id="description" name="description" placeholder="Kerjanya sangat rajin" rows="5" required><?= $oldInput['description'] ?? ''; ?></textarea>
        <div class="invalid-feedback">
          <?= $validation->getError('description'); ?>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const stars = document.querySelectorAll('.stars i');
  const ratingInput = document.getElementById('rating');

  stars.forEach(star => {
    star.addEventListener('click', function() {
      const value = this.getAttribute('data-value');
      ratingInput.value = value;
      updateStars(value);
    });
  });

  function updateStars(value) {
    stars.forEach(star => {
      if (star.getAttribute('data-value') <= value) {
        star.classList.add('active');
      } else {
        star.classList.remove('active');
      }
    });
  }

  const currentRating = ratingInput.value;
  if (currentRating) {
    updateStars(currentRating);
  }
});
</script>
<?= $this->endSection() ?>