<?php

$insert = false;
$delete = false;
$update = false;
$servername = "localhost";
$username = "root";   // your MySQL username
$password = "";
$database = "notes";
$port = "3307";

// your MySQL password

// Create connection
$conn = mysqli_connect(
  $servername,
  $username,
  $password,
  $database,
  $port
);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['delete'])) {
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `sno`=$sno";
  $result = mysqli_query($conn, $sql);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

  if (isset($_POST['snoEdit'])) {
    // UPDATE
    $sno = $_POST['snoEdit'];
    $title = $_POST['titleEdit'];
    $description = $_POST['descriptionEdit'];
    $sql = "UPDATE `notes` SET `title`='$title', `description`='$description' WHERE `sno`='$sno'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      header("Location: index.php?updated=1");
      exit;
    }
  } else {
    // INSERT
    $title = $_POST['title'];
    $description = $_POST['description'];
    $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title','$description')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      header("Location: index.php?inserted=1");
      exit;
    }
  }
}


?>

<?php if (isset($_GET['inserted'])): ?>
  <div class="alert alert-success alert-dismissible fade show💡">✅ Data inserted successfully!</div>
<?php elseif (isset($_GET['updated'])): ?>
  <div class="alert alert-info alert-dismissible fade show">✏️ Data updated successfully!</div>
<?php elseif (isset($_GET['deleted'])): ?>
  <div class="alert alert-danger alert-dismissible fade show">🗑️ Data deleted successfully!</div>
<?php endif; ?>











<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>iNotes</title>

  <!-- ✅ Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">

  <!-- ✅ jQuery (load first!) -->
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <!-- ✅ DataTables JS -->
  <script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>

  <!-- ✅ Initialize DataTable -->
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();
    });
  </script>


</head>


<body>
  <!-- Button trigger modal -->
  <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
    Edit Modal
  </button> -->

  <!-- Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit Record</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" aria-describedby="emailHelp" name="titleEdit">

            </div>
            <div class="mb-3">
              <label for="desc" class="form-label">Note Description</label>
              <textarea class="form-control" id="descriptionEdit" rows="3" name="descriptionEdit"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">update</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">PHP CRUD</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link " href="#" role="button">
              Contact US
            </a>

          </li>

        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>





  <div class="container my-3">
    <h2>Add a Notes</h2>
    <form action="" method="post">
      <div class="mb-3">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" class="form-control" id="title" aria-describedby="emailHelp" name="title">

      </div>
      <div class="mb-3">
        <label for="desc" class="form-label">Note Description</label>
        <textarea class="form-control" id="description" rows="3" name="description"></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
  <div class="container">




    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S.no</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "select * from notes";
        $result = mysqli_query($conn, $sql);
        $sno = 0;
        while ($row = mysqli_fetch_assoc($result)) {
          $sno = $sno + 1;
          echo "
          <tr>
          <th scope='row'>" . $sno . "</th>
          <td>" . $row['title'] . "</td>
          <td>" . $row['description'] . "</td>
          <td> 
          <button class='btn edit btn-sm btn-primary' id=" . $row['sno'] . ">Edit</button>
         <button class='btn delete btn-sm btn-primary' id=d" . $row['sno'] . ">Delete</button>

       </td>
        </tr>
          ";

        }

        ?>


      </tbody>

    </table>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
    integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
    crossorigin="anonymous"></script>
  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit",);

        tr = e.target.parentNode.parentNode;

        title = tr.getElementsByTagName("td")[0].innerText;
        description = tr.getElementsByTagName("td")[1].innerText;
        console.log(title, description);
        titleEdit.value = title;
        descriptionEdit.value = description;
        snoEdit.value = e.target.id;
        console.log(e.target.id);

        $('#editModal').modal('toggle');

      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("delete clicked");

        $sno = e.target.id.substr(1); // remove 'd' from id, e.g. d3 → 3
        if (confirm("Are you sure you want to delete this note?")) {
          console.log("yes");
          // 🔥 this line actually triggers the PHP delete
          window.location = `index.php?delete=${$sno}`;
        } else {
          console.log("no");
        }
      });
    });

  </script>
</body>

</html>