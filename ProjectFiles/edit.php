<?php
	include 'database.php';

	// select data yang akan diedit
	$q_select = "select * from tasks where id = '".$_GET['id']."' ";
	$run_q_select = mysqli_query($conn, $q_select);
	$d = mysqli_fetch_object($run_q_select);

	// proses edit data
	if(isset($_POST['edit'])){

		$q_update = "update tasks set task = '".$_POST['task']."' where id = '".$_GET['id']."' ";
		$run_q_update = mysqli_query($conn, $q_update);


		header('Refresh:0; url=index.php');

	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>To Do List</title>
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
	</style>
	<!-- <link rel="stylesheet" type="text/css" href="styles.css"> -->
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.3/dist/full.css" rel="stylesheet" type="text/css" />
	<script src="https://cdn.tailwindcss.com"></script>

	<script>
	function updateClock() {
    var currentTime = new Date();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();

    hours = (hours < 10 ? "0" : "") + hours;
    minutes = (minutes < 10 ? "0" : "") + minutes;
    seconds = (seconds < 10 ? "0" : "") + seconds;

    var timeString = hours + ":" + minutes + ":" + seconds;

    document.getElementById("clock").innerHTML = timeString;
	}
	setInterval(updateClock, 1000);
	</script>

</head>
<body style="background-color: #2a2438;">

	<div class="navbar bg-base-100 ">
  		<div class="navbar-start">
    			<a class="btn btn-ghost normal-case ">
					<img src="src/percilok.png" class="w-10" alt="percilok">
				</a>
 		</div>
		 <div class="navbar-center">
		</div>
	
  		<div class="navbar-end">
			<a href="logout.php" class="btn text-sm">Logout</a>
  		</div>
	</div>
	<div style="background-image: url('src/bg.gif'); background-size: cover;">
		<div class="container mx-auto pt-6 w-1/2 h-screen" >
			
			<div class="text-4xl text-white text-center">
				<div class="title" >
				<?php
					function getIconBasedOnTime() {
						date_default_timezone_set('Asia/Bangkok');
						$currentTime = date('H');

						if ($currentTime >= 6 && $currentTime < 18) {
							echo "<div class='title'><i class='bx bx-sun'></i>" . date("l, d M Y") . "</div>";
						} else {
							echo "<div class='title'><i class='bx bx-moon'></i>" . date("l, d M Y") . "</div>";
						}
					}

					getIconBasedOnTime();
					?>
				</div>
			</div>
			<div class="text-4xl text-white text-center">
				<div class="title">
					<div id="clock"></div>
				</div>
			</div>
			
			<div class="flex justify-center items-center  pt-6">
				<div class="">
					<form action="" method="post" class="text-center">
						<input type="text" name="task" class="input input-bordered w-full max-w-lg mb-2" placeholder="Edit task" value="<?= $d->task ?>" required>
						<br/>
						<button type="submit" name="edit" class="btn btn-active btn-primary mt-4" >Edit task</button>
					</form>
				</div>
			</div>

		</div>
	</div>
</body>
</html>