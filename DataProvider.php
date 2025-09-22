<?php
/**
 * DataProvider Class - Database Connection Handler
 * BookStore Online Application
 * 
 * This class handles database connections and query executions
 * using MySQLi for improved security and compatibility
 */
class DataProvider
{
    private static $host = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $database = "ebookDB";
    
    /**
     * Execute a SQL query and return the result
     * 
     * @param string $sql The SQL query to execute
     * @return mysqli_result|bool The query result or false on failure
     */
    public static function ExecuteQuery($sql)
    {
        // Create connection using MySQLi
        $connection = new mysqli(self::$host, self::$username, self::$password, self::$database);
        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        
        // Set charset to UTF-8 for proper Vietnamese character handling
        $connection->set_charset("utf8");
        
        // Execute the query
        $result = $connection->query($sql);
        
        // Close connection
        $connection->close();
        
        return $result;
    }
    
    /**
     * Execute a prepared statement for better security
     * 
     * @param string $sql The SQL query with placeholders
     * @param array $params Array of parameters to bind
     * @param string $types String indicating parameter types (i, d, s, b)
     * @param bool $returnInsertId Whether to return the last insert ID for INSERT queries
     * @return mysqli_result|bool|int The query result for SELECT, execution status for UPDATE/DELETE, or insert ID for INSERT
     */
    public static function ExecutePreparedQuery($sql, $params = [], $types = "", $returnInsertId = false)
    {
        // Create connection
        $connection = new mysqli(self::$host, self::$username, self::$password, self::$database);
        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        
        // Set charset to UTF-8
        $connection->set_charset("utf8");
        
        // Prepare statement
        $stmt = $connection->prepare($sql);
        
        if (!$stmt) {
            die("Prepare failed: " . $connection->error);
        }
        
        // Bind parameters if provided
        if (!empty($params) && !empty($types)) {
            $stmt->bind_param($types, ...$params);
        }
        
        // Execute statement
        $executeResult = $stmt->execute();
        
        // Determine query type to return appropriate result
        $queryType = strtoupper(trim(explode(' ', $sql)[0]));
        
        if ($queryType === 'SELECT') {
            // For SELECT queries, return the result set
            $result = $stmt->get_result();
        } elseif ($queryType === 'INSERT' && $returnInsertId && $executeResult) {
            // For INSERT queries when insert ID is requested, return the last insert ID
            $result = $connection->insert_id;
        } else {
            // For INSERT, UPDATE, DELETE queries, return execution status
            $result = $executeResult;
        }
        
        // Close statement and connection
        $stmt->close();
        $connection->close();
        
        return $result;
    }
    
    /**
     * Get the last inserted ID
     * 
     * @return int The last inserted ID
     */
    public static function GetLastInsertId()
    {
        $connection = new mysqli(self::$host, self::$username, self::$password, self::$database);
        
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        
        $lastId = $connection->insert_id;
        $connection->close();
        
        return $lastId;
    }
    
    /**
     * Execute a non-query SQL command (INSERT, UPDATE, DELETE)
     * 
     * @param string $sql The SQL command to execute
     * @return bool True on success, false on failure
     */
    public static function ExecuteNonQuery($sql)
    {
        $connection = new mysqli(self::$host, self::$username, self::$password, self::$database);
        
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        
        $connection->set_charset("utf8");
        
        $result = $connection->query($sql);
        $connection->close();
        
        return $result;
    }
}
?>
