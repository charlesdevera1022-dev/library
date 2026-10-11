<?php
$base = '../';
include '../connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    run_query($conn, "DELETE FROM users WHERE id = ? AND role = 'member'", "i", array((int)$_POST['id']));
    set_message("success", "Member deleted.");
    header("Location: members.php");
    exit;
}

$q = isset($_GET['q']) ? trim($_GET['q']) : "";
$like = "%" . $q . "%";
$members = run_query($conn,
    "SELECT u.*,
            (SELECT COUNT(*) FROM loans l WHERE l.user_id = u.id AND l.returned_on IS NULL) AS borrowing,
            (SELECT COUNT(*) FROM loans l WHERE l.user_id = u.id) AS total_loans
     FROM users u
     WHERE u.role = 'member' AND (u.name LIKE ? OR u.email LIKE ?)
     ORDER BY u.name",
    "ss", array($like, $like));

$title = "Members";
include '../header.php';
?>
<section>
  <h1 class="h3 mb-3">Members</h1>

  <form class="d-flex mb-3" method="GET">
    <input class="form-control me-2" type="search" name="q" placeholder="Search name or email" value="<?php echo e($q); ?>">
    <button type="submit" class="btn btn-outline-primary">Search</button>
  </form>

  <?php if (mysqli_num_rows($members) == 0) { ?>
    <div class="alert alert-info">No members found.</div>
  <?php } else { ?>
  <div class="table-responsive">
    <table class="table align-middle bg-white">
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Borrowing now</th><th>All loans</th><th></th></tr></thead>
      <tbody>
      <?php while ($m = mysqli_fetch_assoc($members)) { ?>
        <tr>
          <td><?php echo e($m['name']); ?></td>
          <td><?php echo e($m['email']); ?></td>
          <td><?php echo e($m['phone']); ?></td>
          <td><?php echo (int)$m['borrowing']; ?></td>
          <td><?php echo (int)$m['total_loans']; ?></td>
          <td class="text-end">
            <form method="POST" class="d-inline confirm-delete" data-message="Delete this member and their loan history?">
              <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
  <?php } ?>
</section>
<?php include '../footer.php'; ?>
