##
## 	makefile
##	date-progress
##
##	Created by:
##		* Jean-Pierre Höhmann
##

#
# Build instructions for date-progress.
#
# This file governs the build process for date-progress, basically just consists of downloading the single dependency
# (bootstrap) and zipping up all the necessary files.  It feels a bit silly to have a makefile for this, but it's just
# barely complex enough that simply zipping up the source tree doesn't quite cut it, and this is nicer than a shell
# script.
#

date-progress.zip :	admin.php bootstrap.min.css date-progress.php lib.php plugin.php script.js style.css
	cd .. \
		&& zip \
				date-progress/date-progress \
				date-progress/{admin.php,bootstrap.min.css,date-progress.php,lib.php,plugin.php,script.js,style.css}

bootstrap.min.css :
	wget https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css

.PHONY : clean

clean :
	rm date-progress.zip bootstrap.min.css
