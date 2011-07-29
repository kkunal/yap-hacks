<?php

require_once('lib/common.inc.php');
require_once('lib/OpenSocial/osapi.php');
require_once('lib/OpenSocial/providers/osapiYahooProvider.php');
/*require_once('lib/OpenSocial/auth/osapiAuth.php');
require_once('lib/OpenSocial/io/osapiHttpProvider.php');
require_once('lib/OpenSocial/providers/osapiProvider.php');
require_once('lib/OpenSocial/providers/osapiYahooProvider.php');
require_once('lib/OpenSocial/storage/osapiStorage.php');*/


/**
* Yahoo! Open Social Rest End Point: http://appstore.apps.yahooapis.com/social/rest
*/

// get current session id
$session_id = session_id();

// enable osapi logging to file
//osapiLogger::setLevel(osapiLogger::ERROR);
//osapiLogger::setAppender(new osapiFileAppender(sys_get_temp_dir().'/opensocial.log'));
error_reporting(255);
// create yahoo open social provider
$provider = new osapiYahooProvider();

// create file system storage using system temp directory
$storage = new osapiFileStorage(realpath(sys_get_temp_dir()));
// if this is a YAP application, the access token and secret
// will be provided.
if(isset($_POST['yap_viewer_access_token']) &&
   isset($_POST['yap_viewer_access_token_secret']) &&
   isset($_POST['yap_viewer_guid'])) {

  $oauth = new osapiOAuth3Legged(
      OAUTH_CONSUMER_KEY,
      OAUTH_CONSUMER_SECRET,
      $storage,
      $provider,
      $_POST['yap_viewer_guid'],
      $_POST['yap_viewer_guid'],
      $_POST['yap_viewer_access_token'],
      $_POST['yap_viewer_access_token_secret']
  );
}
else {
  $oauth = osapiOAuth3Legged::performOAuthLogin(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, $storage, $provider, $session_id);
}

// create open social instance from yahoo provider + oauth credentials
$opensocial = new osapi($provider, $oauth);

// The number of friends to fetch.
$friend_count = 10;

// Start a batch so that many requests may be made at once.
$batch = $opensocial->newBatch();

// Fetch the user profile
$batch->add($opensocial->people->get(array('userId' => '@me', 'groupId' => '@self', 'fields' => array('displayName'))), 'self');

// Fetch the friends of the user
$batch->add($opensocial->people->get(array('userId' => '@me', 'groupId' => '@friends', 'fields' => array('id'), 'count' => 100)), 'friends');

// Request the activities of the current user
$batch->add($opensocial->activities->get(array('userId' => '@me', 'groupId' => '@self', 'count' => 100)), 'userActivities');

// Send the batch request
$result = $batch->execute();

foreach ($result as $key => $result_item) {
  if ($result_item instanceof osapiError) {
    $code = $result_item->getErrorCode();
    $message = $result_item->getErrorMessage();
    echo "<h2>There was a <em>$code</em> error with the <em>$key</em> request:</h2>";
    echo "<pre>";
    echo htmlentities($message);
    echo "</pre>";
  } else {
    echo "<h2>Response for the <em>$key</em> request:</h2>";
    echo "<pre>";
    echo htmlentities(print_r($result_item, True));
    echo "</pre>";
  }
}
