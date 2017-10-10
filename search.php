<?php
//print_r($_GET);
//print_r($_POST);
include_once "Repository.php";

session_start();

$repository = new Repository();

if (!empty($_GET['search'])) {
    $search = $_GET['search'];

    $posts = $repository->SearchPostsByWordsInTitle($search);

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Blog Home</title>

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
                    if (isset($_SESSION['authorized'])) {
                        echo "<li><a href=\"create.php\">Create</a></li>";
                        echo "<li><a href=\"signOut.php\">Sign out</a></li>";
                    } else {
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

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Posts -->
                <?php
                if (!empty($posts)) {
                    echo "<h1 class=\"page-header\">Search result:</h1>";
                    foreach ($posts as $post) {
                        ?>
                        <h2>
                            <a href="#"><?php echo $post->title ?></a>
                        </h2>
                        <p class="lead">
                            by <a href="index.php"><?php echo $post->login ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post->date ?></p>
                        <hr>
                        <?php
                        if ($post->picture !== "null") {
                            echo "<img class=\"img-responsive\" src=\"./uploads/$post->picture\" alt=\"\">";
                            echo "<hr>";
                        }
                        ?>
                        <?php
                        $short = substr($post->description, 0, 300);
                        echo "<p>$short...</p>";
                        ?>
                        <a class="btn btn-primary" href="<?php echo "details.php?post_id=$post->id" ?>">Read More <span
                                    class="glyphicon glyphicon-chevron-right"></span></a>

                        <hr>

                        <?php
                    }
                } else {
                    echo "<h1 class=\"page-header\">Nothing was found by \"$search\"</h1>";
                }
                ?>

                <!-- Pager -->
                <ul class="pager">
                    <li class="previous">
                        <a href="#">&larr; Older</a>
                    </li>
                    <li class="next">
                        <a href="#">Newer &rarr;</a>
                    </li>
                </ul>

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
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci
                        accusamus
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
        $(function () {
            $("#searchBtn").click(function () {
                if ($('#searchInput').val()) {
                    window.location.href = 'search.php?search=' + $('#searchInput').val();
                }
            })
        })
    </script>
    </body>

    </html>


    <?php
} else {
    die(header("location:index.php"));
}