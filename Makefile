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