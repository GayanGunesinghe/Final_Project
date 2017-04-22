    <html>
        <head>
            <meta charset="UTF-8">
            <title>Samtessi</title>
            <link rel="stylesheet" type="text/css" href="http://localhost/Final_Project/finance_administration/Styles/global.css">
            <link rel="stylesheet" type="text/css" href="http://localhost/Final_Project/finance_administration/Styles/Icon/flaticon.css">
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {

                    var data = google.visualization.arrayToDataTable([
                        ['Task', 'Hours per Day'],
                        ['Work',     11],
                        ['Eat',      2],
                        ['Commute',  2],
                        ['Watch TV', 2],
                        ['Sleep',    7]
                    ]);

                    var options = {
                        title: 'My Daily Activities'
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                    chart.draw(data, options);
                }
            </script>
        </head>
        <body>
        	<div id="header">
                <div class="logo" onclick="location.href='http://localhost/Final_Project/home.php';"><img src="http://localhost/Final_Project/finance_administration/Images/samtessi.png"></div>
                <div class="department">Finances and Administration</div>
        	</div>
        	<div id="container">
                <div class="sidebar">
                    <ul id="nav">
                        <li><a class="flaticon-download-business-statistics-symbol-of-a-graphic" id="selected" href="http://localhost/Final_Project/finance_administration/dashboard/dashboard.php"> Dashboard</a></li>
                        <li><a class="flaticon-money-bags" href="#"> Budgeting</a>
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
        		<h1>Dashboard</h1>
        		<p>Here you can see summarized details regarding finance and administration.</p>

        		<div id="box">
                        <?php
                            $conn = new mysqli('localhost', 'root', 'toor', 'final_project');
                            $query = mysqli_query($conn, ("SELECT budget_amount, budget_start_date, budget_end_date FROM fa_budget"));
                            $count = 0;
                            while($result=mysqli_fetch_array($query)){
                                $budget_amount[$count] = $result['budget_amount'];
                                $budget_start_date[$count] = $result['budget_start_date'];
                                $budget_end_date[$count] = $result['budget_end_date'];
                                $count++;
                            }
                        ?>
                        <div class="box-top">Statistics</div>
                        <div class="last"><h2><?php echo "$ ".$budget_amount[$count-3]; ?></h2></br><small><?php echo "FROM ".$budget_start_date[$count-3]." TO ".$budget_end_date[$count-3]; ?></small></div>
                        <div class="present"><h2><?php echo "$ ".$budget_amount[$count-2]; ?></h2></br><small><?php echo "FROM ".$budget_start_date[$count-2]." TO ".$budget_end_date[$count-2]; ?></small></div>
                        <div class="predict"><h2><?php echo "$ ".$budget_amount[$count-1]; ?></h2></br><small><?php echo "FROM ".$budget_start_date[$count-1]." TO ".$budget_end_date[$count-1]; ?></small></div>
                </div>
        			
        		<div id="box">
                        <div class="box-top">Latest Updates</div>
                        <div class="box-panel">
                        Latest updates will be posted here.</br>
                            <div id="piechart" style="width: 900px; height: 500px;"></div>
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