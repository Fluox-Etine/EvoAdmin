.PHONEY: init_server init_frontend run_server run_frontend

init_server: # 初始化服务端
	cd ../server && composer install

init_frontend: # 初始化前端
	cd ../console && npm install

run_server: # 运行服务端
	cd ../server && php windows.php

run_frontend: # 运行前端
	cd ../console && npm run dev