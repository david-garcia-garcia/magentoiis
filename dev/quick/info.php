<?php
include_once('_secure.inc');
?>

<?php
$extras = [
  'get_current_user()' => get_current_user(),
  'getmyuid()' => getmyuid(),
  'getmygid()' => getmygid(),
  'getmypid()' => getmypid(),
  'getmyinode()' => getmyinode(),
  'getlastmod()' => getlastmod(),
  'realpath_cache_size() [Kb]' => round(realpath_cache_size() / 1024, 0),
  ];

$extras = array_merge($extras, openssl_get_cert_locations());

/**
 * Loads the chef runtime settings.
 */
if (!function_exists('loadChefSettings')) {
  function loadChefSettings() {
    $salt = $_SERVER['DOCUMENT_ROOT'] ?? ($_SERVER['APPL_PHYSICAL_PATH'] ?? __FILE__);
    $cache_key = $salt . ':chefsettings';
    if ($settings = @wincache_ucache_get($cache_key)) {
      $_SERVER['CHEF_SETTINGS'] = $settings;
      return;
    }
    // Look for the file a couple of levels up...
    $depth = 0;
    $contentsFile = NULL;
    $contentsDir = NULL;
    while ($contentsFile == NULL && $depth < 3) {
      $prefix = '';
      for ($x = 0; $x < $depth; $x++) {
        $prefix .= '../';
      }
      if ($contentsDir = @file_get_contents($prefix . 'chef-runtime.path')) {
        break;
      }
      $depth++;
    }
    if ($settingsFile = @file_get_contents($contentsDir . '\\chef-settings.json')) {
      $_SERVER['CHEF_SETTINGS'] = json_decode($settingsFile, TRUE);
      if ($_SERVER['CHEF_SETTINGS'] == NULL
        && json_last_error() !== \JSON_ERROR_NONE) {
        throw new \Exception("Error parsing chef settings file: " . json_last_error_msg());
      }
      @wincache_ucache_set($cache_key, $_SERVER['CHEF_SETTINGS']);
    }
  }
}

loadChefSettings();

?>

<center>
  <h2>General settings</h2>
  <table>
    <?php foreach ($extras as $name => $value) { ?>
    <tr>
      <td>
        <?=$name?>
      </td>
      <td>
        <?=$value?>
      </td>
    </tr>
    <?php } ?>
  </table>
  <h2>Chef settings</h2>
  <table>
    <?php foreach ($_SERVER['CHEF_SETTINGS'] ?? [] as $name => $value) { ?>
    <tr>
      <td>
        <?=$name?>
      </td>
      <td>
        <?=$value?>
      </td>
    </tr>
    <?php } ?>
  </table>
</center>

<?php echo phpinfo(); ?>