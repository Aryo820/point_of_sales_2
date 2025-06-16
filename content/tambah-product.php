<?php
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $queryDelete = mysqli_query($config, "DELETE FROM products WHERE id ='$id'");
    if ($queryDelete) {
        header("location:?page=product&hapus=berhasil");
    } else {
        header("location:?page=product&hapus=gagal");
    }
}


$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($config, "SELECT * FROM products WHERE id ='$id'");
$rowEdit  = mysqli_fetch_assoc($queryEdit);


if (isset($_POST['name'])) {
    // ada tidak parameter bernama edit, kalo ada jalankan perintah edit/update, kalo tidak ada
    // tambah data baru / insert
    $name  = $_POST['name'];
    $id_category  = $_POST['id_category'];
    $price  = $_POST['price'];
    $qty  = $_POST['qty'];
    $description  = $_POST['description'];

    if (!isset($_GET['edit'])) {
        $insert = mysqli_query($config, "INSERT INTO products (name, id_category, price, qty, description)
        VALUES('$name', '$id_category', '$price', '$qty', '$description')");
        header("location:?page=product&tambah=berhasil");
    } else {
        $update = mysqli_query($config, "UPDATE products SET name='$name', id_category='$id_category', price='$price', qty='$qty', description='$description'
        WHERE id='$id'");
        header("location:?page=product&ubah=berhasil");
    }
}

$queryCategoryProduct = mysqli_query($config, "SELECT * FROM categories ORDER BY id DESC");
$rowsCategoryProduct = mysqli_fetch_all($queryCategoryProduct, MYSQLI_ASSOC);

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo isset($rowEdit['id']) ? 'Edit' : 'Add' ?> Product</h5>

                <form action="" method="post">
                    <div class="mb-3">
                        <label for="">Category *</label>
                        <select name="id_category" id="" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($rowsCategoryProduct as $rowCategory): ?>
                                <option <?php echo isset($rowEdit) ? ($rowCategory['id'] == $rowEdit['id_category']) ? 'selected' : '' : '' ?> value="<?php echo $rowCategory['id'] ?>"><?php echo $rowCategory['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="">Product *</label>
                        <input value="<?php echo isset($rowEdit['product']) ? $rowEdit['product'] : '' ?>" type="text" class="form-control"
                            name="name" placeholder="Enter your product" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Price *</label>
                        <input value="" type="number" class="form-control" name="price" placeholder="Enter price"
                            <?php echo empty($_GET['price']) ? 'required' : ''  ?>>
                    </div>
                    <div class="mb-3">
                        <label for="">Qty </label>
                        <input value="<?php echo isset($rowEdit['qty']) ? $rowEdit['qty'] : '' ?>" type="number" class="form-control"
                            name="qty" placeholder="Enter qty">
                    </div>
                    <div class="mb-3">
                        <label for="">Description </label>
                        <textarea name="description" id="" class="form-control"><?php echo isset($rowEdit['description']) ? $rowEdit['description'] : '' ?></textarea>
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