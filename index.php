<?php

$server = "localhost";
$usrname = "root";
$password = "";
$database = "note";

$true = false;
$update = false;
$delete = false;


$conn = mysqli_connect($server, $usrname, $password, $database);

if (!$conn) {
  die("Connection Failed" . mysqli_connect_error());
}

if (isset($_GET['delete'])) {
  $Sr = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `notes`.`Sr` = $Sr";
  $result = mysqli_query($conn, $sql);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['snoEdit'])) {
    // Update the record
    $Sr = $_POST["snoEdit"];
    $Title = $_POST["titleEdit"];
    $Description = $_POST["descriptionEdit"];

    // Sql query to be executed
    $sql = "UPDATE `notes` SET `Title` = '$Title', `Description` = ' $Description ' WHERE `notes`.`Sr` = $Sr";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $update = true;
    } else {
      echo "We could not update the record successfully";
    }
  } else {
    $Title = $_POST["Title"];
    $description = $_POST["description"];



    $sql = "INSERT INTO `notes` (`Title`, `Description`) VALUES ('$Title', '$description')";
    //$sql= 'INSERT INTO "notes" ("Sr", "Title", "Description", "Time") VALUES (NULL, $Title, $description, current_timestamp())';

    $result = mysqli_query($conn, $sql);

    if ($result) {
      $true = true;
    }
  }


}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Note_Taking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

  <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>


</head>

<body>
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="/iceico/Note_Taking/index.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="title">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
              <label for="desc">Note Description</label>
              <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>



  <nav class="navbar bg-dark border-bottom border-bottom-dark" data-bs-theme="dark">

    <div class="container-fluid">
      <a class="navbar-brand">Note_Taking</a>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </nav>
  <?php
  if ($true) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Submited Succesfully!
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button>
</div>";
  }
  ?>

  <?php
  if ($delete) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Deleted Succesfully!
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button>
</div>";
  }
  ?>
  <?php
  if ($update) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Updated Succesfully!
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button>
</div>";
    ;
  }


  ?>

  <form action="/iceico/Note_Taking/index.php" method="POST">
    <div class="container  my-5">
      <h2>Add a Note </h2>
      <div class="mb-3">
        <label for="Title" class="form-label" d>Title</label>
        <input type="text" class="form-control" id="Title" name="Title" placeholder="Title" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea type="text" class="form-control" id="description" name="description" rows="3"
          placeholder="Add description here"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>

    </div>
  </form>

  <div class="container my-4">


    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S.No</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Date & Time</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php

        $sql = "SELECT * FROM `notes`";
        $result = mysqli_query($conn, $sql);
        $Sr = 0;
        while ($row = mysqli_fetch_assoc($result)) {
          $Sr = $Sr + 1;

          echo " <tr>
  <th>" . $Sr . "</th>
  <td>" . $row['Title'] . "</td>
  <td>" . $row['Description'] . "</td>
  <td>" . $row['Time'] . "</td>
  <td><button class='edit btn btn-sm btn-primary' id=" . $row['Sr'] . ">Edit</button> <button class='delete btn btn-sm btn-primary' id=d" . $row['Sr'] . ">Delete</button> </td>
  </tr>";

        }



        ?>
      </tbody>
    </table>
  </div>




  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();

    });
  </script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>

  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        tr = e.target.parentNode.parentNode;
        Title = tr.getElementsByTagName("td")[0].innerText;
        Description = tr.getElementsByTagName("td")[1].innerText;
        console.log(Title, Description);
        titleEdit.value = Title;
        descriptionEdit.value = Description;
        snoEdit.value = e.target.id;
        console.log(e.target.id)
        $('#editModal').modal('toggle');
      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit");
        sno = e.target.id.substr(1);

        if (confirm("Want to delete")) {
          console.log("yes");
          window.location = `/iceico/Note_Taking/index.php?delete=${sno}`;
        } else {
          console.log("no");
        }
      })
    })


  </script>

</body>

</html>