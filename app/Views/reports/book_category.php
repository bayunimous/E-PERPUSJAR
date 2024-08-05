<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Data Cetak Kategori</title>
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
        <h5 class="card-title fw-semibold mb-4">Data Cetak Kategori</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari cetak kategori" aria-label="Cari cetak kategori" aria-describedby="searchButton">
                <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
              </div>
            </form>
          </div>
          <div>
            <a href="<?= base_url('reports/print_book_category') . '?search=' . $keyword; ?>" class="btn btn-primary py-2">
                <i class="ti ti-print"></i>
                Cetak
            </a>
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Kategori Buku</th>
          <th scope="col">Jumlah Peminjaman</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php if (empty($statistics)) : ?>
          <tr>
            <td class="text-center" colspan="7"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php foreach ($statistics as $key => $statistic) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td><?= $statistic['category'] ?? 'N/A'; ?></td>
            <td><?= $statistic['total_loans'] ?? 0; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('loans', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>