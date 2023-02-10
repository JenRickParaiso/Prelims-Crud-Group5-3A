<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$id = $name = $age = $contactnumber = $emailaddress = $gender = "";
$id_err = $name_err = $age_err = $contactnumber_err = $emailaddress_err = $gender_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
    } elseif(!ctype_digit($input_age)){
        $age_err = "Please enter a positive integer value.";
    } else{
        $age = $input_age;
    }
    // Validate contact number
    $input_contactnumber = trim($_POST["contactnumber"]);
    if(empty($input_contactnumber)){
        $contactnumber_err = "Please enter your contact number.";     
    } elseif(!ctype_digit($input_contactnumber)){
        $contactnumber_err = "Please enter a positive integer value.";
    } else{
        $contactnumber = $input_contactnumber;
    }
    // Validate email address
    $input_emailaddress = trim($_POST["emailaddress"]);
    if(empty($input_emailaddress)){
        $emailaddress_err = "Please enter your email address.";     
    } else{
        $emailaddress = $input_emailaddress;
    }
     // Validate gender
     $input_gender = trim($_POST["gender"]);
     if(empty($input_gender)){
         $gender_err = "Please enter your Gender.";     
     } else{
         $gender = $input_gender;
     }
     

    // Check input errors before inserting in database
    if(empty($id_err) && empty($name_err) && empty($age_err)&& empty($contactnumber_err)&& empty($emailaddress_err)&& empty($gender_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, age, contactnumber, emailaddress, gender) VALUES (?, ?, ?, ?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "siiss",$param_name, $param_age, $param_contactnumber, $param_emailaddress, $param_gender);
            
            // Set parameters
            $param_name = $name;
            $param_age = $age;
            $param_contactnumber = $contactnumber;
            $param_emailaddress = $emailaddress;
            $param_gender = $gender;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                            <label>Email</label>
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
