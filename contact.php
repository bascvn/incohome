<?php
// do php stuff
include('template/header.php');
?>

<?php
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
		
		$from = 'contact@kiemtraduan.net'; 
		$to = 'support@bansac.vn'; 
		$subject = 'Contact from INCO';
		$body ="From: $name\n E-Mail: $email\n Phone: $phone\n  Message:\n $message";

		// Check if name has been entered
		if (!$_POST['name']) {
			$errName = 'Please enter your name';
		}
		
		// Check if email has been entered and is valid
		if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$errEmail = 'Please enter a valid email address';
		}
		
		//Check if message has been entered
		if (!$_POST['message']) {
			$errMessage = 'Please enter your message';
		}
		//Check if simple anti-bot test is correct
		if ($human !== 5) {
			$errHuman = 'Your anti-spam is incorrect';
		}

		// If there are no errors, send the email
		if (!$errName && !$errEmail && !$errMessage && !$errHuman) {
			
			
			require_once('PHPMailer/PHPMailerAutoload.php');


			$mail = new PHPMailer(); // create a new object
			$mail->IsSMTP(); // enable SMTP
			$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465; // or 587
			$mail->IsHTML(true);
			$mail->Username = "basc.noreply@gmail.com";
			$mail->Password = "loveviet2007";
			$mail->SetFrom("basc.noreply@gmail.com");

			$mail->Subject = $subject;
			$mail->Body = $body;
			$mail->AddAddress("support@bansac.vn");
			 if(!$mail->Send())
			{
				$result='<div class="alert alert-danger">Xin lỗi, hệ thống mail của chúng tôi đang bị trục trặc, bạn vui lòng thử lại.</div>';
			}
			else
			{
				$result='<div class="alert alert-success">Cảm ơn, chúng tôi sẽ liên lạc với bạn sớm.</div>';
			}

			
			//echo $body;
			/*
			if (mail ($to, $subject, $body, $from)) {
				$result='<div class="alert alert-success">Thank You! I will be in touch</div>';
			} else {
				$result='<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later.</div>';
			}
			*/
		}
	}
	
?>

<script>
var top_menu = document.getElementById("a_toplink_contact");
top_menu.style.color = "White";
</script>



  		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h1 class="page-header text-center">Contact Us</h1>
				<form class="form-horizontal" role="form" method="post" action="contact.php">
					<div class="form-group">
						<label for="name" class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="name" name="name" placeholder="First & Last Name" value="<?php echo htmlspecialchars($name); ?>">
							<?php echo "<p class='text-danger'>$errName</p>";?>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php echo htmlspecialchars($email); ?>">
							<?php echo "<p class='text-danger'>$errEmail</p>";?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="phone" class="col-sm-2 control-label">Phone</label>
						<div class="col-sm-10">
							<input type="phone" class="form-control" id="phone" name="phone" placeholder="" value="<?php echo htmlspecialchars($phone); ?>">
							<?php echo "<p class='text-danger'>$errPhone</p>";?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="message" class="col-sm-2 control-label">Message</label>
						<div class="col-sm-10">
							<textarea class="form-control" rows="4" name="message"><?php echo htmlspecialchars($message);?></textarea>
							<?php echo "<p class='text-danger'>$errMessage</p>";?>
						</div>
					</div>
					<div class="form-group">
						<label for="human" class="col-sm-2 control-label">2 + 3 = ?</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="human" name="human" placeholder="Your Answer">
							<?php echo "<p class='text-danger'>$errHuman</p>";?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<input id="submit" name="submit" type="submit" value="Send to Us" class="btn btn-primary">
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
