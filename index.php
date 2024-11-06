<?php
$db = new SQLite3 ("movies.db");

$db->exec ("CREATE TABLE IF NOT EXISTS movie (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, director TEXT, genre TEXT, rating INTEGER, release_year INTEGER)");

$counter = $db->querySingle("SELECT COUNT(*) as count FROM movie");

if ($counter == 0) {
  $db->exec ("INSERT INTO movie (title, director, genre, rating, release_year) VALUES ('The Matrix', 'Lana Wachowski', 'Sci-Fi', 8, 1999), ('Red Notice', 'Rawson Marshall Thurber', 'Drama', 7, 2021), ('Venom', 'Ruben Fleischer', 'Action', 9, 2018), ('The Batman', 'Matt Reeves', 'Action', 8, 2022), ('28 Days Later', 'Danny Boyle', 'Horror', 7, 2002)");
}

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
  $action = $_POST ['action'];

  if ($action == 'add') {
    $movie_name = $_POST ['movie_name'];
    $movie_director = $_POST ['movie_director'];
    $movie_genre = $_POST ['movie_genre'];
    $movie_rating = $_POST ['movie_rating'];
    $movie_release_year = $_POST ['movie_release_year'];

    $db->exec ("INSERT INTO movie (title, director, genre, rating, release_year) VALUES ('$movie_name', '$movie_director', '$movie_genre', '$movie_rating', '$movie_release_year')");
    header ("Location: index.php");
    exit;
  }
}
?>

<html>
<head>
  <title>Movie Database</title>
  <style>
    body {
      display: flex; 
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>
  
<body>
  <h1>Movie Database</h1>

  <form action = "index.php" method = "POST">
    <label>Title:</label>
    <input type = "text" name = "movie_name">

    <label>Director:</label>
    <input type = "text" name = "movie_director">

    <label>Genre:</label>
    <input type = "text" name = "movie_genre">

    <label>Rating:</label>
    <input type = "text" name = "movie_rating">

    <label>Release Year:</label>
    <input type = "text" name = "movie_release_year">

    <input type = "hidden" name = "action" value = "add">
    <button>Add</button>
  </form>

  <?php
  $result = $db->query ("SELECT * FROM movie");

  echo "<table>";
  echo "<tr>
  <th>ID</th>
  <th>Title</th>
  <th>Director</th>
  <th>Genre</th>
  <th>Rating</th>
  <th>Release Year</th>
  </tr>";

  while ($row = $result->fetchArray (SQLITE3_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['title'] . "</td>";
    echo "<td>" . $row['director'] . "</td>";
    echo "<td>" . $row['genre'] . "</td>";
    echo "<td>" . $row['rating'] . "</td>";
    echo "<td>" . $row['release_year'] . "</td>";
    echo "</tr>";
  }

  echo "</table>";
  ?>

  <form action = "index.php" method = "POST">
    <label>Filter By Genre</label>
    <input type = "text" name = "genre">

    <label>Sort By</label>
    <select name = "sorts">
      <option value = "title">title</option>
      <option value = "rating">Rating</option>
      <option value = "release_year">Release Year</option>
    </select>

    <input type = "hidden" name = "action" value = "filter">
    <button>Filter</button>

    
  </form>

  <?php


  if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
    $action = $_POST ['action'];

    if ($action == 'filter') {
      $genre = $_POST ['genre'];
      $sorts = $_POST ['sorts'];

      $q = "SELECT * FROM movie";
      $q .= " WHERE genre = '$genre'";
      $q .= " ORDER BY $sorts";
      
      $result = $db->query ($q);

      echo "<table>";
      echo "<tr>
      <th>ID</th>
      <th>Title</th>
      <th>Director</th>
      <th>Genre</th>
      <th>Rating</th>
      <th>Release Year</th>
      </tr>";

      while ($row = $result->fetchArray (SQLITE3_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['director'] . "</td>";
        echo "<td>" . $row['genre'] . "</td>";
        echo "<td>" . $row['rating'] . "</td>";
        echo "<td>" . $row['release_year'] . "</td>";
        echo "</tr>";
      }

      echo "</table>";
    }
  }
  ?>

</body>
</html>
