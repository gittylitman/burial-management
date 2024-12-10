variable subscription_id {
  type = string
}

variable DOCKER_IMAGE {
  type = string
  default = "graves:latest"
}

variable DOCKER_REGISTARY_URL {
  type = string
  default = "https://skyvaracr.azurecr.io"
}

variable resource_group_name {
  type = string
  default = "rg-graves2"
}

variable resource_group_location {
  type = string
  default = "Canada Central"
}

variable service_plan_name {
  type = string
  default = "app-graves"
}

variable web_app_name {
  type = string
  default = "wa-graves"
}

variable server_name {
  type = string
  default = "sqldb-graves"
}

variable server_version {
  type = number
  default = 14
}

variable server_sku_name {
  type = string
  default = "GP_Standard_D2s_v3"
}

variable server_administrator_login {
  type = string
  default = "GRAVES"
}

variable server_administrator_password {
  type = string
  default = "GRAVES2024!"
}

variable acr_name {
  type = string
  default = "SkyvarACR"
}

variable acr_resource_group_name {
  type = string
  default = "ACR"
}

variable firwall_name {
  type = string
  default = "fw-db-graves"
}

variable END_IP_ADDRESS {
  type    = string
  default = "255.255.255.255"
}

variable START_IP_ADDRESS {
  type    = string
  default = "0.0.0.0"
}