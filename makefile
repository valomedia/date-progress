date-progress.zip :	bootstrap.min.css date-progress.php LICENSE.md README.md script.js style.css
	cd .. \
		&& zip \
				date-progress/date-progress \
				date-progress/{bootstrap.min.css,date-progress.php,LICENSE.md,README.md,script.js,style.css}

bootstrap.min.css :
	wget https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css

.PHONY : clean

clean :
	rm date-progress.zip bootstrap.min.css
