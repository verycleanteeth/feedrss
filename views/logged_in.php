
<!-- if you need user information, just put them into the $_SESSION variable and output them here -->
<b><font color=white>You are logged in as <?php echo $_SESSION['user_name']; ?></font></b> 

<!-- because people were asking: "index.php?logout" is just my simplified form of "index.php?logout=true" -->
<a href="index.php?logout">Logout</a>
