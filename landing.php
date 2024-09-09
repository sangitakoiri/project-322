<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #a2c2e0, #4f6d7a); /* Blue gradient background */
            color: #fff;
            text-align: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
        }
        header {
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-title {
            margin: 0;
            font-size: 2.5em;
            font-weight: 700;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }
        .links {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .links a {
            display: inline-block;
            margin: 15px;
            padding: 20px 30px;
            background: #fff;
            color: #333;
            border-radius: 10px;
            text-decoration: none;
            font-size: 1.2em;
            font-weight: 600;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s, color 0.3s, transform 0.3s, box-shadow 0.3s;
        }
        .links a:hover {
            background-color: #4f6d7a;
            color: #fff;
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }
        .links a:active {
            transform: scale(1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .back-button {
            display: inline-block;
            padding: 15px 30px;
            font-size: 1.2em;
            background: #333;
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }
        .back-button:hover {
            background: #444;
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }
        .back-button:active {
            transform: scale(1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .background-watermark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://tse2.mm.bing.net/th?id=OIP.1WvHwGFGqXHmPfI61hfmggHaEK&pid=Api&P=0&w=300&h=300'); /* Path to your logo */
            background-repeat: no-repeat;
            background-size: cover; /* Adjust based on your needs */
            background-position: center;
            opacity: 0.1; /* Adjust opacity for watermark effect */
            z-index: -1; /* Ensure the watermark is behind the content */
        }
    </style>
</head>
<body>
    <div class="background-watermark"></div>
    <header>
        <h1 class="header-title">Records of Annual Medical Examination</h1>
    </header>
    <section class="links">
        <a href="form.php">Form</a>
        <a href="searchdata.php">Search AME Details</a>
        <a href="BNwise.php">Search Battalion Details</a>
        <a href="import.php">Upload Excel File</a>
        <a href="display.php">Display AME Due</a>
        <a href="record.php">Old Records</a>
        <a href="emprecord.php">Previous Records of Employee</a>
        <a href="index.php" class="back-button">Back to Home</a>
    </section>
</body>
</html>
