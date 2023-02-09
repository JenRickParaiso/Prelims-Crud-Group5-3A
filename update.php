<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $age = $contactnumber = $emailaddress =  $gender = "";
$name_err = $age_err = $contactnumber_err = $emailaddress_err = $gender_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    // Validate age
    $input_age = trim($_POST["age"]);
    if(empty($input_age)){
        $age_err = "Please enter your age.";     
    } else{
        $age = $input_age;
    }
	// Validate  contact number
    $input_contactnumber = trim($_POST["contactnumber"]);
    if(empty($input_contactnumber)){
        $contactnumber_err = "Please enter your contactnumber.";     
    } else{
        $contactnumber = $input_contactnumber;
    }
    // Validate address email address
    $input_emailaddress = trim($_POST["emailaddress"]);
    if(empty($input_emailaddress)){
        $emailaddress_err = "Please enter your email address.";     
    } else{
        $emailaddress = $input_emailaddress;
    }
    
    // Validate gender
    $input_gender = trim($_POST["gender"]);
    if(empty($input_gender)){
        $gender_err = "Please enter your gender.";
    } elseif(!filter_var($input_gender, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $gender_err = "Please enter a valid gender.";
    } else{
        $gender = $input_gender;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($age_err) && empty($contactnumber_err) && empty($emailaddress_err) && empty($gender_err)){
        // Prepare an update statement
        $sql = "UPDATE employees SET name=?, age=?, contactnumber=?, emailaddress=?, gender=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sisssi", $param_name, $param_age, $param_contactnumber, $param_emailaddress, $param_gender, $param_id);
            
            // Set parameters
            $param_name = $name;
			$param_age = $age;
			$param_contactnumber = $contactnumber;
            $param_emailaddress = $emailaddress;
            $param_gender = $gender;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM employees WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
					$age = $row["age"];
					$contactnumber = $row["contactnumber"];
                    $emailaddress = $row["emailaddress"];
                    $gender = $row["gender"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
						<div class="form-group">
                            <label>Age</label>
                            <input type="number" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>">
                            <span class="invalid-feedback"><?php echo $age_err;?></span>
                        </div>
						<div class="form-group">
                            <label>Contact #</label>
                            <input type="text" name="contactnumber" class="form-control <?php echo (!empty($contactnumber_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $contactnumber; ?>">
                            <span class="invalid-feedback"><?php echo $contactnumber_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
							<input type="email" name="emailaddress" class="form-control <?php echo (!empty($emailaddress_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $emailaddress; ?>">
                            <span class="invalid-feedback"><?php echo $emailaddress_err;?></span>
                        </div>
						<div class="form-group">
							<label for="exampleFormControlSelect1">Gender</label>
							<select class="form-control <?php echo (!empty($gender_err)) ? 'is-invalid' : ''; ?>" name="gender">
							  <option value="Male" <?php if($gender=="Male"){echo "selected";}?>>Male</option>
							  <option value="Female" <?php if($gender=="Female"){echo "selected";}?>>Female</option>
							</select>
							<span class="invalid-feedback"><?php echo $gender_err;?></span>
						  </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
