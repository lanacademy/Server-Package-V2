Rename either learnfp.php (using flowplayer video player) or learnvjs.php(using video.js video player) to learn.php, whichever one is working better.

What's currently working for me is learnfp.php. 

If you want to install on another machine, let me know via email and I can send all some packages in a zipped file to you. I'm not including it in this archive because of the size of that file. 

Note that the reason we're getting the pretty file manager in v2 is because of the .htaccess file in this directory. (It's a hidden file, ctrl h in most folder browsers show it). The htaccess is calling head.html and footer.html in /etc.

There's a few files name static-content, these should be replaced with symbolic links to the location of the video content. More details are inside the files themselves.