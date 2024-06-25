<?php
class Employee
{

    // Connection
    private $conn;

    // Table
    private $db_table = "Employee";

    // Columns
    public $id;
    public $count;
    public $phone_id;
    public $date_created;
    public $date_last_modified;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getEmployees()
    {
        $sqlQuery = "SELECT id, count, phone_id FROM " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createEmployee()
    {
        // Check if phone_id already exists
        $checkQuery = "SELECT phone_id FROM " . $this->db_table . " WHERE phone_id = :phone_id";
        $checkStmt = $this->conn->prepare($checkQuery);

        // sanitize
        $this->phone_id = htmlspecialchars(strip_tags($this->phone_id));

        // bind data
        $checkStmt->bindParam(":phone_id", $this->phone_id);
        $checkStmt->execute();

        // If phone_id already exists, display an error message
        if ($checkStmt->rowCount() > 0) {
            echo "Error: Phone ID already exists in the database.";
            return false;
        }

        // If phone_id doesn't exist, proceed with inserting the new item
        $sqlQuery = "INSERT INTO " . $this->db_table . "
                        SET
                        count = :count, 
                        date_last_modified = :date_last_modified, 
                        date_created = :date_created, 
                        phone_id = :phone_id";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->count = htmlspecialchars(strip_tags($this->count));

        // bind data
        $stmt->bindParam(":count", $this->count);
        $stmt->bindParam(":date_created", $this->date_created);
        $stmt->bindParam(":date_last_modified", $this->date_last_modified);
        $stmt->bindParam(":phone_id", $this->phone_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    // UPDATE
    public function getSingleEmployee()
    {
        $sqlQuery = "SELECT
                        id, 
                        count, 
                        phone_id
                      FROM
                        " . $this->db_table . "
                    WHERE 
                    phone_id = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->phone_id = htmlspecialchars(strip_tags($this->phone_id));

        $stmt->bindParam(1, $this->phone_id);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->count = $dataRow['count'];
        $this->phone_id = $dataRow['phone_id'];
    }


    // UPDATE
    public function updateEmployee()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                    count = :count, 
                    date_last_modified = :date_last_modified, 
                        phone_id = :phone_id
                    WHERE 
                    phone_id = :phone_id";

        $stmt = $this->conn->prepare($sqlQuery);
        
        $this->count = htmlspecialchars(strip_tags($this->count));
        $this->date_last_modified = htmlspecialchars(strip_tags($this->date_last_modified));
        $this->phone_id = htmlspecialchars(strip_tags($this->phone_id));

        // bind data
        $stmt->bindParam(":count", $this->count);
        $stmt->bindParam(":date_last_modified", $this->date_last_modified);
        $stmt->bindParam(":phone_id", $this->phone_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    function deleteEmployee()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE phone_id = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->phone_id = htmlspecialchars(strip_tags($this->phone_id));

        $stmt->bindParam(1, $this->phone_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
