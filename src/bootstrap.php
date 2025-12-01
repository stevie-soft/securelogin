<?php
/** Utilities */
require_once __DIR__ . "/utils/route-management.php";
require_once __DIR__ . "/utils/error-management.php";
require_once __DIR__ . "/utils/env-management.php";
require_once __DIR__ . "/utils/config-management.php";
require_once __DIR__ . "/utils/creds-management.php";
require_once __DIR__ . "/utils/db-management.php";
require_once __DIR__ . "/utils/view-management.php";

/** Data Transfer Objects */
require_once __DIR__ . "/dtos/LoginRequestDto.php";

/** Domain Models */
require_once __DIR__ . "/models/User.php";

/** Route Controllers */
require_once __DIR__ . "/controllers/Controller.php";
require_once __DIR__ . "/controllers/HomeController.php";
require_once __DIR__ . "/controllers/LoginController.php";
require_once __DIR__ . "/controllers/ProfileController.php";
require_once __DIR__ . "/controllers/LogoutController.php";
require_once __DIR__ . "/controllers/ErrorController.php";

/** Global Objects */
require_once __DIR__ . "/globals.php";

/** Session Management */
session_start();
