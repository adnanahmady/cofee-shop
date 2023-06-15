#!make
mainShell=bash
mainService=backend

define default
$(if $(1),$(1),$(2))
endef

define execute
docker-compose exec $(call default,$1,${mainService}) $2
endef

up:
	@$(shell touch .backend/bash_history)
	@docker-compose up -d ${options}

down:
	@docker-compose down

status:
	@docker-compose ps

destroy:
	@docker-compose down --volumes --remove-orphans

shell:
	@$(call execute,${service},$(call default,${run},${mainShell}))

logs:
	@docker-compose logs ${service}

test:
	@$(call execute,${service},composer test)