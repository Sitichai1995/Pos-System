<?php

include('./config/function.php');

if (isset($_POST['saveAdmin'])) {
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = validate($_POST['is_ban']) == true ? 1 : 0;

    if ($name != '' &&  $email !== '' && $password != '') {
        $emailCheck = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");

        if ($emailCheck) {
            if (mysqli_num_rows($emailCheck) > 0) {
                redirect('admins-create.php', 'Email Alerady used by another user .');
            }
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $bcrypt_password,
            'phone' => $phone,
            'is_ban' => $is_ban

        ];
        $result = insert('admins', $data);

        if ($result) {
            redirect('admins.php', 'Admin Created Successfully');
        } else {
            redirect('admins-create.php', 'Something Went Wrong!');
        }
    } else {
        redirect('admins-create.php', "Please fill reauired fields.");
    }
}

if (isset($_POST['UpdateAdmin'])) {
    $adminId = validate($_POST['adminId']);

    $adminData = getById('admins', $adminId);
    if ($adminData['status'] !== 200) {
        redirect('admins-edit.php?id=' . $adminId, 'Please fill required fields.');
    }

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = validate($_POST['is_ban']) == true ? 1 : 0;

    $checkEmailQuery = "SELECT * FROM admins WHERE email = '$email' AND id!='$adminId'";
    $checkResult = mysqli_query($conn, $checkEmailQuery);
    if ($checkResult) {
        if (mysqli_num_rows($checkResult) > 0) {
            redirect('admins-edit.php?id=' . $adminId, 'Email Already used by another user');
        }
    };

    if ($password !== "") {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    } else {
        $hashedPassword = $adminData['data']['password'];
    }

    if ($name != '' &&  $email !== '') {
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'phone' => $phone,
            'is_ban' => $is_ban

        ];
        $result = update('admins', $adminId, $data);

        if ($result) {
            redirect('admins-edit.php?id=' . $adminId, 'Admin Updated Successfully');
        } else {
            redirect('admins-edit.php?id=' . $adminId, 'Something Went Wrong!');
        }
    } else {
        redirect('admins-create.php', "Please fill reauired fields.");
    }
}


//categories

if (isset($_POST['saveCategory'])) {

    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status,
    ];
    $result = insert('categories', $data);

    if ($result) {
        redirect('categories.php', 'Category Created Successfully');
    } else {
        redirect('categories-create.php', 'Something Went Wrong!');
    }
}

if (isset($_POST['UpdateCategory'])) {

    $itemId = validate($_POST['itemId']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status,
    ];
    $result = update('categories', $itemId, $data);

    if ($result) {
        redirect('categories.php', 'Category update Successfully');
    } else {
        redirect('categories-create.php', 'Something Went Wrong!');
    }
}


//Products

if (isset($_POST['saveProduct'])) {

    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time() . '.' . $image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);

        $finalImage = 'assets/uploads/products' . $filename;
        // echo $finalImage;
        // exit();
    } else {
        $finalImage = '';
    }


    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalImage,
        'status' => $status,
    ];
    // var_dump($data);
    // exit();
    $result = insert('products', $data);

    if ($result) {
        redirect('products.php', 'products Created Successfully');
    } else {
        redirect('products-create.php', 'Something Went Wrong!');
    }
}


if (isset($_POST['updateProduct'])) {

    $product_id = validate($_POST['product_id']);


    $productData = getById('products', $product_id);
    // var_dump($productData) ;
    // exit();
    if (!$productData) {
        redirect('products.php', 'No such product found');
    }


    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time() . '.' . $image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);

        $finalImage = 'assets/uploads/products' . $filename;

        $deleteImage = "../" . $deleteImage['data']['image'];
        if (file_exists($deleteImage)) {
            unlink($deleteImage);
        }
    } else {
        $finalImage = '';
    }


    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalImage,
        'status' => $status,
    ];
    // var_dump($data);
    // exit();
    $result = update('products', $product_id, $data);

    if ($result) {
        redirect('products-edit.php?id=' . $product_id, 'products Updated Successfully');
    } else {
        redirect('products-edit.php?id=' . $product_id, 'Something Went Wrong!');
    }
}


//customer
if (isset($_POST['updateCustomer'])) {
    //validate
    $customerId = validate($_POST['customerId']);
    $name =  validate($_POST['name']);
    $email =  validate($_POST['email']);
    $phone =  validate($_POST['phone']);
    $status =  isset($_POST['status']) == true ? 1 : 0;

    if ($name != '') {

        //check email is already used.
        $checkEmail = mysqli_query($conn, "SELECT * FROM customers WHERE email = '$email' AND id != '$customerId'");
        if ($checkEmail) {
            if (mysqli_num_rows($checkEmail) > 0) {
                redirect('customers-edit.php?id='.$customerId, 'Email is already used.');
            }
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status
        ];

        $result = update('customers',$customerId, $data);
        if ($result) {
            redirect('customers-edit.php?id='.$customerId, 'customer Updated Successfully.');
        }else{
            redirect('customers-edit.php?id='.$customerId, 'Something went wrong.');

        }
    } else {
        redirect('customers-edit.php?id='.$customerId, 'Please fill requied field.');
    }
}

if (isset($_POST['saveCustomer'])) {
    //validate
    $name =  validate($_POST['name']);
    $email =  validate($_POST['email']);
    $phone =  validate($_POST['phone']);
    $status =  isset($_POST['status']) == true ? 1 : 0;

    if ($name != '') {

        //echeck email is already used.
        $checkEmail = mysqli_query($conn, "SELECT * FROM customers WHERE email = '$email' ");
        if ($checkEmail) {
            if (mysqli_num_rows($checkEmail) > 0) {
                redirect('customers.php', 'Email is already used.');
            }
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status
        ];

        $result = insert('customers', $data);
        if ($result) {
            redirect('customers.php', 'customer Created Successfully.');
        }else{
            redirect('customers.php', 'Something went wrong.');

        }
    } else {
        redirect('customers.php', 'Please fill requied field.');
    }
}