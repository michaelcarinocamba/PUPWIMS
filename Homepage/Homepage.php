<!DOCTYPE html>
<html>
    <head>
        <title>HomePage</title> <link rel="shortcut icon" href= "../images/pupsjlogo.png">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/Homepage.css">
        
    </head>

     
    <body>
        <section>    
   
            <!--LEFT COLUMN CONTENTS-->
            <div class = "Background">
                <img src = "../images/bgsj.jpg">
                <div class = "text-block">
                    <h1>INTERNSHIP MANAGEMENT SYSTEM</h1>
                </div>
            </div> 
            
            <!--MAIN COLUMN CONTENTS--> 
            <div class = "Content"><center>
                <img class = "pupsjlogo" src = "../images/pupsjlogo.png" alt = "PUPSJ Logo">  
                <h3 class = "text" > Hi, PUPian! </h3>
                <p class = "text1" > Please tap your account type </p>

                <br>
                <form method = "post">
                    <input type = "submit" value = "Student" formaction="../Student/Student_Login.php" class = "button hbutton">
                    <input type = "submit" value = "Adviser" formaction="../Adviser/Adviser_Login.php" class = "button hbutton">
                    <input type = "submit" value = "Coordinator" formaction="../Coordinator/Coordinator_Login.php" class = "button hbutton">

                </form> 

                
            </div></center>

        </section>

    </body>
</html>