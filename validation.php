<?php
    $PostUname = $_POST["username"];
    function remoteFileExists($url) {
        $curl = curl_init($url);
        //don't fetch the actual page, you only want to check the connection is ok
        curl_setopt($curl, CURLOPT_NOBODY, true);
        //do request
        $result = curl_exec($curl);
        $ret = false;
        //if request did not fail
        if ($result !== false) {
            //if request was ok, check response code
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                $ret = true;
            }
    }
    curl_close($curl);
    return $ret;
}

    //$PostUname = "K-Kraken";
    $ch = curl_init();
    curl_setopt_array($ch, [
       CURLOPT_URL => "https://api.github.com/users/".$PostUname,
       CURLOPT_HTTPHEADER => [
           "Accept: application/vnd.github.v3+json",
           "Content-Type: application/json",
           "User-Agent: AppleWebKit/567.36 (KHTML, like Gecko) Chrome/45.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/531.36"
       ],
       CURLOPT_RETURNTRANSFER => 1
   ]);
    $json = curl_exec($ch);
    curl_close($ch);
    $jsa = json_decode($json, true);
    try {
        if(isset($jsa['message'])){
            // throw exception if username doesn't exist if "message" json obj exist
            throw new Exception();
        }
        $flag = "true";
        $username = $jsa["login"];
        $fname = $jsa["name"];
        $imgurl = $jsa["avatar_url"];

        /* Check for website */
        $e1 = remoteFileExists("https://".$PostUname.".github.io/Design-Jam-Submission/index.html");

        /* Check for repository */
        $ch1 = curl_init();
        curl_setopt_array($ch1, [
            CURLOPT_URL => "https://api.github.com/repos/".$PostUname."/Design-Jam-Submission",
            CURLOPT_HTTPHEADER => [
                "Accept: application/vnd.github.v3+json",
                "Content-Type: application/json",
                "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/537.36"
            ],
            CURLOPT_RETURNTRANSFER => 1
        ]);
        $json1 = curl_exec($ch1);
        curl_close($ch1);
        $jsa1 = json_decode($json1, true);
        if(isset($jsa1['message'])){
            // throw exception if repo doesn't exist if "message" json obj exist
            $e2 = false;
        }
        else{
            $e2 = true;
        }

        /* Result */
        /*
            e1: site
            e2: repository
        */
        // Repo and site found
        if ($e1 == true && $e2 == true){
            $status = "Website and Repository Found :)";
            $flag = "true";

        }
        // Repo found and site not found
        else if ($e1 == false && $e2 == true){
            $status = "Website not found but Repository exist. Recheck your GitHub Pages settings ";
            $flag = "false";
        }
        // Repo not found and site found
        else if ($e1 == true && $e2 == false){
            $status = "Website exist but the Repository is private or does not exist. Kindly make your website Public ";
            $flag = "false";
        }
        // Repo not found and site not found
        else if ($e1 == false && $e2 == false){
            $status = "Both Website and Repository are inaccessible or not Found.";
            $flag = "false";
        }
    }
    catch(Exception $e){
        $flag = "false";
        $username = "Username not found :(";
        $fname = "Username not found :(";
        $imgurl = "assets/img/notfound.jpg";
        $status = "Username not found :(";
    }
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Submission Page - Design Jam 2.0</title>
    <link rel="icon" type="image/png" sizes="600x600" href="assets/img/acm_sym_1s_flat_pos.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
<title>Design Jam 2.0 - Home</title>
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-lg fixed-top bg-dark navbar-custom">
        <div class="container"><a class="navbar-brand" href="#">Design Jam 2.0</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navbarResponsive"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="https://svce.acm.org/submission.html">Go Back</a></li>
                    <li class="nav-item" role="presentation"></li>
                </ul>
            </div>
        </div>
    </nav>
    <section>
        <div class="container"></div>
    </section>
    <section></section>
    <div class="register-photo" style="height: auto;padding-top: 121px;">
        <div class="form-container">
            <div class="image-holder"></div>
            <form action="push.php" method="post">
                <h2 class="text-center" style="height: 13px;">Validation Result</h2>
                <div style="padding-bottom: 1em;">
                    <img src="<?php echo $imgurl; ?>" alt="Avatar" style="display: block; width: 100px; height: 100px; border:1px solid #dfe7f1; border-radius: 50%; margin-left: auto;  margin-right: auto;" class="avatar">
                </div>
                <div class="form-group">
                    <p style="margin-bottom: 6px;"><strong>Username:</strong></p>
                    <textarea name="gh_id" class="form-control" readonly><?php echo $username; ?></textarea>
                </div>
                <p style="margin-bottom: 6px;"><strong>Name (in GitHub Profile):</strong></p>
                <div class="form-group">
                    <textarea name="gh_fname" class="form-control" readonly ><?php echo $fname; ?></textarea>
                </div>
                <p style="margin-bottom: 6px;"><strong>Repository and Website Status:
                <?php
                    /* Range: Green */
                    if ($flag == "true"){
                        echo "<i style=\"color:DarkGreen;\" class=\"fa fa-check-circle\"></i>";
                    }
                    // Range: Red
                    else {
                        echo "<i style=\"color:DarkRed;\" class=\"fa fa-ban\"></i>";
                    }
                ?>

                </strong></p>
                <div class="form-group">
                    <textarea name="status" class="form-control" style="height: 7em" readonly ><?php echo $status; ?></textarea>
                </div>
                <div class="form-group">
                    <p style="margin-bottom: 6px;"><strong>Enter your registered Email Address (In Design Jam 2.0):
                    <input type="email" name="email" class="form-control" required/>
                </div>
                <div class="form-group">
                    <div class="form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" required>This is my account and I agree to the terms and conditions.</label></div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block" id="submit-btn" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>
    <script src="assets/js/script.min.js"></script>
    <script>
        document.getElementById("submit-btn").addEventListener("click", function(event){
            if (<?php echo $flag; ?> == false) {
                window.alert("Kindly rectify the given errors and refresh the page to unlock the submit button. If any problem persists, reach out to us at acm.svcecse@gmail.com");
                event.preventDefault();
            }
        });
    </script>
</body>

</html>
