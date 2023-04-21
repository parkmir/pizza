<?php
// 데이터베이스 연결
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pizza";

$conn = new mysqli($servername, $username, $password, $dbname);

// POST 데이터 처리
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $topping = $_POST["topping"];
  $quantity = $_POST["quantity"];

  // 데이터베이스에 삽입
  $sql = "INSERT INTO orders (topping, quantity) VALUES ('$topping', $quantity)";
  $conn->query($sql);
}

// 데이터 가져오기
$sql = "SELECT topping, SUM(quantity) as quantity FROM orders GROUP BY topping";
$result = $conn->query($sql);

// 차트 데이터 생성
$rows = array();
while($row = $result->fetch_assoc()) {
  $rows[] = [$row["topping"], (int)$row["quantity"]];
}
$data = json_encode($rows);

// 차트 출력
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pie Chart</title>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {
			var data = google.visualization.arrayToDataTable(<?php echo $data; ?>);

			var options = {
				title: 'Pizza Toppings',
				pieHole: 0.4,
			};

			var chart = new google.visualization.PieChart(document.getElementById('piechart'));

			chart.draw(data, options);
		}
	</script>
</head>
<body>
	<h1>Pie Chart</h1>
	<div id="piechart" style="width: 900px; height: 500</div>
  </body>
  </html>
  <?php
  //데이터베이스 연결해제
  $conn->close();
  ?>
