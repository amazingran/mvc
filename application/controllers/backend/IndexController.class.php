<?php

// application/controllers/backend/IndexController.class.php

class IndexController extends BaseController{

    public function mainAction(){

        //include CURR_VIEW_PATH . "main.html";

        // Load Captcha class

/*        $this->loader->library("Captcha");

        $captcha = new Captcha;

        $captcha->hello();*/

        $userModel = new UserModel("t_users");

        $users = $userModel->getUsers();

        var_dump($users);

    }

    public function kwordAction(){
        $kwordModel=new UserModel("b2b_hc_keyword");
        $kwordInfo=$kwordModel->getUsers();
        var_dump($kwordInfo);
    }

    public function indexAction(){

        $userModel = new UserModel("users");

        $users = $userModel->getUsers();

        // Load View template

//        include  CURR_VIEW_PATH . "index.html";

    }

    public function menuAction(){

        include CURR_VIEW_PATH . "menu.html";

    }

    public function dragAction(){

        include CURR_VIEW_PATH . "drag.html";

    }

    public function topAction(){

        include CURR_VIEW_PATH . "top.html";

    }

    

}