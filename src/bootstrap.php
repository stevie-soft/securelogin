<?php
/** Utilities */
require_once "./utils/route-management.php";
require_once "./utils/error-management.php";
require_once "./utils/env-management.php";
require_once "./utils/config-management.php";
require_once "./utils/creds-management.php";
require_once "./utils/db-management.php";
require_once "./utils/view-management.php";

/** Data Transfer Objects */
require_once "./dtos/LoginRequestDto.php";

/** Domain Models */
require_once "./models/User.php";

/** Route Controllers */
require_once "./controllers/Controller.php";
require_once "./controllers/LoginController.php";
require_once "./controllers/ProfileController.php";

/** Global Objects */
require_once "./globals.php";

/** Session Management */
session_start();
