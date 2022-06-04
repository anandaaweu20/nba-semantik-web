<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NBA Semantic Web</title>
    
        <link rel="stylesheet" href="src/style.css">
    </head>

    <body>

        <!-- Fetching and Connecting data from PHP to SPARQL -->
        <?php
            require_once("sparqllib.php");
            $searchInput = "" ;
            $filter = "" ;
            
            if (isset($_POST['search'])) {
                $searchInput = $_POST['search'];
                $data = sparql_get(
                "http://localhost:3030/nba",
                "
                    PREFIX t: <http://nba.com/team#>
                    PREFIX d: <http://nba.com/team/data#>

                    SELECT ?name ?arena ?capacity ?division ?conference ?NBAChamp ?conferenceChamp
                    WHERE {
                        ?team   d:name              ?name ;
                                d:arena             ?arena ;
                                d:capacity          ?capacity ;
                                d:division          ?division ;
                                d:conference        ?conference ;
                                d:countNBAChamp     ?NBAChamp ;
                                d:countConfChamp    ?conferenceChamp .
                                FILTER 
                                (regex (?name, '$searchInput', 'i') 
                                || regex (?arena, '$searchInput', 'i') 
                                || regex (?capacity, '$searchInput', 'i') 
                                || regex (?division, '$searchInput', 'i') 
                                || regex (?conference, '$searchInput', 'i') 
                                || regex (?NBAChamp, '$searchInput', 'i') 
                                || regex (?conferenceChamp, '$searchInput', 'i'))
                    }
                "
                );
            } else {
                $data = sparql_get(
                "http://localhost:3030/lapbook",
                "
                    PREFIX t: <http://nba.com/team#>
                    PREFIX d: <http://nba.com/team/data#>

                    SELECT ?name ?arena ?capacity ?division ?conference ?NBAChamp ?conferenceChamp
                    WHERE
                    {
                        ?team   d:name              ?name ;
                                d:arena             ?arena ;
                                d:capacity          ?capacity ;
                                d:division          ?division ;
                                d:conference        ?conference ;
                                d:countNBAChamp     ?NBAChamp ;
                                d:countConfChamp    ?conferenceChamp .
                    }
                "
                );
            }

            if (!isset($data)) {
                print "<p>Error: " . sparql_errno() . ": " . sparql_error() . "</p>";
            }
        ?>
        
        <!-- Header -->
        <div class="navbar">
            <a href="index.php"><img src="src/nba-logo.png" alt="nba-logo"></a>
        </div>
        
        <div class="search_bar">
            <form role="search" action="" method="post" id="search" name="search">
                <input class="input_search_bar" type="search" placeholder="Find your NBA Team data here" aria-label="Search" name="search">
                <button class="button" type="submit">Search</button>
            </form>
        </div>
        
        <!-- Result modal -->
        <?php
            if ($searchInput != NULL) {
                ?> 
                    <div class="result">
                        <span>Result of <b>"<?php echo $searchInput; ?>"</b></span> 
                    </div>
                <?php
            }
        ?>

        <!-- Table -->
        <div class="table_container">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Team</th>
                        <th>Arena</th>
                        <th>Capacity</th>
                        <th>Division</th>
                        <th>Conference</th>
                        <th>NBA Champion Rings</th>
                        <th>Conference Champion Rings</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    <?php foreach ($data as $data) : ?>
                        <td><?= ++$i ?></td>
                        <td><?= $data['name'] ?></td>
                        <td><?= $data['arena'] ?></td>
                        <td class="tbody_center"><?= $data['capacity'] ?></td>
                        <td class="tbody_center"><?= $data['division'] ?></td>
                        <td class="tbody_center"><?= $data['conference'] ?></td>
                        <td class="tbody_center"><?= $data['NBAChamp'] ?></td>
                        <td class="tbody_center"><?= $data['conferenceChamp'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <footer class="footer">
            Ananda Sapta Awedhana - 140810190063 | 2022
        </Footer>

    </body>

</html>