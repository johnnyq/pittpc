<?php include("header.php"); ?>
<?php 

if(isset($_GET['year'])){
  $year = intval($_GET['year']);
}else{
  $year = date('Y');
}

$sql_payment_years = mysqli_query($mysqli,"SELECT DISTINCT YEAR(payment_date) AS payment_year FROM payments UNION SELECT DISTINCT YEAR(revenue_date) AS payment_year FROM revenues ORDER BY payment_year DESC");

$sql_categories = mysqli_query($mysqli,"SELECT * FROM categories WHERE category_type =  'Income' ORDER BY category_name ASC");

?>

<div class="card">
  <div class="card-header bg-dark text-white">
    <h6 class="float-left mt-1"><i class="fa fa-fw fa-coins mr-2"></i>Income Summary</h6>
    <button type="button" class="btn btn-primary btn-sm float-right d-print-none" onclick="window.print();"><i class="fas fa-fw fa-print"></i> Print</button>
  </div>
  <div class="card-body">
    <form class="mb-3">
      <select onchange="this.form.submit()" class="form-control" name="year">
        <?php 
                
        while($row = mysqli_fetch_array($sql_payment_years)){
          $payment_year = $row['payment_year'];
        ?>
        <option <?php if($year == $payment_year){ ?> selected <?php } ?> > <?php echo $payment_year; ?></option>
        
        <?php
        }
        ?>

      </select>
    </form>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead class="text-dark">
          <tr>
            <th>Category</th>
            <th class="text-right">January</th>
            <th class="text-right">February</th>
            <th class="text-right">March</th>
            <th class="text-right">April</th>
            <th class="text-right">May</th>
            <th class="text-right">June</th>
            <th class="text-right">July</th>
            <th class="text-right">August</th>
            <th class="text-right">September</th>
            <th class="text-right">October</th>
            <th class="text-right">November</th>
            <th class="text-right">December</th>
            <th class="text-right">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while($row = mysqli_fetch_array($sql_categories)){
            $category_id = $row['category_id'];
            $category_name = $row['category_name'];
          ?>

            <tr>
              <td><?php echo $category_name; ?></td>
              
              <?php
              
              for($month = 1; $month<=12; $month++) {
                //Payments to Invoices
                $sql_payments = mysqli_query($mysqli,"SELECT SUM(payment_amount) AS payment_amount_for_month FROM payments, invoices WHERE payments.invoice_id = invoices.invoice_id AND invoices.category_id = $category_id AND YEAR(payment_date) = $year AND MONTH(payment_date) = $month");
                $row = mysqli_fetch_array($sql_payments);
                $payment_amount_for_month = $row['payment_amount_for_month'];

                //Revenues
                $sql_revenues = mysqli_query($mysqli,"SELECT SUM(revenue_amount) AS revenue_amount_for_month FROM revenues WHERE revenues.category_id = $category_id AND YEAR(revenue_date) = $year AND MONTH(revenue_date) = $month");
                $row = mysqli_fetch_array($sql_revenues);
                $revenues_amount_for_month = $row['revenue_amount_for_month'];

                $payment_amount_for_month = $payment_amount_for_month + $revenues_amount_for_month;
                
                $total_payment_for_all_months = $payment_amount_for_month + $total_payment_for_all_months;

              
              ?>
                <td class="text-right text-monospace">$<?php echo number_format($payment_amount_for_month,2); ?></td>
              
              <?php
              
              }
              
              ?>
              
              <td class="text-right text-monospace">$<?php echo number_format($total_payment_for_all_months,2); ?></td>
            </tr>
          
          <?php 
          
          $total_payment_for_all_months = 0;

          } 
          
          ?>
          
          <tr>
            <th>Total</th>
            <?php
              
            for($month = 1; $month<=12; $month++) {
              $sql_payments = mysqli_query($mysqli,"SELECT SUM(payment_amount) AS payment_total_amount_for_month FROM payments, invoices WHERE payments.invoice_id = invoices.invoice_id AND YEAR(payment_date) = $year AND MONTH(payment_date) = $month");
              $row = mysqli_fetch_array($sql_payments);
              $payment_total_amount_for_month = $row['payment_total_amount_for_month'];

              $sql_revenues = mysqli_query($mysqli,"SELECT SUM(revenue_amount) AS revenue_amount_for_month FROM revenues WHERE category_id > 0 AND YEAR(revenue_date) = $year AND MONTH(revenue_date) = $month");
              $row = mysqli_fetch_array($sql_revenues);
              $revenues_total_amount_for_month = $row['revenue_amount_for_month'];

              $payment_total_amount_for_month = $payment_total_amount_for_month + $revenues_total_amount_for_month;


              $total_payment_for_all_months = $payment_total_amount_for_month + $total_payment_for_all_months;
              
            ?>

              <th class="text-right text-monospace">$<?php echo number_format($payment_total_amount_for_month,2); ?></th>
            <?php

            }

            ?>

            <th class="text-right text-monospace">$<?php echo number_format($total_payment_for_all_months,2); ?></th>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include("footer.php");