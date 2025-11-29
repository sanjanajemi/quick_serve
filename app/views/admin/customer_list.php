<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer List</title>
  <link rel="stylesheet" href="/quick_serve/assets/css/admin/customer_list.css">
</head>
<body>

  <h2>Customer List</h2>

  <div class="top-bar">
    <a href="/quick_serve/admin/dashboard" class="dashboard-button">Back to Dashboard</a>
  </div>

  <table id="customerTable" class="display">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($customers as $customer): ?>
        <tr>
          <td><?= htmlspecialchars($customer['name']) ?></td>
          <td><?= htmlspecialchars($customer['email']) ?></td>
          <td>
            <a class="view-link" href="/quick_serve/admin/customer/view?id=<?= $customer['customer_id'] ?>">View</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#customerTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        order: [[0, 'asc']],
        language: {
          search: "Search customers:",
          paginate: {
            previous: "Prev",
            next: "Next"
          }
        }
      });
    });
  </script>

    <script src="\quick_serve\assets\js\admin\customer_list.js"></script>
</body>
</html>