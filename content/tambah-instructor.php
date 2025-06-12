<?php
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $queryDelete = mysqli_query($config, "DELETE FROM instructors  WHERE id ='$id'");
    if ($queryDelete) {
        header("location:?page=instructor&hapus=berhasil");
    } else {
        header("location:?page=instructors&hapus=gagal");
    }
}


$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($config, "SELECT * FROM instructors WHERE id ='$id'");
$rowEdit  = mysqli_fetch_assoc($queryEdit);


if (isset($_POST['name'])) {
    // ada tidak parameter bernama edit, kalo ada jalankan perintah edit/update, kalo tidak ada
    // tambah data baru / insert
    $name  = $_POST['name'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $address  = $_POST['address'];
    $gender  = $_POST['gender'];
    $id_role = 4;
    $password = isset($_POST['password']) ? sha1($_POST['password']) :  $rowEdit['password'];


    if (!isset($_GET['edit'])) {
        $insert = mysqli_query($config, "INSERT INTO instructors (id_role, name, gender,email, phone, address, password)
         VALUES('$id_role','$name','$gender', '$email', '$phone', '$address','$password')");
        header("location:?page=instructor&tambah=berhasil");
    } else {
        $update = mysqli_query($config, "UPDATE instructors SET id_role='$id_role',
        name='$name', gender='$gender', 
        email='$email',
        password='$password',
        phone='$phone',
        address='$address'
        WHERE id='$id'");
        header("location:?page=instructor&ubah=berhasil");
    }
}




?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo isset($rowEdit['id']) ? 'Edit' : 'Add' ?> Instructor</h5>

                <form action="" method="post">
                    <div class="mb-3">
                        <label for="">Name *</label>
                        <input value="<?php echo isset($rowEdit['name']) ? $rowEdit['name'] : '' ?>" type="text" class="form-control"
                            name="name" placeholder="Enter instructor name" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Gender *</label>
                        <br>
                        <input type="radio" name="gender" value="1" <?php echo isset($rowEdit['gender']) ? ($rowEdit['gender'] == 1) ? 'checked' : '' : '' ?>> Male
                        <input type="radio" name="gender" value="0"
                            <?php echo isset($rowEdit['gender']) ? ($rowEdit['gender'] == 0) ? 'checked' : '' : '' ?>> Female
                    </div>
                    <div class="mb-3">
                        <label for="">Email*</label>
                        <input value="<?php echo isset($rowEdit['email']) ? $rowEdit['email'] : '' ?>" type="text" class="form-control"
                            name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Password *</label>
                        <input value="" type="password" class="form-control" name="password" placeholder="Enter your password"
                            <?php echo empty($_GET['edit']) ? 'required' : ''  ?>>
                        <small>
                            )* If you want to change your password, you can fill this field
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="">Phone </label>
                        <input value="<?php echo isset($rowEdit['phone']) ? $rowEdit['phone'] : '' ?>" type="text" class="form-control"
                            name="phone" placeholder="Enter your phone">
                    </div>
                    <div class="mb-3">
                        <label for="">Address </label>
                        <textarea name="address" id="" class="form-control"><?php echo isset($rowEdit['address']) ? $rowEdit['address'] : '' ?></textarea>
                    </div>
                    <div class="mb-3">
                    </div>
                    <div class="mb-3">
                        <input type="submit" class="btn btn-success" name="save" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>