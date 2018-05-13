<?php 
//  ## Do not import this ##
//  This navbar file is imported in header.php. Therefore, just import header.php and you'll get both the header
//  and the nav bar.
//<h1 style="text-align:center">BasketStats</h1>
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/Teams/">Teams</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/Players/">Players</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/Games/">Games</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/Settings/">Settings</a>
      </li>
    </ul>
    <ul class="navbar-nav">
      <li class="nav-item">
        <button class="btn btn-outline-warning my-2 my-sm-0" onclick="location.href='/Auth/logout.php'">Logout</button>
      </li>
    </ul>
  </div>
</nav>
<?php
