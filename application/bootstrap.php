<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/kohana/core'.EXT;

if (is_file(APPPATH.'classes/kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/kohana'.EXT;
}


/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('America/Mexico_City');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'es_ES.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */

define (FACEBOOK_SDK_V4_SRC_DIR, dirname(Kohana::find_file('vendor', 'facebook/autoload')).'/src/Facebook/');

spl_autoload_register(function ($class)
{
  // project-specific namespace prefix
  $prefix = 'Facebook\\';

  // base directory for the namespace prefix
  $base_dir = defined('FACEBOOK_SDK_V4_SRC_DIR') ? FACEBOOK_SDK_V4_SRC_DIR : __DIR__ . '/src/Facebook/';

  // does the class use the namespace prefix?
  $len = strlen($prefix);
  if (strncmp($prefix, $class, $len) !== 0) {
    // no, move to the next registered autoloader
    return;
  }

  // get the relative class name
  $relative_class = substr($class, $len);

  // replace the namespace prefix with the base directory, replace namespace
  // separators with directory separators in the relative class name, append
  // with .php
  $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

  // if the file exists, require it
  if (file_exists($file)) {
    require $file;
  }
});

spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('es-mx');

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */

if (isset($_SERVER['APP_ENV'])){
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['APP_ENV']));
} else {
	Kohana::$environment = Kohana::PRODUCTION;
}

	Kohana::$environment = Kohana::DEVELOPMENT;

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
	'base_url'   => '/',
	'index_file' => FALSE,
	'errors'     => TRUE,
	'profile'    => FALSE,
));


/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);
/**
* Attach custom config depending on the environment.
*/
if (isset($_SERVER['APP_ENV']))
{
	$environment = strtolower($_SERVER['APP_ENV']);
	Kohana::$config->attach(new Config_File('config/'.$environment));
}


/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	'auth'       => MODPATH.'auth',       // Basic authentication
	// 'cache'      => MODPATH.'cache',      // Caching with multiple backends
	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	'database'   => MODPATH.'database',   // Database access
	'contento'   => MODPATH.'contento',   // Contento modules: Auth, Pagination, etc
	'image'      => MODPATH.'image',      // Image manipulation
	// 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	// 'unittest'   => MODPATH.'unittest',   // Unit testing
	// 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
	));

/**
 * Set the cookie salt.
 */
Cookie::$salt     = 'caDruYSdfw464UFrABr5*PaWe$';
Cookie::$httponly = FALSE;
Cookie::$expiration = Date::YEAR;

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
 
Route::set('backend', array('Controller_Backend_Template', 'route'));

Route::set('homepage', '(<homepage>(/<id>))', array('id' => '[0-9\-]+', 'homepage' => 'homepage|vise'))
	->defaults(array(
		'directory'  => 'frontend',
		'controller' => 'homepage',
	));


Route::set('contacto', 'contacto')
	->defaults(array(
		'directory'  => 'frontend',
		'controller' => 'homepage',
		'action'     => 'contact'
	));

Route::set('experiencias', 'experiencias')
	->defaults(array(
		'directory'  => 'frontend',
		'controller' => 'homepage',
		'action'     => 'experiencias'
	));

Route::set('experienciasdet', 'experiencias/<year>/<month>/<slug>', array('year' => '[0-9]+', 'month' => '[0-9]+', 'slug' => '[a-z0-9\-\.]+'))
	->defaults(array(
		'directory'  => 'frontend',
		'controller' => 'homepage',
		'action'     => 'notes'
	));

Route::set('nosotros', 'nosotros')
	->defaults(array(
		'directory'  => 'frontend',
		'controller' => 'homepage',
		'action'     => 'nosotros'
	));



Route::set('content', '<level1>(/<level2>(/<level3>))', array('level1' => '[a-z0-9\-\.]+', 'level2' => '[a-z0-9\-\.]+', 'level3' => '[a-z0-9\-\.]+'))
	->defaults(array(
		'directory'  => 'frontend',
		'controller' => 'homepage',
		'action'     => 'content'
	));


/*
Route::set('error', 'error/<action>(/<message>)', array('action' => '[0-9]++', 'message' => '.+'))
	->defaults(array(
		'controller' => 'error_handler'
	));
*/

/**
* Set the custom exception handler
*/
//set_exception_handler(array('Kohana_Exception', 'handler'));
