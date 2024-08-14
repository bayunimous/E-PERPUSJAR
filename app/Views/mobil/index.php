<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Data Mobil</title>
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
    <div class="d-flex justify-content-between mb-2">
      <h5 class="card-title fw-semibold mb-4">Data Mobil</h5>
      <div>
        <a href="<?= base_url('mobil/new'); ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i>
          Tambah Mobil Baru
        </a>
      </div>
    </div>
    <div class="table-responsive">
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Merk</th>
          <th scope="col">Model</th>
          <th scope="col">Tahun</th>
          <th scope="col">Warna</th>
          <th scope="col">Harga</th>
          <th scope="col">Tanggal ditambah</th>
          <?php if ($user['role'] !== 'Kepala Dinas') : ?>
          <th scope="col" class="text-center">Aksi</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php if (empty($mobil)) : ?>
          <tr>
            <td class="text-center" colspan="8"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php foreach ($mobil as $key => $mobils) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <b><?= $mobils['merk']; ?></b>
            </td>
            <td>
              <?= $mobils['model']; ?>
            </td>
            <td>
              <?= $mobils['tahun']; ?>
            </td>
            <td>
              <?= $mobils['warna']; ?>
            </td>
            <td>
              <?= $mobils['harga']; ?>
            </td>
            <td>
              <?= $mobils['created_at']; ?>
            </td>
            <?php if ($user['role'] !== 'Kepala Dinas') : ?>
            <td>
              <div class="d-flex justify-content-center gap-2">
                <a href="<?= base_url("mobil/{$mobils['id']}/edit"); ?>" class="btn btn-primary mb-2">
                  <i class="ti ti-edit"></i>
                  Edit
                </a>
                <form action="<?= base_url("mobil/{$mobils['id']}"); ?>" method="post">
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
    <?= $pager->links('mobil', 'my_pager'); ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>