<?php
//Call client_email_menu function to load plugin menu in dashboard
add_action( 'admin_menu', 'client_email_menu' );

// Create WordPress admin menu
if( !function_exists("client_email_menu") )
{
function client_email_menu(){

  $page_title = 'Web Life Reporting Tool Setup';
  $menu_title = 'Web Life Reporting Tool';
  $capability = 'administrator';
  $menu_slug  = 'web-life-options';
  $function   = 'web_life_settings_page';
  $icon_url   = null;
  $position   = null;

  add_menu_page( $page_title,
                 $menu_title,
                 $capability,
                 $menu_slug,
                 $function,
                 $icon_url,
                 $position );

  // Call register_web_life_settings function to update database
  add_action( 'admin_init', 'register_web_life_settings' );

}
}

// Create function to register plugin settings in the database
if( !function_exists("register_web_life_settings") )
{
function register_web_life_settings() {
  register_setting( 'web-life-reporting-settings', 'client_email' );
  register_setting( 'web-life-reporting-settings', 'client_name' );
  register_setting( 'web-life-reporting-settings', 'client_path_to_cpanel' );
}
}

// Create WordPress plugin page
if( !function_exists("web_life_settings_page") )
{
function web_life_settings_page(){
?>
  <h1>Web Life Reporting Tool Options</h1>
  <form method="post" action="options.php">
    <?php settings_fields( 'web-life-reporting-settings' ); ?>
    <?php do_settings_sections( 'web-life-reporting-settings' ); ?>
    <table class="form-table">
      <tr valign="top">
      <th scope="row">Client's Email Address:</th>
      <td><input style='width: 100%' type="text" name="client_email" value="<?php echo get_option('client_email'); ?>"/></td>
      </tr>

      <tr valign="top">
      <th scope="row">Client's Company/Group Name:</th>
      <td><input style='width: 100%' type="text" name="client_name" value="<?php echo get_option('client_name'); ?>"/></td>
      </tr>

      <tr valign="top">
      <th scope="row">Path to Cpanel bandwith info:</th>
      <td><input style='width: 100%' type="text" name="client_path_to_cpanel" value="<?php echo get_option('client_path_to_cpanel'); ?>"/></td>
      </tr>
    </table>
  <?php submit_button(); ?>
  </form>
<?php
}
}

// Plugin logic for adding extra info to posts
if( !function_exists("client_email") )
{
  function client_email($content)
  {
    $clientEmail = get_option('client_email');
    return $content . $clientEmail;
  }
}

// Apply the client_email function on our content
add_filter('the_content', 'client_email');
add_filter('the_content', 'client_name');
add_filter('the_content', 'client_path_to_cpanel');

?>
