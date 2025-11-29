<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Account</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/staff/delete_account.css" />
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
    <h2>Account Deletion Scheduled</h2>
    <p>Your account will be deleted in <span id="timer">5m 0s</span>.</p>
    <form id="deleteForm" method="post" action="/quick_serve/staff/delete-account-final">
      <input type="hidden" name="confirm" value="yes">
    </form>
    <form method="post" action="/quick_serve/staff/cancel-deletion">
      <button type="submit" class="btn cancel">Cancel Deletion</button>
    </form>
  </main>

</body>
</html>