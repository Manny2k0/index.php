<?php

namespace Session; // Define the namespace.

use JetBrains\PhpStorm\NoReturn;

// Import the NoReturn class.

class Session // Define the Session class.
{
    public function killSession(): void // Destroy the session
    {

        $_SESSION = []; // Overwrite the current session array with an empty array.

        // Overwrite the session cookie with empty data too.
        if (ini_get('session.use_cookies')) { // Overwrite the session cookie with empty data too.
            $params = session_get_cookie_params();  // Get the session cookie parameters.
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']); // Set the session cookie parameters.
        }

        // Destroy the session.
        session_destroy();
    }

    #[NoReturn] public function forgetSession(): void // Destroy the session
    {
        $this->killSession(); // Call the killSession method to destroy the session.

        // Redirect to the login page.
        header("location: login.php"); // Redirect to the login page.
        exit; // Exit the script.
    }
}

?>
