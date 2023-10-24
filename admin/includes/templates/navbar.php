<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
<a class="navbar-brand" href="dashboard.php"><?php echo lang("HOME_ADMIN"); ?></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="app-nav">
<ul class="navbar-nav mr-auto">
    <li class="nav-item "><a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES') ?></a></li>
    <li class="nav-item "><a class="nav-link" href="items.php"><?php echo lang('ITEMS') ?></a></li>
    <li class="nav-item "><a class="nav-link" href="members.php?do=Manage"><?php echo lang('MEMBERS') ?></a></li>
    <li class="nav-item "><a class="nav-link" href="#"><?php echo lang('COMMENTS') ?></a></li>
    <li class="nav-item "><a class="nav-link" href="#"><?php echo lang('STATISTICS') ?></a></li>
    <li class="nav-item "><a class="nav-link" href="#"><?php echo lang('LOGS') ?></a></li>
</ul>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown ml-auto">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
        somaia
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a>
        <a class="dropdown-item" href="#">Sitting</a>
        <a class="dropdown-item" href="logout.php">Logout</a>
      </div>
    </li>
  </ul>
</div>
</div>
</nav>