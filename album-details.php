<?php ob_start();

// auth check
require_once('auth.php');

$pageTitle = 'Album Details';
require_once ('header.php');

try {
// check the URL for an albumId in case the user clicked the edit button
$albumId = null;
$title = null;
$year = null;
$artist = null;

if (!empty($_GET['albumId'])) {
    if (is_numeric($_GET['albumId'])) {
        // we have a numeric albumId in the URL
        $albumId = $_GET['albumId'];

        // connect
        require_once ('db.php');

        $sql = "SELECT title, year, artist FROM albums WHERE albumId = :albumId";
        $cmd = $conn->prepare($sql);
        $cmd->bindParam(':albumId', $albumId, PDO::PARAM_INT);
        $cmd->execute();
        $album = $cmd->fetch();

        // populate our album values into variables
        $title = $album['title'];
        $year = $album['year'];
        $artist = $album['artist'];

        // disconnect
        $conn = null;
    }
}

?>

<main class="container">
    <h1>Album Details</h1>

    <form method="post" action="save-album.php">
        <fieldset class="form-group">
            <label for="title" class="col-sm-1">Title: *</label>
            <input name="title" id="title" required placeholder="Album Title" value="<?php echo $title; ?>" />
        </fieldset>
        <fieldset class="form-group">
            <label for="year" class="col-sm-1">Year:</label>
            <input name="year" id="year" type="number" min="1900" placeholder="Release Year" value="<?php echo $year; ?>" />
        </fieldset>
        <fieldset class="form-group">
            <label for="artist" class="col-sm-1">Artist: *</label>
            <input name="artist" id="artist" required placeholder="Artist Name" value="<?php echo $artist; ?>" />
        </fieldset>
        <input name="albumId" id="albumId" value="<?php echo $albumId; ?>" type="hidden" />
        <button class="btn btn-success col-sm-offset-1">Save</button>
    </form>

</main>

<?php
}
catch (exception $e) {
    header('location:error.php');
}
require_once('footer.php');
ob_flush(); ?>
