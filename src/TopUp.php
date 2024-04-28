<?php
// namespace src;

namespace src;


use AllowDynamicProperties;
use PDO;
use PDOException;
#[AllowDynamicProperties] class TopUp
{

    public function __construct($pdo)
    {
        $this->pdo = new \PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!');
    }


    // Function to validate credit card number
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

    // Function to validate expiration date
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

    // Function to validate CVV
    public function validateCVV($cvv)
    {
        // Check if CVV is numeric and has a length of 3 digits
        return is_numeric($cvv) && strlen($cvv) === 3 && ctype_digit($cvv);
    }

    // Function to validate top-up amount
    public function validateTopUpAmount($topUpAmount)
    {
        // Check if the top-up amount is numeric and greater than or equal to 500 (corresponds to $5.00 in cents)
        return is_numeric($topUpAmount) && $topUpAmount >= 500;
    }

    // Function to perform top-up
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