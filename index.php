<?php
  /* Create simple Website with PHP - http://coursesweb.net/php-mysql/ */

// create an array with data for title, and meta, for each page
$pgdata = array();
$pgdata['index'] = array(
  'title'=>'Welcome to Library System',
  'description'=>'Here add the description for Home page',
  'keywords'=>'meta keywords, for, home page'
);


// set the page name
$pgname = isset($_GET['pg']) ? trim(strip_tags($_GET['pg'])) : 'index';

// get title, and meta data for current /accessed page
$title = $pgdata[$pgname]['title'];
$description = $pgdata[$pgname]['description'];
$keywords = $pgdata[$pgname]['keywords'];

// set header for utf-8 encode
header('Content-type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
 <meta charset="utf-8" />
 <title><?php echo $title; ?></title>
 <meta name="description" content="<?php echo $description; ?>" />
 <meta name="keywords" content="<?php echo $keywords; ?>" />
 <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
 <style><!--
 body {
  margin:0;
  text-align:center;
  padding:0 1em;
 }
 header, footer, section, aside, nav, article { display: block; }
 #posts{
  position:relative;
  width:99%;
  margin:0.5em auto;
  background:#fdfefe;
 }
 #menu {
  float:left;
  width:15em;
  margin:0 auto;
  background:#f8f9fe;
  border:1px solid blue;
  text-align:left;
 }
 #menu li a:hover {
   text-decoration:none;
   color:#01da02;
 }
 #article {
  margin:0 1em 0 16em;
  background:#efeffe;
  border:1px solid #01da02;
  padding:0.2em 0.4em;
 }
 #footer {
  clear:both;
  position:relative;
  background:#edfeed;
  border:1px solid #dada01;
  width:99%;
  margin:2em auto 0.5em auto;
 }
 --></style>
</head>
<body>

<header id="header">
 <h1><?php echo $title; ?></h1>
</header>

<section id="posts">
 <nav id="menu">
  <ul>
   <li><a href="index.php" title="Home page">Home</a><li>
   <li><a href="booksearch.php" title="search book">search book</a></li>
   <li><a href="checkout.php" title="check out">check out book</a></li>
   <li><a href="checkin.php" title="check in">check in book</a></li>
   <li><a href="manageborrower.php" title="management">management</a></li>
   <li><a href="fines.php" title="fines">fines</a></li>
  </ul>
 </nav>
</section>


</body>
</html>