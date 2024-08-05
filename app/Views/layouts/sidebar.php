<!-- Sidebar Start -->
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <!-- Brand -->
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <div class="pt-4 mx-auto">
        <a href="<?= base_url('dashboard'); ?>">
          <h2>E-<span class="text-primary">PERPUSJAR</span></h2>
        </a>
      </div>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>

    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <?php
        $username = '';
        $email = '';
        $userGroup = '';
        if (!empty($member)) {
            $username = $member['username'];
            $email = $member['email'];
            $userGroup = 'Anggota';
        } elseif (!empty($user)) {
            $username = $user['username'];
            $email = $user['email'];
            $userGroup = $user['role'];
        }
        ?>

        <!-- Home -->
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(uri_string(), 'dashboard') === 0) ? 'active' : ''; ?>" href="<?= base_url('dashboard'); ?>" aria-expanded="false">
            <span>
              <i class="ti ti-layout-dashboard"></i>
            </span>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>

        <!-- Master -->
        <?php if ($userGroup !== 'Anggota'): ?>
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Master</span>
          </li>
          <?php if ($userGroup == 'Administrator'): ?>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'users') === 0) ? 'active' : ''; ?>" href="<?= base_url('users'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-user"></i>
                </span>
                <span class="hide-menu">Pengguna</span>
              </a>
            </li>
          <?php endif; ?>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'members') === 0) ? 'active' : ''; ?>" href="<?= base_url('members'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-user"></i>
              </span>
              <span class="hide-menu">Anggota</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'books') === 0) ? 'active' : ''; ?>" href="<?= base_url('books'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-book"></i>
              </span>
              <span class="hide-menu">Buku</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'categories') === 0) ? 'active' : ''; ?>" href="<?= base_url('categories'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-category-2"></i>
              </span>
              <span class="hide-menu">Kategori</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'racks') === 0) ? 'active' : ''; ?>" href="<?= base_url('racks'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-columns"></i>
              </span>
              <span class="hide-menu">Rak</span>
            </a>
          </li>
          <?php if ($userGroup == 'Kepala Dinas'): ?>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'printreport') === 0) ? 'active' : ''; ?>" href="<?= base_url('printreport'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-file"></i>
                </span>
                <span class="hide-menu">Laporan Yang Dicetak</span>
              </a>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <!-- Transaksi -->
        <?php if ($userGroup !== 'Anggota' || $userGroup === 'Anggota'): ?>
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Transaksi</span>
          </li>
          <?php if ($userGroup == 'Anggota'): ?>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'membersloan') === 0) ? 'active' : ''; ?>" href="<?= base_url('membersloan'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-arrows-exchange"></i>
                </span>
                <span class="hide-menu">Peminjaman</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'membersreturn') === 0) ? 'active' : ''; ?>" href="<?= base_url('membersreturn'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-check"></i>
                </span>
                <span class="hide-menu">Pengembalian</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'membersbook') === 0) ? 'active' : ''; ?>" href="<?= base_url('membersbook'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-book"></i>
                </span>
                <span class="hide-menu">Daftar Buku</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'membershistory') === 0) ? 'active' : ''; ?>" href="<?= base_url('membershistory'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-history"></i>
                </span>
                <span class="hide-menu">History Peminjaman</span>
              </a>
            </li>
          <?php endif; ?>
          <?php if ($userGroup !== 'Anggota'): ?>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'loans') === 0) ? 'active' : ''; ?>" href="<?= base_url('loans'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-arrows-exchange"></i>
                </span>
                <span class="hide-menu">Peminjaman</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'returns') === 0) ? 'active' : ''; ?>" href="<?= base_url('returns'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-check"></i>
                </span>
                <span class="hide-menu">Pengembalian</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'fines') === 0) ? 'active' : ''; ?>" href="<?= base_url('fines'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-report-money"></i>
                </span>
                <span class="hide-menu">Denda</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'filtersrack/book_category') === 0) ? 'active' : ''; ?>" href="<?= base_url('filtersrack/book_category'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-filter"></i>
                </span>
                <span class="hide-menu">Kategori Buku Terlaris (KBT)</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?= (strpos(uri_string(), 'statisticsloan/statistics') === 0) ? 'active' : ''; ?>" href="<?= base_url('statisticsloan/statistics'); ?>" aria-expanded="false">
                <span>
                  <i class="ti ti-bookmark"></i>
                </span>
                <span class="hide-menu">Anggota Teraktif</span>
              </a>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <!-- Cetak Laporan -->
        <?php if ($userGroup !== 'Anggota'): ?>
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Cetak Laporan</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'reports/loans') === 0) ? 'active' : ''; ?>" href="<?= base_url('reports/loans'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-arrows-exchange"></i>
              </span>
              <span class="hide-menu">Cetak Peminjaman</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'reports/returns') === 0) ? 'active' : ''; ?>" href="<?= base_url('reports/returns'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-check"></i>
              </span>
              <span class="hide-menu">Cetak Pengembalian</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'reports/fines') === 0) ? 'active' : ''; ?>" href="<?= base_url('reports/fines'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-report-money"></i>
              </span>
              <span class="hide-menu">Cetak Denda</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'reports/book_category') === 0) ? 'active' : ''; ?>" href="<?= base_url('reports/book_category'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-filter"></i>
              </span>
              <span class="hide-menu">Cetak Kategori (KBT)</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'reports/statistics') === 0) ? 'active' : ''; ?>" href="<?= base_url('reports/statistics'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-bookmark"></i>
              </span>
              <span class="hide-menu">Cetak Anggota Teraktif</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'reports/book_rack') === 0) ? 'active' : ''; ?>" href="<?= base_url('reports/book_rack'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-columns"></i>
              </span>
              <span class="hide-menu">Cetak Rak Buku Terlaris</span>
            </a>
          </li>
          <?php if ($userGroup == 'Petugas'): ?>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'facilitys') === 0) ? 'active' : ''; ?>" href="<?= base_url('facilitys'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-home"></i>
              </span>
              <span class="hide-menu">Cetak Penggunaan Fasilitas</span>
            </a>
          </li>
          <?php endif; ?>
          <?php if ($userGroup == 'Kepala Dinas'): ?>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'performances') === 0) ? 'active' : ''; ?>" href="<?= base_url('performances'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-users"></i>
              </span>
              <span class="hide-menu">Cetak Pelayanan Petugas</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'reports/report') === 0) ? 'active' : ''; ?>" href="<?= base_url('reports/report'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-file"></i>
              </span>
              <span class="hide-menu">Cetak Laporan Dicetak</span>
            </a>
          </li>
          <?php endif; ?>
          <?php if ($userGroup == 'Administrator'): ?>
          <li class="sidebar-item">
            <a class="sidebar-link <?= (strpos(uri_string(), 'reports/users') === 0) ? 'active' : ''; ?>" href="<?= base_url('reports/users'); ?>" aria-expanded="false">
              <span>
                <i class="ti ti-users"></i>
              </span>
              <span class="hide-menu">Cetak Pengguna</span>
            </a>
          </li>
          <?php endif; ?>
        <?php endif; ?>

      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->