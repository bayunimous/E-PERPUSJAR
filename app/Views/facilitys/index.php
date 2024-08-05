<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Data Penggunaan Fasilitas</title>
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
        <h5 class="card-title fw-semibold mb-4">Data Penggunaan Fasilitas</h5>
        <?php if ($user['role'] !== 'Kepala Dinas') : ?>
        <div>
          <a href="<?= base_url('facilitys/new'); ?>" class="btn btn-primary py-2">
            <i class="ti ti-plus"></i>
            Tambah Penggunaan Fasilitas Baru
          </a>
        </div>
        <?php endif; ?>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="row mb-3">
                <div class="col-md-12">
                  <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari penggunaan fasilitas" aria-label="Cari penggunaan fasilitas" aria-describedby="searchButton">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6 mb-3">
                  <label for="add_date_from">Tanggal Ditambah (Dari):</label>
                  <input type="date" class="form-control" id="add_date_from" name="add_date_from" value="<?= old('add_date_from', $addDateFrom) ?>">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="add_date_to">Tanggal Ditambah (Sampai):</label>
                  <input type="date" class="form-control" id="add_date_to" name="add_date_to" value="<?= old('add_date_to', $addDateTo) ?>">
                </div>
                <div class="col-md-6 d-grid">
                  <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
                </div>
                <div class="col-md-6 d-grid">
                  <a href="<?= base_url('facilitys/report_facilitys') . '?add_date_from=' . $addDateFrom . '&add_date_to=' . $addDateTo; ?>" class="btn btn-primary py-2">
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
          <th scope="col">#</th>
          <th scope="col">Fasilitas</th>
          <th scope="col">Deskripsi</th>
          <th scope="col">Tanggal ditambah</th>
          <?php if ($user['role'] !== 'Kepala Dinas') : ?>
          <th scope="col" class="text-center">Aksi</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php if (empty($facilitys)) : ?>
          <tr>
            <td class="text-center" colspan="5"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php foreach ($facilitys as $key => $facility) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <b><?= $facility['title']; ?></b>
            </td>
            <td>
              <?= nl2br($facility['description']); ?>
            </td>
            <td>
              <?= $facility['created_at']; ?>
            </td>
            <?php if ($user['role'] !== 'Kepala Dinas') : ?>
            <td>
              <div class="d-flex justify-content-center gap-2">
                <a href="<?= base_url("facilitys/{$facility['id']}/edit"); ?>" class="btn btn-primary mb-2">
                  <i class="ti ti-edit"></i>
                  Edit
                </a>
                <form action="<?= base_url("facilitys/{$facility['id']}"); ?>" method="post">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">
                    <i class="ti ti-trash"></i>
                    Delete
                  </button>
                </form>
              </div>
            </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('facilitys', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>