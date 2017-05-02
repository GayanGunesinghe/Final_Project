    <?php
        session_start();
        $i=0;
        $_SESSION['message_search']='';
        $_SESSION['message_create']='';
        $conn = new mysqli('localhost', 'root', 'toor', 'final_project');
        if(isset($_POST['search_budget'])){
            $budget_search = $_POST['budget_search'];
            if(is_numeric($budget_search) === false) {
                $_SESSION['message_search'] = "ERROR: Budget ID should be Numeric";
                $result = mysqli_query($conn, "SELECT * FROM fa_budget");
            }
            else {
                $result = mysqli_query($conn, "SELECT * FROM fa_budget WHERE budget_id ='$budget_search'");
            }
        }
        else{
            $result = mysqli_query($conn, "SELECT * FROM fa_budget");
        }
        $epr = '';
        $msg = '';
        $id = '';
        if(isset($_GET['epr'])) {
            $epr = $_GET['epr'];

            if($epr == 'save'){
                $account_id = $_POST['account_id'];
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
                $amount = $_POST['amount'];
                if(is_numeric($account_id) === false) {
                    $_SESSION['message_create'] = "ERROR: Account ID should be Numeric";
                }
                else if(is_numeric($amount) === false) {
                    $_SESSION['message_create'] = "ERROR: Amount should be Numeric";
                }
                else if($end_date<$start_date){
                    $_SESSION['message_create'] = "ERROR: Check start and end dates";
                }
                else {
                    $save = mysqli_query($conn, ("INSERT INTO fa_budget (account_id,budget_start_date,budget_end_date,budget_amount, budget_amount_remaining) VALUES('$account_id','$start_date','$end_date','$amount','$amount')"));
                    if ($save) {
                        $_SESSION['message_create'] = "Created";
                        header("location:budget.php");
                    } else {
                        $_SESSION['message_create_button'] = 'Error :' . mysqli_error($conn);
                    }
                }
            }
            if ($epr == 'delete') {
                $id = $_GET['id'];
                $delete = mysqli_query($conn, ("DELETE FROM fa_budget WHERE budget_id = '$id'"));
                if(is_numeric($id) === false) {
                    $_SESSION['message_create'] = "ERROR: Budget ID should be Numeric";
                }
                else {
                    if ($delete) {
                        header("location:budget.php");
                    } else {
                        $_SESSION['message_create'] = 'Error :' . mysqli_error($conn);
                    }
                }
            }
            if ($epr == 'saveup'){
                $account_id = $_POST['account_id'];
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
                $amount = $_POST['amount'];
                $id = $_POST['budget_id'];
                if(is_numeric($account_id) === false) {
                    $_SESSION['message_create'] = "ERROR: Account ID should be Numeric";
                }
                else if(is_numeric($amount) === false) {
                    $_SESSION['message_create'] = "ERROR: Amount should be Numeric";
                }
                else if($end_date<$start_date){
                    $_SESSION['message_create'] = "ERROR: Check start and end dates";
                }
                else {
                    $query=mysqli_fetch_array(mysqli_query($conn, ("SELECT budget_amount_remaining, budget_amount FROM fa_budget WHERE budget_id ='$id'")));
                    $bud_amt = $query['budget_amount'];
                    $bud_amt_rem = $query['budget_amount_remaining'];
                    $up_bud_amt = $query['sub_budget_amount'];
                    $up_bud_amt = $amount - $bud_amt;
                    $up_bud_amt_rem = $bud_amt_rem + $up_bud_amt;

                    $saveup = mysqli_query($conn, ("UPDATE fa_budget SET account_id='$account_id', budget_start_date='$start_date', budget_end_date='$end_date', budget_amount='$amount', budget_amount_remaining='$up_bud_amt_rem' WHERE budget_id='$id'"));
                    if ($saveup) {
                        header("location:budget.php");
                    } else {
                        $_SESSION['message'] = 'Error :' . mysqli_error($conn);
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
                <h1>Budget</h1>
                <p>Budget details are listed here</p>
                <div id="box">
                    <div class="box-top">Create And Edit New Budget</div>
                    <div class="box-panel">
                        <?php
                            if($epr == 'update'){
                                $id = $_GET['id'];
                                $_SESSION['message_update']='Update selected Entry';;
                                $row = mysqli_query($conn, "SELECT * FROM fa_budget WHERE budget_id = '$id'");
                                $st_row = mysqli_fetch_array($row);
                                ?>
                                <form action="budget.php?epr=saveup" method="post" name="edit_budget" enctype="multipart/form-data" autocomplete="off">
                                    <table>
                                        <div class="alert"><?php echo $_SESSION['message_update']; ?></div>
                                        <tr>
                                            <td>Budget ID</td>
                                            <td><input type="text" placeholder="Budget ID" name="budget_id" value="<?php echo $st_row['budget_id'] ?>" readonly/></td>
                                            <td>Account ID</td>
                                            <td><input type="text" placeholder="Account ID" name="account_id" value="<?php echo $st_row['account_id'] ?>" required /></td>
                                        </tr>
                                        <tr>
                                            <td>Start Date</td>
                                            <td><input type="date" name="start_date" value="<?php echo $st_row['budget_start_date'] ?>" required /></td>
                                            <td>End Date</td>
                                            <td><input type="date" name="end_date" value="<?php echo $st_row['budget_end_date'] ?>" required /></td>
                                        </tr>
                                        <tr>
                                            <td>Amount</td>
                                            <td><input type="text" placeholder="Amount" name="amount" value="<?php echo $st_row['budget_amount'] ?>" required /></td>
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
                                <form action="budget.php?epr=save" method="post" name="create_budget" enctype="multipart/form-data" autocomplete="off">
                                    <table>
                                        <div class="alert"><?php echo $_SESSION['message_create'];?></div>
                                        <tr>
                                            <td>Account ID</td>
                                            <td><input type="text" placeholder="Account ID" name="account_id" required /></td>
                                            <td>Start Date</td>
                                            <td><input type="date" name="start_date" required /></td>
                                        </tr>
                                        <tr>
                                            <td>End Date</td>
                                            <td><input type="date" name="end_date" required /></td>
                                            <td>Amount</td>
                                            <td><input type="text" placeholder="Amount" name="amount" required /></td>
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
                                    <th width='150'>Budget ID</th>
                                    <th width='150'>Account ID</th>
                                    <th width='150'>Start Date</th>
                                    <th width='150'>End Date</th>
                                    <th width='150'>Amount</th>
                                    <th width='150'>Amount Remaining</th>
                                    <th width="250">Action</th>
                                </tr>
                                <?php
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>".$row['budget_id']."</td>";
                                    echo "<td>".$row['account_id']."</td>";
                                    echo "<td>".$row['budget_start_date']."</td>";
                                    echo "<td>".$row['budget_end_date']."</td>";
                                    echo "<td>".$row['budget_amount']."</td>";
                                    echo "<td>".$row['budget_amount_remaining']."</td>";
                                    echo "<td>";
                                    echo "<a class='button' id='table_button_delete' href='budget.php?epr=delete&id=".$row['budget_id']."'><span>Delete</span></a>";
                                    echo "<a class='button' id='table_button_update' href='budget.php?epr=update&id=".$row['budget_id']."'><span>Update</span></a>";
                                    echo "</td>";
                                    echo "</tr>   ";
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
                                <td>Budget ID</td>
                                <td><input type="text" placeholder="Budget ID" name="budget_search" required /></td>
                                <td><button type="submit" name="search_budget" class="button" style="vertical-align: middle"><span>Search</span></button></td>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
    </body>
    </html>