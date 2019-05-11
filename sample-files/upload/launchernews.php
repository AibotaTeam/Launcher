<?php 

// Database credentials: CHANGE THIS TO THE REAL VALUES
$DBHost = 'localhost';
$DBName = 'MCKidsDB';
$DBUser = 'MCKidsUser';
$DBPass = 'MCKidsPassword';

// DB values for Query: use comma separated lists
$BoardIDs = '2';   // 2="Ankündigungen / News"
$GroupIDs = '4,5'; // 4="Administratoren", 5="Team"

$MaxNews = 5;

//====================================================================

// helper functions:
function mklink($url, $txt, $attrs='')
{
    // the Java HTML renderer is _weird_ and links look ugly without some tricks
    // (well, even with the tricks :/ )
    return '&nbsp;<a href="' . $url . '"' . $attrs . '>' . $txt . '&nbsp;</a>';
}

function htmlstart()
{
?><!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<title>Minecraft Kids Launcher - News</title>
<style>
body {
    background: rgba(0, 0, 0, 0.4) no-repeat;
    color: #ffffff;
    font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 20px;
}
a { color: #44ff44; font-weight: normal; }
img { max-width: 400px; }
</style>
</head>
<body>
<h1>Minecraft Kids Launcher</h1>
<?php
}

function htmlstop()
{
?>
</body>
</html>
<?php
}

function errmsg()
{
    header('HTTP/1.0 500 Internal Server Error');
    htmlstart();
?>
<p>Leider besteht aktuell ein Problem mit der Datenbankanbindung.</p>
<p>Aktuelles zu unserem Projekt könnt ihr <?php echo mklink('https://minecraft-kids.online/dashboard/', 'hier'); ?> erfahren.</p>
<?php
    htmlstop();
    exit(1);
}

function fixhtml($html) {
    // fix links
    $html = preg_replace_callback('#<a(.*?) href="(.*?)"(.*?)>(.*?)</a>#', function($matches) {
        return mklink($matches[2], $matches[4], $matches[1] . $matches[3]);
    }, $html);

    return $html;
}

//====================================================================

// connect to DB:
$DB = new PDO("mysql:host=$DBHost;dbname=$DBName", $DBUser, $DBPass);

if (!$DB) {
    // Database error, show some generic content
    errmsg();
}

// fetch the latest news
$sql = 'SELECT t.topic, p.message' .
        ' FROM wbb1_thread t' .
        '    LEFT JOIN wbb1_post p ON t.firstPostID=p.postID' . 
        '    LEFT JOIN wcf1_user u ON t.userID=u.userID' .
        '    LEFT JOIN wcf1_user_to_group ug ON u.userID=ug.userID' .
        ' WHERE t.boardID IN (' . $BoardIDs . ')' .
        '    AND ug.groupID IN (' . $GroupIDs . ')' .
        ' ORDER BY t.time DESC LIMIT ' . $MaxNews;

$result = $DB->query($sql);
if (!$result) {
    var_dump($DB->errorInfo());
    errmsg();
}
htmlstart();
foreach ($result as $row) {
    echo '<h2>' . utf8_encode($row['topic']) . '</h2>';
    echo fixhtml(utf8_encode($row['message']));
}
