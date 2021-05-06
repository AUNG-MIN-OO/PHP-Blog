<?php
session_start();
require "../config/config.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
}

if ($_POST) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if ($_FILES['image']['name'] != null) {
        $file = 'images/' . ($_FILES['image']['name']);
        $imageType = pathinfo($file, PATHINFO_EXTENSION);

        if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
            echo "<script>alert('image must be png,jpg,jpeg');</script>";
        } else {
            $image = $_FILES['image']['name'];

            move_uploaded_file($_FILES['image']['tmp_name'], $file);

            $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
            $result = $stmt->execute();
            if ($result) {
                echo "<script>alert('Post successfully added');window.location.href='index.php';</script>";
            }
        }
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
        $result = $stmt->execute();
        if ($result) {
            echo "<script>alert('Post successfully added without photo');window.location.href='index.php';</script>";
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=" . $_GET['id']);
$stmt->execute();

$result = $stmt->fetchAll();

?>



<?php include("header.php"); ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <h4 class="text-bold">Edit Post</h4>
                            <div class="form-group">
                                <input type="hidden" id="id" name="id" value="<?php echo $result[0]['id']; ?>">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="title" value="<?php echo $result[0]['title']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea type="text" class="form-control" name="content" value="" required><?php echo $result[0]['content']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Image</label>
                                <br>
                                <img src="images/<?php echo $result[0]['image']; ?>" width="150px" height="150px" alt="" class="mb-3">
                                <br>
                                <input type="file" name="image" value="">
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Submit" name="submit" class="btn btn-primary">
                                <a href="index.php" class="btn btn-default">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php include("footer.html"); ?>