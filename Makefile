all: deploy

deploy:
	sed -i '' 's/APP_DEBUG=true/APP_DEBUG=false/g' ./.env
	sed -i '' 's/REDIRECT_HTTP=false/REDIRECT_HTTP=true/g' ./.env
	zip ../posterboard.zip -r * .[^.]*
dev:
	sed -i '' 's/APP_DEBUG=false/APP_DEBUG=true/g' ./.env
	sed -i '' 's/REDIRECT_HTTP=true/REDIRECT_HTTP=false/g' ./.env
