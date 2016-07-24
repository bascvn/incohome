<?php
// do php stuff
include('template/header.php');
?>

<?php
	
	if(!isset($_SESSION['ANTIHACK_QUES']) ){
		$_SESSION['ANTIHACK_QUES'] = rand(10, 20);
	};
	
	
	$FirstNum = rand(1, 8);
	$AntiHackQues = $FirstNum. "+" .($_SESSION['ANTIHACK_QUES'] - $FirstNum)." = ?";
	
	$name = '';
	$email = '';
	$phone = '';
	
	$message = '';
	if(isset($_GET['message']))
		$message = $_GET['message'];
	
	$human = '';
	$from = ''; 
	$to = ''; 
	$subject = '';
	
	
	$errName = '';
	$errEmail = '';
	$errPhone = '';
	$errMessage = '';
	$errHuman = '';
	$result = '';
	
	
	if (isset($_POST["submit"])) {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$message = $_POST['message'];
		$human = intval($_POST['human']);
		
		$from = FROM_EMAIL; 
		$to = SUPPORT_EMAIL; 
		$subject = 'Contact from INCO';
		$body ="From: $name\n E-Mail: $email\n Phone: $phone\n  Message:\n $message";

		// Check if name has been entered
		if (!$_POST['name']) {
			$errName = 'Vui lòng nhập tên';
		}
		
		// Check if email has been entered and is valid
		if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$errEmail = 'Vui lòng nhập email hợp lệ';
		}
		
		//Check if message has been entered
		if (!$_POST['message']) {
			$errMessage = 'Vui lòng nhập nội dung trao đổi';
		}
		//Check if simple anti-bot test is correct
		if ($human !== $_SESSION['ANTIHACK_QUES']) {
			$errHuman = 'Để chứng thực nhu cầu liên hệ, vui lòng nhập kết quả đúng.';
		}

		// If there are no errors, send the email
		if (!$errName && !$errEmail && !$errMessage && !$errHuman) {
			
			$toArr = array($to);
			$return_data = cm_send_mail($toArr,$subject,$body);
			if(strpos($return_data,"200")<0){
				$result='<div class="alert alert-danger">Xin lỗi, hệ thống mail của chúng tôi đang bị trục trặc, bạn vui lòng thử lại.</div>';
			}
			else
			{
				$result='<div class="alert alert-success">Cảm ơn, chúng tôi sẽ liên lạc với bạn sớm.</div>';
			}
		}
	}
	
?>

<script>
var top_menu = document.getElementById("a_toplink_contact");
top_menu.style.color = "White";
</script>



  		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h1 class="page-header text-center">Liên Hệ</h1>
				<form class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<div class="form-group">
						<label for="name" class="col-sm-2 control-label">Họ Tên</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="name" name="name" placeholder="Họ Tên (bắt buộc)" value="<?php echo htmlspecialchars($name); ?>">
							<?php echo "<p class='text-danger'>$errName</p>";?>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com (bắt buộc)" value="<?php echo htmlspecialchars($email); ?>">
							<?php echo "<p class='text-danger'>$errEmail</p>";?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="phone" class="col-sm-2 control-label">Phone</label>
						<div class="col-sm-10">
							<input type="phone" class="form-control" id="phone" name="phone" placeholder="Tùy chọn" value="<?php echo htmlspecialchars($phone); ?>">
							<?php echo "<p class='text-danger'>$errPhone</p>";?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="message" class="col-sm-2 control-label">Nội Dung</label>
						<div class="col-sm-10">
							<textarea class="form-control" rows="4" name="message" placeholder="Bắt buộc"><?php echo htmlspecialchars($message);?></textarea>
							<?php echo "<p class='text-danger'>$errMessage</p>";?>
						</div>
					</div>
					<div class="form-group">
						<label for="human" class="col-sm-2 control-label"><?php echo $AntiHackQues; ?></label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="human" name="human" placeholder="Câu Trả Lời (bắt buộc)">
							<?php echo "<p class='text-danger'>$errHuman</p>";?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-4">
							<input id="submit" name="submit" type="submit" value="Gởi Chúng Tôi" class="btn btn-primary">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<?php echo $result; ?>	
						</div>
					</div>
				</form> 
			</div>
		</div>
	
<?php
// do php stuff
include('template/footer.php');
?>
