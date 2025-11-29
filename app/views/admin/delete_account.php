<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Account Deletion</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/admin/delete_account.css" />
  <script>
    let countdown = 300;
    function updateTimer() {
      const timer = document.getElementById('timer');
      if (countdown > 0) {
        countdown--;
        timer.textContent = Math.floor(countdown / 60) + "m " + (countdown % 60) + "s";
      } else {
        document.getElementById('deleteForm').submit();
      }
    }
    setInterval(updateTimer, 1000);
  </script>
</head>
<body>
  <main class="delete-card">
    <h2>Admin Account Scheduled for Deletion</h2>
    <p>Your account will be deleted in <span id="timer">5m 0s</span>.</p>

    <form id="deleteForm" method="post" action="/quick_serve/admin/delete-final">
      <input type="hidden" name="confirm" value="yes">
    </form>

     <form method="post" action="/quick_serve/admin/dashboard">
      <button type="submit" class="btn cancel">Cancel Deletion</button>
    </form>
  </main>
</body>
</html>