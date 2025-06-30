<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $argCount=count($arguments);
        
        // Get the current URL by Muddasar
        $currentUrl = current_url();
        //end

        // Already Loggedin it will redirect to respective module
        if($arguments[0]==0)
        {            
            if(session('user_id') || session('module')){
                return redirect()->to(session('module'));
            }
        }
        // 1 will need Loggedin to access the module
        if($arguments[0]==1)
        {
            if(!session('user_id') || !session('module')){
                // Store the current URL in the session by Muddasar on 21st June 2024
                session()->set('redirect_url', $currentUrl);
                //end
                return redirect()->to('/login');
            }
        }
        
        if($argCount>=2 && session('module') && $arguments[1]!=session('module'))
        {
            return redirect()->to(session('module'));
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
