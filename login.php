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
    <h3>Login Page</h3>
    <div class="mb-3">

      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="name" placeholder="email@example.com">
</div><br></br>

<div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="">
</div><br></br>

      <button type="button" class="btn btn-primary">Login</button>
<?php
class login {
    private $conn;
    private $Table;

    public function __construct($conn) {
        $this->conn =$conn;
        $this->tablename ='restapi';
    }
    public function validate($email, $password) {
        $error = false;
        $errmsg = null;

        if(empty($email)) {
            $errmsg ="Email is empty";
            $error = true;
        }
        if(empty($password)) {
            $errmsg ="Password is empty";
            $error = true;
        }
        $errorInfo =[
            "error" =>$error,
            "errmsg" =>$errmsg
        ];
        return $errorInfo;

    }
    public function getlogin() {
        $data = json_decode (file_get_contents("php://input"));

        $email =$data['email'];
        $password= $data['password'];
        $validate =$this->validate($email, $password);

        if(!$validate['error']) {
            $query = "SELECT email , password FROM";
            $query .= $this->restapi;
            $query .="WHERE email=? AND password=?";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $email, $password);

            if($stmt->execute()) {
                $result = $stmt->get_result();
                $rows= $result->fetch_assoc();

                if($result->num_row >0)
                $token =md5(uniqid());

                $query = "UPDATE";
                $query .= $this->restapi;
                $query .= "SET token =";
                $query .= "WHERE id =?";
                $stmt =$this->conn->prepare($query);
                $stmt ->bind_param("ss", $token, $rows['id']);
                $stmt->execute();
                $status = 200;
                $msg ="Logged in successfully";
                $data = $rows;
                $data =[
                    'status' =>200,
                    'msg' =>"Logged in successfully",
                    'data =>$rows'
                ];
            }else {
                $data =[
                    'status' =>404,
                    'msg' =>"Invalid User",
                    'data' =>[]
                ];
            }
            
        }else {
            $data =[
                'status' =>401,
                'msg'=>$validate['errmsg'],
                'data' =>[]
            ];
        }
        return json_encode($data);
    }
}
?>