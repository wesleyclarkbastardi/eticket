<?php
class Ticket{
 
    // database connection and table name
    private $conn;
    private $table_name = "eticket";
 
    // object properties
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $ticketQuantity;
    public $ticketType;
    public $referrals;
    public $specialRequests;
    public $purchaseAgreementSigned;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function create(){

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET firstName=:firstName, lastName=:lastName, email=:email, ticketQuantity=:ticketQuantity, ticketType=:ticketType, referrals=:referrals, specialRequests=:specialRequests, purchaseAgreementSigned=:purchaseAgreementSigned";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize

        $this->firstName=htmlspecialchars(strip_tags($this->firstName));
        $this->lastName=htmlspecialchars(strip_tags($this->lastName));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->ticketQuantity=htmlspecialchars(strip_tags($this->ticketQuantity));
        $this->ticketType=htmlspecialchars(strip_tags($this->ticketType));
        $this->referrals=htmlspecialchars(strip_tags($this->referrals));
        $this->specialRequests=htmlspecialchars(strip_tags($this->specialRequests));
        $this->purchaseAgreementSigned=htmlspecialchars(strip_tags($this->purchaseAgreementSigned));

        // bind values

        $stmt->bindParam(":firstName", $this->firstName);
        $stmt->bindParam(":lastName", $this->lastName);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":ticketQuantity", $this->ticketQuantity);
        $stmt->bindParam(":ticketType", $this->ticketType);
        $stmt->bindParam(":referrals", $this->referrals);
        $stmt->bindParam(":specialRequests", $this->specialRequests);
        $stmt->bindParam(":purchaseAgreementSigned", $this->purchaseAgreementSigned);

        // execute query
        if($stmt->execute()){
            return true;
        }     
        return false;         
    }
}