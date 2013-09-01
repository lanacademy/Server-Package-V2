<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>LAN Academy V2</title>

   	<!-- Viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSS -->
	<link rel="stylesheet" href="./etc/css/normalize.css">
	<link rel="stylesheet" href="./etc/css/styles.css">

	<!-- JS -->
	<!--[if lt IE 9]>
		<script src="/etc/js/html5shiv.js"></script>
	<![endif]-->

	<!-- Favicons -->
	<link rel="shortcut icon" href="./etc/img/favicon.ico">
	<link rel="apple-touch-icon" href="./etc/img/apple-touch-icon.png">
        <link href="./etc/css/playlist.css" rel="stylesheet" type="text/css">

    <!--Flowplayer stuff -->
    <link rel="stylesheet" type="text/css" href="etc/css/minimalist.css">
    <script type="text/javascript" src="etc/lib/jquery.min.js"></script>
    <script type="text/javascript" src="etc/lib/flowplayer.min.js"></script>
    <link rel="stylesheet" href="etc/css/new_playlist.css">
    
    <!-- HTML5shiv, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="/etc/js/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
  </head>

<body>
	<header class="clearfix">
			<div class="wrapper">		
				<h1 class="logo">
<a href="./Home.html">LAN Academy LMS</a>
				</h1>
<!--Nav-->
				<nav>
					<ul>
<li><a href="learn.php?path=/var/www/static/content">Video Courses</a></li>
<li><a href="./typing">Typing Games</a></li>
<li><a href="./quiz">Math Practice</a></li>
					</ul>
				</nav> <!--/ responsive-nav -->
			</div> <!--/ wrapper -->			
		</header>		

<section class="main clearfix">

	<div class="wrapper">	
<!--Add left sidebar here, change width of tasters-->	
		<div class="tasters" style="width:100%;">

<?php
//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);
//Some functions
$script=basename(__FILE__);
$path=!empty($_REQUEST['path']) ? $_REQUEST['path'] : dirname(__FILE__);
$directories = array();
$files = array();
$scriptname=end(explode('/',$_SERVER['SERVER_ADDR']));
$scriptpath=str_replace($scriptname,'',$_SERVER['SERVER_ADDR']);

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
		<div class=\"title-head\"><h2>{$path_name}</h2></div>";
echo "<!--Dir Listing-->
<div class=\"dir-list\" style=\"padding-left:1px;\">
<h4>Back: ";
sort($directories);
foreach($directories as $directory)
{
//$dirfilecount = count(glob(dirname(__FILE__)."$directory*.mp4", GLOB_BRACE));
$fi = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);
$num_fils = iterator_count($fi);
$path_parts = pathinfo($directory);
echo ($directory != $path) ? "<a href=\"{$script}?path={$directory}\">{$path_parts['filename']}</h4>
<h4>" : "";
}
//If you want number of contents in dir, add  : {$num_fils} before </h4>
echo "</h4></div>
<hr>
";
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
	<div class=\"title-head\"><h2>{$path_name}</h2></div>";
echo "
	<div id=\"playwrap\">
	  <div id=\"player\" style=\"margin-bottom: 25px;\"></div>
<div id=\"controls\">
          <button onclick=\"viewVideo(); flowplayer().prev(); pause(); speed();\" style=\"float: left; margin-left: 33%;\">Previous</button>
	<select class=\"span2\" id=\"rate\" onchange=\"speed();\" style=\"float: left;\">
            <option value=\"0.5\">0.5x</option>
            <option value=\"0.75\">0.75x</option>
            <option selected=\"selected\" value=\"1.0\">1.0x</option>
            <option value=\"1.25\">1.25x</option>
            <option value=\"1.5\">1.5x</option>
            <option value=\"1.75\">1.75x</option>
            <option value=\"2.0\">2x</option>
          </select>
          <!--
          <select class=\"span2\" id=\"sub\" onchange=\"lang()\" style=\"float: left;\">
            <option value=\"hi\">Hindi</option>
            <option value=\"en\">English</option>
            <option value=\"no\">None</option>
          </select> -->
	<button onclick=\"viewVideo(); flowplayer().next(); pause(); speed();\" style=\"float: left;\">Next</button>
          <div id=\"khan\"class=\"appendFrame\">
            <script>
              document.getElementById(\"khan\").style.display=\"none\";
            </script>
          </div>

</article>
";

//DYNAMIC STUFF
echo "
<head><script type=\"text/javascript\">
      var api; 
      var link;
      var curr = \"1\";
      
      function showIframe(){
        var flow = document.getElementById(\"playwrap\");
        var frame = document.getElementById(\"khan\");

        if(flow.style.display!=\"none\"){
          flow.style.display=\"none\";
          frame.style.display=\"block\";
          flowplayer().pause();
        } else {
          flow.style.display=\"block\";
          frame.style.display=\"none\";
        }
      }
      function framer(){
        if(document.getElementById(\"khan\").style.display==\"none\"){
          showIframe();
        }
        $('.appendFrame').empty();
        $('.appendFrame').append('<iframe src=\"'+link+'\" name=\"frame\" id=\"frame\"></iframe>');
      }
      function viewVideo(){
        if(document.getElementById(\"playwrap\").style.display==\"none\"){
          showIframe();
        }
      }
      function pause(){
        setTimeout(function(){flowplayer().pause()},400);
      }
      function speed(){
        flowplayer().speed(document.getElementById(\"rate\").value);
      }
      function lang(){  
      }

      function setCurr(s){
        curr = s;
      }
      function getTitle(){
        var str=$(\"video\").attr(\"src\");
        var n=str.split(\".mp4\");
        return n[0].split(\"http://localhost/home-stan-static/content/videos/\")[1];
      }
$(function () { // ensure DOM is ready
   // this will install flowplayer into an element with id=\"player\"
   $(\"#player\").flowplayer({
      swf:\"lib/flowplayer.swf\", 
      ratio:\"0.4167\", 
      flashfit:\"true\", 
      autoPlay:\"false\",
      id:\"video\",
      // one video: a one-member playlist
      playlist: [
         // a list of type-url mappings in picking order,";
sort($files);
foreach ($files as $file)
{
$path_party = pathinfo($file);
$filename=$path_party['filename'];
$fullpath = $path . "/" . basename($file);
$novarpath=str_replace("/var/www/", "/", $fullpath);
$relpath = str_replace(realpath(dirname(__FILE__))."/", "", $fullpath);
$pattern="(\.mp4$)";
if(preg_match($pattern, $file) || is_dir($file)){
echo ',
         [
            { mp4:  "http://' . $scriptname .'' . $novarpath .'" },
            { flash:  "http://' . $scriptname .'' . $novarpath .'" }
         ]';
}
}
echo "
     ]
   }); 
});
    </script></head>";
//VISIBLE DYNAMIC STUFF 
echo "
<!--Playlist -->
<article>
<div class=\"row\">
  <h3>Playlist</h3>
          <div class=\"well activevid\" style=\"\">
<!-- <ul class=\"nav nav-list\" style=\"max-height: 200px; overflow-y: auto;margin: 0 -20px 0 -20px;\"> -->
<ul id=\"ulplaylist\"> 
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
</ul>
          </div><!--/.well -->
        </div><!--/span sidebar-->
</article>
<hr>
</div>
	</div>
		<script>
                $(document).ready(function(){
                  $('.activevid li').click(function() {
                    $(this).siblings('li').removeClass('active');
                    $(this).addClass('active');
                  });
                });
               </script>
";
} //closing out the if filecount !== 0
else 
echo "<!--No vids found-->";

//IFRAMES
$iframecount = count(glob('./' . "*.iframe.html"));

if ($iframecount !== 0)
{ /*
echo "
<article>
<div class=\"well\">
<h3>Excercises</h3>
            <ul class=\"iframe-list\">
";

foreach ($files as $fill)
{
$pattern="(\.iframe.html$)";
if(preg_match($pattern, $fill) || is_dir($fill)){
$fullpath = $path . "/" . basename($fill);
$relpath = str_replace(realpath(dirname(__FILE__))."/", "", $fullpath);
$path_frames = pathinfo($fill);
$framename=$path_frame['filename'];
//echo "<li><a onClick=\"link='{$relpath}';framer();\">{$framename}</a></li>";
//echo '<li><a href="' . $relpath . '">' . $nomp4 . "</a> </li>";
}
}

echo "
 </ul>
          </div><!--/well2-->
</article> <!--/third article -->
";*/
}; //End of iframecount




?>

		</div><!--/ tasters -->		
	</div> <!--/ wrapper -->
</section><!--/ main -->
		
<footer>	
	<div class="wrapper">
		<p>© Copyleft LAN Academy 2013 <a href="./etc/css/acknowledgments.html">Acknowledgments</a>
		</p>
	</div> 
</footer>

<script src="./etc/js/custom.js"></script> 


  </body>
</html>