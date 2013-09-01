<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>LAN Academy V2</title>

   	<!-- Viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSS -->
	<link rel="stylesheet" href="/etc/css/normalize.css">
	<link rel="stylesheet" href="/etc/css/styles.css">

	<!-- JS -->
	<!--[if lt IE 9]>
		<script src="/etc/js/html5shiv.js"></script>
	<![endif]-->

	<!-- Favicons -->
	<link rel="shortcut icon" href="/etc/img/favicon.ico">
	<link rel="apple-touch-icon" href="/etc/img/apple-touch-icon.png">
        <link href="/etc/css/playlist.css" rel="stylesheet" type="text/css">
	<!--Video-->
	<link href="/etc/js/video-js.css" rel="stylesheet" type="text/css">
	<link href="/etc/js/video.playlist.css" rel="stylesheet" type="text/css">

	<script src="/etc/js/jquery-1.9.1.min.js"></script>
	<script src="/etc/js/autoellipsis.min.jquery.js"></script>
	<script src="/etc/js/video.js"></script>
	<script src="/etc/js/video.playlist.js"></script> 

  </head>

<body>
	<header class="clearfix">
			<div class="wrapper">		
				<h1 class="logo">
<a href="/Home.html">LAN Academy LMS</a>
				</h1>
<!--Nav-->
				<nav>
					<ul>
<li><a href="learn.php?path=/var/www/static/content">Video Courses</a></li>
<li><a href="/typing">Typing Games</a></li>
<li><a href="/quiz">Math Practice</a></li>
					</ul>
				</nav> <!--/ responsive-nav -->
			</div> <!--/ wrapper -->			
		</header>		

<section class="main clearfix">

	<div class="wrapper">	
<!--Add left sidebar here, change width of tasters-->	
		<div class="tasters" style="width:100%;">

<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
//Some functions
$script=basename(__FILE__);
$path=!empty($_REQUEST['path']) ? $_REQUEST['path'] : dirname(__FILE__);
$directories = array();
$files = array();

//Check if we are focused on a dir
if(is_dir($path))
{
chdir($path);
if ($handle = opendir('.'))
{
while (($item = readdir($handle)) !== false)
{
if(is_dir($item))
{
if ($path == dirname(__FILE__))
{    if(!preg_match("/^\../", $item))
array_push($directories, realpath($item));
}
else
array_push($directories, realpath($item));
}
else
array_push($files, ($item));
}
}
else
echo "<p class=\"error\">Directory handle could not be obtained.</p>";
}
else
echo "<p class=\"error\">Path is not a directory<\p>";

//CREATING THE PAGE
$path_par = pathinfo($path);
$path_name=$path_par['filename'];

//Making page redirect if directory is found to include a file redirect.txt
//$redirect = file_exists($path/'redirect.txt');
//if($redirect) {
// echo "<meta http-equiv=\"refresh\" content=\"0;URL=learn.php?=\var\www\static\content\">";
//} 
//else {}


//Categories
$filecount = count(glob('./' . "*.mp4"));
//echo "<p>{$filecount}</p>";
if($filecount == 0)
{
echo "
<article>
<!--Title -->
		<div class=\"title-head\"><h3>{$path_name}</h3></div>";
echo "<!--Dir Listing-->
<div class=\"dir-list\" style=\"padding-left:1px;\">";
sort($directories);
foreach($directories as $directory)
{
//$dirfilecount = count(glob(dirname(__FILE__)."$directory*.mp4", GLOB_BRACE));
$fi = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);
$num_fils = iterator_count($fi);
$path_parts = pathinfo($directory);
echo ($directory != $path) ? "<h4><a href=\"{$script}?path={$directory}\">{$path_parts['filename']} : {$num_fils}</h4>" : "";
}
echo "</div>
<hr>
</article>";
}
else 
echo "<!--Files found, not showing dirs -->";
//End of Categories 







//Start Video Player
if ($filecount !== 0)
{ 
//LEFT STUFF
echo "
<article>
<!--Title -->
		<div class=\"title-head\"><h3>{$path_name}</h3></div>";
echo "
<!--Video -->
<video id=\"video1\" class=\"video-js vjs-default-skin\" controls preload=\"none\">

		<source src=\"/etc/img/lan.mp4\" type='video/mp4'>";
$subz = glob("$path/*.vtt");
if(!empty($subz)){
 echo "
<track kind=\"captions\" src=\"#.vtt\" srclang=\"en\" label=\"English\">
</video>";
} 
else {				
echo "
<!-- no subs found-->
</video>"; }
//echo "$subz";
//PLAYLIST STUFF
echo "
<!--Playlist -->
<head>
<script type=\"text/javascript\">
  _V_.options.flash.swf = \"js/video-js.swf\";
  _V_.options.flash.iFrameMode = true;
  _V_.options.playlist = [";
echo '
{
          "title": "' . $path_name .'",
          "sources": [
          {
              "src": "/etc/img/lan.mp4",
              "type": "video/mp4"
          }]
      },';

sort($files);
foreach ($files as $file)
{
$path_party = pathinfo($file);
$filename=$path_party['filename'];
$fullpath = $path . "/" . basename($file);
$relpath = str_replace(realpath(dirname(__FILE__))."/", "", $fullpath);
$virtpath = str_replace("/home/ted", ".", $relpath);
$nomp4=str_replace(".mp4", "", $virtpath);
$pattern="(\.mp4$)i";
if(preg_match($pattern, $file) || is_dir($file)){
echo '
{
          "title": "' . $filename .'",
          "track": "' . $nomp4 .'.vtt",
          "sources": [
          {
              "src": "' . $virtpath .'",
              "type": "video/mp4"
          },
          {
              "src":  "' . $virtpath .'",
              "type": "application/pdf",
              "title":"Download Video"
          }]
      },';
}
}
echo "
      ];
  // Uncomment the following line to hide popup button
  // _V_.options.popup.hide = true;

</script></head>";
}

/*
//DYNAMIC STUFF 2
echo "
<div class=\"span3\">
          <div class=\"well sidebar-nav activevid\">
            <ul class=\"nav nav-list\" style=\"max-height: 200px; overflow-y: auto;margin: 0 -20px 0 -20px;\">
              <li class=\"nav-header\">Playlist</li>
";
foreach ($files as $fil){
$path_part = pathinfo($fil);
$filname=$path_part['filename'];
//$nomp4=str_replace(".mp4", "", $file);
$fullpat = $path . "/" . basename($fil);
$relpat = str_replace(realpath(dirname(__FILE__))."/", "", $fullpat);
$pattern="(\.mp4$)";
//echo "<li class=\"active\"><a onClick=\"viewVideo();flowplayer().play(0); pause(); speed();\">{$filename}</a></li>";
//for( $num0=0; $num0<=$filecount-1; $num0++ ){
if(preg_match($pattern, $fil) || is_dir($fil)){
$num0=0;
$num0=$num0+1;
echo  "
       <li><a onClick=\"viewVideo(); flowplayer().play(" . ($num0) ."); pause(); speed();\">{$filname}</a></li> ";
//this lists the files echo '<li><a href="' . $relpath . '">' . $filename . "</a> </li>";
//}
}
}
echo "
<script>
                $(document).ready(function(){
                  $('.activevid li').click(function() {
                    $(this).siblings('li').removeClass('active');
                    $(this).addClass('active');
                  });
                });
              </script>
            </ul>
          </div><!--/.well -->
";
} //closing out the if filecount !== 0
else 
echo "<!--No vids found-->";

*/

/*
//IFRAMES
$iframecount = count(glob('./' . "*.iframe.html"));

if ($iframecount !== 0)
{ 
echo "
<div class=\"well sidebar-nav\">
            <ul class=\"nav nav-list\" style=\"max-height: 200px; overflow-y: auto;margin: 0 -20px 0 -20px;\">
              <li class=\"nav-header\">Excercises</li>
";

foreach ($files as $fill)
{
$pattern="(\.iframe.html$)";
if(preg_match($pattern, $fill) || is_dir($fill)){
$fullpath = $path . "/" . basename($fill);
$relpath = str_replace(realpath(dirname(__FILE__))."/", "", $fullpath);
$path_frames = pathinfo($fill);
$framename=$path_frame['filename'];
echo "<li><a onClick=\"link='{$relpath}';framer();\">{$framename}</a></li>";
//echo '<li><a href="' . $relpath . '">' . $nomp4 . "</a> </li>";
}
}

echo "
 </ul>
          </div><!--/well2-->
";
}; //End of iframecount
*/

?>

		</div><!--/ tasters -->		
	</div> <!--/ wrapper -->
</section><!--/ main -->
		
<footer>	
	<div class="wrapper">
		<p>© Copyleft LAN Academy 2013 <a href="/etc/css/acknowledgments.html">Acknowledgments</a>
		</p>
	</div> 
</footer>
<!--Other js-->
<script type="text/javascript">
  var playerWidth = 600;
  var playerHeight = 400;    
</script>

<script src="/etc/js/custom.js"></script> 


  </body>
</html>