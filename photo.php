<?php
$getphotoid = $_GET["id"];
function runQuery($query_text) {
	// Define a return array
	$ret = array();
	// Connect to database and run query
	$link = mysqli_connect("localhost", "ryan.rodd", "Circus123!", "student-database");
	$result = mysqli_query($link, $query_text);
	// Looping through results and creating our output array
	if ($result) {
		while ($data = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$ret[] = $data;
		}
	}
	return $ret;
}
$query = "SELECT * FROM urimg_photos WHERE id='" . $getphotoid . "'";
$photoarr = runQuery($query);

$description = $photoarr[0]['description'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>urimg</title>
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">
	<link rel="stylesheet" href="./main.css">
</head>
<body>
	<header class="header row z-depth-1 teal p-b-3">
        <div class="col s6 offset-s3">
             <a href="index.php"><h1 class="white-text center-align">URIMG</h1></a>
        </div>
        <div class="col s3 center-align">
    		<a href="logout.php" class="white-text">Logout</a>
        </div>
        <div class="col s12 center-align">
        	<a href="upload.html" class="white-text btn btn-large">Upload a File <i class="material-icons right">play_for_work</i></a>
    	</div>
    </header>
    
	<div class="container">
		<h2><?php echo @$photoarr[0][title]; ?></h2>
		<div class="row">
			<div class="col m8 s12">
				<?php echo "<img class='responsive-img' src='./".$photoarr[0]['imagelink']."'>" ?>
			</div>
			<div class="col m4 s12">
				<?php echo "<p>$description</p>"; ?>
			</div>
		</div>
		<div class="row">
			<form id="add-comment-form" class="col s12 z-depth-1" onsubmit="return false;">
				<div class="row">
					<div class="input-field col s12">
						<textarea id="add-comment-text" class="materialize-textarea"></textarea>
						<label for="add-comment-text">Comment</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<button id="add-comment-submit" class="btn waves-effect waves-light" type="submit" name="action">Submit</button>	
					</div>
				</div>
			</form>
		</div>
		<div id="comment-list" class="row">
			
			
			<?php
				// get all comments from db
				
				$commentquery = "SELECT * FROM urimg_comments WHERE photo_id='" . $getphotoid . "' ORDER BY created DESC";
				$conn = mysqli_connect("localhost", "ryan.rodd", "Circus123!", "student-database");
				$commentresult = mysqli_query($conn, $commentquery);
					
				while($commentrow = mysqli_fetch_array($commentresult, MYSQLI_ASSOC))
			    {
				  $username=$commentrow['username'];
				  $comment=$commentrow['text'];
			      $time=$commentrow['created'];
			?>
				    <div class="comment-item col s12 z-depth-1">
					  <p class="teal-text"><?php echo $username; ?></p>
					  <p class=""><?php echo $time; ?></p>
				      <p><?php echo $comment; ?></p>	
					</div>
			<?php
				}
			?>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js"></script>
	<script>
		$(document).ready(function() {
			console.log('oshit')
			$('#add-comment-form').on('submit', function(e) {
				var username = "<?php echo $username ?>";
				var text = $('#add-comment-text').val();
				var photoid = <?php echo $getphotoid ?>;
				var postData = {
				'username': username,
				'text': text,
				'photoid': photoid
				};
				$.post('handle_comment.php', postData, function(result) {
					$('#comment-list').prepend(result);
					$('.tofade').fadeIn(1000);
					$('#add-comment-form')[0].reset();
				});
			});
		});
	</script>
</body>
</html>