terraform {
  backend "azurerm" {
    resource_group_name  = "rg-graves"
    storage_account_name = "stgraves"
    container_name       = "terraform-graves"
    key                  = "terraform.tfstate"
  }
}

provider "azurerm" {
  features {
    resource_group {
      prevent_deletion_if_contains_resources = false
    }
  }
  subscription_id = var.subscription_id
}

resource "azurerm_resource_group" "resource_group" {
  name     = var.resource_group_name
  location = var.resource_group_location
}

resource "azurerm_service_plan" "service_plan" {
  name                = var.service_plan_name
  resource_group_name = azurerm_resource_group.resource_group.name
  location            = azurerm_resource_group.resource_group.location
  os_type             = "Linux"
  sku_name            = "P1v2"
}


resource "azurerm_linux_web_app" "web_app" {
  name                = var.web_app_name
  resource_group_name = azurerm_resource_group.resource_group.name
  location            = azurerm_service_plan.service_plan.location
  service_plan_id     = azurerm_service_plan.service_plan.id
  
  depends_on = [ 
    azurerm_postgresql_flexible_server.database_postgresql
  ]

  app_settings = {
    APP_ENV = "production"
    DB_CONNECTION = "pgsql"
    DB_DATABASE = "postgres"
    DB_HOST = "${azurerm_postgresql_flexible_server.database_postgresql.name}.postgres.database.azure.com"
    DB_PASSWORD = var.server_administrator_password
    DB_USERNAME = var.server_administrator_login
    DB_PORT = 5432
    WEBSITES_PORT = 8000
    WEBSITES_ENABLE_APP_SERVICE_STORAGE = true
    WEBSITES_CONTAINER_START_TIME_LIMIT= 1700
    PORT = 8000
  }

  site_config {
    container_registry_use_managed_identity = true
    application_stack {
      docker_image_name = var.DOCKER_IMAGE
      docker_registry_url = var.DOCKER_REGISTARY_URL
    }
  }

  identity {
    type = "SystemAssigned"
  }
}

resource "azurerm_postgresql_flexible_server" "database_postgresql" {
  name                          = var.server_name
  resource_group_name           = azurerm_resource_group.resource_group.name
  location                      = azurerm_service_plan.service_plan.location
  version                       = var.server_version
  sku_name                      = var.server_sku_name
  administrator_login           = var.server_administrator_login
  administrator_password        = var.server_administrator_password
  zone                          = 3
}

resource "azurerm_postgresql_flexible_server_firewall_rule" "firewall" {
  name             = var.firwall_name
  server_id        = azurerm_postgresql_flexible_server.database_postgresql.id
  start_ip_address = var.START_IP_ADDRESS
  end_ip_address   = var.END_IP_ADDRESS
}
 
data "azurerm_container_registry" "container_registry" {
  name                = var.acr_name
  resource_group_name = var.acr_resource_group_name
}

resource "azurerm_role_assignment" "role_assignment_web_app" {
  principal_id                     = azurerm_linux_web_app.web_app.identity[0].principal_id
  role_definition_name             = "AcrPull"
  scope                            = data.azurerm_container_registry.container_registry.id
  skip_service_principal_aad_check = true
}
