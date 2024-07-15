<?php
class villenoir_OKThemes_Theme_Demo_Data_Importer {

    /**
     * Holds a copy of the object for easy reference.
     *
     * @since 2.2.0
     *
     * @var object
     */
    private static $instance;
    

    /**
     * Constructor. Hooks all interactions to initialize the class.
     *
     * @since 2.2.0
     */
    public function __construct() {
        
        self::$instance = $this;

        add_action( 'admin_init', array($this, 'villenoir_theme_activation') );
        add_action( 'admin_menu', array($this, 'villenoir_add_admin') );
        add_action( 'admin_print_scripts', array($this, 'villenoir_enqueue_admin_assets') );
    }

    // Custom assets for admin pages
    function villenoir_enqueue_admin_assets() {
        wp_enqueue_style( 'villenoir-theme-admin', get_template_directory_uri() . '/admin/importer/assets/admin-style.css' );
    }

    // Redirect to Demo Import page after Theme activation
    public function villenoir_theme_activation() {
        global $pagenow;
        if ( is_admin() AND $pagenow == 'themes.php' AND isset( $_GET['activated'] ) ) {
            //Redirect to demo import
            header( 'Location: ' . admin_url( 'admin.php?page=villenoir-home' ) );
        }
    }

    /**
     * Add Panel Page
     *
     * @since 2.2.0
     */
    public function villenoir_add_admin() {
        //Output buffering
        ob_start();
        add_theme_page("About the theme", "About the theme", 'switch_themes', 'villenoir-home', array($this, 'villenoir_welcome_page'));
        if ( class_exists( 'acf' ) ) {
            add_theme_page("Customizer Import", "Import theme options", 'switch_themes', 'import', array($this, 'villenoir_import_option_page'));
            add_theme_page("Customizer Export", "Export theme options", 'switch_themes', 'export', array($this, 'villenoir_export_option_page'));
        }
    }


        public function villenoir_welcome_page() {

        $theme = wp_get_theme();
        if ( is_child_theme() ) {
            $theme = wp_get_theme( $theme->get( 'Template' ) );
        }
        $theme_name = $theme->get( 'Name' );
        $theme_version = $theme->get( 'Version' );

        $return_url = admin_url('admin.php?page=villenoir-home');

        
        ?>
        
        <div class="gg-admin-welcome-page">
            

            <div class="welcome-panel" style="padding-bottom: 23px;">
                <div class="welcome-panel-content">

                    <h2><?php echo sprintf( __( 'Welcome to <strong>%s</strong>', 'villenoir' ), $theme_name . ' ' . $theme_version ) ?></h2>
                    <p class="about-description"><?php _e( 'Beautifully crafted WordPress theme ready to take your wine journey to the next level.', 'villenoir' ) ?></p>

                    <div class="welcome-panel-column-container">
                        <div class="welcome-panel-column">
                            <h3><i class="dashicons dashicons-screenoptions"></i><?php _e( 'Install Plugins', 'villenoir' ) ?></h3>
                            <p><?php echo sprintf( __( '%s has bundled popular premium plugins which greatly increases the flexibility of the theme. Install them in order to maximize the theme power.', 'villenoir' ), $theme_name ); ?></p>
                            <a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=tgmpa-install-plugins' ); ?>"><?php _e( 'Install Plugins', 'villenoir' ) ?></a>
                        </div>
                        <div class="welcome-panel-column">
                            <h3><i class="dashicons dashicons-download"></i><?php _ex( 'Import Demo Content', 'noun', 'villenoir' ) ?></h3>
                            <p><?php _e( 'If you have installed this theme on a clean WordPress installation then this is where you\'ll want to go next. This feature imports the demo content.', 'villenoir' ) ?></p>
                           
                            <?php if (class_exists('OCDI_Plugin')) : ?>
                            <a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=one-click-demo-import' ); ?>"><?php _e( 'Import Demo Content', 'villenoir' ) ?></a>
                            <?php endif; ?>

                        </div>
                        <div class="welcome-panel-column welcome-panel-last">
                            <h3><i class="dashicons dashicons-admin-appearance"></i><?php _e( 'Customize Appearance', 'villenoir' ) ?></h3>
                            <p><?php _e( 'Customize the look and feel of your site with the help of the Theme Options panel.', 'villenoir' ) ?></p>
                            
                            <?php if (class_exists('acf')) : ?>
                            <a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=theme-options' ); ?>"><?php _e( 'Go to Theme Options', 'villenoir' ) ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer" style="display:none;">
                <ul">
                    <li>
                        <i class="dashicons dashicons-editor-help"></i>
                        <a href="#" target="_blank"><?php _e( 'Online Documentation', 'villenoir' ) ?></a>
                    </li>
                    <li>
                        <i class="dashicons dashicons-sos"></i>
                        <a href="#" target="_blank"><?php _e( 'Support Portal', 'villenoir' ) ?></a>
                    </li>
                    <li>
                        <i class="dashicons dashicons-backup"></i>
                        <a href="#" target="_blank"><?php _e( 'Theme Changelog', 'villenoir' ) ?></a>
                    </li>
                </ul>
            </div>

        </div>
        <?php
    }


    public function villenoir_import_option_page() {
        global $wp_filesystem;
        WP_Filesystem();
    ?>
      <div class="wrap">
        <div id="icon-tools" class="icon32"><br></div>
        <h2><?php esc_html_e('Customizer Import', 'villenoir');?></h2>
        <?php
        if ( isset( $_FILES['import'] ) && check_admin_referer( 'gg-customizer-import' ) ) {
          if ( $_FILES['import']['error'] > 0 ) {
            wp_die( 'An error occured.' );
          } else {
            $file_name = $_FILES['import']['name'];
            $file_name = explode( '.', $file_name );
            $file_ext  = strtolower( end( $file_name ) );
            $file_size = $_FILES['import']['size'];
            if ( ( $file_ext == 'json' ) && ( $file_size < 500000 ) ) {
              
              $encode_options = $wp_filesystem->get_contents( $_FILES['import']['tmp_name'] );

              $options        = json_decode( $encode_options, true );

              foreach ( $options as $key => $value ) {
                update_option( $key, $value );
              }

              echo '<div class="updated"><p>'.esc_html__('All options were restored successfully!', 'villenoir').'</p></div>';
            } else {
              echo '<div class="error"><p>'.esc_html__('Invalid file or file size too big.', 'villenoir').'</p></div>';
            }
          }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
          <?php wp_nonce_field( 'gg-customizer-import' ); ?>
          <p><?php esc_html_e('If you have settings in a backup file (json) on your computer, the Import system can import it into this site. To get started, upload your backup file using the form below.', 'villenoir'); ?></p>
          <p><?php esc_html_e('Choose a file (json) from your computer:', 'villenoir');?> <input type="file" id="customizer-upload" name="import"></p>
          <p class="submit">
            <input type="submit" name="submit" id="customizer-submit" class="button" value="Upload file and import">
          </p>
        </form>
      </div>
    <?php
    }

    /**
     * [Export theme options]
     *
     * @since 2.2.0
     *
     */

    public function villenoir_export_option_page() {
      if ( ! isset( $_POST['export'] ) ) {
      ?>
        <div class="wrap">
          <div id="icon-tools" class="icon32"><br></div>
          <h2><?php esc_html_e('Customizer Export', 'villenoir');?></h2>
          <form method="post">
            <?php wp_nonce_field( 'gg-customizer-export' ); ?>
            <p><?php esc_html_e('When you click the button below, the Export system will create a backup file (json) for you to save to your computer.', 'villenoir'); ?></p>
            <p><?php esc_html_e('This text file can be used to restore your settings or to easily setup another website with the same theme settings.', 'villenoir'); ?></p>
            <p><em><?php esc_html_e('Please note that this export manager backs up only your theme settings and not your content. To backup your content, please use the WordPress Export Tool.', 'villenoir'); ?></em></p>
            <p class="submit"><input type="submit" name="export" class="button button-primary" value="Download Backup File"></p>
          </form>
        </div>

      <?php

        $options   = get_theme_mods();

      } elseif ( check_admin_referer( 'gg-customizer-export' ) ) {

        $blogname  = strtolower( str_replace(' ', '', get_option( 'blogname' ) ) );
        $date      = date( 'm-d-Y' );
        $json_name = $blogname . '-customizer-' . $date;

        $options = wp_load_alloptions();
        $query_string = 'options_';
        $query_string_sec = '_options';
        
        foreach ($options as $key => $value) {
            
            if ( (substr($key, 0, strlen($query_string)) === $query_string) || (substr($key, 0, strlen($query_string_sec)) === $query_string_sec) ) {
                $value = maybe_unserialize($value);
                $need_options[$key] = $value;
            }
            
        }

        ob_clean();

        echo json_encode( $need_options );

        header( 'Content-Type: text/json; charset=' . get_option( 'blog_charset' ) );
        header( 'Content-Disposition: attachment; filename=' . $json_name . '.json' );

        exit();

      }
    }


}

new villenoir_OKThemes_Theme_Demo_Data_Importer;