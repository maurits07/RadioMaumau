<?php
function HTMLhead($pagetitle){
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css.css">
        <title><?php echo $pagetitle ?></title>
    </head>
    <body>
        <form action="" method="post">
            <header>
                <nav>
                    <ul> <?php
                             $selectedDirect = 1;
                             $directs = GetDirectInfo($selectedDirect); ?>
                        <li><a href="<?= $directs['DirectHome'] ?>"> Home</a></li>
                        <li><a href="<?= $directs['DirectContact'] ?>">Contact</a></li>
                        <li><a href="<?= $directs['DirectPlaylist'] ?>"> Playlist</a><li>
                        <li><a href="<?= $directs['DirectTop2000'] ?>"> Top2000</a><li>
                    </ul>
                </nav>
                <div class="logo">
                    <a href="<?= $directs['DirectPlaylist'] ?>">
                        <img class="ImgLogo" src="img/RadioMauMau.png">
                    </a>
                </div>
            </header>
    <?php
}

function HTMLfoot(){}
?>
<?php
function Info(){
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["video"])) {
        $selectedVideo = $_POST["video"];
        $videoInfo = GetVideoInfo($selectedVideo);
        $audioInfo = getAudioInfo($selectedVideo);
    } else {
        // Default video
        $selectedVideo = 1;
        $videoInfo = GetVideoInfo($selectedVideo);
        $audioInfo = getAudioInfo($selectedVideo);
    }
    ?>
    <body>
        <div class="main"> 
            <iframe class="GrowUp" src="<?= $videoInfo['albumVideo'] ?>" controls poster="<?= $videoInfo['posterURL'] ?>"></iframe>
            <table class="tableAudio">
                <tr>
                    <th>Title</th>
                    <th>Length</th>
                    <th>Song</th>
                </tr>
                <?php foreach ($audioInfo as $audio): ?>
                    <tr>
                        <td><?= $audio['trackTitle'] ?></td>
                        <td><?= $audio['trackDuration'] ?></td>
                        <td><audio class="GrowUpAudio" src="<?= $audio['trackFile'] ?>" controls></audio></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <ol>
                <li>
                    <button name="video" value="1">
                        <img class="Album" src="img/NFcover.jpeg">
                    </button>
                </li>
                <li>
                    <button name="video" value="2">
                        <img class="Album" src="img/EminemCover.jpeg">
                    </button>
                </li>
                <li>
                    <button name="video" value="3">
                        <img class="Album" src="img/NFClouds.webp">
                    </button>
                </li>
            </ol>
        </div>
    </form>
    </body>
    </html>
    <?php
}

function GetVideoInfo($selectedVideo) {
    $conn = Connect();
    $query = "SELECT * FROM album WHERE albumID = $selectedVideo";
    $result = $conn->query($query);
    $videoInfo = $result->fetch_assoc();
    $conn->close();
    return $videoInfo;
}

function getAudioInfo($selectedVideo){
    $conn = Connect();
    $query = "SELECT * FROM tracks WHERE albumID = $selectedVideo";
    $result = $conn->query($query);

    $audioInfo = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $audioInfo[] = $row;
        }
    }

    $conn->close();
    return $audioInfo;
}

function Connect(){
    $serverName = "localhost";
    $userName = "root";
    $password = "";
    $databaseName = "radiomaumau";

    // Create a connection
    $conn = new mysqli($serverName, $userName, $password, $databaseName);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function GetArtistInfo() {
    $conn = Connect();
    $query = "SELECT * FROM `artist` ORDER BY `artistld` ASC";
    $result = $conn->query($query);
    $artistInfo = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $artistInfo;
}


function artist(){  

$artistInfo = GetArtistInfo();

// dd($artistInfo);
    
    ?>
   <table class= "ArtistTable">
    <?php
    foreach($artistInfo as $artist)
    {
        ?>
         <tr>
            <td> <img src="<?= $artist['artistImage'] ?>" style="width: 300px"; alt=""></td>
            <td><?= $artist['artistName'] ?></td>
            <td><?= substr($artist['artistDetails'], 0, 50) ?>...</td>
        </tr>
        <?php
    }
    ?>
</table>
<?php

}

 
function GetDirectInfo($selectedDirect) {
    $conn = Connect();
    $query = "SELECT * FROM `direct` ORDER BY `direct`.`DirectHome` DESC";
    $result = $conn->query($query);
    
    // Fetch the row for the selected direct
    $directInfo = $result->fetch_assoc();
    
    $conn->close();
    return $directInfo;
}



function GetTop2000Info() {
    $conn = Connect();
    $query = "SELECT * FROM `brpj_top2000_2023`";
    $result = $conn->query($query);
    $top2000Info = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $top2000Info;
}


function top2000() {
    $top2000Info = GetTop2000Info(); 
    ?>
    <table class="Top2000Table">
        <?php
        foreach ($top2000Info as $top2000) {
        ?>
            <tr id="top">
                <td><?= $top2000['songPosition'] ?></td> 
                <td><?= $top2000['songTitle'] ?></td>
                <td><?= substr($top2000['songArtist'], 0, 50) ?>...</td>
                <td><?= $top2000['songYear'] ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <?php 
}

