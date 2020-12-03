<?php
/**
 * User: Thomas
 * Date: 15/10/2018
 * Time: 10:13
 */
// PDO Connection singleton
// Ensures only one PDO connection will exist
class PDODB{
    // private static to hold the connection
    private static $dbConnection = null;

    // private to prevent normal
    // class intervention
    private function __construct(){

    }
    private function __clone(){

    }

    /*
     * Return DB connection or create initial connection
     * @return object (PDO)
     * @access public
     */
    public static function getConnection(){
        // If there is'nt a connection already then create one
        if(!self::$dbConnection){
            try{
                // Connection options to include using exception mode
                $options = array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                );

                // Pass in the options as the last parameter so pdo uses exceptions
                self::$dbConnection = new PDO("mysql:host=localhost;dbname=unn_w16030457", "unn_w16030457", "starkiller", $options);
                // self::$dbConnection = new PDO("mysql:host=localhost;dbname=timesheets;", "root", "", $options);



            } catch(PDOException $e){
                // In a production system you would log the error not display it
                echo $e->getMessage();
            }
            // Return the connection
            return self::$dbConnection;
        }
    }
}
