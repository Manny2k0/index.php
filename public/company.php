<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revolut Culture</title>

    <style>


        .header {
            background-color: #f2f2f2;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }

        .header h3 {
            margin-top: 0;
            margin-bottom: 0;
            font-size: 24px;
            color: #333;
        }

        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 500px; /* Adjusted max-width */
            margin: 0 auto;
            padding: 20px;
        }

        nav {
            background-color: #333;
            border-radius: 5px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .founded-date1 {
            font-style: italic; /* Make the text italic */
            font-size: 20px; /* Adjust the font size */
            color: #666; /* Change the color */
            margin-top: 20px; /* Add some space between paragraphs */
        }

        nav ul li {
            display: inline-block;
        }

        nav ul li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #555;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Changed to 2 columns */
            gap: 15px;
            width: 100%;
        }

        /* Additional CSS to place the grid items in each corner */
        .grid-item:nth-child(1),
        .grid-item:nth-child(2),
        .grid-item:nth-child(5),
        .grid-item:nth-child(6) {
            grid-column: 1; /* Align to the first column */
        }

        .grid-item:nth-child(2),
        .grid-item:nth-child(4),
        .grid-item:nth-child(7),
        .grid-item:nth-child(8) {
            grid-column: 2; /* Align to the second column */
        }

        .grid-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        p {
            margin-bottom: 0;
        }

        /* Footer styles */
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
            margin-top: 20px;
            color: #333;
            border-radius: 10px; /* Adding border-radius */
        }

        .founded-date {
            font-style: italic; /* Make the text italic */
            font-size: 14px; /* Adjust the font size */
            color: #666; /* Change the color */
            margin-top: 10px; /* Add some space between paragraphs */
        }
    </style>
</head>
<body>

<header class="header">
    <h1 class="founded-date1">Revolut</h1>
</header>


<div class="container">
    <div class="header">
        <h3 class="text-muted">Company</h3>
        <hr>
        <br>
        <nav>
            <ul>

                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Sign-In</a></li>
            </ul>
        </nav>
        <div class="grid-layout">
            <div class="grid-item">
                <h2>About Us</h2>
                <p>  We're Revolut, not just a bank, but a hub of innovation and change. We challenge norms, redefine banking, and deliver cutting-edge solutions globally.</p>
            </div>
            <div class="grid-item">
                <h2>Careers</h2>
                <p>At Revolut, we nurture talent and ambition, offering endless opportunities for personal and professional growth. Join us to make a real impact on the world.</p>
            </div>
            <div class="grid-item">
                <h2>Contact</h2>
                <p>We value open communication. Reach out with questions, feedback, or partnership inquiries. Our dedicated team ensures your experience with Revolut is exceptional.</p>
            </div>
            <div class="grid-item">
                <h2>Feedback</h2>
                <p>Your feedback matters. It drives our continuous improvement to meet your needs better. Share your thoughts, and together, we innovate to serve you better.</p>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Revolut. All rights reserved.</p>
        <p class="founded-date">Founded July 1, 2015</p> <!-- code derived from https://github.com/mariofont -->
    </footer>

</body>
</html>
