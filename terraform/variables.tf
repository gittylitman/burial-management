variable subscription_id {
  type = string
}

variable DOCKER_IMAGE {
  type = string
  default = "gravetry2:latest"
}

variable gov_url_cemetery_cities {
  type = string
  default = "https://data.gov.il/api/3/action/datastore_search?resource_id=23ef02e8-ac18-4568-8d94-2fb17040a9ae"
}


variable DOCKER_REGISTARY_URL {
  type = string
  default = "https://skyvaracr.azurecr.io"
}

variable resource_group_name {
  type = string
  default = "rg-grave-new"
}

variable resource_group_location {
  type = string
  default = "Canada Central"
}

variable service_plan_name {
  type = string
  default = "app-graves-new"
}

variable web_app_name {
  type = string
  default = "wa-graves-new"
}

variable server_name {
  type = string
  default = "sqldb-graves-new"
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
  default = "fw-db-graves-new"
}

variable END_IP_ADDRESS {
  type    = string
  default = "255.255.255.255"
}

variable START_IP_ADDRESS {
  type    = string
  default = "0.0.0.0"
}