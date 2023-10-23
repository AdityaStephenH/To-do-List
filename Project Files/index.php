<?php
	session_start();
	if (!isset($_SESSION["user"])) {
	header("Location: login.php");
	}
	include 'database.php';
	$userid = $_SESSION['user'];
	// proses insert data
	if(isset($_POST['add'])){
		
	  

		$q_insert = "insert into tasks (user_id, task, status, progress) value (
		'$userid',
		'".$_POST['task']."',
		'open',
		'Not Yet Started'
		)";
		$run_q_insert = mysqli_query($conn, $q_insert);

		if($run_q_insert){
			header('Refresh:0; url=index.php');
		}

	}

	$open = "open";
	$close = "close";

	// proses show data
	$q_select_done = "select * from tasks where user_id = '".$userid."' and status = '".$close."' order by id desc";
	$run_q_select_done = mysqli_query($conn, $q_select_done);
	$q_select_open = "select * from tasks where user_id = '".$userid."' and status = '".$open."' order by id desc";
	$run_q_select_open = mysqli_query($conn, $q_select_open);
	$user_name = "select * from users where id = '".$userid."'";
	$run_user_name = mysqli_query($conn, $user_name);
	$nameShow = mysqli_fetch_array($run_user_name);


	// proses delete data
	if(isset($_GET['delete'])){

		$q_delete = "delete from tasks where id = '".$_GET['delete']."' ";
		$run_q_delete = mysqli_query($conn, $q_delete);

		header('Refresh:0; url=index.php');

	}

	if (isset($_GET['change'])) {
		$q_progress = "update tasks set progress = '".$_GET['changes']."' where id = '".$_GET['change']."' ";
		$run_q_progress = mysqli_query($conn, $q_progress);

		header('Refresh:0; url=index.php');
	}


	// proses update data (close or open)
	if(isset($_GET['done'])){
		$status = 'close';

		if($_GET['status'] == 'open'){
			$status = 'close';
		}else{
			$status = 'open';
		}

		$q_update = "update tasks set status = '".$status."' where id = '".$_GET['done']."' ";
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
<body>
		<div class="navbar bg-base-100 ">
			<div class="navbar-start">
					<a class="btn btn-ghost normal-case ">
						<img src="src/percilok.png" class="w-10" alt="percilok">
					</a>
			</div>
			<div class="navbar-center">
				<p>To Do List</p>
			</div>
		
			<div class="navbar-end">
				<a href="logout.php" class="btn text-sm">Logout</a>
			</div>
		</div>
		<div style="background-image: url('src/bg.gif'); background-size: cover;">
			<div class="container mx-auto pt-6 w-1/2 h-screen">
				<div class="text-4xl text-white text-center">
					<div class="title" >
						Halo, <?= $nameShow['name']; ?>
					</div>
				</div>
				<div class="text-4xl text-white text-center">
					<div class="title" >
						<?php
						function getIconBasedOnTime() {
							date_default_timezone_set('Asia/Bangkok');
							$currentTime = date('H');

							if ($currentTime >= 6 && $currentTime < 18) {
								echo "<div class='title'><i class='bx bx-sun'></i>" . date("l, d M Y") . "</div>";
							} else {
								echo "<div class='title'><i class='bx bx-moon'></i>" ." ". date("l, d M Y") . "</div>";
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
				

				<div class="pt-6 flex items-center">
					<?php if (mysqli_num_rows($run_q_select_done) > 0 || mysqli_num_rows($run_q_select_open) > 0):
					?>
					<div class="flex items-center mx-auto ">
						<form action="" method="post">
							<div class="row">
								<input type="text" name="task" class="input input-bordered w-max" placeholder="Add task" required>
								<button type="submit" name="add" class="btn btn-active btn-primary mt-4" >Add</button>
							</div>
							
						</form>
					</div>

					<div class="text-lg pt-8">
					<table class="table table-hover bg-base-200">
						<thead class="text-center">
							<tr class="text-white">
								<th>Task</th>
								<th>Status</th>
								<th>Progress</th>
								<th>Edit/Delete</th>
							</tr>
						</thead>
						<tbody>
					<?php  endif; ?>
					<?php
						if(mysqli_num_rows($run_q_select_open) > 0):
							while($r = mysqli_fetch_array($run_q_select_open)):
						?>
							<tr>
								<td class="align-middle text-center">
									<div>
										<span class="<?= $r['status'] == 'close' ? 'line-through':'' ?> text-white"><?= $r['task'] ?></span>
									</div>
								</td>
								<td class="align-middle text-center">
									<input type="checkbox" class="checkbox checkbox-accent" onclick="window.location.href = '?done=<?= $r['id'] ?>&status=<?= $r['status'] ?>'" <?= $r['status'] == 'close' ? 'checked':'' ?>>
								</td>
								<td class="align-middle text-center">
									<details class="dropdown">
										<summary class="m-1 btn"><?= $r['progress'] ?></summary>
										<ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52">
											<li><a href="?change=<?= $r['id'] ?>&changes=Not+Yet+Started">Not Yet Started</a></li>
											<li><a href="?change=<?= $r['id'] ?>&changes=In+Progress">In Progress</a></li>
											<li><a href="?change=<?= $r['id'] ?>&changes=Waiting+on">Waiting On</a></li>
										</ul>
									</details>
								</td>
								<td class="align-middle text-center">
									<div class="">
										<a href="edit.php?id=<?= $r['id'] ?>" class="btn text-orange-500" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
											<path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
											<path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
											</svg>
										</a>
									</div>
									<div class="">
										<a href="?delete=<?= $r['id'] ?>" class="btn text-red-700" title="Remove" onclick="return confirm('Are you sure?')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
											<path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
											</svg>
										</a>
									</div>
								</td>
							</tr>
						<?php endwhile;
						endif; ?>
						<?php
						if(mysqli_num_rows($run_q_select_done) > 0):
							while($s = mysqli_fetch_array($run_q_select_done)):
						?>
							<tr>
								<td class="align-middle text-center">
									<div>
										<span class="<?= $s['status'] == 'close' ? 'line-through':'' ?> text-white"><?= $s['task'] ?></span>
									</div>
								</td>
								<td class="align-middle text-center">
									<input type="checkbox" class="checkbox checkbox-accent" onclick="window.location.href = '?done=<?= $s['id'] ?>&status=<?= $s['status'] ?>'" <?= $s['status'] == 'close' ? 'checked':'' ?>>
								</td>
								<td class="align-middle text-center">
									<details class="dropdown">
										<summary class="m-1 btn"><?= $s['progress'] ?></summary>
										<ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52">
											<li><a href="?change=<?= $s['id'] ?>&changes=Not+Yet+Started">Not Yet Started</a></li>
											<li><a href="?change=<?= $s['id'] ?>&changes=In+Progress">In Progress</a></li>
											<li><a href="?change=<?= $s['id'] ?>&changes=Waiting+on">Waiting On</a></li>
										</ul>
									</details>
								</td>
								<td class="align-middle text-center">
									<div class="">
										<a href="edit.php?id=<?= $s['id'] ?>" class="btn text-orange-500" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
											<path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
											<path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
											</svg>
										</a>
									</div>
									<div class="">
										<a href="?delete=<?= $s['id'] ?>" class="btn text-red-700" title="Remove" onclick="return confirm('Are you sure?')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
											<path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
											</svg>
										</a>
									</div>
								</td>
							</tr>
						<?php endwhile;
						endif; ?>
						</tbody>
					</table>

					
					<?php if (mysqli_num_rows($run_q_select_open) == 0 && mysqli_num_rows($run_q_select_done) == 0):
					?>
					<div class="flex items-center mx-auto ">
						<form action="" method="post">
							<div class="row">
								<input type="text" name="task" class="input input-bordered w-max" placeholder="Add task" required>
								<button type="submit" name="add" class="btn btn-active btn-primary mt-4" >Add</button>
							</div>
							
						</form>
					</div>
				
					<div class="text-2xl text-white text-center">
						Belum Ada Task
					</div>
					<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
</body>
</html>

