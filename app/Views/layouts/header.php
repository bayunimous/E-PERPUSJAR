<!--  Header Start -->
<style>
  @media only screen and (max-width: 768px) {
    #navBtn {
      display: none;
    }
  }
</style>
<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
    </ul>
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end gap-2" id="headerCollapse">
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
        
        <?php if ($userGroup == 'Anggota') : ?>
        <li class=" nav-item" id="navBtn">
          <a href="<?= base_url('membersloan/new/books/search'); ?>?member-uid=<?= $member['uid']; ?>" class="btn btn-primary">Ajukan peminjaman</a>
        </li>
        <li class="nav-item" id="navBtn">
          <a href="<?= base_url('membersreturn/new/search'); ?>" class="btn btn-outline-primary">Pengembalian buku</a>
        </li>
        <?php else : ?>
        <li class=" nav-item" id="navBtn">
          <a href="<?= base_url('loans/new/books/search'); ?>" class="btn btn-primary">Ajukan peminjaman</a>
        </li>
        <li class="nav-item" id="navBtn">
          <a href="<?= base_url('returns/new/search'); ?>" class="btn btn-outline-primary">Pengembalian buku</a>
        </li>
        <li class="nav-item" id="navBtn">
          <a href="<?= base_url('fines/returns/search'); ?>" class="btn btn-outline-warning">Bayar denda</a>
        </li>
        <?php endif; ?>
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon-hover position-relative" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
            <img alt="" width="35" height="35" class="rounded-circle border border-primary" style="background-color: white;">
            <i class="ti ti-user position-absolute top-50 start-50 translate-middle text-primary"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" style="min-width: 300px;" aria-labelledby="drop2">
            <div class="message-body">
                <div class="mx-3 mt-2">
                    <h5>Profil</h5>
                    <span>Username: <b><?= $username; ?></b></span><br>
                    <span>Email: <b><?= $email; ?></b></span><br>
                    <span>Level: </span>
                    <?php if (!empty($userGroup)) : ?>
                        <span class="badge bg-success rounded-3 fw-semibold text-black"><?= $userGroup; ?></span>
                    <?php else : ?>
                        <span class="badge bg-black rounded-3 fw-semibold">Unknown</span>
                    <?php endif; ?>
                </div>
                <a href="<?= base_url('logout'); ?>" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>
<!--  Header End -->