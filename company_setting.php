<?php
  include 'include/header.php';
?>
<div class="container"  style="padding:30px 0 0 0">
<h4>顧客一覧</h4>
<table class="table table-striped">
    <thead>
      <tr>
        <th>顧客名</th>
        <th>登録サービス</th>
        <th>登録アプリケーション</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
<?php
include 'include/db_connect.php';
$company_query = mysqli_query($link,"select code,company_name from company_info");
while ($company_row = mysqli_fetch_row($company_query)) {
  $company_code = $company_row[0];
  $company_name = $company_row[1];
  echo "<tr>";
  echo "<td>".$company_name."</td>";
  echo "<td>";
  $service_query = mysqli_query($link,"select service_name from service_regist inner join service_master on service_regist.service_code = service_master.code where company_code = '{$company_code}'");
  while ($service_row = mysqli_fetch_row($service_query)) {
    echo $service_row[0]."<br/>";
  }
  echo "</td>";
  echo "<td>";
  $application_query = mysqli_query($link,"select application_name from application_regist inner join application_master on application_regist.application_code = application_master.code where company_code = '{$company_code}'");
  while ($application_row = mysqli_fetch_row($application_query)) {
    echo $application_row[0]."<br/>";
  }
  echo "</td>";
  echo "<td>";
  echo '<form action="./mail_regist.php" method="post">';
  echo '<input type="hidden" name="company_code" value="'.$company_code.'">';
  echo '<button class="btn" input type="hidden" value="'.$company_name.'" name="company_name" type="submit">配信設定</button>';
  echo '</form>';
  echo "</td>";
  echo "</tr>";
}

?>
    </tbody>
</table>
</div>