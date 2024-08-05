<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>History Peminjaman</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php

use CodeIgniter\I18n\Time;

if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">

    <div class="row mb-2">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-4">History Peminjaman</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari history peminjaman" aria-label="Cari history peminjaman" aria-describedby="searchButton">
                <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama peminjam</th>
          <th scope="col">Judul buku</th>
          <th scope="col" class="text-center">Jumlah</th>
          <th scope="col">Tgl pinjam</th>
          <th scope="col" class="text-center">Status</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php
        $i = 1 + ($itemPerPage * ($currentPage - 1));

        $now = Time::now(locale: 'id');
        ?>
        <?php if (empty($loans)) : ?>
          <tr>
            <td class="text-center" colspan="9"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php
        foreach ($loans as $key => $loan) :
          $loanCreateDate = Time::parse($loan['loan_date'], locale: 'id');
          $loanDueDate = Time::parse($loan['due_date'], locale: 'id');

          $isLate = $now->isAfter($loanDueDate);
          $isDueDate = $now->today()->difference($loanDueDate)->getDays() == 0;
        ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <b><?= "{$loan['first_name']} {$loan['last_name']}"; ?></b>
            </td>
            <td>
              <a href="<?= base_url("membersbook/{$loan['slug']}"); ?>">
                <p class="text-primary-emphasis text-decoration-underline"><b><?= "{$loan['title']} ({$loan['year']})"; ?></b></p>
                <p class="text-body"><?= "Author: {$loan['author']}"; ?></p>
              </a>
            </td>
            <td class="text-center"><?= $loan['quantity']; ?></td>
            <td>
              <b><?= $loanCreateDate->toLocalizedString('dd/MM/y'); ?></b><br>
              <b><?= $loanCreateDate->toLocalizedString('HH:mm:ss'); ?></b>
            </td>
            <td class="text-center">
              <?php if ($loan['status_loan'] == 'Setuju') : ?>
                <span class="badge bg-success rounded-3 fw-semibold">Disetujui</span>
              <?php elseif ($loan['status_return'] == 'Setuju') : ?>
                <span class="badge bg-warning rounded-3 fw-semibold">Selesai</span>
              <?php elseif ($loan['status_loan'] == 'Proses') : ?>
                <span class="badge bg-warning rounded-3 fw-semibold">Proses</span>
              <?php elseif ($loan['status_loan'] == 'Tolak') : ?>
                <span class="badge bg-danger rounded-3 fw-semibold">Ditolak</span>
              <?php else : ?>
                <span class="badge bg-danger rounded-3 fw-semibold">Tidak Diketahui</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('membershistory', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>