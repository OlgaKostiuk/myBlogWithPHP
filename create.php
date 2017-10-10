<?php
include_once "Repository.php";
include_once "Credentials.php";

session_start();

if(!isset($_SESSION['authorized']))
{
    die(header("location:signIn.php"));
}

$repository = new Repository();

extract($_POST);

if (isset($title) && isset($description) && isset($category_id)) {

    $post = new Post();
    $post->title = $title;
    $post->description = $description;
    $post->category_id = $category_id;

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
            $info = getimagesize($_FILES['picture']['tmp_name']);
            if ($info === false) {
                die("Unable to determine image type of uploaded file");
            }
            if (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
                die("Not a gif/jpeg/png");
            }
            $name = $_FILES['picture']['name'];
            $ext = pathinfo($name)['extension'];
            $newName = trim(com_create_guid(), "{}") . "." . $ext;
            move_uploaded_file($_FILES['picture']['tmp_name'], "uploads/" . $newName);
            $post->picture = $newName;
    } else {
        $post->picture = "null";
    }

    $post = $repository->AddNewPost($post);

    die(header("location:details.php?post_id=$post->id"));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog Create</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/blog-home.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">My BLOG</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                if(isset($_SESSION['authorized'])){
                    echo "<li class='active'><a href=\"create.php\">Create</a></li>";
                    echo "<li><a href=\"signOut.php\">Sign out</a></li>";
                }
                else{
                    echo "<li><a href=\"signIn.php\">Sign in</a></li>";
                }
                ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <div class="col-lg-8">
            <form action="create.php" method="POST" class="form-horizontal" enctype="multipart/form-data" role="form">
                <div class="form-group">
                    <legend>Create post</legend>
                </div>

                <div class="form-group">
                    <label for="title" class="col-sm-2" control-label">Title:</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" id="title" class="form-control"
                               value="" title="" required="required">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-2" control-label">Description:</label>
                    <div class="col-sm-10">
                        <textarea name="description" id="description" class="form-control"
                               required="required"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="category_id" class="col-sm-2 control-label">Category:</label>
                    <div class="col-sm-10">
                        <select name="category_id" id="category_id" class="form-control">
                            <?php
                            $categories = $repository->getAllCategories();
                            foreach ($categories as $category) {
                                echo "<option value=\"$category->id\">$category->title</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="picture" class="col-sm-2 control-label">Picture:</label>
                    <div class="col-sm-10">
                        <input type="hidden" name="MAX_FILE_SIZE" value="700000"/>
                        <input type="file" class="" name="picture" id="picture" placeholder="picture"
                               accept="image/jpeg,image/png,image/gif">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Blog Sidebar Widgets Column -->
        <div class="col-md-4">

            <!-- Blog Search Well -->
            <div class="well">
                <h4>Blog Search</h4>
                <div class="input-group">
                    <input id="searchInput" type="text" class="form-control">
                    <span class="input-group-btn">
                            <button id="searchBtn" class="btn btn-default" type="button">
                                <span class="glyphicon glyphicon-search"></span>
                        </button>
                        </span>
                </div>
                <!-- /.input-group -->
            </div>

            <!-- Blog Categories Well -->
            <div class="well">
                <h4>Blog Categories</h4>
                <div class="row">
                    <?php
                    $allCategories = $repository->GetAllCategories();
                    $halved = array_chunk($allCategories, ceil(count($allCategories) / 2));
                    ?>
                    <div class="col-lg-6">
                        <ul class="list-unstyled">
                            <?php
                            foreach ($halved[0] as $category) {
                                echo "<li><a href=\"index.php?category_id=$category->id\">$category->title</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <ul class="list-unstyled">
                            <?php
                            foreach ($halved[1] as $category) {
                                echo "<li><a href=\"index.php?category_id=$category->id\">$category->title</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <!-- /.row -->
            </div>

            <!-- Side Widget Well -->
            <div class="well">
                <h4>Side Widget Well</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus
                    laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
            </div>

        </div>

    </div>
    <!-- /.row -->

    <hr>

    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; Your Website 2014</p>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </footer>

</div>
<!-- /.container -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<script>
    $(function(){
        $("#searchBtn").click(function () {
            if($('#searchInput').val()){
                window.location.href = 'search.php?search=' + $('#searchInput').val();
            }
        })
    })
</script>
</body>

</html>
