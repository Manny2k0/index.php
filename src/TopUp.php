<?php

namespace src;

use AllowDynamicProperties;
use PDO;
use PDOException;

/**
 * Class TopUp
 *
 * This class is responsible for handling top-up operations.
 */
#[AllowDynamicProperties]
class TopUp
{
    /**
     * TopUp constructor.
     *
     * @param $pdo - The PDO instance for database operations
     */
    public function __construct($pdo)
    {
        // Establish a database connection
        $this->pdo = new \PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!');
    }

    /**
     * Validate the credit card number.
     *
     * @param $creditCardNumber - The credit card number to validate
     * @return bool - Returns true if the credit card number is valid, false otherwise
     */
    public function validateCreditCardNumber($creditCardNumber)
    {
        // Check if the credit card number matches the pattern of four groups of four digits separated by hyphens
        if (!preg_match("/^\d{4}-\d{4}-\d{4}-\d{4}$/", $creditCardNumber)) {
            return false;
        }

        // Remove hyphens from the credit card number
        $creditCardNumber = str_replace('-', '', $creditCardNumber);

        // Check if the credit card number is numeric and has a length of 16 digits
        return is_numeric($creditCardNumber) && strlen($creditCardNumber) === 16;
    }

    /**
     * Validate the expiration date of the credit card.
     *
     * @param $expirationMonth - The expiration month to validate
     * @param $expirationYear - The expiration year to validate
     * @return bool - Returns true if the expiration date is valid, false otherwise
     */
    public function validateExpirationDate($expirationMonth, $expirationYear)
    {
        // Check if both month and year are numeric and have the correct length
        if (!is_numeric($expirationMonth) || strlen($expirationMonth) !== 2 ||
            !is_numeric($expirationYear) || strlen($expirationYear) !== 4) {
            return false;
        }

        // Check if month is within valid range (1 to 12)
        if ($expirationMonth < 1 || $expirationMonth > 12) {
            return false;
        }

        // Check if year is within the specified range (2025 to 2045)
        if ($expirationYear < 2025 || $expirationYear > 2045) {
            return false;
        }

        // Get current year and month
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Check if the expiration date is in the past
        if ($expirationYear < $currentYear || ($expirationYear == $currentYear && $expirationMonth < $currentMonth)) {
            return false;
        }

        // Expiration date is valid
        return true;
    }

    /**
     * Validate the CVV of the credit card.
     *
     * @param $cvv - The CVV to validate
     * @return bool - Returns true if the CVV is valid, false otherwise
     */
    public function validateCVV($cvv)
    {
        // Check if CVV is numeric and has a length of 3 digits
        return is_numeric($cvv) && strlen($cvv) === 3 && ctype_digit($cvv);
    }

    /**
     * Validate the top-up amount.
     *
     * @param $topUpAmount - The top-up amount to validate
     * @return bool - Returns true if the top-up amount is valid, false otherwise
     */
    public function validateTopUpAmount($topUpAmount)
    {
        // Check if the top-up amount is numeric and greater than or equal to 500 (corresponds to $5.00 in cents)
        return is_numeric($topUpAmount) && $topUpAmount >= 500;
    }

    /**
     * Perform a top-up operation.
     *
     * @param $creditCardNumber - The credit card number to use for the top-up
     * @param $expirationMonth - The expiration month of the credit card
     * @param $expirationYear - The expiration year of the credit card
     * @param $cvv - The CVV of the credit card
     * @param $topUpAmount - The amount to top-up
     * @return bool - Returns true if the top-up is successful, false otherwise
     */
    public function topUp($creditCardNumber, $expirationMonth, $expirationYear, $cvv, $topUpAmount)
    {
        // Validate the credit card number
        if (!$this->validateCreditCardNumber($creditCardNumber)) {
            return false;
        }

        // Validate the expiration date
        if (!$this->validateExpirationDate($expirationMonth, $expirationYear)) {
            return false;
        }

        // Validate the CVV
        if (!$this->validateCVV($cvv)) {
            return false;
        }

        // Validate the top-up amount
        if (!$this->validateTopUpAmount($topUpAmount)) {
            return false;
        }

        // If all validations pass, the top-up is successful
        return true;
    }
}
