<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Button Click </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
      
        .Btn {
            display: block;
            width: 150px;
            height: 50px;
            margin: 20px auto;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .Btn:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #333;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .Btn {
  --black: #000000;
  --ch-black: #141414;
  --eer-black: #1b1b1b;
  --night-rider: #2e2e2e;
  --white: #ffffff;
  --af-white: #f3f3f3;
  --ch-white: #e1e1e1;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  width: 45px;
  height: 45px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition-duration: .3s;
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
  background-color: var(--af-white);
  margin-left: auto;
}

/* plus sign */
.sign {
  width: 100%;
  transition-duration: .3s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sign svg {
  width: 17px;
}

.sign svg path {
  fill: var(--night-rider);
}
/* text */
.text {
  position: absolute;
  right: 0%;
  width: 0%;
  opacity: 0;
  color: var(--night-rider);
  font-size: 1.2em;
  font-weight: 600;
  transition-duration: .3s;
}
/* hover effect on button width */
.Btn:hover {
  width: 125px;
  border-radius: 5px;
  transition-duration: .3s;
}

.Btn:hover .sign {
  width: 30%;
  transition-duration: .3s;
  padding-left: 20px;
}
/* hover effect button's text */
.Btn:hover .text {
  opacity: 1;
  width: 70%;
  transition-duration: .3s;
  padding-right: 10px;
}
/* button click effect*/
.Btn:active {
  transform: translate(2px ,2px);
}

    </style>
</head>
<body>
   
<button class="Btn.logout-btn" onclick="logout()">
  
  <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
  
  <div class="text">Logout</div>
</button>
    <button class="Btn" onclick="sendButtonClick('Low')">Low</button>
    <button class="Btn" onclick="sendButtonClick('Medium')">Medium</button>
    <button class="Btn" onclick="sendButtonClick('High')">High</button>
    <button class="Btn" onclick="sendButtonClick('Emergency')">Emergency</button>

    <script>
        // Function to handle logout
        function logout() {
            $.ajax({
                url: 'logout.php',
                type: 'POST',
                success: function(response) {
                    console.log(response);
                    // Redirect to login page after successful logout
                    window.location.href = 'po.php';
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error occurred. Check console for details.');
                }
            });
        }

        // Function to display the popup message
        function sendPopup(message) {
            alert(message);
            // You can customize how you want to display the popup here
        }

        // Function to handle button clicks
        function sendButtonClick(buttonType) {
            alert(buttonType); // Display button label in a popup
            $.ajax({
                url: 'update_login.php',
                type: 'POST',
                data: { button: buttonType },
                success: function(response) {
                    console.log(response);
                    // No need for alert(response); here
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error occurred. Check console for details.');
                }
            });
        }
    </script>

</body>
</html>


       

       

