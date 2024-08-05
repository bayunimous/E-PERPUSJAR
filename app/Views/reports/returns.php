<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Data Cetak Pengembalian</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (session()->getFlashdata('msg')) : ?>
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
        <h5 class="card-title fw-semibold mb-4">Data Cetak Pengembalian</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="row mb-3">
                <div class="col-md-12">
                  <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari cetak pengembalian" aria-label="Cari cetak pengembalian" aria-describedby="searchButton">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6 mb-3">
                  <label for="loan_date_from">Tanggal Pinjam (Dari):</label>
                  <input type="date" class="form-control" id="loan_date_from" name="loan_date_from" value="<?= old('loan_date_from', $loanDateFrom) ?>">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="loan_date_to">Tanggal Pinjam (Sampai):</label>
                  <input type="date" class="form-control" id="loan_date_to" name="loan_date_to" value="<?= old('loan_date_to', $loanDateTo) ?>">
                </div>
                <div class="col-md-6 d-grid">
                  <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
                </div>
                <div class="col-md-6 d-grid">
                  <a href="<?= base_url('reports/report_returns') . '?loan_date_from=' . $loanDateFrom . '&loan_date_to=' . $loanDateTo . '&return_date_from=' . $returnDateFrom . '&return_date_to=' . $returnDateTo; ?>" class="btn btn-primary py-2">
                    <i class="ti ti-print"></i>
                    Cetak
                  </a>
                </div>
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
          <th scope="col">Nama Peminjam</th>
          <th scope="col">Judul Buku</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Tanggal Pinjam</th>
          <th scope="col">Tanggal Pengembalian</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php if (empty($loans)) : ?>
          <tr>
            <td class="text-center" colspan="7"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php foreach ($loans as $key => $loan) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td><?= $loan['first_name'] . ' ' . $loan['last_name']; ?></td>
            <td><?= $loan['title']; ?></td>
            <td><?= $loan['quantity']; ?></td>
            <td><?= $loan['loan_date']; ?></td>
            <td><?= $loan['return_date']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('loans', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>