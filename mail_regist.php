<?php
  include 'include/header.php';
?>
<div class="container"  style="padding:30px 0 0 0">
<table class="table table-striped">
    <thead>
      <tr>
        <th>登録サービス</th>
        <th>登録アプリケーション</th>
      </tr>
    </thead>
    <tbody>
<?php
$company_code = $_POST['company_code'];
$company_name = $_POST['company_name'];
echo "<h4>".$company_name."</h4>";
include 'include/db_connect.php';
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
  echo "</tr>";

?>
    </tbody>
</table>
<h4>脆弱性情報配信登録</h4>
<?php
    echo '<form action="./mail_db_regist.php" method="post">';
    echo '<input type="hidden" name="company_code" value="'.$company_code.'">';
    echo '<button class="btn btn-danger pull-right" type="submit">登録</button>';
    echo '<br/>';
    echo '<br/>';
    echo '<br/>';
    echo '<section>'."\n";
    echo '<button type="button" style="width:300px" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#microsoft">microsoft</button>'."\n";
    echo '<div id="microsoft" class="collapse">'."\n";
    echo '<div class="panel panel-default">'."\n";
    echo '<div class="panel-body">'."\n";
    $microsoft_query = mysqli_query($link,"select DISTINCT product from cpes where jvn_id != 0 and vendor = 'microsoft' and product = 'sql_server' or product like 'windows%'");
    while ($microsoft_row = mysqli_fetch_row($microsoft_query)) {
      echo '<div class="checkbox">'."\n";
      echo '<label><input type="checkbox" value="'.$microsoft_row[0].'" name="microsoft_item[]">'.$microsoft_row[0].'</input></label>'."\n";
      echo '</div>'."\n";
    }
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</section>'."\n";
    echo '<section>'."\n";
    echo '<button type="button" style="width:300px" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#cybozu">cybozu</button>'."\n";
    echo '<div id="cybozu" class="collapse">'."\n";
    echo '<div class="panel panel-default">'."\n";
    echo '<div class="panel-body">'."\n";
    $cybozu_query = mysqli_query($link,"select DISTINCT product from cpes where jvn_id != 0 and vendor = 'cybozu' and product = 'office' or product = 'garoon' or product = 'remote_service_manager' or product = 'dezie'");
    while ($cybozu_row = mysqli_fetch_row($cybozu_query)) {
      echo '<div class="checkbox">'."\n";
      echo '<label><input type="checkbox" value="'.$cybozu_row[0].'" name="cybozu_item[]">'.$cybozu_row[0].'</input></label>'."\n";
      echo '</div>'."\n";
    }
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</section>'."\n";
    echo '<section>'."\n";
    echo '<button type="button" style="width:300px" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#linux">linux</button>'."\n";
    echo '<div id="linux" class="collapse">'."\n";
    echo '<div class="panel panel-default">'."\n";
    echo '<div class="panel-body">'."\n";
    $linux_query = mysqli_query($link,"select DISTINCT product from cpes where jvn_id != 0 and vendor = 'linux'");
    while ($linux_row = mysqli_fetch_row($linux_query)) {
      echo '<div class="checkbox">'."\n";
      echo '<label><input type="checkbox" value="'.$linux_row[0].'" name="linux_item[]">'.$linux_row[0].'</input></label>'."\n";
      echo '</div>'."\n";
    }
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</section>'."\n";
    echo '<section>'."\n";
    echo '<button type="button" style="width:300px" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#qnap">qnap</button>'."\n";
    echo '<div id="qnap" class="collapse">'."\n";
    echo '<div class="panel panel-default">'."\n";
    echo '<div class="panel-body">'."\n";
    $qnap_query = mysqli_query($link,"select DISTINCT product from cpes where jvn_id != 0 and vendor = 'qnap' and product = 'nas'");
    while ($qnap_row = mysqli_fetch_row($qnap_query)) {
      echo '<div class="checkbox">'."\n";
      echo '<label><input type="checkbox" value="'.$qnap_row[0].'" name="qnap_item[]">'.$qnap_row[0].'</input></label>'."\n";
      echo '</div>'."\n";
    }
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</section>'."\n";
    echo "</form>"
?>




</div>