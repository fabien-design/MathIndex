export COMPOSE=docker compose
export EXEC=$(COMPOSE) exec symfony

ssh:
	$(EXEC) bash

# Cache
cc:
	$(EXEC) php bin/console c:cl --no-warmup
	$(EXEC) php bin/console c:warmup

# Fixer
php-cs-fixer:
	 docker compose run phpqa php-cs-fixer fix --config=".php-cs-fixer.dist.php"


dsu-f:
	docker compose exec symfony bin/console doctrine:schema:update --force && \
	echo "Database has been updated. End of make.";

dsu-d:
	docker compose exec symfony bin/console doctrine:schema:update --dump-sql 2>/dev/null && \
	read -p "Voulez-vous forcer l'update? (y/N) " response && \
	if [ "$$response" = "y" ] || [ "$$response" = "Y" ] || [ "$$response" = "o" ] || [ "$$response" = "O" ]; then \
		docker compose exec symfony bin/console doctrine:schema:update --force 2>/dev/null; \
		echo "Database has been updated. End of make."; \
	else \
		echo "Nothing has been updated. End of make."; \
	fi

gp:
	git reset --hard origin/develop && \
	git pull && \
	bin/console c:cl && \
	npm run build && \
	cd .. && chmod -R 777 PhotoGodard


dcu:
	docker compose stop  && \
	docker compose down && \
	docker compose up --build --force-recreate -d


install:
	sudo chmod -R 777 * && \
	docker compose down && \
	make dcu && \
	docker compose exec symfony composer install && \
	docker compose exec symfony bin/console d:d:c --if-not-exists && \
	make dsu-f && \
	docker compose exec symfony bin/console d:f:l -n
