#
# This file is part of DateProgress by valo.media.
#
# DateProgress is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
# License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
# version.
#
# DateProgress is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
# warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along with DateProgress. If not, see
# <https://www.gnu.org/licenses/>.
#

date-progress.zip :	bootstrap.min.css date-progress.php LICENSE.md script.js style.css
	cd .. \
		&& zip \
				date-progress/date-progress \
				date-progress/{bootstrap.min.css,date-progress.php,LICENSE.md,script.js,style.css}

bootstrap.min.css :
	wget https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css

.PHONY : clean

clean :
	rm date-progress.zip bootstrap.min.css
