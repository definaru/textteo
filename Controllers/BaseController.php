<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form', 'url', 'text', 'string', 'common'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     * 
     * @return mixed
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // $language = \Config\Services::language();
        // $language->setLocale('english');

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        // Check if the time_zone variable is set in the session
        if (isset($_SESSION['time_zone'])) {
            // Use the session's timezone
            date_default_timezone_set($_SESSION['time_zone']);
        } else {
            // Set the default timezone to 'Asia/Kolkata'
            date_default_timezone_set('Asia/Kolkata');

            // Save the timezone in the session variable for future use
            $_SESSION['time_zone'] = 'Asia/Kolkata';
        }
    }
}
