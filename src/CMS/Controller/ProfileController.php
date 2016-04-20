<?php
/**
 * ProfileController.php
 */

namespace CMS\Controller;

use CMS\Model\Profile;
use Framework\Controller\Controller;
use Framework\DI\Service;
use Framework\Exception\DatabaseException;
use Framework\Response\ResponseRedirect;
use Framework\Validation\Validator;

class ProfileController extends Controller
{

    public function getAction()
    {
	if (!Service::get('security')->isAuthenticated()) {
            return new ResponseRedirect($this->generateRoute('login'));
        }
        
        $errors  = array();
        $msgs    = array();
        $user_id = Service::get('Session')->user->id;
        
        if(isset(Service::get('Session')->profile)){
            $profile = Service::get('Session')->profile;
        }else{
            $profile = Profile::getProfile($user_id);
        }
        
        if (!$profile) {
            $profile = new Profile();
        }
        
        return $this->render('profile.html', array(
                'errors'  => $errors,
                'msgs'    => $msgs,
                'profile' => $profile
            ));
    }
    
    public function updateAction()
    {
        $errors  = array();
        $msgs    = array();
        $user_id = Service::get('Session')->user->id;
        
        if(isset(Service::get('Session')->profile)){
            $profile = Service::get('Session')->profile;
        }else{
            $profile = Profile::getProfile($user_id);
        }
        
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $user_id;
        }
        
        if ($this->getRequest()->isPost()) {
            try{
                $profile->name        = $this->getRequest()->post('name');
                $profile->second_name = $this->getRequest()->post('second_name');
                $profile->info        = $this->getRequest()->post('info');

                $validator = new Validator($profile);
                if ($validator->isValid()) {
                    $profile->save();
                    return $this->redirect($this->generateRoute('home'), 'The data has been saved successfully');
                } else {
                    $errors = $validator->getErrors();
                }
            } catch(DatabaseException $e){
                $msgs = $e->getMessage();
            }
        }

        return $this->render('profile.html', array(
                'errors'  => $errors,
                'msgs'    => $msgs,
                'profile' => $profile
            ));
    }
}
