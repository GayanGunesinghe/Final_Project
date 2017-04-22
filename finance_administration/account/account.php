    <?php
        session_start();
        $i=0;
        $_SESSION['message_create']='';
        $_SESSION['message_search']='';
        $conn = new mysqli('localhost', 'root', 'toor', 'final_project');
        if(isset($_POST['search_account'])){
            $account_search = $_POST['account_search'];
            if(is_numeric($account_search) === false) {
                $_SESSION['message_search'] = "ERROR: Account ID should be Numeric";
                $result = mysqli_query($conn, "SELECT * FROM fa_accounts");
            }
            else {
                $result = mysqli_query($conn, "SELECT * FROM fa_accounts WHERE account_id ='$account_search'");
            }
        }
        else{
            $result = mysqli_query($conn, "SELECT * FROM fa_accounts");
        }
        $epr = '';
        $msg = '';
        $id = '';
        if(isset($_GET['epr'])) {
            $epr = $_GET['epr'];

            if($epr == 'save'){
                $account_no = $_POST['account_number'];
                $account_name = $_POST['account_name'];
                $account_type = $_POST['account_type'];
                $balance = $_POST['balance'];
                $reference = $_POST['reference'];
                $description = $_POST['description'];
                if(is_numeric($account_no) === false) {
                    $_SESSION['message_create'] = "ERROR: Account Number should be Numeric";
                }
                else if(is_numeric($balance) === false) {
                    $_SESSION['message_create'] = "ERROR: Balance should be Numeric";
                }
                else if(ctype_alpha(str_replace(' ','',$account_name)) ==false){
                    $_SESSION['message_create'] = "ERROR: Account Name should only contain Letters";
                }
                else {
                    $save = mysqli_query($conn, ("INSERT INTO fa_accounts (account_no,account_name,account_type,balance,reference,description) VALUES('$account_no','$account_name','$account_type','$balance','$reference','$description')"));
                    if ($save) {
                        $_SESSION['message_create'] = 'New Account Created';
                        header("location:account.php");
                    } else {
                        $_SESSION['message_create'] = 'Error :' . mysqli_error($conn);
                    }
                }
            }
            if ($epr == 'delete') {
                $id = $_GET['id'];
                if(is_numeric($id) === false) {
                    $_SESSION['message_create'] = "ERROR: Account ID should be Numeric";
                }
                else {
                    $delete = mysqli_query($conn, ("DELETE FROM fa_accounts WHERE account_id = '$id'"));
                    if ($delete) {
                        header("location:account.php");
                    } else {
                        $_SESSION['message_create'] = 'Error :' . mysqli_error($conn);
                    }
                }
            }
            if ($epr == 'saveup'){
                $account_no = $_POST['account_number'];
                $account_name = $_POST['account_name'];
                $account_type = $_POST['account_type'];
                $balance = $_POST['balance'];
                $reference = $_POST['reference'];
                $description = $_POST['description'];
                $id = $_POST['account_id'];
                if(is_numeric($account_no) === false) {
                    $_SESSION['message_create'] = "ERROR: Account Number should be Numeric";
                }
                else if(is_numeric($balance) === false) {
                    $_SESSION['message_create'] = "ERROR: Balance should be Numeric";
                }
                else if(ctype_alpha(str_replace(' ','',$account_name)) ==false){
                    $_SESSION['message_create'] = "ERROR: Account Name should only contain Letters";
                }
                else {
                    $saveup = mysqli_query($conn, ("UPDATE fa_accounts SET account_no='$account_no',account_name='$account_name',account_type='$account_type',balance='$balance',reference='$reference',description='$description' WHERE account_id='$id';"));
                    if ($saveup) {
                        header("location:account.php.php");
                    } else {
                        $_SESSION['message_update'] = 'Error :' . mysqli_error($conn);
                    }
                }
            }
        }
    ?>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Samtessi</title>
            <link rel="stylesheet" type="text/css" href="http://localhost/Final_Project/finance_administration/Styles/table.css">
            <link rel="stylesheet" type="text/css" href="http://localhost/Final_Project/finance_administration/Styles/global.css">
            <link rel="stylesheet" type="text/css" href="http://localhost/Final_Project/finance_administration/Styles/Icon/flaticon.css">
        </head>
        <body>
            <div id="header">
                <div class="logo" onclick="location.href='http://localhost/Final_Project/home.php';"><img src="http://localhost/Final_Project/finance_administration/Images/samtessi.png"></div>
                <div class="department">Finances and Administration</div>
            </div>
            <div id="container">
                <div class="sidebar">
                    <ul id="nav">
                        <li><a class="flaticon-download-business-statistics-symbol-of-a-graphic" href="http://localhost/Final_Project/finance_administration/dashboard/dashboard.php"> Dashboard</a></li>
                        <li><a class="flaticon-money-bags" href="#"> Budgeting</a>
                            <ul id="sub_nav">
                                <li><a href="http://localhost/Final_Project/finance_administration/budgetting/budget/budget.php">Budget</a></li>
                                <li><a href="http://localhost/Final_Project/finance_administration/budgetting/sub_budget/sub_budget.php">Sub Budget</a></li>
                            </ul>
                        </li>
                        <li><a class="flaticon-budget-management" id="selected" href="http://localhost/Final_Project/finance_administration/account/account.php"> Accounts</a></li>
                        <li><a class="flaticon-sent-mail" href="http://localhost/Final_Project/finance_administration/transaction/transaction.php"> Transactions</a></li>
                    </ul>
                </div>
                <div class="content">
                    <h1>Accounts</h1>
                    <p>Account details are listed here</p>
                    <div id="box">
                        <div class="box-top">Create and Edit New Account</div>
                        <div class="box-panel">
                            <?php
                                if($epr == 'update'){
                                    unset($_SESSION['username']);
                                    $id = $_GET['id'];
                                    $_SESSION['message_update']='Update selected Entry';;
                                    $row = mysqli_query($conn, "SELECT * FROM fa_accounts WHERE account_id = '$id'");
                                    $st_row = mysqli_fetch_array($row);
                                    ?>
                                    <form action="account.php?epr=saveup" method="post" name="edit_account" enctype="multipart/form-data" autocomplete="off">
                                        <table>
                                            <div class="alert"><?php echo $_SESSION['message_update']; ?></div>
                                            <tr>
                                                <td>Account ID</td>
                                                <td><input type="text" placeholder="Account ID" name="account_id" value="<?php echo $st_row['account_id'] ?>" readonly/></td>
                                                <td>Account Number</td>
                                                <td><input type="text" placeholder="Account Number" name="account_number" value="<?php echo $st_row['account_no'] ?>" required /></td>
                                            </tr>
                                            <tr>
                                                <td>Account Name</td>
                                                <td><input type="text" placeholder="Account Name" name="account_name" value="<?php echo $st_row['account_name'] ?>" required /></td>
                                                <td>Account Type</td>
                                                <td>
                                                    <select name="account_type" required>
                                                        <option value="<?php echo $st_row['account_type'] ?>"><?php echo $st_row['account_type'] ?></option>
                                                        <option value="Credit">Credit</option>
                                                        <option value="Debit">Debit</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Balance</td>
                                                <td><input type="text" placeholder="Balance" name="balance" value="<?php echo $st_row['balance'] ?>" required /></td>
                                                <td>Reference</td>
                                                <td><input type="text" placeholder="Reference" name="reference" value="<?php echo $st_row['reference'] ?>" required /></td>
                                            </tr>
                                            <tr>
                                                <td>Description</td>
                                                <td><textarea name='description' rows="4" cols="30" placeholder="Description" value="<?php echo $st_row['description'] ?>"><?php echo $st_row['description'] ?></textarea></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <button type="submit" name="create_account" class="button" style="vertical-align: middle"><span>Submit</span></button>
                                                </td>
                                            </tr>
                                        </table>
                                        <br/>
                                    </form>
                                <?php }else{
                            ?>
                                <form action="account.php?epr=save" method="post" name="create_account" enctype="multipart/form-data" autocomplete="off">
                                    <table>
                                        <div class="alert"><?php echo $_SESSION['message_create'];?></div>
                                        <tr>
                                            <td>Account Number</td>
                                            <td><input type="text" placeholder="Account Number" name="account_number" required /></td>
                                            <td>Account Name</td>
                                            <td><input type="text" placeholder="Account Name" name="account_name" required /></td>
                                        </tr>
                                        <tr>
                                            <td>Account Type</td>
                                            <td>
                                                <select name="account_type" required>
                                                    <option disabled selected value class="disabled">--Select an Option--</option>
                                                    <option value="Credit">Credit</option>
                                                    <option value="Debit">Debit</option>
                                                </select>
                                            </td>
                                            <td>Balance</td>
                                            <td><input type="text" placeholder="Balance" name="balance" required /></td>
                                        </tr>
                                        <tr>
                                            <td>Reference</td>
                                            <td><input type="text" placeholder="Reference" name="reference" required /></td>
                                            <td>Description</td>
                                            <td><textarea name='description' rows="4" cols="30" placeholder="Description"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <button type="submit" name="create_account" class="button" style="vertical-align: middle"><span>Create</span></button>
                                                <button type="reset" class="button" style="vertical-align: middle"><span>Reset</span></button>
                                            </td>
                                        </tr>
                                    </table>
                                    <br/>
                                </form>
                            <?php } ?>
                            <table class='table1'>
                                <tr>
                                    <th width="150">Account ID</th>
                                    <th width="150">Account Number</th>
                                    <th width="150">Account Name</th>
                                    <th width="150">Type</th>
                                    <th width="150">Balance</th>
                                    <th width="150">Reference</th>
                                    <th width="200">Description</th>
                                    <th width="300">Action</th>
                                </tr>
                                <?php
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td>".$row['account_id']."</td>";
                                        echo "<td>".$row['account_no']."</td>";
                                        echo "<td>".$row['account_name']."</td>";
                                        echo "<td>".$row['account_type']."</td>";
                                        echo "<td>".$row['balance']."</td>";
                                        echo "<td>".$row['reference']."</td>";
                                        echo "<td>".$row['description']."</td>";
                                        echo "<td>";
                                        echo "<a class='button' id='table_button_delete' href='account.php?epr=delete&id=".$row['account_id']."'><span>Delete</span></a>";
                                        echo "<a class='button' id='table_button_update' href='account.php?epr=update&id=".$row['account_id']."'><span>Update</span></a>";
                                        echo "</td>";
                                        echo "</tr>";
                                        $i++;
                                    }
                                ?>
                            </table>
                            <?php echo "<small>Records found in database ( ".$i." )</small>"; ?>
                            <br><br>
                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="search_account" enctype="multipart/form-data" autocomplete="off" onsubmit="return(validate());">
                                *Search Account Entries using Account ID
                                <div class="alert"><?php echo $_SESSION['message_search']; ?></div>
                                <table>
                                    <td>Account ID</td>
                                    <td><input type="text" placeholder="Account ID" name="account_search"/></td>
                                    <td><button type="submit" name="search_account" class="button" style="vertical-align: middle"><span>Search</span></button></td>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
        </body>
    </html>