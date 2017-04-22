    <?php
        session_start();
        $i=0;
        $_SESSION['message_search']='';
        $_SESSION['message_create']='';
        $conn = new mysqli('localhost', 'root', 'toor', 'final_project');
        if(isset($_POST['search_sub_budget'])){
            $sub_budget_search = $_POST['sub_budget_search'];
            if(is_numeric($sub_budget_search) === false) {
                $_SESSION['message_search'] = "ERROR: Sub Budget ID should be Numeric";
                $result = mysqli_query($conn, "SELECT * FROM fa_sub_budget");
            }
            else {
                $result = mysqli_query($conn, "SELECT * FROM fa_sub_budget WHERE sub_budget_id ='$sub_budget_search'");
            }
        }
        else{
            $result = mysqli_query($conn, "SELECT * FROM fa_sub_budget");
        }
        $epr = '';
        $msg = '';
        $id = '';
        if(isset($_GET['epr'])) {
            $epr = $_GET['epr'];

            if($epr == 'sub_save'){
                $sub_budget_budget_id = $_POST['sub_budget_budget_id'];
                $sub_budget_department = $_POST['sub_budget_department'];
                $sub_budget_amount = $_POST['sub_budget_amount'];
                $sub_budget_description = $_POST['sub_budget_description'];
                if(is_numeric($sub_budget_budget_id) === false) {
                    $_SESSION['message_create'] = "ERROR: Budget ID should be Numeric";
                }
                else if(is_numeric($sub_budget_amount) === false) {
                    $_SESSION['message_create'] = "ERROR: Amount should be Numeric";
                }
                else {
                    $query=mysqli_fetch_array(mysqli_query($conn, ("SELECT budget_amount FROM fa_budget WHERE budget_id ='$sub_budget_budget_id'")));
                    $bud_amt = $query['budget_amount'];
                    $up_bud_amt=$bud_amt - $sub_budget_amount;
                    if($up_bud_amt<0){
                        $_SESSION['message_create'] = "ERROR: Exceed Main Budget Amount";
                    }
                    else {
                        mysqli_query($conn, ("UPDATE fa_budget SET budget_amount='$up_bud_amt' WHERE budget_id='$sub_budget_budget_id';"));
                        $save = mysqli_query($conn, ("INSERT INTO fa_sub_budget (budget_id,sub_budget_department,sub_budget_amount,sub_budget_description) VALUES('$sub_budget_budget_id','$sub_budget_department','$sub_budget_amount','$sub_budget_description')"));
                        if ($save) {
                            header( "Refresh:3; url=http://localhost/Final_Project/finance_administration/budgetting/sub_budget/sub_budget.php", true, 303);
                            $_SESSION['message_create'] = "Sub Budget Created..    Rs ".$up_bud_amt." Remaining in Budget ID ( ".$sub_budget_budget_id." )";
                            echo "<script type='text/javascript'>alert('Sub Budget Created..    Rs '+$up_bud_amt+' Remaining in Budget ID ( '+$sub_budget_budget_id+' )')</script>";
                        } else {
                            $_SESSION['message_create'] = 'Error :' . mysqli_error($conn);
                        }
                    }
                }
            }
            if ($epr == 'sub_delete') {
                $id = $_GET['id'];
                if(is_numeric($id) === false) {
                    $_SESSION['message_create'] = "ERROR: Sub Budget ID should be Numeric";
                }
                else {
                    $query=mysqli_fetch_array(mysqli_query($conn, ("SELECT sub_budget_amount, budget_id FROM fa_sub_budget WHERE sub_budget_id ='$id'")));
                    $sub_bud_amt = $query['sub_budget_amount'];
                    $budget_id = $query['budget_id'];
                    $query2=mysqli_fetch_array(mysqli_query($conn, ("SELECT budget_amount FROM fa_budget WHERE budget_id ='$budget_id'")));
                    $bud_amt = $query2['budget_amount'];
                    $up_bud_amt=$bud_amt + $sub_bud_amt;
                    if($up_bud_amt<0){
                        $_SESSION['message_create'] = "ERROR";
                    }
                    else {
                        mysqli_query($conn, ("UPDATE fa_budget SET budget_amount='$up_bud_amt' WHERE budget_id='$budget_id';"));
                        $delete = mysqli_query($conn, ("DELETE FROM fa_sub_budget WHERE sub_budget_id = '$id'"));
                        if ($delete) {
                            header( "Refresh:3; url=http://localhost/Final_Project/finance_administration/budgetting/sub_budget/sub_budget.php", true, 303);
                            $_SESSION['message_create'] = "Deleted";
                        } else {
                            $_SESSION['message_create'] = 'Error :' . mysqli_error($conn);
                        }
                    }
                }
            }
            if ($epr == 'sub_saveup'){
                $sub_budget_budget_id = $_POST['sub_budget_budget_id'];
                $sub_budget_department = $_POST['sub_budget_department'];
                $sub_budget_amount = $_POST['sub_budget_amount'];
                $sub_budget_description = $_POST['sub_budget_description'];
                $id = $_POST['sub_budget_id'];
                if(is_numeric($sub_budget_budget_id) === false) {
                    $_SESSION['message_create'] = "ERROR: Budget ID should be Numeric";
                }
                else if(is_numeric($sub_budget_amount) === false) {
                    $_SESSION['message_create'] = "ERROR: Amount should be Numeric";
                }
                else {
                    $query=mysqli_fetch_array(mysqli_query($conn, ("SELECT budget_amount FROM fa_budget WHERE budget_id ='$sub_budget_budget_id'")));
                    $bud_amt = $query['budget_amount'];
                    $query2=mysqli_fetch_array(mysqli_query($conn, ("SELECT sub_budget_amount FROM fa_sub_budget WHERE sub_budget_id ='$id'")));
                    $sub_bud_amt = $query2['sub_budget_amount'];
                    $up_sub_bud_amt = $sub_bud_amt - $sub_budget_amount;
                    $up_bud_amt=$bud_amt + $up_sub_bud_amt;
                    if($up_bud_amt<0){
                        $_SESSION['message_create'] = "ERROR: Exceed Main Budget Amount";
                    }
                    else {
                        mysqli_query($conn, ("UPDATE fa_budget SET budget_amount='$up_bud_amt' WHERE budget_id='$sub_budget_budget_id';"));
                        $saveup = mysqli_query($conn, ("UPDATE fa_sub_budget SET budget_id='$sub_budget_budget_id', sub_budget_department='$sub_budget_department', sub_budget_amount='$sub_budget_amount', sub_budget_description='$sub_budget_description' WHERE sub_budget_id='$id'"));
                        if ($saveup) {
                            header("location:sub_budget.php");
                        } else {
                            $_SESSION['message'] = 'Error :' . mysqli_error($conn);
                        }
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
                    <li><a class="flaticon-money-bags" id="selected" href="#"> Budgeting</a>
                        <ul id="sub_nav">
                            <li><a href="http://localhost/Final_Project/finance_administration/budgetting/budget/budget.php">Budget</a></li>
                            <li><a href="http://localhost/Final_Project/finance_administration/budgetting/sub_budget/sub_budget.php">Sub Budget</a></li>
                        </ul>
                    </li>
                    <li><a class="flaticon-budget-management" href="http://localhost/Final_Project/finance_administration/account/account.php"> Accounts</a></li>
                    <li><a class="flaticon-sent-mail" href="http://localhost/Final_Project/finance_administration/transaction/transaction.php"> Transactions</a></li>
                </ul>
            </div>
            <div class="content">
                <h1>Sub Budget</h1>
                <p>Sub Budget details are listed here</p>
                <div id="box">
                    <div class="box-top">Create And Edit New Sub Budget</div>
                    <div class="box-panel">
                        <?php
                            if($epr == 'sub_update'){
                                $id = $_GET['id'];
                                $_SESSION['message_update']='Update selected Entry';;
                                $row = mysqli_query($conn, "SELECT * FROM fa_sub_budget WHERE sub_budget_id = '$id'");
                                $st_row = mysqli_fetch_array($row);
                                ?>
                                <form action="sub_budget.php?epr=sub_saveup" method="post" name="edit_sub_budget" enctype="multipart/form-data" autocomplete="off">
                                    <table>
                                        <div class="alert"><?php echo $_SESSION['message_update']; ?></div>
                                        <tr>
                                            <td>Sub Budget ID</td>
                                            <td><input type="text" placeholder=" Sub Budget ID" name="sub_budget_id" value="<?php echo $st_row['sub_budget_id'] ?>" readonly /></td>
                                            <td>Budget ID</td>
                                            <td><input type="text" placeholder="Budget ID" name="sub_budget_budget_id" value="<?php echo $st_row['budget_id'] ?>" required /></td>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td>
                                                <select name="sub_budget_department">
                                                    <option value="<?php echo $st_row['sub_budget_department'] ?>"><?php echo $st_row['sub_budget_department'] ?></option>
                                                    <option value="HR Management">HR Management</option>
                                                    <option value="Sales">Sales</option>
                                                    <option value="Transportation and Shipping">Transportation and Shipping</option>
                                                </select>
                                            </td>
                                            <td>Amount</td>
                                            <td><input type="text" placeholder="Amount" name="sub_budget_amount" value="<?php echo $st_row['sub_budget_amount'] ?>" required /></td>
                                        </tr>
                                        <tr>
                                            <td>Description</td>
                                            <td><input type="text" placeholder="Description" name="sub_budget_description" value="<?php echo $st_row['sub_budget_description'] ?>" required /></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <button type="submit" name="create_sub_budget" class="button" style="vertical-align: middle"><span>Submit</span></button>
                                            </td>
                                        </tr>
                                    </table>
                                    <br/>
                                </form>
                            <?php }else{
                                ?>
                                <form action="sub_budget.php?epr=sub_save" method="post" name="create_sub_budget" enctype="multipart/form-data" autocomplete="off">
                                    <table>
                                        <div class="alert"><?php echo $_SESSION['message_create'];?></div>
                                        <tr>
                                            <td>Budget ID</td>
                                            <td><input type="text" placeholder="Budget ID" name="sub_budget_budget_id" required /></td>
                                            <td>Department</td>
                                            <td>
                                                <select name="sub_budget_department">
                                                    <option disabled selected value class="disabled">--Select an Option--</option>
                                                    <option value="HR Management">HR Management</option>
                                                    <option value="Sales">Sales</option>
                                                    <option value="Transportation and Shipping">Transportation and Shipping</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Amount</td>
                                            <td><input type="text" placeholder="Amount" name="sub_budget_amount" required /></td>
                                            <td>Description</td>
                                            <td><input type="text" placeholder="Description" name="sub_budget_description" required /></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <button type="submit" name="create_sub_budget" class="button" style="vertical-align: middle"><span>Create</span></button>
                                                <button type="reset" class="button" style="vertical-align: middle"><span>Reset</span></button>
                                            </td>
                                        </tr>
                                    </table>
                                    <br/>
                                </form>
                            <?php } ?>
                            <table class='table1'>
                                <tr>
                                    <th width='150'>Sub Budget ID</th>
                                    <th width='150'>Budget ID</th>
                                    <th width='150'>Department</th>
                                    <th width='150'>Amount</th>
                                    <th width='150'>Description</th>
                                    <th width="250">Action</th>
                                </tr>
                                <?php
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>".$row['sub_budget_id']."</td>";
                                    echo "<td>".$row['budget_id']."</td>";
                                    echo "<td>".$row['sub_budget_department']."</td>";
                                    echo "<td>".$row['sub_budget_amount']."</td>";
                                    echo "<td>".$row['sub_budget_description']."</td>";
                                    echo "<td>";
                                    echo "<a class='button' id='table_button_delete' href='sub_budget.php?epr=sub_delete&id=".$row['sub_budget_id']."'><span>Delete</span></a>";
                                    echo "<a class='button' id='table_button_update' href='sub_budget.php?epr=sub_update&id=".$row['sub_budget_id']."'><span>Update</span></a>";
                                    echo "</td>";
                                    echo "</tr>";
                                    $i++;
                                }
                        ?>
                        </table>
                        <?php echo "<small>Records found in database ( ".$i." )</small>"; ?>
                        <br><br>
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="search_account" enctype="multipart/form-data" autocomplete="off">
                            *Search Budget Entries using Budget ID
                            <div class="alert"><?php echo $_SESSION['message_search'];?></div>
                            <table>
                                <td>Sub Budget ID</td>
                                <td><input type="text" placeholder=" Sub Budget ID" name="sub_budget_search" required /></td>
                                <td><button type="submit" name="search_sub_budget" class="button" style="vertical-align: middle"><span>Search</span></button></td>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <footer>
                <p>Samtessi Brush Manufacturers (pvt) LTD</p>
                <p>Icons from <a id="footer" href="http://www.flaticon.com" rel="nofollow">Flaticon</a>. Web fonts from <a id="footer" href="http://www.google.com/webfonts" rel="nofollow">Google</a></p>
            </footer>
        </div>
    </body>
    </html>