<?php
if (isset($_GET['delete'])) {
    $id_user = $_GET['delete'];
    $queryDelete = mysqli_query($config, "UPDATE users SET deleted_at = 1 WHERE id ='$id_user'");
    if ($queryDelete) {
        header("location:?page=user&hapus=berhasil");
    } else {
        header("location:?page=user&hapus=gagal");
    }
}


$id_user = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($config, "SELECT * FROM users WHERE id ='$id_user'");
$rowEdit  = mysqli_fetch_assoc($queryEdit);


if (isset($_POST['name'])) {
    // ada tidak parameter bernama edit, kalo ada jalankan perintah edit/update, kalo tidak ada
    // tambah data baru / insert
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $password = isset($_POST['password']) ? sha1($_POST['password']) :  $rowEdit['password'];


    if (!isset($_GET['edit'])) {
        $insert = mysqli_query($config, "INSERT INTO users (name, email, password) VALUES('$name','$email','$password')");
        header("location:?page=user&tambah=berhasil");
    } else {
        $update = mysqli_query($config, "UPDATE users SET name='$name', email='$email', password='$password' 
        WHERE id='$id_user'");
        header("location:?page=user&ubah=berhasil");
    }
}



$id_users = isset($_GET['add-user-role']) ? $_GET['add-user-role'] : '';

$queryUserRoles = mysqli_query($config, "SELECT user_roles.*, roles.name FROM user_roles 
LEFT JOIN roles ON user_roles.id_role = roles.id
WHERE id_user = '$id_users'
ORDER BY user_roles.id DESC");
$rowUserRoles = mysqli_fetch_all($queryUserRoles, MYSQLI_ASSOC);

if (isset($_POST["id_role"])) {
    $id_role = $_POST["id_role"];
    $insert = mysqli_query($config, "INSERT INTO user_roles (id_users, id_role) VALUES('$id_users', '$id_role')");
    header("location:?page=tambah-user&add-user-role" . $id_users . "&add-role=berhasil");
}

$queryProducts = mysqli_query($config, "SELECT * FROM products ORDER BY id DESC");
$rowProducts = mysqli_fetch_all($queryProducts, MYSQLI_ASSOC);

$queryNoTrans = mysqli_query($config, "SELECT  MAX(id) AS id_trans FROM transactions");
$rowNoTrans = mysqli_fetch_assoc($queryNoTrans);
$id_trans = $rowNoTrans['id_trans'];
$id_trans = $id_trans++;

// if (mysqli_num_rows($queryNoTrans) == 0) {
//     $id_trans = $rowNoTrans['id_trans'] = + 1;
// } else {
//     $id_trans = 1;
// }

$format_no = "TR";
$date = date('dmy');
$increment_number = sprintf("%03s", $id_trans);
$no_transaction = $format_no . "-" . $date . "-" . $increment_number;
// $no_trans = $format_no . "-" . $date . "-" . str_pad("0", $id_trans, STR_PAD_LEFT);

if (isset($_POST["save"])) {
    $no_transaction = $_POST["no_transaction"];
    $id_user = $_POST["id_user"];
    $grand_total = $_POST["grand_total"];

    $insTraction = mysqli_query($config, "INSERT INTO transactions (id_user, no_transaction, sub_total) VALUES('$id_user', '$no_transaction', '$grand_total')");

    if ($insTraction) {
        $id_transaction = mysqli_insert_id($config);
        $id_products = $_POST["id_products"];
        $qtys = $_POST["qtys"];
        $totals = $_POST["totals"];

        foreach ($id_products as $key => $id_product) {
            $id_product = $id_products[$key];
            $qty = $qtys[$key];
            $total = $totals[$key];
            $insTractionDetail = mysqli_query($config, "INSERT INTO transaction_details (id_transaction, id_product, qty, total) VALUES('$id_transaction', '$id_product', '$qty', '$total')");
        }
    }
    header("location:?page=pos");
    exit();
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <?php if (isset($_GET['add-user-role'])):
                    $title = 'Add User Role';
                elseif (isset($_GET['edit'])):
                    $title = 'Edit User';
                else:
                    $title = 'Tambah User';
                endif ?>
                <h5 class="card-title"><?php echo $title ?></h5>
                <?php if (isset($_GET["add-user-role"])): ?>
                    <div align="right" class="mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Role</button>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Role Name</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rowUserRoles as $index => $rowUserRole): ?>
                                <tr>
                                    <td><?php echo $index += 1 ?></td>
                                    <td><?php echo $rowUserRole['name'] ?></td>
                                    <td>
                                        <a href="" class="btn btn-primary btn-sm">Edit</a>
                                        <a onclick="return confirm('Are you sure?')" href="" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label for="">No Transaction *</label>
                                    <input value="<?php echo $no_transaction ?>" type="text" class="form-control" readonly name="no_transaction">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label for="">Product *</label>
                                    <select name="" id="id_product" class="form-control">
                                        <option value="">Select One</option>
                                        <?php foreach ($rowProducts as $rowProduct): ?>
                                            <option data-price="<?php echo $rowProduct['price'] ?>" value="<?php echo $rowProduct['id'] ?>"><?php echo $rowProduct['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label for="">Cashier *</label>
                                    <input value="<?php echo $_SESSION['NAME'] ?>" type="text" class="form-control" readonly>
                                    <input type="hidden" name="<?php echo $_SESSION['ID_USER'] ?>">
                                    
                                </div>
                            </div>

                            <div align="right" class="mb-3">
                                <button type="button" class="btn btn-primary addRow" id="addRow">Add Row</button>
                            </div>
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <br>
                            <p><strong>Grand Total: Rp. <span id="grandTotal"></span></strong></p>
                            <input type="hidden" name="grand_total" id="grandTotalInput" value="0">
                            <!-- <div class="mb-3">
                                <label for="">Email *</label>
                                <input value="<?php echo isset($rowEdit['email']) ? $rowEdit['email'] : '' ?>" type="email" class="form-control" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="">Password *</label>
                                <input value="" type="password" class="form-control" name="password" placeholder="Enter your password"
                                    <?php echo empty($id_user) ? 'required' : ''  ?>>
                                <small>
                                    * If you want to change your password, you can fill this field
                                </small>
                            </div> -->
                            <div class="mb-3">
                                <input type="submit" class="btn btn-success" name="save" value="Save">
                            </div>
                    </form>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Role : </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label">Role Name</label>
                        <select name="id_role" id="" class="form-control">
                            <option value="">Select One</option>
                            <?php foreach ($rowRoles as $rowRole): ?>
                                <option value="<?php echo $rowRole['id'] ?>"><?php echo $rowRole['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const button = document.querySelector('.addRow');
    const tbody = document.querySelector('#myTable tbody');
    const select = document.querySelector('#id_product');
    // button.textContent = "Duarr";
    // button.style.color = "red";
    const grandTotal = document.getElementById('grandTotal');
    const grandTotalInput = document.getElementById('grandTotalInput');

    let no = 1;
    button.addEventListener("click", function() {

        const selectedProduct = select.options[select.selectedIndex];
        const ProductValue = selectedProduct.value;
        if (ProductValue == "") {
            alert("Please Select Product");
            return;
        }
        const ProductName = selectedProduct.textContent;
        const ProductPrice = selectedProduct.dataset.price;
        const tr = document.createElement('tr'); //<tr></tr>
        tr.innerHTML = `
        <td>${no}</td>
        <td>
            <input type='hidden' name='id_product[]' class='id_products' value='${select.Value}'>${ProductName}
        </td>
        <td>
            <input type='number' name='qty[]' value='1' class='qtys'>
            <input type='hidden' class='priceInput' name='price[]' value='${ProductPrice}'>
        </td>
        <td>
            <input type='hidden' name='total[]' class='totals' value='${ProductPrice}'><span class='totalText'>${ProductPrice}</span>
        </td>
        <td>
            <button class="btn btn-success btn-sm removeRow" type='button'>Delete</button>
        </td>
        `; //<tr><td></td></tr>

        tbody.appendChild(tr);
        no++;
        select.value = "";

        updateGrandTotal();
    });

    tbody.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
        }

        updateNumber();
        updateGrandTotal();
    });

    tbody.addEventListener('input', function(e) {
        if (e.target.classList.contains('qtys')) {
            const row = e.target.closest('tr');
            const qty = parseInt(e.target.value) || 0;

            const price = parseInt(row.querySelector('.priceInput').value);

            row.querySelector('.totalText').textContent = price * qty;
            row.querySelector('.totals').value = price * qty;
            // consloe.log(price);
            updateGrandTotal();
        }
    });

    function updateNumber() {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(function(row, index) {
            row.cells[0].textContent = index + 1;
        });

        no = rows.length + 1;
    }

    function updateGrandTotal() {
        const totalCells = tbody.querySelectorAll('.totals');
        let grand = 0;
        totalCells.forEach(function(input) {
            grand += parseInt(input.value) || 0;
        });
        grandTotal.textContent = grand.toLocaleString('id-ID');
        grandTotalInput.value = grand;
    }
</script>