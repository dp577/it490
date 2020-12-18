
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <style>
            @media 
            only screen and (max-width: 760px),
            (min-device-width: 768px) and (max-device-width: 1024px)  {

                table, thead, tbody, th, td, tr { 
                    display: block; 
                }
                thead tr { 
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }
                
                tr { border: 1px solid #ccc; }
                
                td { 
                    border: none;
                    border-bottom: 1px solid #eee; 
                    position: relative;
                    padding-left: 50%; 
                }
                
                td:before { 
                    position: absolute;
                    top: 6px;
                    left: 6px;
                    width: 45%; 
                    padding-right: 10px; 
                    white-space: nowrap;
                }
            }
        </style>
    </head>
    <body>
        <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
            
            include ('testRabbitMQClient.php');
            include ('functionHolder.php');
            
            session_start();
            
            if(!isset($_SESSION['username'])) {
                header("location:../loginPage.php");
                exit(0);
            }
            echo '<nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                                <span class="glyphicon glyphicon-align-justify"></span>
                            </button>
                            <a class="navbar-brand" href="homepage.php">CDKT-Tech.Com</a>
                        </div>
                        <div class="collapse navbar-collapse" id="myNavbar">
                            <ul class="nav navbar-nav">
                                <li><a class="nav-item nav-link active" href="homepage.php">Homepage</a></li>
                                <li><a class="nav-item nav-link" href="songDiscovery.php">Song Discovery</a></li>
                                <li><a class="nav-item nav-link" href="profile.php">Your Profile</a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>';
        ?>
        <div class="container bg-faded">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1><u>Welcome to Song Search</u></h1>
                    <h3>Below is a limited list of songs within our database.</h3>
                    <h3>Feel free to use the provided search queries to find a song you are looking for.</h3>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12">
                    <div class="center-block well" style="width: 80%">
                        <form class="form-inline" action="./songSearcher.php">
                            <div class="form-group">
                                <label for="email">Song:</label>
                                <input type="text" class="form-control" placeholder="Enter Song" name="song">
                            </div>
                            <div class="form-group">
                                <label for="email">Album:</label>
                                <input type="text" class="form-control" placeholder="Enter Album" name="album">
                            </div>
                            <div class="form-group">
                                <label for="email">Artist:</label>
                                <input type="text" class="form-control" placeholder="Enter Artist" name="artist">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-default" value="Search" name="searchQuery">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center well">
                    <form class="form-inline" action="./songSearcher.php">
                        <div class="form-group">
                            <input type="submit" class="btn btn-default" value="Random Results" name="random">
                        </div>
                    </form>
                </div>
            </div>
        
            <?php
                if(isset($_GET['Add'])){
                    $songAdded = addSongToProfile($_SESSION['username'], $_GET['Add']);
                    
                    if($songAdded) {
                        echo "<b>".$_GET['songName']." by ".$_GET['artistName']." has been successfully added to your profile.</b><br><br>";
                    }
                    else {
                        echo "<b>Song not added because it is already added to your profile.</b><br><br>";
                    }
                }
            ?>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <table class="table table-striped" role="table">
                        <thead role="rowgroup">
                            <tr role="row">
                                <th role="columnheader">Song</th>
                                <th role="columnheader">Album</th>
                                <th role="columnheader">Artist</th>
                                <th role="columnheader">Release Date</th>
                                <th role="columnheader">Song Length</th>
                                <th role="columnheader">Popularity</th>
                                <th role="columnheader">Add Song to Profile</th>
                            </tr>
                        </thead>
                        <tbody role="rowgroup">
                            
                        <?php
                            if(isset($_GET['searchQuery'])) {
                                $querySong = strtolower($_GET['song']);
                                $queryArtist = strtolower($_GET['artist']);
                                $queryAlbum = strtolower($_GET['album']);
                                $searchQueryArray = songSearchQuery($querySong, $queryAlbum, $queryArtist);
                                
                                if($searchQueryArray == "no rows") {
                                    echo "<b>No results found, please try again.</b><br><br>";
                                } elseif (((empty($_GET['song'])) && (empty($_GET['artist'])) && (empty($_GET['album'])))) {
                                    echo "<b>Too many search fields left empty, please try again.</b><br><br>";
                                } else {
                                    for($i = 0; $i<count($searchQueryArray); $i++) {
                        ?>
                            <tr role="row">
                                <td role="cell">
                                    <?php echo $searchQueryArray[$i][2];?>
                                </td>
                                <td role="cell">
                                    <?php echo $searchQueryArray[$i][3];?>
                                </td>
                                <td role="cell">
                                    <?php echo $searchQueryArray[$i][4];?>
                                </td>
                                <td role="cell">
                                    <?php echo $searchQueryArray[$i][5];?>
                                </td>
                                <td role="cell">
                                    <?php echo milliConversion($searchQueryArray[$i][6]);?>
                                </td>
                                <td role="cell">
                                    <?php echo $searchQueryArray[$i][7];?>
                                </td>
                                <td role="cell">
                                    <form action="./songSearcher.php">
                                        <input type="submit" name=<?php echo $searchQueryArray[$i][1]; ?> value="Add">
                                        <input type="hidden" name="Add" value=<?php echo $searchQueryArray[$i][1];?>>
                                        <input type="hidden" name="songName" value="<?php echo $searchQueryArray[$i][2];?>">
                                        <input type="hidden" name="artistName" value="<?php echo $searchQueryArray[$i][4];?>">
                                    </form>
                                </td>
                            </tr>
                        <?php
                                    }   
                                }   
                            } elseif ((!isset($_GET['searchQuery']) && isset($_SESSION['username']))) {
                                $songInfoArray = songSearch();
                                for($i=1; $i<count($songInfoArray); $i++) {
                        ?>
                            <tr role="row">
                                <td role="cell">
                                    <?php echo $songInfoArray[$i][2]; ?>
                                </td>
                                <td role="cell">
                                    <?php echo $songInfoArray[$i][3]; ?>
                                </td>
                                <td role="cell">
                                    <?php echo $songInfoArray[$i][4]; ?>
                                </td>
                                <td role="cell">
                                    <?php echo $songInfoArray[$i][5]; ?>
                                </td>
                                <td role="cell">
                                    <?php echo milliConversion($songInfoArray[$i][6]); ?>
                                </td>
                                <td role="cell">
                                    <?php echo $songInfoArray[$i][7]; ?>
                                </td>
                                <td role="cell">
                                    <form action=./songSearcher.php> <input type="submit" name=<?php echo $songInfoArray[$i][1]; ?>
                                        value="Add">
                                        <input type="hidden" name="Add" value=<?php echo $songInfoArray[$i][1];?>>
                                        <input type="hidden" name="songName" value="<?php echo $songInfoArray[$i][2];?>">
                                        <input type="hidden" name="artistName" value="<?php echo $songInfoArray[$i][4];?>">
                                    </form>
                                </td>
                            </tr>
                        <?php
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="homepage.php">Homepage</a> | <a href="songDiscovery.php">Song Discovery</a> | <a href="profile.php">Your Profile</a> | <a href="logout.php?logout">Logout</a>
        <script>
            $(document).ready(function () {
                let css_Prop = '<style>@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px) {';
                let index = 1;
                $("table thead tr th").each(function () {
                    css_Prop += 'td:nth-of-type(' + index++ + '):before { content: "' + $(this).text() + '"; }';
                });
                css_Prop += '}</style>';
                $(css_Prop).appendTo('head');
            });
        </script>
    </body>
</html>
