<html>
<head>
<title>cURL Share Max Speed</title>
</head>
<body>
<script src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
<div id="ldavn"></div>
<?php
    if(isset($_GET['token'], $_GET['idshare'])){ // share.php?token=TOKEN&idshare=IDSTT
        $token = $_GET['token'];
        $idshare = $_GET['idshare'];
    }else{
        echo 'CÚT';
    }
    $token_page = json_decode(file_get_contents('https://graph.fb.me/me/accounts?access_token='.$token),true);
    //print_r($token_page);
        foreach ($token_page['data'] as $data) {
        echo '<script>$("#ldavn").load("https://graph.facebook.com/'.$idshare.'/sharedposts?access_token='.$data['access_token'].'&method=post");</script>';
    }
?>
</body>
</html>
