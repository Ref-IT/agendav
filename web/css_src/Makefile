BOOTSTRAP_DIR = ../../libs/twitter-bootstrap
PUBLIC_DIR = ../public
CUSTOM_BOOTSTRAP_LESS = custom_bootstrap.less
AGENDAV_LESS = agendav.less
LESS_COMPRESSOR ?= `which lessc`
WATCHR ?= `which watchr`

#
# BUILD SIMPLE BOOTSTRAP DIRECTORY
# lessc & uglifyjs are required
#

css:
	cp ${BOOTSTRAP_DIR}/img/* ${PUBLIC_DIR}/img
	${LESS_COMPRESSOR} --include-path=${BOOTSTRAP_DIR}/less \
		${CUSTOM_BOOTSTRAP_LESS} > ${PUBLIC_DIR}/css/bootstrap.agendav.css 
	${LESS_COMPRESSOR} --include-path=${BOOTSTRAP_DIR}/less \
		${AGENDAV_LESS} > ${PUBLIC_DIR}/css/app.css 

#
# WATCH LESS FILES
#

watch:
	echo "Watching less files..."; \
	watchr -e "watch('*\.less') { system 'make' }"


.PHONY: docs watch gh-pages
