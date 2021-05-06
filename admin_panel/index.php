<?php
session_start();
require "../config/config.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
?>

<?php include("header.php"); ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Post Table</h3>
          </div>

          <?php
          if (!empty($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
          } else {
            $pageno = 1;
          }

          $numOfRecs = 2;
          $offset = ($pageno - 1) * $numOfRecs;

          if (empty($_POST['search'])) {
            $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
            $stmt->execute();
            $rawresult = $stmt->fetchAll();
            $totalpages = ceil(count($rawresult) / $numOfRecs);

            $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfRecs");
            $stmt->execute();
            $result = $stmt->fetchAll();
          } else {
            $searchKey = $_POST['search'];
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
            $stmt->execute();
            $rawresult = $stmt->fetchAll();
            $totalpages = ceil(count($rawresult) / $numOfRecs);

            $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfRecs");
            $stmt->execute();
            $result = $stmt->fetchAll();
          }
          ?>
          <!-- /.card-header -->
          <div class="card-body">
            <a href="add.php" type="button" class="btn btn-success mb-4">Create New post</a>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th style="width: 40px">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php

                if ($result) {
                  $i = 1;
                  foreach ($result as $r) {


                ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td class="text-nowrap"><?php echo $r['title']; ?></td>
                      <td><?php echo substr($r['content'], 0, 50); ?></td>
                      <td class="text-nowrap">
                        <a href="edit.php?id=<?php echo $r['id']; ?>" type="button" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $r['id']; ?>" type="button" class="btn btn-danger" onclick="return confirm('Are you sure to want to delete?')">Delete</a>
                      </td>
                    </tr>
                <?php
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
            <nav aria-label="Page navigation example" class="mt-3">
              <ul class="pagination justify-content-end">
                <li class="page-item">
                  <a class="page-link" href="?pageno=1" tabindex="-1">First</a>
                </li>
                <li class="page-item <?php if ($pageno <= 1) {
                                        echo 'disabled';
                                      } ?>">
                  <a class="page-link" href="<?php if ($pageno <= 1) {
                                                echo '#';
                                              } else {
                                                echo '?pageno=' . ($pageno - 1);
                                              } ?>">Prev</a>
                </li>
                <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                <li class="page-item <?php if ($pageno >= $totalpages) {
                                        echo 'disabled';
                                      } ?>">
                  <a class="page-link" href="<?php if ($pageno >= $totalpages) {
                                                echo '#';
                                              } else {
                                                echo '?pageno=' . ($pageno + 1);
                                              } ?>">Next</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="?pageno=<?php echo $totalpages; ?>">Last</a>
                </li>
              </ul>
            </nav>
          </div>
          <!-- /.card-body -->

        </div>
      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php include("footer.html"); ?>