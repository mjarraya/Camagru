
<?php
include 'functions.php';
include 'connect.php';
$dbh = connect();
$array = explode("/", $_SERVER['REQUEST_URI']);
$path = $array[1];

if ($_POST['submit'] === "OK")
{
	if (strlen($_POST['login']) > 12)
	{
		echo "<script>alert('Login is too long (max 12 characters)');location.href='inscription.html';</script>";
		return ;
	}
	if (strlen($_POST['passwd']) > 12)
	{
		echo "<script>alert('Password is too long (max 12 characters)');location.href='inscription.html';</script>";
		return ;
	}
	if (strlen($_POST['passwd']) < 6)
	{
		echo "<script>alert('Password is too short (between 6 and 12 characters)');location.href='inscription.html';</script>";
		return ;
	}
	if (!accepted_cred($_POST['passwd']))
	{
		echo "<script>alert('Password must contain only alphanumeric characters and underscores/hyphens');location.href='inscription.html';</script>";
		return ;
	}
	if (!accepted_cred($_POST['login']))
	{
		echo "<script>alert('Login must contain only alphanumeric characters and underscores/hyphens');location.href='inscription.html';</script>";
		return ;
	}

	if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
	{
		echo "<script>alert('Email format is invalid');location.href='inscription.html';</script>";
		return ;
	}
	if ($_POST['login'] !== "" && $_POST['passwd'] !== "" && $_POST['mail'] !== "")
	{
		if (!$_POST['passwd'] || !$_POST['login'] || !$_POST['mail'])
		{
			echo "<script>alert('Please fill in all fields');location.href='inscription.html';</script>";
			return ;
		}
		$req = $dbh->prepare('SELECT * FROM members WHERE mail = :mail OR login = :login');
		$req->execute(array(
		 	'mail' => $_POST['mail'],
			'login' => $_POST['login']
		));
		$i = 0;
		while ($ret = $req->fetch())
			$i++;
		if ($i == 0)
		{
		$req = $dbh->prepare('INSERT INTO members(mail, login, password) VALUES(:mail, :login, :password)');
		$req->execute(array(
			'mail' => htmlspecialchars($_POST['mail']),
			'login' => htmlspecialchars($_POST['login']),
			'password' => hash("whirlpool", $_POST['passwd'])
		));
		$cle = md5(microtime(TRUE)*100000);
		$req = $dbh->prepare("UPDATE members SET cle = :cle WHERE login like :login");
		$req->execute(array(
			'cle' => $cle,
			'login' => $_POST['login']
		));
		$recipient = $_POST['mail'];
		$subject = "Activate your account";
		$header = "From: camagru@42.fr";
		$link = 'http://montasar.me/'.$path.'/activation.php?login='.urlencode($_POST['login']).'&cle='.urlencode($cle);
		$message = 'Welcome to Camagru!

		To activate your account, please click on the link below.'."\n".$link.'
		-------------
		Do not reply.';
		mail($recipient, $subject, $message, $header);
		echo "<script>alert('Account was successfully created. Click the link in email for validation');location.href='index.php';</script>";
		}
		else
			echo "<script>alert('Username or email already taken');location.href='inscription.html';</script>";
	}
	else
		echo "<script>alert('Please fill in all fields');location.href='inscription.html';</script>";
}
else
{
	header('Location: index.php');
}
?>
