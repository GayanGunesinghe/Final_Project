    <?php
        session_start();








        $i=0;
        $_SESSION['message_create']='';
        $_SESSION['message_search']='';
        $conn = new mysqli('localhost', 'root', 'toor', 'final_project');
        $dropdown_dept = mysqli_query($conn, "SELECT department_name FROM fa_department");
        if(isset($_POST['search_transaction'])){
            $transaction_search = $_POST['transaction_search'];
            if(is_numeric($transaction_search) === false) {
                $_SESSION['message_search'] = "ERROR: Transaction ID should be Numeric";
                $result = mysqli_query($conn, "SELECT * FROM fa_transaction");
            }
            else {
                $result = mysqli_query($conn, "SELECT * FROM fa_transaction WHERE transaction_id ='$transaction_search'");
            }
        }

        else if(isset($_POST["create_transaction"])){
            $transaction_date = $_POST['transaction_date'];
            $transaction_description = $_POST['transaction_description'];
            $transaction_type = $_POST['transaction_type'];
            $transaction_department = $_POST['t_department'];
            $transaction_account_id = $_POST['t_account_id'];
            $transaction_payee = $_POST['payee'];
            $transaction_amount = $_POST['amount'];
            if(is_numeric($transaction_amount) === false) {
                $_SESSION['message_create'] = "ERROR: Amount should be Numeric";
            }
            else if(ctype_alpha(str_replace(' ','',$transaction_payee)) ==false){
                $_SESSION['message_create'] = "ERROR: Payee should only contain Letters";
            }
            else{
                $save=mysqli_query($conn,("INSERT INTO fa_transaction (transaction_date,transaction_description,transaction_type,transaction_department,transaction_account_id,transaction_payee,transaction_amount) VALUES('$transaction_date','$transaction_description','$transaction_type','$transaction_department','$transaction_account_id','$transaction_payee','$transaction_amount')"));
                if ($save) {
                    $_SESSION['message_create'] = "Created";
                    header("location:transaction.php");
                } else {
                    $_SESSION['message_create'] = 'Error :' . mysqli_error($conn);
                }
            }
        }


        else{
            $result = mysqli_query($conn, "SELECT * FROM fa_transaction");
        }
        $epr = '';
        $msg = '';
        $id = '';
        if(isset($_GET['epr'])) {
            $epr = $_GET['epr'];

            if ($epr == 'delete') {
                $id = $_GET['id'];
                if(is_numeric($id) === false) {
                    $_SESSION['message_create'] = "ERROR: Transaction ID should be Numeric";
                }
                else {
                    $delete = mysqli_query($conn, ("DELETE FROM fa_transaction WHERE transaction_id = '$id'"));
                    if ($delete) {
                        header("location:transaction.php");
                    } else {
                        $_SESSION['message_create'] = 'Error :' . mysqli_error($conn);
                    }
                }
            }
            if ($epr == 'saveup'){
                $transaction_date = $_POST['transaction_date'];
                $transaction_description = $_POST['transaction_description'];
                $transaction_type = $_POST['transaction_type'];
                $transaction_department = $_POST['t_department'];
                $transaction_account_id = $_POST['t_account_id'];
                $transaction_payee = $_POST['payee'];
                $transaction_amount = $_POST['amount'];
                $id = $_POST['transaction_id'];
                if(is_numeric($transaction_amount) === false) {
                    $_SESSION['message_create'] = "ERROR: Amount should be Numeric";
                }
                else if(ctype_alpha(str_replace(' ','',$transaction_payee)) ==false){
                    $_SESSION['message_create'] = "ERROR: Payee should only contain Letters";
                }
                else {
                    $saveup = mysqli_query($conn, ("UPDATE fa_transaction SET transaction_date='$transaction_date',transaction_description='$transaction_description',transaction_type='$transaction_type',transaction_department='$transaction_department',transaction_account_id='$transaction_account_id',transaction_payee='$transaction_payee',transaction_amount='$transaction_amount' WHERE transaction_id='$id';"));
                    if ($saveup) {
                        header("location:transaction.php");
                    } else {
                        $_SESSION['message_create'] = 'Error :' . mysqli_error($conn);
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
                    <li><a class="flaticon-budget-management" href="http://localhost/Final_Project/finance_administration/account/account.php"> Accounts</a></li>
                    <li><a class="flaticon-sent-mail" id="selected" href="http://localhost/Final_Project/finance_administration/transaction/transaction.php"> Transactions</a></li>
                </ul>
            </div>
            <div class="content">
                <h1>Transactions</h1>
                <p>Transaction details are listed here</p>
                <div id="box">
                    <div class="box-top">Create And Edit New Transaction</div>
                    <div class="box-panel">
                        <?php
                        if($epr == 'update'){
                            $id = $_GET['id'];
                            $_SESSION['message_update']='Update selected Entry';
                            $row = mysqli_query($conn, "SELECT * FROM fa_transaction WHERE transaction_id = '$id'");
                            $st_row = mysqli_fetch_array($row);
                            ?>
                            <form action="transaction.php?epr=saveup" method="post" name="edit_transaction" enctype="multipart/form-data" autocomplete="off">
                                <table>
                                    <div class="alert"><?php echo $_SESSION['message_update']; ?></div>
                                    <tr>
                                        <td>Transaction ID</td>
                                        <td><input type="text" name="transaction_id" value="<?php echo $st_row['transaction_id'] ?>"  readonly/></td>
                                        <td>Transaction Date</td>
                                        <td><input type="date" name="transaction_date" value="<?php echo $st_row['transaction_date'] ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <td>Transaction Description</td>
                                        <td><textarea name='transaction_description' rows="4" cols="30" value="<?php echo $st_row['transaction_description'] ?>" placeholder="Description"><?php echo $st_row['transaction_description'] ?></textarea></td>
                                        <td>Transaction Type</td>
                                        <td>
                                            <select name="transaction_type" required>
                                                <option value="<?php echo $st_row['transaction_type'] ?>"><?php echo $st_row['transaction_type'] ?></option>
                                                <option value="Credit">Credit</option>
                                                <option value="Debit">Debit</option>
                                                <option value="Cheque">Cheque</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Department</td>
                                        <td>
                                            <select name="t_department" required>
                                                <option value="<?php echo $st_row['transaction_department'] ?>"><?php echo $st_row['transaction_department'] ?></option>
                                                <?php
                                                    while ($row = mysqli_fetch_array($dropdown_dept)) {
                                                        echo "<option value='" . $row['department_name'] ."'>" . $row['department_name'] ."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td>Account ID</td>
                                        <td><input type="text" placeholder="Account ID" name="t_account_id" value="<?php echo $st_row['transaction_account_id'] ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <td>Payee</td>
                                        <td><input type="text" placeholder="Payee" name="payee" value="<?php echo $st_row['transaction_payee'] ?>" required /></td>
                                        <td>Amount</td>
                                        <td><input type="text" placeholder="Amount" name="amount" value="<?php echo $st_row['transaction_amount'] ?>" required /></td>
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
                            <form action="transaction.php?epr=save" method="post" name="create_transaction" enctype="multipart/form-data" autocomplete="off">
                                <table>
                                    <div class="alert"><?php echo $_SESSION['message_create'];?></div>
                                    <tr>
                                        <td>Transaction Date</td>
                                        <td><input type="date" name="transaction_date" required /></td>
                                        <td>Transaction Description</td>
                                        <td><textarea name='transaction_description' rows="4" cols="30" placeholder="Description"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td>Transaction Type</td>
                                        <td>
                                            <select name="transaction_type" required>
                                                <option disabled selected value class="disabled">--Select an Option--</option>
                                                <option value="Credit">Credit</option>
                                                <option value="Debit">Debit</option>
                                                <option value="Cheque">Cheque</option>
                                            </select>
                                        </td>
                                        <td>Department</td>
                                        <td>
                                            <select name="t_department" required>
                                                <option disabled selected value class="disabled">--Select an Option--</option>
                                                <?php
                                                    while ($row = mysqli_fetch_array($dropdown_dept)) {
                                                        echo "<option value='" . $row['department_name'] ."'>" . $row['department_name'] ."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Account ID</td>
                                        <td><input type="text" placeholder="Account ID" name="t_account_id" required /></td>
                                        <td>Payee</td>
                                        <td><input type="text" placeholder="Payee" name="payee" required /></td>
                                    </tr>
                                    <tr>
                                        <td>Amount</td>
                                        <td><input type="text" placeholder="Amount" name="amount" required /></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit" name="create_transaction" class="button" style="vertical-align: middle"><span>Create</span></button>
                                            <button type="submit" name="print_transaction" class="button" style="vertical-align: middle"><span>Print</span></button>
                                            <button type="reset" class="button" style="vertical-align: middle"><span>Reset</span></button>
                                        </td>
                                    </tr>
                                </table>
                                <br/>
                            </form>
                        <?php } ?>
                        <table class='table1'>
                            <tr>
                                <th width="150">Transaction ID</th>
                                <th width="170">Transaction Date</th>
                                <th width="150">Description</th>
                                <th width="150">Type</th>
                                <th width="150">Department</th>
                                <th width="150">Account ID</th>
                                <th width="200">Payee</th>
                                <th width="200">Amount</th>
                                <th width="300">Action</th>
                            </tr>
                            <?php
                            while($row = mysqli_fetch_array($result)){
                                echo "<tr>";
                                echo "<td>".$row['transaction_id']."</td>";
                                echo "<td>".$row['transaction_date']."</td>";
                                echo "<td>".$row['transaction_description']."</td>";
                                echo "<td>".$row['transaction_type']."</td>";
                                echo "<td>".$row['transaction_department']."</td>";
                                echo "<td>".$row['transaction_account_id']."</td>";
                                echo "<td>".$row['transaction_payee']."</td>";
                                echo "<td>".$row['transaction_amount']."</td>";
                                echo "<td>";
                                echo "<a class='button' id='table_button_delete' href='transaction.php?epr=delete&id=".$row['transaction_id']."'><span>Delete</span></a>";
                                echo "<a class='button' id='table_button_update' href='transaction.php?epr=update&id=".$row['transaction_id']."'><span>Update</span></a>";
                                echo "</td>";
                                echo "</tr>";
                                $i++;
                            }
                            ?>
                        </table>
                        <?php echo "<small>Records found in database ( ".$i." )</small>"; ?>
                        <br><br>
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="search_account" enctype="multipart/form-data" autocomplete="off">
                            *Search Transaction Entries using Transaction ID
                            <div class="alert"><?php echo $_SESSION['message_search'];?></div>
                            <table>
                                <td>Transaction ID</td>
                                <td><input type="text" placeholder="Transaction ID" name="transaction_search" required /></td>
                                <td><button type="submit" name="search_transaction" class="button" style="vertical-align: middle"><span>Search</span></button></td>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
    </body>
    </html>
