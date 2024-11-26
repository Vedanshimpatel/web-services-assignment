<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>
<body>
    <h3>Registration Page</h3>
    <div class="mb-3">
      <label for="name" class="form-label">Firstname:</label>
      <input type="name" class="form-control" id="name" name="name" placeholder="name">
</div><br></br>
    <div class="mb-3">
      <label for="name" class="form-label">Lastname:</label>
      <input type="name" class="form-control" id="name" name="name" placeholder="name">
</div><br></br>
<div class="mb-3">
      <label for="name" class="form-label">Gender:</label>
      <div class="form-check">
          <input class="form-check-input" type="radio" name="gender" id="gender" value="male">
          <lable class="form-check-lable" for="flexRadioDefault1">Male
</lable>
      </div>
      <div class="form-check">
          <input class="form-check-input" type="radio" name="gender" id="gender" value="female">
          <lable class="form-check-lable" for="flexRadioDefault1">FeMale
</lable>
      </div>
</div><br></br>
<div class="mb-3">

      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="name" placeholder="email@example.com">
</div><br></br>

<div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="name@example.com">
</div><br></br>

      <button type="button" class="btn btn-primary">Save</button>



<?php

class registration{
    private $conn;
    private $Table;
    public function __construct($conn){
        $this->conn=$conn;
        $this->tablename='restapi';
    }

    public function validate($firstName, $lastName, $gender, $email, $password) {
    $error=false;
    $errmsg= null;

    if(empty ($firstName)) {
        $errmsg = "First Name is empty";
        $error = true;
    }
    if(empty ($lastName)) {
        $errmsg = "Last Name is empty";
        $error = true;
    }
    if(empty ($gender)) {
        $errmsg = "gender is empty";
        $error = true;
    }
    if(empty ($email)) {
        $errmsg = "email is empty";
        $error = true;
    }
    if(empty ($password)) {
        $errmsg = "password is empty";
        $error = true;
    }
    $errorinfo=[
        "error" =>$error,
        "errmsg" =>$errmsg
    ];

    return $errorinfo;
    }

    public function create(){
        
        $data = json_decode(file_get_contents("php://input"), true);
        $firstName =$data['firstname'];
        $lastName =$data['lastname'];
        $gender =$data['gender'];
        $email =$data['email'];
        $password=$data['password'];
        $check =$this->getByEmail($email);
        if(!$check){
            $validate =$this->validate($firstName, $lastName, $gender, $email, $password);
            $success =false;

        if (!$validate['error']){
                $query = "INSERT into ";
                $query .= $this->restapi; 
                $query .= " (firstName, lastName, gender, email, password ) ";
                $query .= " Values (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("sssss", $firstName, $lastName, $gender, $email, $password);
        
                if ($stmt->execute()) {
                    $status = 200;
                    $msg = "You are registered successfully";
                } else{
                    $status = 400;
                    $msg = $this->conn->error;
                }

            }else{
                $status =401;
                $msg =$validate['errmsg'];
           }  
           }else {
            $status =401;
            $msg ="This email already registered";
           }
            
           $data =[
            'ststus'=> $status,
            'msg'=> $msg,
            'data'=>$data
          ];
          return json_decode($data);
    }

         public function getByEmail($email){
            $isExist = false;
            $query ="SELECT email from";
            $query .=$this->tablename;
            $query .="WHERE email=?";
            $stmt->bind_param("s", $email);
           
            if($stmt->execute()) {
            $result =$stmt->get_result();
            $row=$result->fetch_assoc();

            if ($result->num_rowa >0) {
                $isExist= true;
            }
        }
            return $isExist;
    }
}    
            
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</body>

</html>