<?php
# Caesar Cipher Implementation
# 
# This script implements the Caesar Cipher encryption and decryption based on the
# extended ASCII range from 33 ('!') to 126 ('~'), as required by the assignment.
# It accepts user inputs from the command line for encryption or decryption, along with
# a key value and input/output file paths.
# 
# Module Learning Objectives Met:
# - K0080: Use of basic software design tools and methods
# - K0082: Demonstration of secure programming principles through encryption
# - K0147: Implementation of encryption techniques to mitigate security risks
# - K0493: Understanding of obfuscation techniques through encryption (Caesar cipher)
# - K0396: Use of programming concepts (e.g., data manipulation, file handling)

# Function to encrypt or decrypt text using the Caesar Cipher
# 
# This function supports both encryption and decryption based on the 'operation'
# argument, which determines whether the characters will be shifted forward (encryption)
# or backward (decryption).
# 
# @param string $text The input text to be encrypted or decrypted.
# @param int $key The key value used for shifting characters (0-93 to ensure wrapping within ASCII 33-126).
# @param string $operation 'encrypt' or 'decrypt' to indicate the operation.
# @return string The resulting encrypted or decrypted text
# 
# K0080: Demonstrates software design techniques by separating the cipher logic into a function.
# K0082: Ensures that code is modular and can handle both encryption and decryption securely.
function caesar_cipher($text, $key, $operation) {
    $result = '';  # To store the resulting encrypted or decrypted text
    # Determine shift direction based on operation (encrypt or decrypt)
    $shift = ($operation === 'encrypt') ? $key : -$key;

    # Loop through each character in the input text
    foreach (str_split($text) as $char) {
        $ascii_val = ord($char); # Get the ASCII value of the character
        if ($ascii_val >= 33 && $ascii_val <= 126) {
            # Shift the character while wrapping around within the ASCII range 33-126
            $shifted_val = ($ascii_val - 33 + $shift) % 94;
            if ($shifted_val < 0) {
                $shifted_val += 94;  # Handle negative wrap-around for decryption
            }
            $result .= chr($shifted_val + 33);  # Append shifted character to the result
        } else {
            # Leave characters outside the ASCII range unchanged (per assignment requirements)
            $result .= $char;
        }
    }

    return $result;  # Return the final encrypted or decrypted string
}

# Function to handle file reading, cipher processing, and file writing.
# 
# This function reads text from the input file, performs Caesar Cipher encryption
# or decryption, and writes the result to the output file.
# 
# @param string $input_file Path to the input text file.
# @param string $output_file Path to the output text file.
# @param int $key The key value for character shifting.
# @param string $operation 'encrypt' or 'decrypt' to indicate the operation.
# 
# K0396: Demonstrates the use of file handling in programming to read and write files.
function handle_files($input_file, $output_file, $key, $operation) {
    # Check if input file exists, and if not, exit the script with an error message
    if (!file_exists($input_file)) {
        echo "Error: Input file does not exist.\n";
        exit(1);
    }
    
    $text = file_get_contents($input_file);  # Read the content from the input file
    $result = caesar_cipher($text, $key, $operation);  # Perform Caesar Cipher encryption/decryption
    file_put_contents($output_file, $result);  # Write the resulting text to the output file
    echo ucfirst($operation) . "ion completed. Output written to $output_file\n";  # Confirmation message
}

# Command-line interface (CLI) for Caesar Cipher program
# Usage: php main_caesarcipher.php <encrypt|decrypt> <key> <input_file> <output_file>
if ($argc == 5) {
    $operation = $argv[1];  # First argument is 'encrypt' or 'decrypt'
    $key = intval($argv[2]);  # Second argument is the key (integer)
    $input_file = $argv[3];  # Third argument is the input file path
    $output_file = $argv[4];  # Fourth argument is the output file path

    # Validate the operation argument
    if (!in_array($operation, ['encrypt', 'decrypt'])) {
        echo "Error: Invalid operation. Use 'encrypt' or 'decrypt'.\n";
        exit(1);
    }

    # Validate the key range (0-93)
    if ($key < 0 || $key > 93) {
        echo "Error: Invalid key. Key must be between 0 and 93.\n";
        exit(1);
    }

    # Process the input and output files using the specified key and operation
    handle_files($input_file, $output_file, $key, $operation);
} else {
    # Display usage instructions if the wrong number of arguments is provided
    echo "Usage: php main_caesarcipher.php <encrypt|decrypt> <key> <input_file> <output_file>\n";
}
?>
