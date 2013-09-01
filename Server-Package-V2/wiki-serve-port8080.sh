#!/bin/bash
#Yeah this doesn't need to be a real bash script, I just figured this was something you'll read
#The command is:
#kiwix-serve --port=8080 /home/ted/wiki/data/content/wikipedia_en_all_nopic_01_2012.zimaa
#For other wikis:

kiwix-serve --port=8080 /home/ted/wiki/data/content/wikipedia_en_simple_all_08_2011.zim
#kiwix-serve --port=8080 /home/ted/wiki/data/content/wikipedia_en_for_schools_2013.zim

#All the content is in ~/wiki/data/content/ 
#The app files are in /usr/lib/ the kiwix-linux you see here is a sym link to that
#There's symbolic links of kiwix and kiwix-serve in /usr/bin/ so you can just run from terminal



 