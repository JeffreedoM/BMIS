<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./assets/css/main.css" />
    <title>Signup</title>
</head>

<body class="signup-body">

    <div class="signup">

        <div class="signup__desc">
            <p class="signup__p1">No account yet?</p>
            <p class="signup__p2">Signing up is easy. It only takes a few steps</p>
        </div>

        <!-- signup -->
        <form action="signup.php" method="POST" class="signup__form">
            <div>
                <input type="text" name="username" id="username" placeholder="Username" required>
            </div>
            <div>
                <input type="text" name="email" id="email" placeholder="Email" required>
            </div>
            <div>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <button type="submit" name="submit">SIGN UP</button>
        </form>
        <div class="create-account">
            <span>Already have an account?</span>
            <a href="login.php">Login</a>
        </div>


    </div>

</body>

</html>