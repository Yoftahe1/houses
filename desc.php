<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
            width: 80px;
            text-align: center;
        }

        .nav {
            display: flex;
            height: 60px;
            align-items: center;
            justify-content: space-between;
            background-color: white;
            padding: 0 60px;
            position: sticky;
            top: 0;
            right: 0;
        }

        body {
            background: url("./images/home image.webp");
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
        }

        .desc {
            margin: 70px;
            padding: 50px;
            /* background-color: white; */
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            color: white;
        }

        .body a {
            background-color: #3876BF;
            color: white;
            padding: 7px;
        }

        h1 {
            font-size: 100px;

        }

        h3 {
            font-size: 50px;
        }

        .use {
            display: flex;
            justify-content: center;
            text-align: center;
            gap: 50px;

        }

        .center {
            padding: 70px;
            background-color: white;
            display: flex;
            gap: 200px;
            border-radius: 20px;
        }

        img {
            height: 200px;
            width: 200px;
            background: red;
            border-radius: 10px;
        }

        .footer {
            padding: 10px 0;
            font-size: 25px;
            background-color: white;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="nav">
        <h2>houses</h2>
        <div>
            <a href="signin.php">sign-in</a>
            <a href="signup.php">sign-up</a>
        </div>
    </div>
    <div class="body">
        <div class="desc">
            <h1>houses</h1>
            <h3>
                it is one of the fastest growing company<br>
                for buying and selling houses
            </h3>
            <a href="signin.php">sign in</a>
        </div>
        <div class="use">
            <div class="center">
                <div>
                    <img src="./images/buy.jpg">
                    <h4>buy</h4>
                </div>
                <div>
                    <img src="./images/sell.jpg">
                    <h4>sell</h4>
                </div>
            </div>

        </div>
    </div>
    <div class="footer">
        contact us:house@gmail.com
        <br >
        phone no:+2519665328965
    </div>

</body>

</html>